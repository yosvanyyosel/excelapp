<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expediente: {{ $pairName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --indigo-900: #1e1b4b;
            --indigo-700: #4338ca;
            --indigo-600: #4f46e5;
            --indigo-500: #6366f1;
            --indigo-400: #818cf8;
            --indigo-100: #e0e7ff;
            --indigo-50: #eef2ff;
            --bg-canvas: #f8fafc;
        }
        body { background-color: var(--bg-canvas); font-family: 'Inter', sans-serif; color: #1e293b; }
        .navbar { background-color: var(--indigo-900); padding: 1rem 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .card-flutter { border: none; border-radius: 24px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); margin-bottom: 2rem; overflow: hidden; background: white; }

        /* MBTI Styles */
        .mbti-type-card { background: white; border-radius: 24px; padding: 2rem; text-align: center; border: 1px solid var(--indigo-50); box-shadow: 0 4px 20px rgba(79, 70, 229, 0.05); margin-bottom: 2rem; }
        .mbti-type-title { font-size: 3.5rem; font-weight: 800; color: var(--indigo-600); letter-spacing: 3px; line-height: 1; margin: 10px 0; }

        .dim-progress-container { height: 14px; background: #e2e8f0; border-radius: 10px; position: relative; overflow: hidden; margin-bottom: 25px; }
        .dim-progress-fill { height: 100%; background: linear-gradient(90deg, var(--indigo-600), var(--indigo-400)); border-radius: 10px; transition: width 0.8s ease-out; }

        .interpretation-card { background: white; border-radius: 20px; border: 1px solid var(--indigo-50); margin-bottom: 1.5rem; transition: transform 0.2s; }
        .type-pill { background: var(--indigo-600); color: white; padding: 5px 15px; border-radius: 10px; font-weight: 800; font-size: 0.9rem; }

        /* Dones Styles */
        .dones-table-container { overflow-x: auto; background: white; border-radius: 20px; padding: 20px; border: 1px solid var(--indigo-50); margin-bottom: 2rem; }
        .dones-grid { display: grid; grid-template-columns: repeat(14, minmax(42px, 1fr)); gap: 2px; background: #f1f5f9; border: 1px solid #f1f5f9; }
        .dones-cell { background: white; padding: 10px 2px; text-align: center; font-size: 14px; }
        .c-num { color: #94a3b8; font-size: 10px; background: #f8fafc; font-weight: 600; }
        .c-total { font-weight: 800; color: var(--indigo-600); background: var(--indigo-50); font-size: 16px; }
        .c-code { font-weight: 700; font-size: 11px; background: #f1f5f9; color: #475569; }

        .rank-circle { width: 38px; height: 38px; border-radius: 50%; background: var(--indigo-50); color: var(--indigo-600); display: flex; align-items: center; justify-content: center; font-weight: 800; margin-right: 1.25rem; font-size: 1.1rem; }
        .rank-circle.top { background: var(--indigo-600); color: white; }
        .score-chip { background: var(--indigo-50); color: var(--indigo-600); padding: 5px 16px; border-radius: 20px; font-weight: 800; font-size: 0.9rem; }

        /* Tabs Styling */
        .nav-main { background: white; padding: 0.5rem; border-radius: 20px; border: 1px solid #edf2f7; margin-bottom: 2.5rem; }
        .nav-main .nav-link { border-radius: 15px; font-weight: 800; padding: 14px 30px; color: #64748b; border: none; }
        .nav-main .nav-link.active { background-color: var(--indigo-600); color: white; box-shadow: 0 4px 12px rgba(79,70,229,0.3); }

        .nav-sub { gap: 12px; border: none; margin-bottom: 2rem; }
        .nav-sub .nav-link { border: 1.5px solid #e2e8f0; border-radius: 14px; color: #64748b; font-weight: 700; padding: 10px 20px; background: white; transition: all 0.2s; }
        .nav-sub .nav-link.active { border-color: var(--indigo-600); color: var(--indigo-600); background: var(--indigo-50); }

        .tag-s { background: #ecfdf5; color: #065f46; padding: 4px 12px; border-radius: 8px; font-size: 0.85rem; margin-right: 6px; font-weight: 700; display: inline-block; margin-bottom: 5px; }
        .tag-w { background: #fef2f2; color: #991b1b; padding: 4px 12px; border-radius: 8px; font-size: 0.85rem; margin-right: 6px; font-weight: 700; display: inline-block; margin-bottom: 5px; }

        .btn-flutter { border-radius: 16px; padding: 12px 24px; font-weight: 800; transition: all 0.2s; border: none; text-transform: uppercase; letter-spacing: 1px; }
        .btn-flutter-primary { background: var(--indigo-600); color: white; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2); }
        .btn-flutter-warning { background: #f59e0b; color: white; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2); }

        .summary-header { font-size: 1.25rem; font-weight: 800; color: var(--indigo-900); margin-bottom: 1.5rem; display: flex; align-items: center; }
        .summary-header i { margin-right: 0.75rem; color: var(--indigo-600); }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-800" href="{{ route('centers.show', $center->id) }}">
                <i class="bi bi-chevron-left me-2"></i> EXPEDIENTE PAREJA
            </a>
            <span class="badge bg-white text-dark py-2 px-4 rounded-pill fw-800 shadow-sm">{{ $pairName }}</span>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="card card-flutter p-4 text-center">
                    @if($husband->pair_photo)
                        <img src="{{ asset('storage/' . $husband->pair_photo) }}" class="rounded-4 w-100 object-fit-cover shadow-sm mb-4" style="height: 250px;">
                    @else
                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center mb-4" style="height: 200px;">
                            <i class="bi bi-people-fill display-1 text-muted opacity-25"></i>
                        </div>
                    @endif
                    <h3 class="fw-800 mb-1" style="color: var(--indigo-900);">{{ $pairName }}</h3>
                    <p class="text-muted small fw-600"><i class="bi bi-geo-alt-fill me-1"></i> {{ $center->name }}</p>
                    <div class="d-grid mt-4">
                        <a href="{{ route('pdf.pair', $husband->id) }}" target="_blank" class="btn btn-danger btn-lg rounded-4 fw-800 shadow">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i> PORTADA PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <ul class="nav nav-pills nav-main shadow-sm" id="userTabs" role="tablist">
                    <li class="nav-item flex-fill text-center">
                        <button class="nav-link active w-100" id="h-tab" data-bs-toggle="tab" data-bs-target="#husband-content">
                            <i class="bi bi-gender-male me-2"></i>{{ $husband->name }}
                        </button>
                    </li>
                    @if($wife)
                    <li class="nav-item flex-fill text-center">
                        <button class="nav-link w-100" id="w-tab" data-bs-toggle="tab" data-bs-target="#wife-content">
                            <i class="bi bi-gender-female me-2"></i>{{ $wife->name }}
                        </button>
                    </li>
                    @endif
                </ul>

                <div class="tab-content">
                    @php
                        $usersArr = [
                            ['id' => 'husband', 'mbti' => $hMbti, 'dones' => $hDones, 'name' => $husband->name, 'obj' => $husband],
                            ['id' => 'wife', 'mbti' => $wMbti, 'dones' => $wDones, 'name' => $wife ? $wife->name : '', 'obj' => $wife]
                        ];
                    @endphp

                    @foreach($usersArr as $idx => $u)
                    @if($u['name'])
                    <div class="tab-pane fade {{ $idx == 0 ? 'show active' : '' }}" id="{{ $u['id'] }}-content">

                        <!-- Sub-tabs Menu -->
                        <ul class="nav nav-pills nav-sub" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#{{ $u['id'] }}-summary">✨ RESUMEN</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#{{ $u['id'] }}-dones">🛡️ TEST DE DONES</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#{{ $u['id'] }}-mbti">🧠 PERSONALIDAD</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- TAB 1: RESUMEN EJECUTIVO -->
                            <div class="tab-pane fade show active" id="{{ $u['id'] }}-summary">
                                <div class="card card-flutter p-4" style="background: linear-gradient(145deg, #ffffff 0%, #fdfdff 100%);">
                                    <div class="row">
                                        <div class="col-md-7 border-end border-light">
                                            <div class="summary-header"><i class="bi bi-person-badge"></i>Personalidad Identificada</div>
                                            @if($u['mbti'])
                                                @php $type = $u['mbti']->metadata['mbti_types'][0] ?? 'N/A'; @endphp
                                                <div class="d-flex align-items-center mb-4">
                                                    <span class="mbti-title me-3" style="font-size: 2.8rem; color: var(--indigo-600)">{{ $type }}</span>
                                                    <div>
                                                        <h5 class="fw-800 mb-0">{{ $mbtiInfo[$type]['name'] ?? 'Perfil MBTI' }}</h5>
                                                        <small class="text-muted fw-600">Dominante</small>
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <p class="fw-800 small text-uppercase mb-2 text-primary">Principales Fortalezas:</p>
                                                    @foreach($mbtiInfo[$type]['strengths'] ?? [] as $st)
                                                        <span class="tag-s">{{ $st }}</span>
                                                    @endforeach
                                                </div>
                                                <div>
                                                    <p class="fw-800 small text-uppercase mb-2 text-danger">Áreas de Mejora:</p>
                                                    @foreach($mbtiInfo[$type]['weaknesses'] ?? [] as $wk)
                                                        <span class="tag-w">{{ $wk }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-5 bg-light rounded-4">
                                                    <i class="bi bi-shield-lock display-4 text-muted mb-3 opacity-25"></i>
                                                    <p class="text-muted fw-800 mb-3 small">TEST DE PERSONALIDAD PENDIENTE</p>
                                                    <a href="{{ route('tests.take', [$u['obj']->id, 'mbti']) }}" class="btn btn-flutter btn-flutter-primary px-4 shadow-sm">
                                                        <i class="bi bi-play-circle-fill me-2"></i>EMPEZAR AHORA
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-5 ps-4">
                                            <div class="summary-header"><i class="bi bi-stars text-warning"></i>4 Dones más Fuertes</div>
                                            @if($u['dones'])
                                                @foreach(array_slice($u['dones']->metadata['dones_ranking'] ?? [], 0, 4) as $index => $don)
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="rank-circle top" style="width:34px; height:34px; font-size: 14px;">{{ $index + 1 }}</div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-800 small text-dark">{{ $don['name'] }}</div>
                                                            <div class="progress" style="height: 6px; border-radius: 10px;">
                                                                <div class="progress-bar bg-primary rounded-pill" style="width: {{ ($don['score']/35)*100 }}%"></div>
                                                            </div>
                                                        </div>
                                                        <span class="ms-3 fw-800 text-primary small">{{ $don['score'] }} pts</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center py-5 bg-light rounded-4">
                                                    <i class="bi bi-lightning-charge display-4 text-muted mb-3 opacity-25"></i>
                                                    <p class="text-muted fw-800 mb-3 small">TEST DE DONES PENDIENTE</p>
                                                    <a href="{{ route('tests.take', [$u['obj']->id, 'dones']) }}" class="btn btn-flutter btn-flutter-warning px-4 shadow-sm">
                                                        <i class="bi bi-play-circle-fill me-2"></i>EMPEZAR AHORA
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: DONES DETALLADO -->
                            <div class="tab-pane fade" id="{{ $u['id'] }}-dones">
                                @if($u['dones'])
                                    <div class="card card-flutter p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-800 mb-0 text-indigo-900"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Cuadro de Calificación Técnica</h5>
                                            <a href="{{ route('results.print', $u['dones']->id) }}" target="_blank" class="btn btn-sm btn-outline-dark px-3 rounded-pill fw-800">
                                                <i class="bi bi-printer-fill me-2"></i>IMPRIMIR PDF
                                            </a>
                                        </div>
                                        <div class="dones-table-container shadow-sm mb-5">
                                            @php
                                                $cols = 14; $rows = 7;
                                                $codes = ["Adm","Dis","Evan","Exh","Fe","Dar","Con","Lid","Mis","Past","Pro","Serv","Ense","Sab"];
                                                $grid = array_fill(0, $rows, array_fill(0, $cols, 0));
                                                $totals = array_fill(0, $cols, 0);
                                                foreach($u['dones']->answers as $i => $ans) {
                                                    $r = (int)($i / $cols); $c = $i % $cols;
                                                    if($r < $rows) {
                                                        $grid[$r][$c] = (int)($ans['answer'] ?? 0);
                                                        $totals[$c] += $grid[$r][$c];
                                                    }
                                                }
                                            @endphp
                                            <div class="dones-grid">
                                                @for($r = 0; $r < $rows; $r++)
                                                    @for($c = 0; $c < $cols; $c++) <div class="dones-cell c-num">{{ ($r * $cols) + $c + 1 }}</div> @endfor
                                                    @for($c = 0; $c < $cols; $c++) <div class="dones-cell fw-600">{{ $grid[$r][$c] }}</div> @endfor
                                                @endfor
                                                @for($c = 0; $c < $cols; $c++) <div class="dones-cell c-total">{{ $totals[$c] }}</div> @endfor
                                                @for($c = 0; $c < $cols; $c++) <div class="dones-cell c-code">{{ $codes[$c] }}</div> @endfor
                                            </div>
                                        </div>
                                        <h5 class="fw-800 mb-4 text-indigo-900"><i class="bi bi-award-fill me-2 text-warning"></i>Ranking de Fortaleza Completo</h5>
                                        <div class="border rounded-4 overflow-hidden shadow-sm">
                                            @foreach($u['dones']->metadata['dones_ranking'] ?? [] as $idx => $don)
                                                <div class="rank-row {{ $idx % 2 == 0 ? 'bg-white' : 'bg-light' }}">
                                                    <div class="rank-circle {{ $idx < 3 ? 'top' : '' }}">{{ $idx + 1 }}</div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-800 text-dark">{{ $don['name'] }}</div>
                                                        <small class="text-muted fw-800 text-uppercase" style="font-size: 11px;">{{ $don['code'] }}</small>
                                                    </div>
                                                    <span class="score-chip shadow-sm">{{ $don['score'] }} pts</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <div class="p-5">
                                            <i class="bi bi-lightning-charge-fill display-2 text-warning opacity-25 mb-4"></i>
                                            <h4 class="fw-800 text-muted">Test de Dones no disponible</h4>
                                            <p class="text-muted mb-4 fw-600">Este participante aún no ha evaluado sus dones espirituales.</p>
                                            <a href="{{ route('tests.take', [$u['obj']->id, 'dones']) }}" class="btn btn-flutter btn-flutter-warning btn-lg px-5 shadow">
                                                <i class="bi bi-play-fill me-1"></i> EMPEZAR TEST DE DONES
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- TAB 3: PERSONALIDAD DETALLADO -->
                            <div class="tab-pane fade" id="{{ $u['id'] }}-mbti">
                                @if($u['mbti'])
                                    <div class="card card-flutter p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-800 mb-0 text-indigo-900"><i class="bi bi-bar-chart-steps me-2"></i>Análisis de Dimensiones</h5>
                                            <a href="{{ route('results.print', $u['mbti']->id) }}" target="_blank" class="btn btn-sm btn-outline-dark px-3 rounded-pill fw-800">
                                                <i class="bi bi-printer-fill me-2"></i>IMPRIMIR PDF
                                            </a>
                                        </div>
                                        <div class="bg-light p-4 rounded-4 shadow-inner mb-4">
                                            @foreach([['E','I','Extroversión','Introversión'], ['S','N','Sensación','Intuición'], ['T','F','Pensamiento','Sentimiento'], ['J','P','Juicio','Percepción']] as $dim)
                                                @php
                                                    $s = $u['mbti']->metadata['scores'] ?? [];
                                                    $v1 = $s[$dim[0]] ?? 0; $v2 = $s[$dim[1]] ?? 0;
                                                    $total = ($v1 + $v2) ?: 1;
                                                    $p = ($v1 >= $v2) ? ($v1 / $total) * 100 : ($v2 / $total) * 100;
                                                    $isLeft = $v1 >= $v2;
                                                @endphp
                                                <div class="mb-4">
                                                    <div class="d-flex justify-content-between mb-2 small fw-800 text-uppercase">
                                                        <span class="{{ $isLeft ? 'text-primary' : 'text-muted' }}">{{ $dim[2] }}: {{ $v1 }}</span>
                                                        <span class="{{ !$isLeft ? 'text-primary' : 'text-muted' }}">{{ $v2 }} :{{ $dim[3] }}</span>
                                                    </div>
                                                    <div class="dim-bar-bg">
                                                        <div class="dim-bar-fill shadow-sm" style="width: {{ $p }}%; margin-left: {{ $isLeft ? '0' : (100-$p).'%' }}"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @foreach($u['mbti']->metadata['mbti_types'] ?? [] as $type)
                                            <div class="interpretation-card p-4 shadow-sm border border-light">
                                                <div class="d-flex align-items-center mb-3">
                                                    <span class="type-pill me-3 shadow-sm">{{ $type }}</span>
                                                    <h5 class="mb-0 fw-800 text-dark">{{ $mbtiInfo[$type]['name'] ?? '' }}</h5>
                                                </div>
                                                <p class="text-muted fw-600 mb-0" style="line-height: 1.6;">{{ $mbtiInfo[$type]['description'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <div class="p-5">
                                            <i class="bi bi-person-vcard-fill display-2 text-primary opacity-25 mb-4"></i>
                                            <h4 class="fw-800 text-muted">Test de Personalidad no disponible</h4>
                                            <p class="text-muted mb-4 fw-600">Este participante aún no ha realizado el análisis de personalidad MBTI.</p>
                                            <a href="{{ route('tests.take', [$u['obj']->id, 'mbti']) }}" class="btn btn-flutter btn-flutter-primary btn-lg px-5 shadow">
                                                <i class="bi bi-play-fill me-1"></i> EMPEZAR TEST MBTI
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
