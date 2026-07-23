<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - {{ $result->user_name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #6366f1; padding-bottom: 20px; margin-bottom: 30px; }
        .title { font-size: 24px; color: #4338ca; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #64748b; }

        .section-title { font-size: 18px; font-weight: bold; color: #1e3a8a; margin-top: 25px; margin-bottom: 15px; border-left: 4px solid #6366f1; padding-left: 10px; }

        /* MBTI Styles */
        .mbti-type { font-size: 42px; font-weight: bold; color: #4f46e5; text-align: center; margin: 20px 0; letter-spacing: 5px; }
        .dimension-row { margin-bottom: 15px; }
        .dimension-labels { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px; }
        .bar-container { height: 12px; background-color: #e2e8f0; border-radius: 6px; width: 100%; position: relative; }
        .bar-fill { height: 100%; background-color: #6366f1; border-radius: 6px; }

        /* Dones Styles */
        .don-item { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .don-rank { display: inline-block; width: 25px; height: 25px; background: #4f46e5; color: white; border-radius: 50%; text-align: center; line-height: 25px; font-weight: bold; font-size: 12px; margin-right: 10px; }
        .don-name { font-weight: bold; font-size: 16px; }
        .don-score { float: right; font-weight: bold; color: #4f46e5; }

        /* Answers Grid */
        .answers-grid { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .answers-grid td { border: 1px solid #e2e8f0; padding: 5px; font-size: 10px; text-align: center; }
        .ans-num { color: #64748b; font-size: 8px; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; padding: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $result->test_type == 'mbti' ? 'Test de Personalidad MBTI' : 'Test de Dones Espirituales' }}</div>
        <div class="subtitle">Participante: {{ $result->user_name }} | Pareja: {{ $result->pair_name }}</div>
        <div class="subtitle">Fecha: {{ $result->completed_at->format('d/m/Y H:i') }}</div>
    </div>

    @if($result->test_type == 'mbti' && isset($result->metadata['scores']))
        <div class="section-title">Resultados del Perfil</div>
        <div class="mbti-type">{{ implode(' / ', $result->metadata['mbti_types'] ?? []) }}</div>

        @foreach([['E','I','Extroversión','Introversión'], ['S','N','Sensación','Intuición'], ['T','F','Pensamiento','Sentimiento'], ['J','P','Juicio','Percepción']] as $dim)
            @php
                $v1 = $result->metadata['scores'][$dim[0]] ?? 0;
                $v2 = $result->metadata['scores'][$dim[1]] ?? 0;
                $total = ($v1 + $v2) ?: 1;
                $p1 = ($v1 / $total) * 100;
                $p2 = ($v2 / $total) * 100;
                $isLeft = $v1 >= $v2;
            @endphp
            <div class="dimension-row">
                <div style="overflow: hidden;">
                    <span style="float: left; font-size: 11px; {{ $isLeft ? 'font-weight:bold; color:#4f46e5;' : '' }}">{{ $dim[2] }}: {{ $v1 }}</span>
                    <span style="float: right; font-size: 11px; {{ !$isLeft ? 'font-weight:bold; color:#4f46e5;' : '' }}">{{ $v2 }} :{{ $dim[3] }}</span>
                </div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: {{ max($p1, $p2) }}%; margin-left: {{ !$isLeft ? (100 - max($p1, $p2)) : 0 }}%"></div>
                </div>
            </div>
        @endforeach

    @elseif($result->test_type == 'dones' && isset($result->metadata['dones_ranking']))
        <div class="section-title">Dones Identificados (Orden de Fortaleza)</div>
        @foreach($result->metadata['dones_ranking'] as $index => $don)
            <div class="don-item">
                <span class="don-rank">{{ $index + 1 }}</span>
                <span class="don-name">{{ $don['code'] }}</span>
                <span class="don-score">{{ $don['score'] }} pts</span>
            </div>
        @endforeach
    @endif

    <div class="section-title">Detalle de Respuestas</div>
    <table class="answers-grid">
        @php $chunks = array_chunk($result->answers, 10); @endphp
        @foreach($chunks as $chunk)
            <tr>
                @foreach($chunk as $ans)
                    <td>
                        <div class="ans-num">Q{{ $ans['number'] }}</div>
                        <div style="font-weight: bold;">{{ $ans['answer'] }}</div>
                    </td>
                @endforeach
                {{-- Completar celdas vacías si el chunk es menor a 10 --}}
                @for($i = count($chunk); $i < 10; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
    </table>

    <div class="footer">
        Centro de Descubrimiento - Excelencia Cristiana &copy; {{ date('Y') }}
    </div>
</body>
</html>
