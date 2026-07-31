<?php

namespace App\Http\Controllers;

use App\Models\DiscoveryCenter;
use App\Models\User;
use App\Models\TestResult;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard() {
        $user = Auth::user();
          if ($user->role === 'participant') {
                return redirect()->route('pairs.show', [$user->center_id, $user->pair_name]);
            }
            // Redirección para Mentores
            if ($user->role === 'admin' && $user->center_id) {
                return redirect()->route('centers.show', $user->center_id);
            }
        $centers = DiscoveryCenter::with('users')->get();
        $admins = User::where('role', 'admin')->whereNull('center_id')->get();
        return view('admin.dashboard', compact('centers', 'admins'));
    }

    public function showCenter($id) {
        $user = Auth::user();
        if ($user->role === 'admin' && $user->center_id && $user->center_id != $id) {
            abort(403, 'No tienes permiso para acceder a este centro.');
        }

        $center = DiscoveryCenter::with(['users', 'notes.author', 'notes.taggedUser'])->findOrFail($id);
        $pairs = $center->users->where('role', 'participant')->groupBy('pair_name');
        $staff = $center->users->where('role', 'admin');

        $notesQuery = $center->notes()->with('author');
        if ($user->role !== 'master' && !is_null($user->center_id)) {
            $notesQuery->where(function($q) use ($user) {
                $q->where('is_public', true)->orWhere('author_id', $user->id);
            });
        }
        $notes = $notesQuery->orderBy('created_at', 'desc')->get();

        return view('admin.center_details', compact('center', 'pairs', 'staff', 'notes'));
    }

    public function showPair($centerId, $pairName) {

        $user = Auth::user();
        $center = DiscoveryCenter::findOrFail($centerId);
        $users = User::where('center_id', $centerId)->where('pair_name', $pairName)->get();
        $husband = $users->first();
        $wife = $users->count() > 1 ? $users->last() : null;

        $hResults = $this->getEnrichedResults($husband->name, $pairName);
        $wResults = $wife ? $this->getEnrichedResults($wife->name, $pairName) : collect();

        // Fetch notes helper with visibility logic
        $getVisibleNotes = function($query) use ($user) {
            if ($user->role !== 'master' && !is_null($user->center_id)) {
                $query->where(function($q) use ($user) {
                    $q->where('is_public', true)->orWhere('author_id', $user->id);
                });
            }
            return $query->with('author')->orderBy('created_at', 'desc')->get();
        };

        $pairNotes = $getVisibleNotes(Note::where('center_id', $centerId)->where('tagged_pair_name', $pairName));
        $hNotes = $getVisibleNotes(Note::where('center_id', $centerId)->where('tagged_user_id', $husband->id));
        $wNotes = $wife ? $getVisibleNotes(Note::where('center_id', $centerId)->where('tagged_user_id', $wife->id)) : collect();

        $data = [
            'center' => $center,
            'pairName' => $pairName,
            'husband' => $husband,
            'wife' => $wife,
            'hDones' => $hResults->where('test_type', 'dones')->first(),
            'hMbti' => $hResults->where('test_type', 'mbti')->first(),
            'wDones' => $wResults->where('test_type', 'dones')->first(),
            'wMbti' => $wResults->where('test_type', 'mbti')->first(),
            'mbtiInfo' => $this->getMbtiInfo(),
            'donesInfo' => $this->getDonesInfo(),
            'pairNotes' => $pairNotes,
            'hNotes' => $hNotes,
            'wNotes' => $wNotes
        ];

        return view('admin.pair_details', $data);
    }

    public function printResult($id) {
        $result = TestResult::findOrFail($id);
        $result = $this->enrichSingleResult($result);

        $mbtiInfo = $this->getMbtiInfo();
        $donesInfo = $this->getDonesInfo();

        $pdf = Pdf::loadView('pdf.test_result', compact('result', 'mbtiInfo', 'donesInfo'))->setPaper('a4', 'portrait');
        return $pdf->stream("resultado_{$result->test_type}_{$result->user_name}.pdf");
    }

    public function takeTest($userId, $type) {
        $user = User::with('center')->findOrFail($userId);
        $questions = ($type == 'mbti') ? $this->getMbtiQuestions() : $this->getDonesQuestions();
        $timer = $user->center->quiz_timer ?? 15;
        return view('admin.take_test', compact('user', 'type', 'questions', 'timer'));
    }

    public function saveTest(Request $request) {
        $user = User::findOrFail($request->user_id);
        $answers = [];
        foreach ($request->answers as $num => $val) {
            $answers[] = ['number' => (int)$num, 'answer' => (int)$val];
        }

        TestResult::create([
            'user_name' => $user->name,
            'pair_name' => $user->pair_name,
            'center_name' => $user->center->name ?? null,
            'test_type' => $request->test_type,
            'completed_at' => now(),
            'answers' => $answers,
        ]);

        return redirect()->route('pairs.show', [$user->center_id, $user->pair_name])->with('success', 'Test guardado correctamente.');
    }

    private function getEnrichedResults($userName, $pairName) {
        $results = TestResult::where('user_name', 'like', trim($userName))
            ->where('pair_name', $pairName)
            ->orderBy('completed_at', 'desc')
            ->get();

        return $results->map(function($res) {
            return $this->enrichSingleResult($res);
        });
    }

    private function enrichSingleResult($res) {
        $meta = $res->metadata ?? [];
        $ans = $res->answers;

        if ($res->test_type == 'dones') {
            $codes = ["Adm", "Dis", "Evan", "Exh", "Fe", "Dar", "Con", "Lid", "Mis", "Past", "Pro", "Serv", "Ense", "Sab"];
            $names = $this->getDonesInfo();
            $scores = array_fill_keys($codes, 0);

            foreach ($ans as $index => $item) {
                $code = $codes[$index % 14];
                $scores[$code] += (int)($item['answer'] ?? 0);
            }

            $ranking = [];
            foreach ($scores as $code => $score) {
                $ranking[] = ['code' => $code, 'name' => $names[$code], 'score' => $score];
            }
            usort($ranking, function($a, $b) { return $b['score'] <=> $a['score']; });
            $meta['dones_ranking'] = $ranking;
            $meta['scores_raw'] = $scores;
        }

        if ($res->test_type == 'mbti') {
            $scores = ["E" => 0, "I" => 0, "S" => 0, "N" => 0, "T" => 0, "F" => 0, "J" => 0, "P" => 0];
            $dims = ['I', 'E', 'S', 'N', 'T', 'F', 'J', 'P'];
            foreach ($ans as $item) {
                $idx = ($item['number'] - 1) % 8;
                if (isset($dims[$idx])) {
                    $scores[$dims[$idx]] += (int)$item['answer'];
                }
            }
            $meta['scores'] = $scores;
            $type = ($scores['E'] >= $scores['I'] ? 'E' : 'I') .
                    ($scores['S'] >= $scores['N'] ? 'S' : 'N') .
                    ($scores['T'] >= $scores['F'] ? 'T' : 'F') .
                    ($scores['J'] >= $scores['P'] ? 'J' : 'P');
            $meta['mbti_types'] = [$type];
        }

        $res->metadata = $meta;
        return $res;
    }

    private function getMbtiQuestions() {
        return [
            ["number" => 1, "text" => "Prefiere realizar actividades a solas o con una o dos personas de confianza."],
            ["number" => 2, "text" => "Se siente lleno de energía cuando está rodeado de mucha gente."],
            ["number" => 3, "text" => "Se enfoca en los hechos, los detalles y la realidad presente."],
            ["number" => 4, "text" => "Prefiere pensar en las posibilidades futuras y en el panorama general."],
            ["number" => 5, "text" => "Toma decisiones basadas en la lógica y el análisis objetivo."],
            ["number" => 6, "text" => "Toma decisiones basadas en sus valores personales y en cómo afectarán a los demás."],
            ["number" => 7, "text" => "Prefiere tener un estilo de vida organizado y con planes establecidos."],
            ["number" => 8, "text" => "Prefiere ser flexible, espontáneo y dejar las opciones abiertas."],
            ["number" => 9, "text" => "A menudo necesita tiempo a solas para 'recargar pilas' después de socializar."],
            ["number" => 10, "text" => "Tiende a actuar primero y pensar después."],
            ["number" => 11, "text" => "Confía en la experiencia y en lo que es práctico."],
            ["number" => 12, "text" => "Confía en su instinto y en los significados simbólicos."],
            ["number" => 13, "text" => "Cree que es más importante ser directo y honesto que ser amable."],
            ["number" => 14, "text" => "Cree que es más importante ser diplomático y tactful."],
            ["number" => 15, "text" => "Se siente mejor cuando las tareas están terminadas y los problemas resueltos."],
            ["number" => 16, "text" => "Disfruta del proceso de trabajar en algo, incluso si no se termina pronto."],
            ["number" => 17, "text" => "Se considera una persona reservada o tranquila."],
            ["number" => 18, "text" => "Se considera una persona abierta y entusiasta."],
            ["number" => 19, "text" => "Es realista y prefiere las cosas que funcionan de verdad."],
            ["number" => 20, "text" => "Es imaginativo y disfruta explorando ideas abstractas."],
            ["number" => 21, "text" => "Valora la justicia y la imparcialidad por encima de todo."],
            ["number" => 22, "text" => "Valora la armonía y la compasión por encima de todo."],
            ["number" => 23, "text" => "Le gusta hacer listas de tareas y seguir un horario."],
            ["number" => 24, "text" => "Prefiere improvisar y adaptarse a los cambios sobre la marcha."],
            ["number" => 25, "text" => "Prefiere comunicarse por escrito que hablar en persona."],
            ["number" => 26, "text" => "Prefiere las conversaciones cara a cara que los mensajes escritos."],
            ["number" => 27, "text" => "Nota los cambios específicos en el entorno físico."],
            ["number" => 28, "text" => "Nota los patrones y las conexiones entre diferentes ideas."],
            ["number" => 29, "text" => "Es bueno encontrando fallos y analizando problemas de forma crítica."],
            ["number" => 30, "text" => "Es bueno entendiendo los sentimientos de los demás y mediando conflictos."],
            ["number" => 31, "text" => "Siente estrés si las cosas están desordenadas o sin planificar."],
            ["number" => 32, "text" => "Se siente limitado por las estructuras rígidas y los horarios estrictos."],
            ["number" => 33, "text" => "Prefiere observar antes de participar en una actividad nueva."],
            ["number" => 34, "text" => "Se lanza a nuevas experiencias sin dudar demasiado."],
            ["number" => 35, "text" => "Prefiere aprender cosas que tengan una aplicación práctica inmediata."],
            ["number" => 36, "text" => "Prefiere aprender conceptos teóricos y complejos."],
            ["number" => 37, "text" => "A veces los demás lo perciben como alguien frío o distante."],
            ["number" => 38, "text" => "A veces los demás lo perciben como alguien demasiado emocional."],
            ["number" => 39, "text" => "Siempre llega a tiempo o un poco antes a sus citas."],
            ["number" => 40, "text" => "A menudo llega tarde o justo a tiempo porque se distrae fácilmente."],
            ["number" => 41, "text" => "Tiene pocos amigos pero la relación con ellos es muy profunda."],
            ["number" => 42, "text" => "Tiene un círculo social amplio y disfruta conociendo gente nueva."],
            ["number" => 43, "text" => "Se describe como una persona 'con los pies en la tierra'."],
            ["number" => 44, "text" => "Se describe como una persona 'con la cabeza en las nubes'."],
            ["number" => 45, "text" => "Sigue su cabeza antes que su corazón."],
            ["number" => 46, "text" => "Sigue su corazón antes que su cabeza."],
            ["number" => 47, "text" => "Le gusta establecer metas claras y trabajar para alcanzarlas."],
            ["number" => 48, "text" => "Prefiere explorar diferentes caminos sin una meta final fija."],
            ["number" => 49, "text" => "Reflexiona mucho antes de tomar la palabra en una reunión."],
            ["number" => 50, "text" => "Piensa en voz alta y procesa sus ideas hablando."],
            ["number" => 51, "text" => "Disfruta de la rutina y de saber qué esperar."],
            ["number" => 52, "text" => "Se abure fácilmente con la rutina y busca la novedad."],
            ["number" => 53, "text" => "Cree que las reglas deben aplicarse por igual a todo el mundo."],
            ["number" => 54, "text" => "Cree que cada situación es única y las reglas deben ser flexibles."],
            ["number" => 55, "text" => "Prefiere preparar las vacaciones con mucha antelación."],
            ["number" => 56, "text" => "Prefiere irse de viaje sin un plan detallado."],
            ["number" => 57, "text" => "Se siente agotado después de un evento social largo."],
            ["number" => 58, "text" => "Se siente estimulado después de una fiesta o reunión social."],
            ["number" => 59, "text" => "Valora el sentido común por encima de la creatividad abstracta."],
            ["number" => 60, "text" => "Valora la originalidad por encima de los métodos tradicionales."],
            ["number" => 61, "text" => "Se enorgullece de ser una persona racional y objetiva."],
            ["number" => 62, "text" => "Se enorgullece de ser una persona empática y sensible."],
            ["number" => 63, "text" => "Prefiere comprar solo lo que hay en su lista de la compra."],
            ["number" => 64, "text" => "A menudo termina comprando cosas que no estaban planeadas."],
            ["number" => 65, "text" => "Es una persona más bien privada que no comparte su vida fácilmente."],
            ["number" => 66, "text" => "Es una persona muy expresiva y abierta sobre sus sentimientos."],
            ["number" => 67, "text" => "Se enfoca en lo que es, no en lo que podría ser."],
            ["number" => 68, "text" => "Se enfoca en lo que podría ser, no en lo que es."],
            ["number" => 69, "text" => "Toma decisiones de forma rápida y decisiva."],
            ["number" => 70, "text" => "Toma decisiones con calma y después de mucha reflexión."],
            ["number" => 71, "text" => "Se siente incómodo si tiene tareas pendientes sin terminar."],
            ["number" => 72, "text" => "No le importa dejar cosas para después si surge algo más interesante."],
        ];
    }

    private function getDonesQuestions() {
        return [
            ["number" => 1, "text" => "Me gusta organizar personas y tareas para lograr metas."],
            ["number" => 2, "text" => "Puedo percibir si un motivo es genuino o no."],
            ["number" => 3, "text" => "Siento una gran carga por compartir el evangelio con los no creyentes."],
            ["number" => 4, "text" => "Me gusta animar a otros en tiempos de dificultad."],
            ["number" => 5, "text" => "Confío plenamente en que Dios proveerá a pesar de las circunstancias."],
            ["number" => 6, "text" => "Me da alegría dar dinero o recursos a quienes lo necesitan."],
            ["number" => 7, "text" => "Tengo facilidad para entender y sistematizar verdades bíblicas."],
            ["number" => 8, "text" => "Me siente cómodo liderando y dirigiendo a otros."],
            ["number" => 9, "text" => "Siento profunda compasión por las personas que sufren."],
            ["number" => 10, "text" => "Disfruto guiar y cuidar del crecimiento espiritual de un grupo."],
            ["number" => 11, "text" => "A veces recibo mensajes claros de Dios para compartir con otros."],
            ["number" => 12, "text" => "Prefiero trabajar detrás de escena para ayudar en tareas prácticas."],
            ["number" => 13, "text" => "Me apasiona explicar la Palabra de Dios de forma clara."],
            ["number" => 14, "text" => "A menudo tengo claridad sobre qué decisión tomar en situaciones complejas."],
            ["number" => 15, "text" => "Disfruto planificar eventos y asegurar que todo salga bien."],
            ["number" => 16, "text" => "Puedo distinguir rápidamente entre la verdad y el error."],
            ["number" => 17, "text" => "Busco activamente oportunidades para hablar de Jesús."],
            ["number" => 18, "text" => "Las personas suelen acudir a mí en busca de consejo y ánimo."],
            ["number" => 19, "text" => "Creo firmemente en las promesas de Dios incluso cuando no hay pruebas."],
            ["number" => 20, "text" => "Trato de administrar mis recursos para poder dar generosamente."],
            ["number" => 21, "text" => "Me gusta profundizar en el estudio de temas complejos."],
            ["number" => 22, "text" => "Tengo visión para el futuro y puedo motivar a otros a seguirla."],
            ["number" => 23, "text" => "Me siente movido a ayudar a los marginados y necesitados."],
            ["number" => 24, "text" => "Me preocupo por el bienestar de los miembros de mi comunidad."],
            ["number" => 25, "text" => "Me siente impulsado a denunciar el pecado y llamar al arrepentimiento."],
            ["number" => 26, "text" => "Cualquier tarea, por pequeña que sea, me hace feliz si ayuda a otros."],
            ["number" => 27, "text" => "Puedo comunicar conceptos difíciles de manera sencilla."],
            ["number" => 28, "text" => "Dios me da sabiduría para resolver conflictos o problemas."],
            ["number" => 29, "text" => "Sé cómo delegar tareas a las personas adecuadas."],
            ["number" => 30, "text" => "Tengo una intuición espiritual sobre las intenciones de la gente."],
            ["number" => 31, "text" => "Me siente cómodo hablando con extraños sobre mi fe."],
            ["number" => 32, "text" => "Sé cómo motivar a alguien que se siente derrotado."],
            ["number" => 33, "text" => "Mi fe me permite mantenerme firme en las pruebas."],
            ["number" => 34, "text" => "Doy con alegría y sin esperar nada a cambio."],
            ["number" => 35, "text" => "Paso mucho tiempo investigando detalles históricos o teológicos."],
            ["number" => 36, "text" => "Me gusta influir en la dirección que toma un proyecto."],
            ["number" => 37, "text" => "Visitar enfermos o personas en prisión es algo natural para mí."],
            ["number" => 38, "text" => "Siento responsabilidad por proteger a otros de influencias dañinas."],
            ["number" => 39, "text" => "A veces hablo palabras que confrontan la realidad actual de alguien."],
            ["number" => 40, "text" => "Me gusta que el lugar de reunión esté listo y ordenado."],
            ["number" => 41, "text" => "Me frustra cuando la enseñanza bíblica es superficial."],
            ["number" => 42, "text" => "La gente me pide consejo cuando no saben qué hacer."],
            ["number" => 43, "text" => "Me enfoco en la eficiencia para lograr los objetivos del grupo."],
            ["number" => 44, "text" => "Puedo detectar influencias espirituales negativas en un ambiente."],
            ["number" => 45, "text" => "Mi mayor deseo es ver a la gente entregar su vida a Cristo."],
            ["number" => 46, "text" => "Acompaño a las personas en sus procesos de cambio personal."],
            ["number" => 47, "text" => "He visto a Dios hacer cosas imposibles por haber confiado en Él."],
            ["number" => 48, "text" => "Invierto mi dinero en el reino de Dios con regularidad."],
            ["number" => 49, "text" => "Me gusta descubrir nuevas verdades en la Biblia."],
            ["number" => 50, "text" => "Asumo el mando cuando veo que falta liderazgo."],
            ["number" => 51, "text" => "Me duele ver el sufrimiento ajeno y busco aliviarlo."],
            ["number" => 52, "text" => "Disfruto discipular a otros uno a uno."],
            ["number" => 53, "text" => "Me siento llamado a ser una voz de advertencia para la iglesia."],
            ["number" => 54, "text" => "Prefiero hacer el trabajo práctico que dirigirlo."],
            ["number" => 55, "text" => "Me encanta ver cómo la gente aprende y aplica la verdad."],
            ["number" => 56, "text" => "Tengo una visión clara de cómo aplicar principios bíblicos hoy."],
            ["number" => 57, "text" => "Soy bueno coordinando el trabajo de muchos voluntarios."],
            ["number" => 58, "text" => "Juzgo las enseñanzas según si están alineadas con la Biblia."],
            ["number" => 59, "text" => "Me siento impulsado a invitar a personas a la iglesia."],
            ["number" => 60, "text" => "Soy paciente con aquellos que están pasando por crisis."],
            ["number" => 61, "text" => "No me rindo aunque las circunstancias parezcan perdidas."],
            ["number" => 62, "text" => "Siento que lo que tengo le pertenece a Dios y lo comparto."],
            ["number" => 63, "text" => "Me gusta estudiar el significado original de las palabras bíblicas."],
            ["number" => 64, "text" => "Puedo organizar a un equipo para cumplir una visión."],
            ["number" => 65, "text" => "Me siente atraído por el ministerio de ayuda social."],
            ["number" => 66, "text" => "Me gusta caminar junto a las personas en su día a día."],
            ["number" => 67, "text" => "A veces Dios me revela pecados ocultos que deben ser tratados."],
            ["number" => 68, "text" => "Me gusta servir comida o limpiar después de un evento."],
            ["number" => 69, "text" => "Me aseguro de que mis enseñanzas sean precisas."],
            ["number" => 70, "text" => "Dios me guía a dar la palabra justa en el momento oportuno."],
            ["number" => 71, "text" => "Disfruto estableciendo sistemas administrativos efectivos."],
            ["number" => 72, "text" => "Siento cuando algo no suena 'bien' doctrinalmente."],
            ["number" => 73, "text" => "Me preparo constantemente para explicar por qué creo en Jesús."],
            ["number" => 74, "text" => "Mi consejo ayuda a otros a tomar acciones concretas."],
            ["number" => 75, "text" => "Mi confianza en Dios anima a los que tienen dudas."],
            ["number" => 76, "text" => "Me gusta dar de forma anónima."],
            ["number" => 77, "text" => "Me gusta coleccionar libros y recursos de estudio."],
            ["number" => 78, "text" => "Soy capaz de mantener al grupo enfocado en su misión."],
            ["number" => 79, "text" => "Me gusta estar con personas que otros evitan."],
            ["number" => 80, "text" => "Me siento responsable de la salud espiritual de mi grupo."],
            ["number" => 81, "text" => "No tengo miedo de decir la verdad aunque sea impopular."],
            ["number" => 82, "text" => "Soy feliz haciendo tareas de mantenimiento o apoyo."],
            ["number" => 83, "text" => "Dedico mucho tiempo a preparar mis enseñanzas."],
            ["number" => 84, "text" => "Suelo ver la solución lógica a problemas espirituales."],
            ["number" => 85, "text" => "Me gusta optimizar los procesos en la iglesia."],
            ["number" => 86, "text" => "Distingo entre una emoción pasajera y una obra del Espíritu."],
            ["number" => 87, "text" => "Busco formas creativas de presentar el evangelio."],
            ["number" => 88, "text" => "Exhorto a otros a vivir de acuerdo a su llamado."],
            ["number" => 89, "text" => "Acepto desafíos grandes porque sé que Dios está conmigo."],
            ["number" => 90, "text" => "Soy generoso incluso cuando mis propios recursos son limitados."],
            ["number" => 91, "text" => "Me apasiona la teología profunda."],
            ["number" => 92, "text" => "Sé cómo guiar a un equipo a través de un cambio difícil."],
            ["number" => 93, "text" => "Me ofrezco de voluntario para ayudar a los necesitados."],
            ["number" => 94, "text" => "Me gusta ver a las personas crecer y madurar bajo mi cuidado."],
            ["number" => 95, "text" => "Mis palabras a menudo traen convicción de pecado."],
            ["number" => 96, "text" => "Disfruto ayudando a otros para que ellos puedan cumplir su ministerio."],
            ["number" => 97, "text" => "Soy muy riguroso con la verdad de las Escrituras."],
            ["number" => 98, "text" => "A menudo sé exactamente qué consejo dar en una crisis."],
        ];
    }

    public function getDonesInfo() {
        return ["Adm"=>"Administración","Dis"=>"Discernimiento","Evan"=>"Evangelismo","Exh"=>"Exhortación","Fe"=>"Fe","Dar"=>"Dar","Con"=>"Conocimiento","Lid"=>"Liderazgo","Mis"=>"Misericordia","Past"=>"Pastoreo","Pro"=>"Profecía","Serv"=>"Servicio / Ministerio","Ense"=>"Enseñanza","Sab"=>"Sabiduría"];
    }

    public function getMbtiInfo() {
        return [
            "ISTJ" => ["name" => "El Inspector", "description" => "Personas reservadas, prácticas y silenciosas. Disfrutan del orden y la organización en todas las áreas de sus vidas, incluidos el hogar, el trabajo, la familia y los proyectos. Valoran la lealtad en sí mismos y en los demás, y ponen énfasis en las tradiciones.", "strengths" => ["Responsables", "Organizados", "Leales", "Detallistas"], "weaknesses" => ["Rígidos", "Poco empáticos", "Resistentes al cambio"]],
            "ISFJ" => ["name" => "El Protector", "description" => "Convencionales y con los pies en la tierra, disfrutan de la continuidad y la tradición. Tienen un fuerte sentido de la responsabilidad y el deber. Son personas cálidas y protectoras que valoran la armonía.", "strengths" => ["Serviciales", "Confiables", "Observadores", "Prácticos"], "weaknesses" => ["Demasiado altruistas", "Se toman las cosas personalmente", "Reprimen sentimientos"]],
            "INFJ" => ["name" => "El Consejero", "description" => "Idealistas que tienen un gran sentido de la integridad personal y un impulso para ayudar a otros a alcanzar su potencial. Creativos, dedicados y con una visión profunda.", "strengths" => ["Perspicaces", "Inspiradores", "Decididos", "Apasionados"], "weaknesses" => ["Sensibles a la crítica", "Extremadamente reservados", "Agotamiento"]],
            "INTJ" => ["name" => "La Mente Maestra", "description" => "Analíticos, lógicos y creativos. Tienen una fuerte necesidad de autonomía y competencia. Son pensadores estratégicos con planes para todo.", "strengths" => ["Estratégicos", "Independientes", "Analíticos", "Determinados"], "weaknesses" => ["Arrogantes", "Demasiado críticos", "Desconectados"]],
            "ISTP" => ["name" => "El Artesano", "description" => "Prácticos y realistas, tienen una afinidad natural por las máquinas y herramientas. Son observadores y valoran la eficiencia y la resolución inmediata de problemas.", "strengths" => ["Optimistas", "Creativos", "Prácticos", "Relajados"], "weaknesses" => ["Tercos", "Insensibles", "Reservados"]],
            "ISFP" => ["name" => "El Compositor", "description" => "Artísticos, sensibles y amables. Disfrutan del momento presente y de lo que les rodea. Valoran su propio espacio y trabajar a su propio ritmo.", "strengths" => ["Encantadores", "Sensibles", "Imaginativos", "Apasionados"], "weaknesses" => ["Independientes en exceso", "Impredecibles", "Estresables"]],
            "INFP" => ["name" => "El Sanador", "description" => "Sensibles, idealistas y leales a sus valores. Tienen curiosidad por las posibilidades del futuro y buscan entender a los demás y ayudarlos.", "strengths" => ["Idealistas", "Buscadores de armonía", "Mente abierta", "Creativos"], "weaknesses" => ["Demasiado idealistas", "Demasiado altruistas", "Se lo toman personal"]],
            "INTP" => ["name" => "El Arquitecto", "description" => "Lógicos, precisos y reservados. Valoran la inteligencia y el conocimiento. Son teóricos y abstractos, más interesados en las ideas que sociales.", "strengths" => ["Objetivos", "Imaginativos", "Entusiastas", "Grandes pensadores"], "weaknesses" => ["Desconectados", "Insensibles", "Dudosos"]],
            "ESTP" => ["name" => "El Promotor", "description" => "Enérgicos y orientados a la acción. Disfrutan de los resultados inmediatos y de resolver problemas de forma pragática. Son sociables y observadores.", "strengths" => ["Audaces", "Directos", "Sociables", "Perspicaces"], "weaknesses" => ["Insensibles", "Impacientes", "Arriesgados"]],
            "ESFP" => ["name" => "El Actor", "description" => "Amantes de la diversión, sociables y entusiastas. Les gusta trabajar con otros para hacer que las cosas sucedan. Tienen un fuerte sentido común.", "strengths" => ["Audaces", "Originales", "Estéticos", "Prácticos"], "weaknesses" => ["Sensibles", "Evitan conflictos", "Aburrimiento rápido"]],
            "ENFP" => ["name" => "El Campeón", "description" => "Entusiastas, creativos e imaginativos. Ven la vida como algo lleno de posibilidades. Son cálidos y están dispuestos a ayudar a cualquiera.", "strengths" => ["Curiosos", "Observadores", "Enérgicos", "Populares"], "weaknesses" => ["Falta de enfoque", "Pensamiento excesivo", "Muy emocionales"]],
            "ENTP" => ["name" => "El Inventor", "description" => "Rápidos, ingeniosos y estimulantes. Son muy buenos resolviendo problemas nuevos y desafiantes. Valoran la competencia y el pensamiento lógico.", "strengths" => ["Conocedores", "Pensadores rápidos", "Originales", "Carismáticos"], "weaknesses" => ["Argumentativos", "Insensibles", "Intolerantes"]],
            "ESTJ" => ["name" => "El Supervisor", "description" => "Prácticos, realistas y orientados a los hechos. Tienen una habilidad natural para organizar proyectos y personas para obtener resultados.", "strengths" => ["Dedicados", "Voluntad fuerte", "Directos", "Organizadores"], "weaknesses" => ["Inflexibles", "Incómodos con lo nuevo", "Demasiado críticos"]],
            "ESFJ" => ["name" => "El Proveedor", "description" => "Cooperativos, sociables y de buen corazón. Buscan la armonía en su entorno y trabajan con determinación para lograrla.", "strengths" => ["Sentido del deber", "Leales", "Sensibles", "Buenos conectores"], "weaknesses" => ["Preocupados por estatus", "Inflexibles", "Vulnerables"]],
            "ENFJ" => ["name" => "El Profesor", "description" => "Cálidos, empáticos y responsables. Son muy sensibles a las necesidades y sentimientos de los demás. Encuentran potencial en todos.", "strengths" => ["Tolerantes", "Confiables", "Carismáticos", "Altruistas"], "weaknesses" => ["Demasiado idealistas", "Desinteresados en exceso", "Muy sensibles"]],
            "ENTJ" => ["name" => "El Mariscal de Campo", "description" => "Francos, decididos y líderes naturales. Identifican rápidamente procedimientos ineficientes y desarrollan sistemas organizativos.", "strengths" => ["Eficientes", "Enérgicos", "Confiados", "Voluntad fuerte"], "weaknesses" => ["Tercos", "Intolerantes", "Impacientes", "Arrogantes"]],
        ];
    }

    public function deletePair(Request $request) {
        $users = User::where('pair_name', $request->pair_name)->where('center_id', $request->center_id)->get();
        foreach ($users as $user) {
            if ($user->pair_photo) Storage::disk('public')->delete($user->pair_photo);
            $user->delete();
        }
        return back()->with('success', 'Pareja eliminada.');
    }

    public function generatePairPdf($userId) {
        $user = User::with('center')->findOrFail($userId);
        return Pdf::loadView('pdf.pair_cover', compact('user'))->stream("portada_{$user->pair_name}.pdf");
    }

    public function printCenterPairs($centerId) {
        $center = DiscoveryCenter::with('users')->findOrFail($centerId);
        $pairs = $center->users->where('role', 'participant')->groupBy('pair_name');

        $pdf = Pdf::loadView('pdf.center_pairs_grid', compact('center', 'pairs'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("parejas_{$center->name}.pdf");
    }

    public function createCenter(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'quiz_timer' => 'required|integer|min:1',
            'banner_photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('banner_photo')) {
            $data['banner_photo'] = $request->file('banner_photo')->store('centers', 'public');
        }

        DiscoveryCenter::create($data);
        return back()->with('success', 'Centro creado exitosamente.');
    }

    public function updateCenter(Request $request, $id) {
        $center = DiscoveryCenter::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'quiz_timer' => 'required|integer|min:1',
            'banner_photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('banner_photo')) {
            if ($center->banner_photo) Storage::disk('public')->delete($center->banner_photo);
            $data['banner_photo'] = $request->file('banner_photo')->store('centers', 'public');
        }

        $center->update($data);
        return back()->with('success', 'Centro actualizado.');
    }

    public function deleteCenter($id) {
        $center = DiscoveryCenter::findOrFail($id);
        if ($center->banner_photo) Storage::disk('public')->delete($center->banner_photo);
        $center->delete();
        return back()->with('success', 'Centro eliminado.');
    }

    public function addPair(Request $request) {


        if (Auth::user()->center_id && Auth::user()->role !== 'master') {
            abort(403, 'No tienes permiso para agregar parejas.');
        }


        $request->validate([
            'pair_name' => 'required',
            'husband_name' => 'required',
            'husband_username' => 'required|unique:users,username',
            'husband_password' => 'required',
            'wife_name' => 'required',
            'wife_username' => 'required|unique:users,username',
            'wife_password' => 'required',
            'pair_photo' => 'nullable|image'
        ]);

        $photoPath = null;
        if ($request->hasFile('pair_photo')) {
            $photoPath = $request->file('pair_photo')->store('pairs', 'public');
        }

        User::create([
            'name' => $request->husband_name,
            'username' => $request->husband_username,
            'password' => Hash::make($request->husband_password),
            'role' => 'participant',
            'pair_name' => $request->pair_name,
            'center_id' => $request->center_id,
            'pair_photo' => $photoPath
        ]);

        User::create([
            'name' => $request->wife_name,
            'username' => $request->wife_username,
            'password' => Hash::make($request->wife_password),
            'role' => 'participant',
            'pair_name' => $request->pair_name,
            'center_id' => $request->center_id,
            'pair_photo' => $photoPath
        ]);

        return redirect()->route('centers.show', $request->center_id . '#tab-pairs')->with('success', 'Pareja registrada.');
    }

    public function updatePair(Request $request) {
        $husband = User::findOrFail($request->husband_id);
        $wife = User::findOrFail($request->wife_id);

        $photoPath = $husband->pair_photo;
        if ($request->hasFile('pair_photo')) {
            if ($photoPath) Storage::disk('public')->delete($photoPath);
            $photoPath = $request->file('pair_photo')->store('pairs', 'public');
        }

        $husband->update([
            'name' => $request->husband_name,
            'username' => $request->husband_username,
            'pair_name' => $request->pair_name,
            'pair_photo' => $photoPath
        ]);

        if ($request->husband_password) {
            $husband->update(['password' => Hash::make($request->husband_password)]);
        }

        $wife->update([
            'name' => $request->wife_name,
            'username' => $request->wife_username,
            'pair_name' => $request->pair_name,
            'pair_photo' => $photoPath
        ]);
        if ($request->wife_password) {
            $wife->update(['password' => Hash::make($request->wife_password)]);
        }

        return redirect()->route('centers.show', $request->center_id . '#tab-pairs')->with('success', 'Datos de pareja actualizados.');
    }

    public function addStaff(Request $request) {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'staff_title' => 'required',
            'center_id' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'staff_title' => $request->staff_title,
            'center_id' => $request->center_id
        ]);

        return redirect()->route('centers.show', $request->center_id . '#tab-staff')->with('success', 'Miembro del staff agregado.');
    }

    public function updateStaff(Request $request, $id) {
        $staff = User::findOrFail($id);
        $staff->update([
            'name' => $request->name,
            'username' => $request->username,
            'staff_title' => $request->staff_title,
        ]);
        if ($request->password) {
            $staff->update(['password' => Hash::make($request->password)]);
        }
        return redirect()->route('centers.show', $staff->center_id . '#tab-staff')->with('success', 'Staff actualizado.');
    }

    public function deleteStaff($id) {
        $staff = User::findOrFail($id);
        $centerId = $staff->center_id;
        $staff->delete();
        return redirect()->route('centers.show', $centerId . '#tab-staff')->with('success', 'Miembro del staff eliminado.');
    }

    public function addNote(Request $request) {
        $request->validate([
            'content' => 'required',
            'center_id' => 'required'
        ]);

        Note::create([
            'author_id' => Auth::id(),
            'center_id' => $request->center_id,
            'content' => $request->content,
            'is_public' => $request->has('is_public'),
            'tagged_user_id' => $request->tagged_user_id,
            'tagged_pair_name' => $request->tagged_pair_name
        ]);

        return redirect()->route('centers.show', $request->center_id . '#tab-notes')->with('success', 'Nota guardada.');
    }

    public function updateNote(Request $request, $id) {
        $note = Note::findOrFail($id);

        if (!$this->canManageNote($note)) {
            return back()->with('error', 'No tienes permiso para editar esta nota.');
        }

        $note->update([
            'content' => $request->content,
            'is_public' => $request->has('is_public'),
            'tagged_user_id' => $request->tagged_user_id,
            'tagged_pair_name' => $request->tagged_pair_name
        ]);
        return redirect()->route('centers.show', $note->center_id . '#tab-notes')->with('success', 'Nota actualizada.');
    }

    public function deleteNote($id) {
        $note = Note::findOrFail($id);
        $centerId = $note->center_id;

        if (!$this->canManageNote($note)) {
            return back()->with('error', 'No tienes permiso para eliminar esta nota.');
        }

        $note->delete();
        return redirect()->route('centers.show', $centerId . '#tab-notes')->with('success', 'Nota eliminada.');
    }

    private function canManageNote($note) {
        $user = Auth::user();
        if ($note->author_id == $user->id) return true;
        if ($user->role == 'master') return true;
        if ($user->role == 'admin' && is_null($user->center_id)) return true;
        return false;
    }

    public function updateAdmin(Request $request, $id) {
        $admin = User::findOrFail($id);
        $admin->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);
        if ($request->password) {
            $admin->update(['password' => Hash::make($request->password)]);
        }
        return back()->with('success', 'Administrador actualizado.');
    }

    public function deleteAdmin($id) {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Administrador eliminado.');
    }
}
