<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de {{ $result->test_type == 'mbti' ? 'Personalidad MBTI' : 'Dones'}} - {{ $result->user_name }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1a1a1a;
            line-height: 1.4;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .footer {
            position: fixed;
            bottom: -1cm;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .header-box {
            text-align: center;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .brand {
            color: #22c55e;
            font-weight: 800;
            font-size: 16px;
            margin-bottom: 2px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin: 15px 0 8px 0;
            border-left: 4px solid #22c55e;
            padding-left: 8px;
            background: #f9fafb;
        }

        .page-break {
            page-break-after: always;
        }

        /* MBTI Visual Styles - Exact Match to Image */
        .mbti-container {
            padding: 10px 0;
        }
        .mbti-row {
            margin-bottom: 25px;
            width: 100%;
        }
        .mbti-labels {
            width: 100%;
            height: 16px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .label-left {
            float: left;
            width: 50%;
            text-align: left;
        }
        .label-right {
            float: right;
            width: 50%;
            text-align: right;
        }
        .mbti-bar-bg {
            height: 12px;
            background: #f3f4f6;
            border-radius: 6px;
            width: 100%;
            position: relative;
            clear: both;
        }
        .mbti-bar-fill {
            height: 100%;
            background: #22c55e;
            border-radius: 6px;
            position: absolute;
            top: 0;
        }

        /* Dones Visual Styles - Exact Match to Image */
        .dones-container {
            width: 100%;
            margin-top: 10px;
        }
        .dones-card {
            width: 82mm;
            display: inline-block;
            margin: 0.5%;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff;
            box-sizing: border-box;
            vertical-align: top;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .dones-name {
            font-size: 11px;
            color: #334155;
            font-weight: 500;
            width: 75%;
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
        }
        .dones-badge {
            float: right;
            background: #007bff;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 0;
            border-radius: 6px;
            width: 22px;
            text-align: center;
            vertical-align: middle;
            margin-top: -1px;
        }

        /* Technical Table */
        .tech-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 8px;
        }
        .tech-table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: center;
        }
        .cell-num { background: #f9fafb; color: #999; font-size: 7px; }
        .cell-total { background: #f0fdf4; font-weight: bold; color: #22c55e; font-size: 10px; }
        .cell-code { font-weight: bold; background: #f9fafb; }

        .tag { display: inline-block; padding: 3px 8px; border-radius: 5px; font-size: 8.5px; margin: 1px; font-weight: bold; }
        .tag-green { background: #dcfce7; color: #166534; }
        .tag-red { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 8;
            $color = array(0.5, 0.5, 0.5);
            $pdf->page_text(270, 760, $text, $font, $size, $color);
        }
    </script>

    <div class="footer">
        {{ $result->center_name ?? 'EXCEL' }} - Resultados del Test
    </div>

    @if($result->test_type == 'mbti')
        @foreach($result->metadata['mbti_types'] ?? [] as $type)
            <div class="page-break">
                <div class="header-box">
                    <div class="brand">
                    <img src="./logoexcel.png"  style="width: 40px; height: 40px" class="fw-800 fs-4"/>
                </div>
                    <div class="title">Resultados de Personalidad</div>
                </div>

                <div style="text-align: center; padding: 25px; background: #f0fdf4; border-radius: 15px; margin-bottom: 25px;">
                    <div style="font-size: 10px; color: #666; font-weight: bold; text-transform: uppercase;">Perfil Identificado</div>
                    <div style="font-size: 60px; font-weight: 900; color: #22c55e; letter-spacing: 10px; margin: 10px 0;">{{ $type }}</div>
                    <div style="font-size: 24px; font-weight: bold; color: #000;">{{ $mbtiInfo[$type]['name'] ?? '' }}</div>
                    <div style="margin-top: 15px; font-size: 11px; color: #4b5563;">
                        {{ $result->user_name }} &bull; {{ $result->completed_at->format('d/m/Y') }}
                    </div>
                </div>

                <div class="section-title">Análisis de Dimensiones</div>

                <div class="mbti-container">
                    @php
                        $mScores = $result->metadata['scores'] ?? [];
                        $dims = [
                            ['E', 'EXTROVERSIÓN', 'I', 'INTROVERSIÓN'],
                            ['S', 'SENSACIÓN', 'N', 'INTUICIÓN'],
                            ['T', 'PENSAMIENTO', 'F', 'SENTIMIENTO'],
                            ['J', 'JUICIO', 'P', 'PERCEPCIÓN']
                        ];
                        // In the 72-question MBTI, there are 9 questions per dimension.
                        $maxPossible = 9;
                    @endphp

                    @foreach($dims as $d)
                        @php
                            $vLeft = $mScores[$d[0]] ?? 0;
                            $vRight = $mScores[$d[2]] ?? 0;
                            $isLeftDominant = $vLeft >= $vRight;
                            // Calculate percentage for the bar based on the dominant score
                            $dominantScore = $isLeftDominant ? $vLeft : $vRight;
                            $percent = ($dominantScore / $maxPossible) * 100;
                            $percent = min($percent, 100);
                        @endphp
                        <div class="mbti-row">
                            <div class="mbti-labels">
                                <span class="label-left" style="color: {{ $isLeftDominant ? '#007bff' : '#6b7280' }}">
                                    {{ $d[1] }}: {{ $vLeft }}
                                </span>
                                <span class="label-right" style="color: {{ !$isLeftDominant ? '#007bff' : '#6b7280' }}">
                                    {{ $vRight }}: {{ $d[3] }}
                                </span>
                            </div>
                            <div class="mbti-bar-bg">
                                <div class="mbti-bar-fill" style="width: {{ $percent }}%; {{ $isLeftDominant ? 'left: 0;' : 'right: 0;' }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="page-break">
                <div class="header-box">
                    <div class="brand">
                    <img src="./logoexcel.png"  style="width: 40px; height: 40px" class="fw-800 fs-4"/></div>
                    <div class="title">{{ $type }} - {{ $mbtiInfo[$type]['name'] ?? '' }}</div>
                </div>

                <div class="section-title">Descripción Detallada</div>
                <div style="padding: 20px; background: #fff; border: 1px solid #eee; border-radius: 12px; line-height: 1.6;">
                    <p style="font-size: 11px; color: #374151; text-align: justify;">{{ $mbtiInfo[$type]['description'] ?? '' }}</p>

                    <div style="margin-top: 25px;">
                        <h4 style="color: #166534; font-size: 12px; margin-bottom: 10px; border-bottom: 1px solid #dcfce7; padding-bottom: 5px;">Fortalezas Principales</h4>
                        @foreach($mbtiInfo[$type]['strengths'] ?? [] as $s)
                            <span class="tag tag-green">{{ $s }}</span>
                        @endforeach
                    </div>

                    <div style="margin-top: 20px;">
                        <h4 style="color: #991b1b; font-size: 12px; margin-bottom: 10px; border-bottom: 1px solid #fee2e2; padding-bottom: 5px;">Áreas de Crecimiento</h4>
                        @foreach($mbtiInfo[$type]['weaknesses'] ?? [] as $w)
                            <span class="tag tag-red">{{ $w }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

    @elseif($result->test_type == 'dones')
        <div class="header-box">
            <div class="brand">
                    <img src="./logoexcel.png"  style="width: 40px; height: 40px" class="fw-800 fs-4"/></div>
            <div class="title">Resultado de Dones Espirituales</div>
            <p style="margin-top: 5px; font-weight: bold; font-size: 11px;">{{ $result->user_name }} &bull; {{ $result->pair_name }}</p>
        </div>

        <div class="section-title">Desglose Técnico de Calificación</div>
        <table class="tech-table">
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
                        <td class="cell-num">{{ ($r * $cols) + $c + 1 }}</td>
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
                    <td class="cell-total">{{ $totals[$c] }}</td>
                @endfor
            </tr>
            <tr>
                @for($c = 0; $c < $cols; $c++)
                    <td class="cell-code">{{ $codes[$c] }}</td>
                @endfor
            </tr>
        </table>

        <div class="section-title" style="margin-top: 20px;">Ranking de Dones</div>
        <div class="dones-container">
            @foreach($result->metadata['dones_ranking'] ?? [] as $don)
                <div class="dones-card">
                    <div class="dones-name">{{ $donesInfo[$don['code']] ?? $don['code'] }}</div>
                    <div class="dones-badge">{{ $don['score'] }}</div>
                </div>
            @endforeach
            <div style="clear: both; height: 1px;"></div>
        </div>

    @endif
</body>
</html>
