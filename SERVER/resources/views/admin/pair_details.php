<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expediente: {{ $pairName }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --dark: #0f172a;
            --bg-body: #f8fafc;
            --sidebar-bg: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--dark);
        }

        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            width: 16.666667%;
            z-index: 100;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 0.8rem 1rem;
            margin: 0.2rem 0;
            border-radius: 8px;
            transition: 0.2s;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.05);
        }

        .sidebar .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .main-content {
            margin-left: 16.666667%;
            padding: 2rem;
        }

        .card-premium {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            background: white;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .nav-main {
            background: #f1f5f9;
            padding: 0.5rem;
            border-radius: 16px;
            margin-bottom: 2.5rem;
            border: none;
        }

        .nav-main .nav-link {
            border-radius: 12px;
            font-weight: 700;
            padding: 12px 24px;
            color: #64748b;
            border: none;
        }

        .nav-main .nav-link.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .nav-sub {
            gap: 10px;
            border: none;
            margin-bottom: 2rem;
        }

        .nav-sub .nav-link {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #64748b;
            font-weight: 700;
            padding: 8px 20px;
            background: white;
        }

        .nav-sub .nav-link.active {
            border-color: var(--primary);
            color: var(--primary);
            background: #eef2ff;
        }

        .mbti-type-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 2px;
        }

        .dim-bar-container {
            height: 12px;
            background: #f1f5f9;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .dim-bar-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 10px;
            transition: width 1s ease;
        }

        .dones-grid {
            display: grid;
            grid-template-columns: repeat(14, 1fr);
            gap: 2px;
            background: #e2e8f0;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .dones-cell {
            background: white;
            padding: 8px 2px;
            text-align: center;
            font-size: 12px;
        }

        .cell-header { background: #f8fafc; color: #94a3b8; font-size: 9px; font-weight: 700; }
        .cell-total { background: #eef2ff; color: var(--primary); font-weight: 800; }
        .cell-code { background: #f1f5f9; font-weight: 700; font-size: 10px; color: #64748b; }

        .tag-strength { background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; margin: 2px; display: inline-block; }
        .tag-weakness { background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; margin: 2px; display: inline-block; }

        .note-card {
            border-left: 4px solid var(--primary);
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 1rem; }
            .sidebar { position: relative; width: 100%; min-height: auto; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 sidebar d-none d-md-block">
                <div class="p-4 text-white fw-800 fs-5 mb-4">
                    <i class="bi bi-hexagon-fill text-primary me-2"></i> DISCOVERY
                </div>
                <div class="px-3">
                    @if(Auth::user()->role !== 'participant')
                        <a href="{{ route('dashboard') }}" class="nav-link mb-2"><i class="bi bi-grid-fill me-2"></i> Dashboard</a>
                        <a href="{{ route('centers.show', $center->id) }}" class="nav-link active"><i class="bi bi-arrow-left-circle me-2"></i> Volver al Centro</a>
                    @else
                        <a href="#" class="nav-link active"><i class="bi bi-person-circle me-2"></i> Mi Perfil</a>
                    @endif
                </div>
                <div class="mt-auto p-3 w-100 position-absolute bottom-0">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger w-100 border-0 d-flex align-items-center justify-content-center">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>

            <main class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <div>
                        <h1 class="fw-800 h2 mb-0">Expediente de Pareja</h1>
                        <p class="text-muted mb-0">{{ $pairName }} en {{ $center->name }}</p>
                    </div>
                    @if(Auth::user()->role !== 'participant')
                        <a href="{{ route('pdf.pair', $husband->id) }}" target="_blank" class="btn btn-danger rounded-pill px-4 fw-700 shadow-sm">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i> Generar Portada
                        </a>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="card-premium p-4 text-center">
                            @if($husband->pair_photo)
                                <img src="{{ asset('storage/' . $husband->pair_photo) }}" class="rounded-4 w-100 object-fit-cover shadow-sm mb-4" style="height: 250px;">
                            @else
                                <div class="bg-light rounded-4 d-flex align-items-center justify-content-center mb-4" style="height: 200px;">
                                    <i class="bi bi-people-fill display-1 text-muted opacity-25"></i>
                                </div>
                            @endif
                            <h4 class="fw-800 mb-1">{{ $pairName }}</h4>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <ul class="nav nav-pills nav-main shadow-sm" role="tablist">
                            {{-- Si es participante, mostramos su nombre con una estrella --}}
                            <li class="nav-item flex-fill">
                                <button class="nav-link @if(Auth::id() == $husband->id) active @endif" data-bs-toggle="tab" data-bs-target="#tab-husband">
                                    <i class="bi bi-gender-male me-2"></i> {{ $husband->name }} @if(Auth::id() == $husband->id) (Yo) @endif
                                </button>
                            </li>
                            @if($wife)
                                <li class="nav-item flex-fill">
                                    <button class="nav-link @if(Auth::id() == $wife->id) active @endif" data-bs-toggle="tab" data-bs-target="#tab-wife">
                                        <i class="bi bi-gender-female me-2"></i> {{ $wife->name }} @if(Auth::id() == $wife->id) (Yo) @endif
                                    </button>
                                </li>
                            @endif
                            @if(Auth::user()->role !== 'participant')
                                <li class="nav-item flex-fill"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bitacora"><i class="bi bi-journal-text me-2"></i>Bitácora de Pareja</button></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            @php $uData = [['id' => 'husband', 'u' => $husband, 'dones' => $hDones, 'mbti' => $hMbti], ['id' => 'wife', 'u' => $wife, 'dones' => $wDones, 'mbti' => $wMbti]]; @endphp
                            @foreach($uData as $data)
                                @if($data['u'])
                                <div class="tab-pane fade @if((Auth::id() == $data['u']->id) || (Auth::user()->role !== 'participant' && $data['id'] == 'husband')) show active @endif" id="tab-{{ $data['id'] }}">
                                    <ul class="nav nav-pills nav-sub" role="tablist">
                                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#{{ $data['id'] }}-resumen">✨ Resumen</button></li>
                                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#{{ $data['id'] }}-dones-tab">🛡️ Dones</button></li>
                                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#{{ $data['id'] }}-mbti-tab">🧠 Personalidad</button></li>
                                    </ul>

                                    <div class="tab-content">
                                        <!-- RESUMEN -->
                                        <div class="tab-pane fade show active" id="{{ $data['id'] }}-resumen">
                                            <div class="card-premium p-4">
                                                <div class="row g-4">
                                                    <div class="col-md-7 border-end">
                                                        <h6 class="fw-800 text-muted small mb-4">PERSONALIDAD (MBTI)</h6>
                                                        @if($data['mbti'])
                                                            @php $type = $data['mbti']->metadata['mbti_types'][0]; @endphp
                                                            <div class="d-flex align-items-center mb-4">
                                                                <span class="mbti-type-title me-3">{{ $type }}</span>
                                                                <div>
                                                                    <h5 class="fw-800 mb-0">{{ $mbtiInfo[$type]['name'] }}</h5>
                                                                </div>
                                                            </div>
                                                            <p class="fw-800 small mb-2 text-primary">FORTALEZAS:</p>
                                                            <div class="mb-3">
                                                                @foreach($mbtiInfo[$type]['strengths'] as $s) <span class="tag-strength">{{ $s }}</span> @endforeach
                                                            </div>
                                                            <p class="fw-800 small mb-2 text-danger">DEBILIDADES:</p>
                                                            <div>
                                                                @foreach($mbtiInfo[$type]['weaknesses'] as $w) <span class="tag-weakness">{{ $w }}</span> @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-center py-4 bg-light rounded-4">
                                                                <p class="text-muted fw-700">Test de Personalidad pendiente</p>
                                                                @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                                                    <a href="{{ route('tests.take', [$data['u']->id, 'mbti']) }}" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                                                        <i class="bi bi-play-fill me-1"></i> Realizar Test
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-5">
                                                        <h6 class="fw-800 text-muted small mb-4">DONES PRINCIPALES</h6>
                                                        @if($data['dones'])
                                                            @foreach(array_slice($data['dones']->metadata['dones_ranking'], 0, 4) as $d)
                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <span class="fw-700 small">{{ $d['name'] }}</span>
                                                                    <span class="fw-800 text-primary small">{{ $d['score'] }}</span>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="text-center py-4 bg-light rounded-4">
                                                                <p class="text-muted fw-700">Test de Dones pendiente</p>
                                                                @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                                                    <a href="{{ route('tests.take', [$data['u']->id, 'dones']) }}" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                                                        <i class="bi bi-play-fill me-1"></i> Realizar Test
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DONES DETALLE -->
                                        <div class="tab-pane fade" id="{{ $data['id'] }}-dones-tab">
                                            @if($data['dones'])
                                                <div class="card-premium p-4">
                                                    <div class="dones-grid mb-4">
                                                        @php
                                                            $cols = 14; $rows = 7;
                                                            $codes = ["Adm","Dis","Evan","Exh","Fe","Dar","Con","Lid","Mis","Past","Pro","Serv","Ense","Sab"];
                                                            $totals = array_fill(0, $cols, 0);
                                                            foreach($data['dones']->answers as $i => $ans) {
                                                                if($i < $cols * $rows) $totals[$i % $cols] += (int)($ans['answer'] ?? 0);
                                                            }
                                                        @endphp
                                                        @for($c = 0; $c < $cols; $c++) <div class="dones-cell cell-header">{{ $codes[$c] }}</div> @endfor
                                                        @for($c = 0; $c < $cols; $c++) <div class="dones-cell cell-total">{{ $totals[$c] }}</div> @endfor
                                                    </div>
                                                    <div class="row">
                                                        @foreach($data['dones']->metadata['dones_ranking'] as $idx => $don)
                                                            <div class="col-md-6 mb-2">
                                                                <div class="p-2 border rounded-3 d-flex justify-content-between align-items-center">
                                                                    <span class="fw-600 small">{{ $don['name'] }}</span>
                                                                    <span class="badge bg-light text-primary fw-800">{{ $don['score'] }}</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card-premium p-5 text-center">
                                                    <p class="text-muted fw-700">Aún no se ha realizado el test de dones.</p>
                                                    @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                                        <a href="{{ route('tests.take', [$data['u']->id, 'dones']) }}" class="btn btn-primary rounded-pill px-5 shadow-sm">Realizar Test</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- MBTI DETALLE -->
                                        <div class="tab-pane fade" id="{{ $data['id'] }}-mbti-tab">
                                            @if($data['mbti'])
                                                <div class="card-premium p-4">
                                                    <div class="bg-light p-4 rounded-4 mb-4">
                                                        @foreach([['E','I','Extroversión','Introversión'], ['S','N','Sensación','Intuición'], ['T','F','Pensamiento','Sentimiento'], ['J','P','Juicio','Percepción']] as $dim)
                                                            @php
                                                                $s = $data['mbti']->metadata['scores'] ?? [];
                                                                $v1 = $s[$dim[0]] ?? 0; $v2 = $s[$dim[1]] ?? 0;
                                                                $total = ($v1 + $v2) ?: 1;
                                                                $p = ($v1 >= $v2) ? ($v1 / $total) * 100 : ($v2 / $total) * 100;
                                                                $isLeft = $v1 >= $v2;
                                                            @endphp
                                                            <div class="mb-3">
                                                                <div class="d-flex justify-content-between mb-1 small fw-800 text-uppercase">
                                                                    <span class="{{ $isLeft ? 'text-primary' : 'text-muted' }}">{{ $dim[2] }}: {{ $v1 }}</span>
                                                                    <span class="{{ !$isLeft ? 'text-primary' : 'text-muted' }}">{{ $v2 }} :{{ $dim[3] }}</span>
                                                                </div>
                                                                <div class="dim-bar-container">
                                                                    <div class="dim-bar-fill shadow-sm" style="width: {{ $p }}%; margin-left: {{ $isLeft ? '0' : (100-$p).'%' }}"></div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @foreach($data['mbti']->metadata['mbti_types'] as $type)
                                                        <div class="p-3 border rounded-4">
                                                            <h5 class="fw-800 text-primary">{{ $type }} - {{ $mbtiInfo[$type]['name'] }}</h5>
                                                            <p class="small text-muted mb-0" style="line-height: 1.6;">{{ $mbtiInfo[$type]['description'] }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="card-premium p-5 text-center">
                                                    <p class="text-muted fw-700">Aún no se ha realizado el test de personalidad.</p>
                                                    @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                                        <a href="{{ route('tests.take', [$data['u']->id, 'mbti']) }}" class="btn btn-primary rounded-pill px-5 shadow-sm">Realizar Test</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach

                            <!-- TAB: BITÁCORA -->
                            @if(Auth::user()->role !== 'participant')
                            <div class="tab-pane fade" id="tab-bitacora">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="card-premium p-4 border-start border-primary border-4">
                                            <h5 class="fw-800 mb-4 text-primary d-flex align-items-center">
                                                <i class="bi bi-people-fill me-2"></i> Notas de la Pareja ({{ $pairName }})
                                            </h5>
                                            @forelse($pairNotes as $note)
                                                <div class="note-card bg-white border border-light shadow-sm p-3 mb-3 rounded-4">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="fw-800 small text-primary">{{ $note->author->name }} ({{ $note->author->staff_title ?? 'Admin' }})</span>
                                                        <span class="text-muted small" style="font-size: 11px;">{{ $note->created_at->format('d M, Y - H:i') }}</span>
                                                    </div>
                                                    <p class="mb-0 small text-secondary" style="line-height: 1.6;">{{ $note->content }}</p>
                                                </div>
                                            @empty
                                                <p class="text-muted small italic text-center py-3">No hay notas generales para esta pareja.</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card-premium p-4 border-start border-info border-4">
                                            <h5 class="fw-800 mb-4 text-info d-flex align-items-center">
                                                <i class="bi bi-person-circle me-2"></i> Sobre {{ $husband->name }}
                                            </h5>
                                            @forelse($hNotes as $note)
                                                <div class="note-card bg-white border border-light shadow-sm p-3 mb-3 rounded-4" style="border-left-color: #0ea5e9;">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="fw-800 small text-info">{{ $note->author->name }}</span>
                                                        <span class="text-muted small" style="font-size: 11px;">{{ $note->created_at->format('d/m/y H:i') }}</span>
                                                    </div>
                                                    <p class="mb-0 small text-secondary">{{ $note->content }}</p>
                                                </div>
                                            @empty
                                                <p class="text-muted small italic text-center py-3">Sin notas individuales.</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    @if($wife)
                                    <div class="col-md-6">
                                        <div class="card-premium p-4 border-start border-4" style="border-left-color: #ec4899 !important;">
                                            <h5 class="fw-800 mb-4 d-flex align-items-center" style="color: #ec4899;">
                                                <i class="bi bi-person-circle me-2"></i> Sobre {{ $wife->name }}
                                            </h5>
                                            @forelse($wNotes as $note)
                                                <div class="note-card bg-white border border-light shadow-sm p-3 mb-3 rounded-4" style="border-left-color: #ec4899;">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="fw-800 small" style="color: #ec4899;">{{ $note->author->name }}</span>
                                                        <span class="text-muted small" style="font-size: 11px;">{{ $note->created_at->format('d/m/y H:i') }}</span>
                                                    </div>
                                                    <p class="mb-0 small text-secondary">{{ $note->content }}</p>
                                                </div>
                                            @empty
                                                <p class="text-muted small italic text-center py-3">Sin notas individuales.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
