@extends('layouts.admin')

@section('title', 'Expediente: ' . $pairName)
@section('page_title', 'Expediente de Pareja')

@section('sidebar_menu')
    @if(Auth::user()->role !== 'participant')
        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="bi bi-grid-fill"></i>
            <span class="sidebar-text-hide">Dashboard</span>
        </a>
        <a href="{{ route('centers.show', $center->id) }}" class="nav-link active">
            <i class="bi bi-arrow-left-circle"></i>
            <span class="sidebar-text-hide">Volver al Centro</span>
        </a>
    @else
        <a href="#" class="nav-link active">
            <i class="bi bi-person-circle"></i>
            <span class="sidebar-text-hide">Mi Perfil</span>
        </a>
    @endif
@endsection

@section('styles')
<style>
    .nav-main {
        background: var(--bg-card);
        padding: 0.5rem;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .nav-main .nav-link {
        border-radius: 12px;
        font-weight: 700;
        padding: 12px 24px;
        color: var(--text-muted);
        border: none;
        flex: 1;
        text-align: center;
        margin: 0;
    }

    .nav-main .nav-link.active {
        background-color: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3);
    }

    .nav-sub {
        gap: 10px;
        border: none;
        margin-bottom: 2rem;
    }

    .nav-sub .nav-link {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        color: var(--text-muted);
        font-weight: 700;
        padding: 8px 20px;
        background: var(--bg-card);
    }

    .nav-sub .nav-link.active {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(var(--primary-rgb), 0.1);
    }

    .mbti-type-title {
        font-size: 3rem;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: 2px;
    }

    .dim-bar-container {
        height: 12px;
        background: var(--bg-body);
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
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
        background: var(--border-color);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
    }

    .dones-cell {
        background: var(--bg-card);
        padding: 10px 2px;
        text-align: center;
        font-size: 12px;
    }

    .cell-header { background: var(--bg-body); color: var(--text-muted); font-size: 9px; font-weight: 700; }
    .cell-total { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); font-weight: 800; }

    .tag-strength { background: rgba(34, 197, 94, 0.1); color: #16a34a; padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; margin: 2px; display: inline-block; }
    .tag-weakness { background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; margin: 2px; display: inline-block; }

    .evaluation-item {
        border-bottom: 1px solid var(--border-color);
        padding: 12px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .decision-badge {
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
    }

    .decision-green { background: #dcfce7; color: #166534; border: 2px solid #22c55e; }
    .decision-yellow { background: #fef9c3; color: #854d0e; border: 2px solid #eab308; }
    .decision-red { background: #fee2e2; color: #991b1b; border: 2px solid #ef4444; }

    .note-card {
        border-left: 4px solid var(--primary);
        background: var(--bg-body);
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 1rem;
        border-right: 1px solid var(--border-color);
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-4">
        <div>
            <h1 class="fw-800 h2 mb-1">{{ $pairName }}</h1>
            <p class="text-muted mb-0">Evento: {{ $center->name }}</p>
        </div>
        @if(Auth::user()->role !== 'participant')
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-dark dark:btn-light rounded-pill px-4 fw-700 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDecision">
                    <i class="bi bi-flag-fill me-2"></i> Decisión Final
                </button>
                <a href="{{ route('pdf.pair', $husband->id) }}" target="_blank" class="btn btn-primary rounded-pill px-4 fw-700 shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Reporte PDF
                </a>
            </div>
        @endif
    </div>

    @if($pairEvaluation && $showDecision)
        <div class="card p-4 mb-4 text-center border-start border-5 @if($pairEvaluation->decision == 'green') border-success @elseif($pairEvaluation->decision == 'yellow') border-warning @else border-danger @endif">
            <h6 class="text-muted small fw-800 mb-3">RECOMENDACIÓN FINAL DEL CENTRO</h6>
            <span class="decision-badge @if($pairEvaluation->decision == 'green') decision-green @elseif($pairEvaluation->decision == 'yellow') decision-yellow @else decision-red @endif">
                {{ $pairEvaluation->decision_text }}
            </span>
            @if($pairEvaluation->visible_at)
                <p class="mt-3 small text-muted">Programado para: {{ $pairEvaluation->visible_at->format('d/m/Y H:i') }}</p>
            @endif
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card p-4 text-center">
                @if($husband->pair_photo)
                    <img src="{{ asset('storage/' . $husband->pair_photo) }}" class="rounded-4 w-100 object-fit-cover shadow-sm mb-4" style="height: 250px;">
                @else
                    <div class="bg-light dark:bg-dark rounded-4 d-flex align-items-center justify-content-center mb-4 border" style="height: 250px;">
                        <i class="bi bi-people-fill display-1 text-muted opacity-25"></i>
                    </div>
                @endif
                <h4 class="fw-800 mb-0">{{ $pairName }}</h4>
            </div>
        </div>

        <div class="col-lg-9">
            <ul class="nav nav-pills nav-main shadow-sm" role="tablist">
                <li class="nav-item flex-fill">
                    <button class="nav-link @if(Auth::id() == $husband->id) active @elseif(Auth::user()->role !== 'participant') active @endif" data-bs-toggle="tab" data-bs-target="#tab-husband">
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
                    <li class="nav-item flex-fill"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bitacora"><i class="bi bi-journal-text me-2"></i>Bitácora</button></li>
                @endif
            </ul>

            <div class="tab-content">
                @php $uData = [['id' => 'husband', 'u' => $husband, 'dones' => $hDones, 'mbti' => $hMbti], ['id' => 'wife', 'u' => $wife, 'dones' => $wDones, 'mbti' => $wMbti]]; @endphp
                @foreach($uData as $data)
                    @if($data['u'])
                    <div class="tab-pane fade @if((Auth::id() == $data['u']->id) || (Auth::user()->role !== 'participant' && $data['id'] == 'husband')) show active @endif" id="tab-{{ $data['id'] }}">
                        <ul class="nav nav-pills nav-sub" role="tablist">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#{{ $data['id'] }}-resumen">Resumen</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#{{ $data['id'] }}-dones-tab">Dones</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#{{ $data['id'] }}-mbti-tab">Personalidad</button></li>
                        </ul>

                        <div class="tab-content">
                            <!-- RESUMEN -->
                            <div class="tab-pane fade show active" id="{{ $data['id'] }}-resumen">
                                <div class="card p-4 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5 class="fw-800 mb-0">Evaluación de Desempeño</h5>
                                        @if(Auth::user()->role !== 'participant')
                                            <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="openEvalModal({{ $data['u']->id }}, '{{ addslashes($data['u']->name) }}')">
                                                <i class="bi bi-plus-lg me-1"></i> Agregar
                                            </button>
                                        @endif
                                    </div>
                                    <div class="row g-4">
                                        @foreach(['strength' => 'Fortalezas', 'growth' => 'Áreas de Crecimiento', 'suggestion' => 'Sugerencias'] as $type => $label)
                                            <div class="col-md-4">
                                                <h6 class="small fw-800 text-muted border-bottom pb-2 mb-3">{{ strtoupper($label) }}</h6>
                                                @forelse($data['u']->evaluationItems->where('type', $type) as $item)
                                                    <div class="evaluation-item">
                                                        <span class="small">{{ $item->content }}</span>
                                                        @if(Auth::user()->role !== 'participant')
                                                            <div class="dropdown">
                                                                <button class="btn btn-link btn-sm text-muted p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
                                                                    <li><a class="dropdown-item small" href="#" onclick="editEval({{ $item->id }}, '{{ addslashes($item->content) }}', '{{ $item->type }}', '{{ addslashes($data['u']->name) }}')"><i class="bi bi-pencil me-2"></i>Editar</a></li>
                                                                    <li>
                                                                        <form action="{{ route('evaluation.delete', $item->id) }}" method="POST">
                                                                            @csrf @method('DELETE')
                                                                            <button class="dropdown-item small text-danger"><i class="bi bi-trash me-2"></i>Eliminar</button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <p class="text-muted small italic">Pendiente...</p>
                                                @endforelse
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="card p-4">
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
                                                <div class="text-center py-5 bg-light dark:bg-dark rounded-4">
                                                    <p class="text-muted fw-700">Test de Personalidad pendiente</p>
                                                    @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                                        <a href="{{ route('tests.take', [$data['u']->id, 'mbti']) }}" class="btn btn-primary rounded-pill px-5">
                                                            <i class="bi bi-play-fill me-1"></i> Realizar Test
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-5">
                                            <h6 class="fw-800 text-muted small mb-4">DONES PRINCIPALES</h6>
                                            @if($data['dones'])
                                                @foreach(array_slice($data['dones']->metadata['dones_ranking'], 0, 5) as $d)
                                                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light dark:bg-dark rounded-3 border">
                                                        <span class="fw-700 small">{{ $d['name'] }}</span>
                                                        <span class="badge bg-primary rounded-pill">{{ $d['score'] }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center py-5 bg-light dark:bg-dark rounded-4">
                                                    <p class="text-muted fw-700">Test de Dones pendiente</p>
                                                    @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                                        <a href="{{ route('tests.take', [$data['u']->id, 'dones']) }}" class="btn btn-primary rounded-pill px-5">
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
                                    <div class="card p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-800 mb-0">Resultados del Test de Dones</h5>
                                            <a href="{{ route('results.print', $data['dones']->id) }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill px-3 dark:btn-outline-light">
                                                <i class="bi bi-printer me-1"></i> Imprimir Dones
                                            </a>
                                        </div>
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
                                        <div class="row g-2">
                                            @foreach($data['dones']->metadata['dones_ranking'] as $idx => $don)
                                                <div class="col-md-4 col-lg-3">
                                                    <div class="p-2 border rounded-3 d-flex justify-content-between align-items-center bg-light dark:bg-dark">
                                                        <span class="fw-600 small">{{ $don['name'] }}</span>
                                                        <span class="badge bg-primary px-2">{{ $don['score'] }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="card p-5 text-center">
                                        <p class="text-muted fw-700">Aún no se ha realizado el test de dones.</p>
                                        @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                            <a href="{{ route('tests.take', [$data['u']->id, 'dones']) }}" class="btn btn-primary rounded-pill px-5">Realizar Test</a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- MBTI DETALLE -->
                            <div class="tab-pane fade" id="{{ $data['id'] }}-mbti-tab">
                                @if($data['mbti'])
                                    <div class="card p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-800 mb-0">Perfil de Personalidad</h5>
                                            <a href="{{ route('results.print', $data['mbti']->id) }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill px-3 dark:btn-outline-light">
                                                <i class="bi bi-printer me-1"></i> Imprimir MBTI
                                            </a>
                                        </div>
                                        <div class="bg-light dark:bg-dark p-4 rounded-4 mb-4 border">
                                            @foreach([['E','I','Extroversión','Introversión'], ['S','N','Sensación','Intuición'], ['T','F','Pensamiento','Sentimiento'], ['J','P','Juicio','Percepción']] as $dim)
                                                @php
                                                    $s = $data['mbti']->metadata['scores'] ?? [];
                                                    $v1 = $s[$dim[0]] ?? 0; $v2 = $s[$dim[1]] ?? 0;
                                                    $total = ($v1 + $v2) ?: 1;
                                                    $p = ($v1 >= $v2) ? ($v1 / $total) * 100 : ($v2 / $total) * 100;
                                                    $isLeft = $v1 >= $v2;
                                                @endphp
                                                <div class="mb-4">
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
                                            <div class="p-4 border rounded-4 bg-light dark:bg-dark">
                                                <h5 class="fw-800 text-primary mb-3">{{ $type }} - {{ $mbtiInfo[$type]['name'] }}</h5>
                                                <p class="text-muted mb-0" style="line-height: 1.8;">{{ $mbtiInfo[$type]['description'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="card p-5 text-center">
                                        <p class="text-muted fw-700">Aún no se ha realizado el test de personalidad.</p>
                                        @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                            <a href="{{ route('tests.take', [$data['u']->id, 'mbti']) }}" class="btn btn-primary rounded-pill px-5">Realizar Test</a>
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
                            <div class="note-card border-start border-primary border-4 shadow-sm">
                                <h5 class="fw-800 mb-4 text-primary d-flex align-items-center">
                                    <i class="bi bi-people-fill me-2"></i> Notas de la Pareja
                                </h5>
                                @forelse($pairNotes as $note)
                                    <div class="bg-card border shadow-sm p-4 mb-3 rounded-4">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="fw-800 text-primary">{{ $note->author->name }} <small class="text-muted fw-500 ms-1">({{ $note->author->staff_title ?? 'Admin' }})</small></span>
                                            <span class="text-muted small">{{ $note->created_at->format('d M, Y - H:i') }}</span>
                                        </div>
                                        <p class="mb-0 text-main" style="line-height: 1.7;">{{ $note->content }}</p>
                                    </div>
                                @empty
                                    <p class="text-muted italic text-center py-4">No hay notas registradas para esta pareja.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="note-card border-start border-info border-4 shadow-sm">
                                <h5 class="fw-800 mb-4 text-info d-flex align-items-center">
                                    <i class="bi bi-person-circle me-2"></i> Sobre {{ $husband->name }}
                                </h5>
                                @forelse($hNotes as $note)
                                    <div class="bg-card border shadow-sm p-4 mb-3 rounded-4">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="fw-800 text-info">{{ $note->author->name }}</span>
                                            <span class="text-muted small">{{ $note->created_at->format('d/m/y H:i') }}</span>
                                        </div>
                                        <p class="mb-0 text-main">{{ $note->content }}</p>
                                    </div>
                                @empty
                                    <p class="text-muted small italic text-center">Sin notas individuales.</p>
                                @endforelse
                            </div>
                        </div>

                        @if($wife)
                        <div class="col-md-6">
                            <div class="note-card border-start border-4 shadow-sm" style="border-left-color: #ec4899 !important;">
                                <h5 class="fw-800 mb-4 d-flex align-items-center" style="color: #ec4899;">
                                    <i class="bi bi-person-circle me-2"></i> Sobre {{ $wife->name }}
                                </h5>
                                @forelse($wNotes as $note)
                                    <div class="bg-card border shadow-sm p-4 mb-3 rounded-4">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="fw-800" style="color: #ec4899;">{{ $note->author->name }}</span>
                                            <span class="text-muted small">{{ $note->created_at->format('d/m/y H:i') }}</span>
                                        </div>
                                        <p class="mb-0 text-main">{{ $note->content }}</p>
                                    </div>
                                @empty
                                    <p class="text-muted small italic text-center">Sin notas individuales.</p>
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

    <!-- Modals -->
    <div class="modal fade" id="modalEval" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg dark:bg-dark">
                <form id="formEval" action="{{ route('evaluation.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="eval_method" value="POST">
                    <input type="hidden" name="user_id" id="eval_user_id">
                    <div class="modal-header border-0 p-4 pb-0">
                        <h5 class="fw-800" id="evalModalTitle">Evaluación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-700">Tipo de Comentario</label>
                            <select name="type" id="eval_type" class="form-select">
                                <option value="strength">Fortaleza</option>
                                <option value="growth">Área de Crecimiento</option>
                                <option value="suggestion">Sugerencia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Contenido</label>
                            <textarea name="content" id="eval_content" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-primary w-100 py-2 fw-700" id="evalSubmitBtn">Guardar Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDecision" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg dark:bg-dark">
                <form action="{{ route('pair_evaluation.decision') }}" method="POST">
                    @csrf
                    <input type="hidden" name="center_id" value="{{ $center->id }}">
                    <input type="hidden" name="pair_name" value="{{ $pairName }}">
                    <div class="modal-header border-0 p-4 pb-0"><h5 class="fw-800">Decisión Final</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-700">Estado de Recomendación</label>
                            <select name="decision" class="form-select">
                                <option value="green" {{ ($pairEvaluation->decision ?? '') == 'green' ? 'selected' : '' }}>Luz Verde (Recomendado)</option>
                                <option value="yellow" {{ ($pairEvaluation->decision ?? '') == 'yellow' ? 'selected' : '' }}>Luz Amarilla (Con Reservas)</option>
                                <option value="red" {{ ($pairEvaluation->decision ?? '') == 'red' ? 'selected' : '' }}>Luz Roja (No Recomendado)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Texto descriptivo</label>
                            <input type="text" name="decision_text" class="form-control" value="{{ $pairEvaluation->decision_text ?? 'Recomendado' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Fecha/Hora de publicación para la pareja</label>
                            <input type="datetime-local" name="visible_at" class="form-control" value="{{ $pairEvaluation && $pairEvaluation->visible_at ? $pairEvaluation->visible_at->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0"><button class="btn btn-primary w-100 py-2 fw-700">Actualizar Decisión</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openEvalModal(id, name) {
        document.getElementById('formEval').action = "{{ route('evaluation.add') }}";
        document.getElementById('eval_method').value = "POST";
        document.getElementById('eval_user_id').value = id;
        document.getElementById('eval_content').value = "";
        document.getElementById('evalModalTitle').innerText = "Nueva Evaluación para " + name;
        document.getElementById('evalSubmitBtn').innerText = "Guardar Item";
        new bootstrap.Modal(document.getElementById('modalEval')).show();
    }

    function editEval(id, content, type, userName) {
        document.getElementById('formEval').action = "/evaluation-items/" + id;
        document.getElementById('eval_method').value = "PUT";
        document.getElementById('eval_type').value = type;
        document.getElementById('eval_content').value = content;
        document.getElementById('evalModalTitle').innerText = "Editar Item de " + userName;
        document.getElementById('evalSubmitBtn').innerText = "Actualizar Item";
        new bootstrap.Modal(document.getElementById('modalEval')).show();
    }
</script>
@endsection
