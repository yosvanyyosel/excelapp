<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expediente: {{ $pairName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --primary: #6366f1; --dark: #0f172a; --bg-body: #f8fafc; --sidebar-bg: #1e293b; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-body); color: var(--dark); }
        .sidebar { min-height: 100vh; background: var(--sidebar-bg); position: fixed; width: 16.666667%; z-index: 100; }
        .sidebar .nav-link { color: #94a3b8; font-weight: 500; padding: 0.8rem 1rem; border-radius: 8px; }
        .sidebar .nav-link.active { background: var(--primary); color: white; }
        .main-content { margin-left: 16.666667%; padding: 2rem; }
        .card-premium { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); background: white; overflow: hidden; margin-bottom: 2rem; }
        .nav-main { background: #f1f5f9; padding: 0.5rem; border-radius: 16px; margin-bottom: 2.5rem; border: none; }
        .nav-main .nav-link { border-radius: 12px; font-weight: 700; padding: 12px 24px; color: #64748b; border: none; }
        .nav-main .nav-link.active { background-color: var(--primary); color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); }
        .mbti-type-title { font-size: 3rem; font-weight: 800; color: var(--primary); }
        .tag-strength { background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; margin: 2px; display: inline-block; }
        .tag-weakness { background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; margin: 2px; display: inline-block; }
        .evaluation-item { border-bottom: 1px solid #f1f5f9; padding: 10px 0; display: flex; justify-content: space-between; align-items: center; }
        .decision-badge { padding: 10px 20px; border-radius: 50px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .decision-green { background: #dcfce7; color: #166534; border: 2px solid #22c55e; }
        .decision-yellow { background: #fef9c3; color: #854d0e; border: 2px solid #eab308; }
        .decision-red { background: #fee2e2; color: #991b1b; border: 2px solid #ef4444; }
        .note-card { border-left: 4px solid var(--primary); background: #f8fafc; padding: 1.25rem; border-radius: 12px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 sidebar d-none d-md-block">
                <div class="p-4 text-white fw-800 fs-5 mb-4"><i class="bi bi-hexagon-fill text-primary me-2"></i> DISCOVERY</div>
                <div class="px-3">
                    @if(Auth::user()->role !== 'participant')
                        <a href="{{ route('dashboard') }}" class="nav-link mb-2"><i class="bi bi-grid-fill me-2"></i> Dashboard</a>
                        <a href="{{ route('centers.show', $center->id) }}" class="nav-link active"><i class="bi bi-arrow-left-circle me-2"></i> Volver al Centro</a>
                    @else
                        <a href="#" class="nav-link active"><i class="bi bi-person-circle me-2"></i> Mi Perfil</a>
                    @endif
                </div>
            </nav>

            <main class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <div>
                        <h1 class="fw-800 h2 mb-0">Expediente de Pareja</h1>
                        <p class="text-muted mb-0">{{ $pairName }} en {{ $center->name }}</p>
                    </div>
                    @if(Auth::user()->role !== 'participant')
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-dark rounded-pill px-4 fw-700 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDecision">
                                <i class="bi bi-flag-fill me-2"></i> Decisión Final
                            </button>
                            <a href="{{ route('pdf.pair', $husband->id) }}" target="_blank" class="btn btn-danger rounded-pill px-4 fw-700 shadow-sm">
                                <i class="bi bi-file-earmark-pdf-fill me-2"></i> Reporte Completo
                            </a>
                        </div>
                    @endif
                </div>

                @if($pairEvaluation && $showDecision)
                    <div class="card-premium p-4 mb-4 text-center border-start border-5 @if($pairEvaluation->decision == 'green') border-success @elseif($pairEvaluation->decision == 'yellow') border-warning @else border-danger @endif">
                        <h6 class="text-muted small fw-800 mb-3">RECOMENDACIÓN FINAL DEL CENTRO</h6>
                        <span class="decision-badge @if($pairEvaluation->decision == 'green') decision-green @elseif($pairEvaluation->decision == 'yellow') decision-yellow @else decision-red @endif">
                            {{ $pairEvaluation->decision_text }}
                        </span>
                        @if($pairEvaluation->visible_at)
                            <p class="mt-3 small text-muted">Programado para: {{ $pairEvaluation->visible_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                @endif

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
                            <li class="nav-item flex-fill"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-husband"><i class="bi bi-gender-male me-2"></i> {{ $husband->name }}</button></li>
                            @if($wife)<li class="nav-item flex-fill"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-wife"><i class="bi bi-gender-female me-2"></i> {{ $wife->name }}</button></li>@endif
                            @if(Auth::user()->role !== 'participant')<li class="nav-item flex-fill"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bitacora"><i class="bi bi-journal-text me-2"></i>Bitácora</button></li>@endif
                        </ul>

                        <div class="tab-content">
                            @foreach([['id' => 'husband', 'u' => $husband, 'dones' => $hDones, 'mbti' => $hMbti], ['id' => 'wife', 'u' => $wife, 'dones' => $wDones, 'mbti' => $wMbti]] as $data)
                                @if($data['u'])
                                <div class="tab-pane fade @if($data['id'] == 'husband') show active @endif" id="tab-{{ $data['id'] }}">
                                    <div class="card-premium p-4 mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-800 mb-0">Evaluación de Desempeño</h5>
                                            @if(Auth::user()->role !== 'participant')
                                                <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="openEvalModal({{ $data['u']->id }}, '{{ $data['u']->name }}')">
                                                    <i class="bi bi-plus-lg me-1"></i> Agregar Item
                                                </button>
                                            @endif
                                        </div>
                                        <div class="row">
                                            @foreach(['strength' => 'Fortalezas', 'growth' => 'Áreas de Crecimiento', 'suggestion' => 'Sugerencias'] as $type => $label)
                                                <div class="col-md-4">
                                                    <h6 class="small fw-800 text-muted border-bottom pb-2 mb-3">{{ strtoupper($label) }}</h6>
                                                    @forelse($data['u']->evaluationItems->where('type', $type) as $item)
                                                        <div class="evaluation-item">
                                                            <span class="small">{{ $item->content }}</span>
                                                            @if(Auth::user()->role !== 'participant')
                                                                <div class="dropdown">
                                                                    <button class="btn btn-link btn-sm text-muted" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                                                    <ul class="dropdown-menu shadow border-0">
                                                                        <li><a class="dropdown-item small" href="#" onclick="editEval({{ $item->id }}, '{{ addslashes($item->content) }}', '{{ $item->type }}', '{{ $data['u']->name }}')">Editar</a></li>
                                                                        <li>
                                                                            <form action="{{ route('evaluation.delete', $item->id) }}" method="POST">
                                                                                @csrf @method('DELETE')
                                                                                <button class="dropdown-item small text-danger">Eliminar</button>
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

                                    <div class="card-premium p-4">
                                        <div class="row g-4">
                                            <div class="col-md-7 border-end">
                                                <h6 class="fw-800 text-muted small mb-4">PERSONALIDAD (MBTI)</h6>
                                                @if($data['mbti'])
                                                    @php $type = $data['mbti']->metadata['mbti_types'][0]; @endphp
                                                    <div class="d-flex align-items-center mb-4">
                                                        <span class="mbti-type-title me-3">{{ $type }}</span>
                                                        <h5 class="fw-800 mb-0">{{ $mbtiInfo[$type]['name'] }}</h5>
                                                    </div>
                                                    <div class="mb-3">@foreach($mbtiInfo[$type]['strengths'] as $s) <span class="tag-strength">{{ $s }}</span> @endforeach</div>
                                                    <div>@foreach($mbtiInfo[$type]['weaknesses'] as $w) <span class="tag-weakness">{{ $w }}</span> @endforeach</div>
                                                @else
                                                    <div class="text-center py-4 bg-light rounded-4">
                                                        <p class="text-muted fw-700">Test pendiente</p>
                                                        @if(Auth::id() == $data['u']->id || Auth::user()->role !== 'participant')
                                                            <a href="{{ route('tests.take', [$data['u']->id, 'mbti']) }}" class="btn btn-primary rounded-pill shadow-sm">Realizar Test</a>
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
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach

                            @if(Auth::user()->role !== 'participant')
                            <div class="tab-pane fade" id="tab-bitacora">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="card-premium p-4 border-start border-primary border-4">
                                            <h5 class="fw-800 mb-4 text-primary"><i class="bi bi-people-fill me-2"></i> Notas de la Pareja</h5>
                                            @forelse($pairNotes as $note)
                                                <div class="note-card bg-white border border-light shadow-sm">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="fw-800 small text-primary">{{ $note->author->name }}</span>
                                                        <span class="text-muted small" style="font-size: 11px;">{{ $note->created_at->format('d M, Y H:i') }}</span>
                                                    </div>
                                                    <p class="mb-0 small">{{ $note->content }}</p>
                                                </div>
                                            @empty
                                                <p class="text-muted small italic text-center">No hay notas de pareja.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-premium p-4 border-start border-info border-4">
                                            <h5 class="fw-800 mb-4 text-info"><i class="bi bi-person-circle me-2"></i> {{ $husband->name }}</h5>
                                            @forelse($hNotes as $note)
                                                <div class="note-card bg-white border border-light shadow-sm">
                                                    <p class="mb-1 small">{{ $note->content }}</p>
                                                    <span class="text-muted" style="font-size: 10px;">{{ $note->created_at->format('d/m/y H:i') }} por {{ $note->author->name }}</span>
                                                </div>
                                            @empty
                                                <p class="text-muted small italic text-center">Sin notas.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    @if($wife)
                                    <div class="col-md-6">
                                        <div class="card-premium p-4 border-start border-4" style="border-left-color: #ec4899 !important;">
                                            <h5 class="fw-800 mb-4" style="color: #ec4899;"><i class="bi bi-person-circle me-2"></i> {{ $wife->name }}</h5>
                                            @forelse($wNotes as $note)
                                                <div class="note-card bg-white border border-light shadow-sm">
                                                    <p class="mb-1 small">{{ $note->content }}</p>
                                                    <span class="text-muted" style="font-size: 10px;">{{ $note->created_at->format('d/m/y H:i') }} por {{ $note->author->name }}</span>
                                                </div>
                                            @empty
                                                <p class="text-muted small italic text-center">Sin notas.</p>
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

    <!-- Modals -->
    <div class="modal fade" id="modalEval" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <form id="formEval" action="{{ route('evaluation.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="eval_method" value="POST">
                    <input type="hidden" name="user_id" id="eval_user_id">
                    <div class="modal-header border-0 p-4 pb-0">
                        <h5 class="fw-800" id="evalModalTitle">Evaluación para <span id="eval_user_name"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-700">Tipo</label>
                            <select name="type" id="eval_type" class="form-select rounded-3">
                                <option value="strength">Fortaleza</option>
                                <option value="growth">Área de Crecimiento</option>
                                <option value="suggestion">Sugerencia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Contenido</label>
                            <textarea name="content" id="eval_content" class="form-control rounded-3" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-primary w-100 rounded-pill fw-700" id="evalSubmitBtn">Guardar Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDecision" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <form action="{{ route('pair_evaluation.decision') }}" method="POST">
                    @csrf
                    <input type="hidden" name="center_id" value="{{ $center->id }}">
                    <input type="hidden" name="pair_name" value="{{ $pairName }}">
                    <div class="modal-header border-0 p-4 pb-0"><h5 class="fw-800">Decisión Final</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-700">Estado</label>
                            <select name="decision" class="form-select rounded-3">
                                <option value="green" {{ ($pairEvaluation->decision ?? '') == 'green' ? 'selected' : '' }}>Luz Verde (Recomendado)</option>
                                <option value="yellow" {{ ($pairEvaluation->decision ?? '') == 'yellow' ? 'selected' : '' }}>Luz Amarilla (Recomendado con reservas)</option>
                                <option value="red" {{ ($pairEvaluation->decision ?? '') == 'red' ? 'selected' : '' }}>Luz Roja (No Recomendado)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Texto de la Decisión</label>
                            <input type="text" name="decision_text" class="form-control rounded-3" value="{{ $pairEvaluation->decision_text ?? 'Recomendado' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Visible para la Pareja el:</label>
                            <input type="datetime-local" name="visible_at" class="form-control rounded-3" value="{{ $pairEvaluation && $pairEvaluation->visible_at ? $pairEvaluation->visible_at->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0"><button class="btn btn-primary w-100 rounded-pill fw-700">Actualizar Decisión</button></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEvalModal(id, name) {
            document.getElementById('formEval').action = "{{ route('evaluation.add') }}";
            document.getElementById('eval_method').value = "POST";
            document.getElementById('eval_user_id').value = id;
            document.getElementById('eval_user_name').innerText = name;
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
            document.getElementById('eval_user_name').innerText = userName;
            document.getElementById('evalModalTitle').innerText = "Editar Item de " + userName;
            document.getElementById('evalSubmitBtn').innerText = "Actualizar Item";
            new bootstrap.Modal(document.getElementById('modalEval')).show();
        }
    </script>
</body>
</html>
