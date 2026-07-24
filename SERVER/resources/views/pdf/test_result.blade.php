<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Resultados - {{ $result->user_name }}</title>
    <style>
        @page { margin: 1.5cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; line-height: 1.4; font-size: 11px; }
        .header { text-align: center; border-bottom: 3px solid #4f46e5; padding-bottom: 15px; margin-bottom: 25px; }
        .title { font-size: 24px; font-weight: bold; color: #1e1b4b; margin-bottom: 5px; text-transform: uppercase; }
        .subtitle { font-size: 12px; color: #64748b; }

        .section-title { font-size: 14px; font-weight: bold; color: #1e1b4b; margin: 25px 0 12px 0; border-left: 4px solid #4f46e5; padding-left: 10px; background: #f8fafc; padding-top: 5px; padding-bottom: 5px; }

        /* MBTI Styles */
        .mbti-hero { background: #f1f5f9; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 20px; border: 1px solid #e2e8f0; }
        .mbti-type { font-size: 38px; font-weight: bold; color: #4f46e5; letter-spacing: 6px; margin: 5px 0; }
        .mbti-name { font-size: 18px; font-weight: bold; color: #334155; margin-bottom: 10px; }

        .dim-row { margin-bottom: 12px; }
        .dim-text { overflow: hidden; margin-bottom: 4px; font-size: 10px; font-weight: bold; }
        .progress-bg { height: 10px; background: #e2e8f0; border-radius: 5px; position: relative; }
        .progress-fill { height: 100%; background: #4f46e5; border-radius: 5px; }

        .detail-box { border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; margin-bottom: 15px; }
        .tag { display: inline-block; padding: 3px 8px; border-radius: 5px; font-size: 9px; margin-right: 5px; margin-bottom: 5px; font-weight: bold; }
        .tag-s { background: #ecfdf5; color: #065f46; }
        .tag-w { background: #fef2f2; color: #991b1b; }

        /* Dones Styles */
        .table-dones { width: 100%; border-collapse: collapse; text-align: center; margin-bottom: 25px; }
        .table-dones td { border: 1px solid #e2e8f0; padding: 4px; font-size: 9px; }
        .header-cell { background: #f8fafc; color: #94a3b8; font-size: 7px; }
        .total-cell { background: #eef2ff; color: #4f46e5; font-weight: bold; font-size: 11px; }
        .code-cell { background: #f1f5f9; font-weight: bold; color: #475569; }

        .rank-item { padding: 10px 15px; border-bottom: 1px solid #f1f5f9; clear: both; }
        .rank-num { float: left; width: 25px; height: 25px; background: #4f46e5; color: white; border-radius: 50%; text-align: center; line-height: 25px; font-weight: bold; margin-right: 15px; }
        .rank-label { font-weight: bold; font-size: 12px; color: #1e293b; }
        .rank-pts { float: right; font-weight: bold; color: #4f46e5; background: #f5f3ff; padding: 3px 10px; border-radius: 15px; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $result->test_type == 'mbti' ? 'Resultados de Personalidad' : 'Resultados de Dones' }}</div>
        <div class="subtitle">
            <strong>Participante:</strong> {{ $result->user_name }} &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Centro:</strong> {{ $result->center_name ?? 'N/A' }} &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Fecha:</strong> {{ $result->completed_at->format('d/m/Y H:i') }}
        </div>
    </div>

    @if($result->test_type == 'mbti' && isset($result->metadata['scores']))
        @foreach($result->metadata['mbti_types'] ?? [] as $type)
            <div class="mbti-hero">
                <div style="font-size: 10px; color: #64748b; text-transform: uppercase;">Perfil Identificado</div>
                <div class="mbti-type">{{ $type }}</div>
                <div class="mbti-name">{{ $mbtiInfo[$type]['name'] ?? '' }}</div>
            </div>

            <div class="section-title">Análisis de Dimensiones</div>
            @foreach([['E','I','Extroversión','Introversión'], ['S','N','Sensación','Intuición'], ['T','F','Pensamiento','Sentimiento'], ['J','P','Juicio','Percepción']] as $dim)
                @php
                    $v1 = $result->metadata['scores'][$dim[0]] ?? 0;
                    $v2 = $result->metadata['scores'][$dim[1]] ?? 0;
                    $total = ($v1 + $v2) ?: 1;
                    $p = max($v1, $v2) / $total * 100;
                    $leftWins = $v1 >= $v2;
                @endphp
                <div class="dim-row">
                    <div class="dim-text">
                        <span style="float: left; {{ $leftWins ? 'color:#4f46e5;' : 'color:#94a3b8;' }}">{{ $dim[2] }}: {{ $v1 }}</span>
                        <span style="float: right; {{ !$leftWins ? 'color:#4f46e5;' : 'color:#94a3b8;' }}">{{ $v2 }} :{{ $dim[3] }}</span>
                    </div>
                    <div class="progress-bg">
                        <div class="progress-fill" style="width: {{ $p }}%; margin-left: {{ $leftWins ? '0' : (100 - $p) . '%' }}"></div>
                    </div>
                </div>
            @endforeach

            @if(isset($mbtiInfo[$type]))
            <div class="section-title">Interpretación y Rasgos</div>
            <div class="detail-box">
                <p style="margin-top: 0; color: #475569; font-size: 10.5px;">{{ $mbtiInfo[$type]['description'] }}</p>
                <div style="margin-top: 15px;">
                    <div style="font-weight: bold; margin-bottom: 5px; color: #065f46;">Fortalezas Principales:</div>
                    @foreach($mbtiInfo[$type]['strengths'] as $st)
                        <span class="tag tag-s">{{ $st }}</span>
                    @endforeach
                </div>
                <div style="margin-top: 10px;">
                    <div style="font-weight: bold; margin-bottom: 5px; color: #991b1b;">Áreas de Oportunidad / Debilidades:</div>
                    @foreach($mbtiInfo[$type]['weaknesses'] as $wk)
                        <span class="tag tag-w">{{ $wk }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

    @elseif($result->test_type == 'dones')
        <div class="section-title">Cuadro de Calificación Técnica</div>
        <table class="table-dones">
            @php
                $cols = 14; $rows = 7;
                $codes = ["Adm","Dis","Evan","Exh","Fe","Dar","Con","Lid","Mis","Past","Pro","Serv","Ense","Sab"];
                $grid = array_fill(0, $rows, array_fill(0, $cols, 0));
                $totals = array_fill(0, $cols, 0);
                foreach($result->answers as $i => $ans) {
                    $r = (int)($i / $cols); $c = $i % $cols;
                    if($r < $rows) {
                        $val = (int)($ans['answer'] ?? 0);
                        $grid[$r][$c] = $val;
                        $totals[$c] += $val;
                    }
                }
            @endphp
            @for($r = 0; $r < $rows; $r++)
                <tr>
                    @for($c = 0; $c < $cols; $c++)
                        <td class="header-cell">{{ ($r * $cols) + $c + 1 }}</td>
                    @endfor
                </tr>
                <tr>
                    @for($c = 0; $c < $cols; $c++)
                        <td>{{ $grid[$r][$c] }}</td>
                    @endfor
                </tr>
            @endfor
            <tr>
                @for($c = 0; $c < $cols; $c++)
                    <td class="total-cell">{{ $totals[$c] }}</td>
                @endfor
            </tr>
            <tr>
                @for($c = 0; $c < $cols; $c++)
                    <td class="code-cell">{{ $codes[$c] }}</td>
                @endfor
            </tr>
        </table>

        <div class="section-title">Ranking de Dones Espirituales</div>
        <div style="border: 1px solid #e2e8f0; border-radius: 10px;">
            @foreach($result->metadata['dones_ranking'] ?? [] as $index => $don)
                <div class="rank-item">
                    <div class="rank-num">{{ $index + 1 }}</div>
                    <span class="rank-label">{{ $donesInfo[$don['code']] ?? $don['code'] }}</span>
                    <span style="color: #64748b; font-size: 10px;">({{ $don['code'] }})</span>
                    <span class="rank-pts">{{ $don['score'] }} pts</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="footer">
        Centro de Descubrimiento - Excelencia Cristiana &copy; {{ date('Y') }} - Sistema de Gestión de Parejas
    </div>
</body>
</html>
