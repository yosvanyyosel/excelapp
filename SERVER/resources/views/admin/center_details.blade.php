<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $center->name }} - Gestión</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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

        .header-banner {
            height: 180px;
            background-size: cover;
            background-position: center;
            position: relative;
            border-radius: 20px;
            margin-bottom: 2rem;
        }

        .nav-main {
            background: #f1f5f9;
            padding: 0.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            border: none;
        }

        .nav-main .nav-link {
            border-radius: 12px;
            font-weight: 700;
            padding: 10px 20px;
            color: #64748b;
            border: none;
            flex: 1;
            text-align: center;
        }

        .nav-main .nav-link.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
        }

        .note-card {
            border-left: 4px solid var(--primary);
            background: #f8fafc;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            position: relative;
        }

        .tag-badge {
            background: #eef2ff;
            color: var(--primary);
            font-size: 0.75rem;
            padding: 2px 10px;
            border-radius: 100px;
            font-weight: 700;
        }

        .action-btn-small {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            transition: 0.2s;
        }

        .action-btn-small:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .decision-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .bg-decision-green { background-color: #22c55e; }
        .bg-decision-yellow { background-color: #eab308; }
        .bg-decision-red { background-color: #ef4444; }

        @media (max-width: 992px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar d-none d-md-block">
                <div class="p-4 text-white fw-800 fs-5 mb-4">
                    <i class="bi bi-hexagon-fill text-primary me-2"></i> DISCOVERY
                </div>
                <div class="px-3">
                    @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                        <a href="{{ route('dashboard') }}" class="nav-link mb-2"><i class="bi bi-grid-fill me-2"></i> Dashboard</a>
                    @endif
                    <a href="#" class="nav-link active"><i class="bi bi-building me-2"></i> {{ $center->name }}</a>
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

            <!-- Contenido Principal -->
            <main class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-800 mb-0">Gestión del Centro</h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('centers.print_pairs', $center->id) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-printer me-2"></i> Imprimir Gafetes
                        </a>
                    </div>
                </div>

                @if($center->banner_photo)
                    <div class="header-banner" style="background-image: url('{{ asset('storage/' . $center->banner_photo) }}')"></div>
                @else
                    <div class="header-banner" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%)"></div>
                @endif

                <ul class="nav nav-pills nav-main shadow-sm" id="centerTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-info"><i class="bi bi-info-circle me-2"></i>Información</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pairs"><i class="bi bi-people me-2"></i>Parejas</button></li>
                    @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-staff"><i class="bi bi-shield-lock me-2"></i>Staff</button></li>
                    @endif
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-notes"><i class="bi bi-journal-text me-2"></i>Notas</button></li>
                    @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-config"><i class="bi bi-gear me-2"></i>Configuración</button></li>
                    @endif
                </ul>

                <div class="tab-content mt-4">
                    <!-- TAB: INFORMACIÓN -->
                    <div class="tab-pane fade show active" id="tab-info">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-premium p-4 text-center">
                                    <div class="display-5 fw-800 text-primary mb-1">{{ $pairs->count() }}</div>
                                    <div class="text-muted small text-uppercase fw-700">Parejas Participantes</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-premium p-4 text-center">
                                    <div class="display-5 fw-800 text-success mb-1">{{ $staff->count() }}</div>
                                    <div class="text-muted small text-uppercase fw-700">Miembros del Staff</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-premium p-4 text-center">
                                    <div class="display-5 fw-800 text-info mb-1">{{ $center->quiz_timer }}s</div>
                                    <div class="text-muted small text-uppercase fw-700">Tiempo de Respuesta</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: PAREJAS -->
                    <div class="tab-pane fade" id="tab-pairs">
                        <div class="row">
                            @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                            <div class="col-md-4">
                                <div class="card-premium p-4">
                                    <h5 class="fw-800 mb-4">Nueva Pareja</h5>
                                    <form action="{{ route('pairs.add') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="center_id" value="{{ $center->id }}">
                                        <input type="text" name="pair_name" class="form-control mb-3" placeholder="Nombre de la Pareja" required>
                                        <div class="bg-light p-3 rounded-4 mb-3">
                                            <p class="small fw-700 text-primary mb-2">Esposo</p>
                                            <input type="text" name="husband_name" class="form-control mb-2" placeholder="Nombre completo" required>
                                            <input type="text" name="husband_username" class="form-control mb-2" placeholder="Usuario" required>
                                            <input type="password" name="husband_password" class="form-control" placeholder="Contraseña" required>
                                        </div>
                                        <div class="bg-light p-3 rounded-4 mb-3">
                                            <p class="small fw-700 text-danger mb-2">Esposa</p>
                                            <input type="text" name="wife_name" class="form-control mb-2" placeholder="Nombre completo" required>
                                            <input type="text" name="wife_username" class="form-control mb-2" placeholder="Usuario" required>
                                            <input type="password" name="wife_password" class="form-control" placeholder="Contraseña" required>
                                        </div>
                                        <input type="file" name="pair_photo" class="form-control mb-3">
                                        <button class="btn btn-primary w-100">Registrar Pareja</button>
                                    </form>
                                </div>
                            </div>
                            @endif
                            <div class="{{ Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)) ? 'col-md-8' : 'col-12' }}">
                                <div class="card-premium">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4">Pareja</th>
                                                    <th>Integrantes</th>
                                                    <th>Estado</th>
                                                    <th class="text-end pe-4">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pairs as $pairName => $users)
                                                @php
                                                    $h = $users->first();
                                                    $w = $users->count() > 1 ? $users->last() : null;
                                                    $eval = $center->pairEvaluations->where('pair_name', $pairName)->first();
                                                @endphp
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center gap-3">
                                                            @if($h->pair_photo)
                                                                <img src="{{ asset('storage/' . $h->pair_photo) }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 10px;">
                                                            @else
                                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                                    <i class="bi bi-people text-muted"></i>
                                                                </div>
                                                            @endif
                                                            <div class="fw-700">{{ $pairName }}</div>
                                                        </div>
                                                    </td>
                                                    <td class="small">{{ $users->pluck('name')->join(' & ') }}</td>
                                                    <td>
                                                        @if($eval)
                                                            <span class="small fw-700">
                                                                <span class="decision-dot bg-decision-{{ $eval->decision }}"></span>
                                                                {{ $eval->decision_text }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted small">Pendiente</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <a href="{{ route('pairs.show', [$center->id, $pairName]) }}" class="action-btn-small" title="Ver Detalles"><i class="bi bi-eye"></i></a>
                                                        @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                                                            <button class="action-btn-small" onclick="editPair('{{ addslashes($pairName) }}', '{{ $h->id }}', '{{ addslashes($h->name) }}', '{{ $h->username }}', '{{ $w ? $w->id : '' }}', '{{ $w ? addslashes($w->name) : '' }}', '{{ $w ? $w->username : '' }}')"><i class="bi bi-pencil"></i></button>
                                                            <a href="{{ route('pdf.pair', $h->id) }}" target="_blank" class="action-btn-small"><i class="bi bi-printer"></i></a>
                                                            <form action="{{ route('pairs.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta pareja?')">
                                                                @csrf @method('DELETE')
                                                                <input type="hidden" name="pair_name" value="{{ $pairName }}">
                                                                <input type="hidden" name="center_id" value="{{ $center->id }}">
                                                                <button class="action-btn-small text-danger border-0"><i class="bi bi-trash"></i></button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: STAFF -->
                    @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                    <div class="tab-pane fade" id="tab-staff">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-premium p-4 mb-4">
                                    <h5 class="fw-800 mb-4">Agregar Staff</h5>
                                    <form action="{{ route('staff.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="center_id" value="{{ $center->id }}">
                                        <input type="text" name="name" class="form-control mb-2" placeholder="Nombre completo" required>
                                        <input type="text" name="staff_title" class="form-control mb-2" placeholder="Rol (ej: Mentor, Coordinador)" required>
                                        <input type="text" name="username" class="form-control mb-2" placeholder="Usuario de acceso" required>
                                        <input type="password" name="password" class="form-control mb-3" placeholder="Contraseña" required>
                                        <button class="btn btn-primary w-100">Registrar Staff</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-premium">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4">Nombre</th>
                                                    <th>Rol</th>
                                                    <th>Usuario</th>
                                                    <th class="text-end pe-4">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($staff as $s)
                                                <tr>
                                                    <td class="ps-4 fw-700">{{ $s->name }}</td>
                                                    <td><span class="badge bg-light text-primary border">{{ $s->staff_title }}</span></td>
                                                    <td><code>{{ $s->username }}</code></td>
                                                    <td class="text-end pe-4">
                                                        <button class="action-btn-small" onclick="editStaff({{ $s->id }}, '{{ addslashes($s->name) }}', '{{ addslashes($s->staff_title) }}', '{{ $s->username }}')"><i class="bi bi-pencil"></i></button>
                                                        <form action="{{ route('staff.delete', $s->id) }}" method="POST" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button class="action-btn-small text-danger border-0"><i class="bi bi-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- TAB: NOTAS -->
                    <div class="tab-pane fade" id="tab-notes">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-premium p-4 mb-4">
                                    <h5 class="fw-800 mb-3">Nueva Nota</h5>
                                    <form action="{{ route('notes.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="center_id" value="{{ $center->id }}">
                                        <textarea name="content" class="form-control mb-3" rows="3" placeholder="Observaciones..." required></textarea>
                                        <div class="mb-3">
                                            <select name="tagged_pair_name" class="form-control mb-2">
                                                <option value="">Etiquetar Pareja</option>
                                                @foreach($pairs as $pName => $u)
                                                    <option value="{{ $pName }}">{{ $pName }}</option>
                                                @endforeach
                                            </select>
                                            <select name="tagged_user_id" class="form-control">
                                                <option value="">Etiquetar Persona</option>
                                                @foreach($center->users->where('role', 'participant') as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_public" id="isPublicAdd" checked>
                                                <label class="form-check-label small fw-600" for="isPublicAdd">Pública</label>
                                            </div>
                                            <button class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-premium p-4">
                                    <h5 class="fw-800 mb-4">Bitácora del Centro</h5>
                                    @foreach($notes as $note)
                                    <div class="note-card">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-800 small text-primary">{{ $note->author->name }} ({{ $note->author->staff_title ?? 'Admin' }})</span>
                                            <div class="d-flex gap-2">
                                                @if($note->author_id == Auth::id() || Auth::user()->role == 'master' || (Auth::user()->role == 'admin' && is_null(Auth::user()->center_id)))
                                                <button class="btn btn-sm p-0 text-muted" onclick="editNote({{ $note->id }}, '{{ addslashes($note->content) }}', '{{ $note->tagged_pair_name }}', '{{ $note->tagged_user_id }}', {{ $note->is_public ? 'true' : 'false' }})"><i class="bi bi-pencil"></i></button>
                                                <form action="{{ route('notes.delete', $note->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm p-0 text-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                                @endif
                                                <span class="text-muted small" style="font-size: 11px;">{{ $note->created_at->format('d/m/y H:i') }}</span>
                                            </div>
                                        </div>
                                        <p class="mb-2" style="font-size: 14px;">{{ $note->content }}</p>
                                        <div class="d-flex gap-2">
                                            @if($note->tagged_pair_name)
                                                <span class="tag-badge"><i class="bi bi-people me-1"></i>{{ $note->tagged_pair_name }}</span>
                                            @endif
                                            @if($note->taggedUser)
                                                <span class="tag-badge"><i class="bi bi-person me-1"></i>{{ $note->taggedUser->name }}</span>
                                            @endif
                                            @if(!$note->is_public)
                                                <span class="badge bg-warning text-dark small" style="font-size: 10px;">Privada</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: CONFIGURACIÓN -->
                    @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                    <div class="tab-pane fade" id="tab-config">
                        <div class="card-premium p-4">
                            <h5 class="fw-800 mb-4">Ajustes del Centro</h5>
                            <form action="{{ route('centers.update', $center->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-700">Nombre del Centro</label>
                                        <input type="text" name="name" class="form-control" value="{{ $center->name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-700">Timer por Pregunta (segundos)</label>
                                        <input type="number" name="quiz_timer" class="form-control" value="{{ $center->quiz_timer }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-700">Banner / Portada</label>
                                        <input type="file" name="banner_photo" class="form-control">
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-4 px-5">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- MODALS -->
    <!-- Edit Staff Modal -->
    <div class="modal fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 p-4 pb-0"><h5 class="fw-800">Editar Staff</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <form id="editStaffForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <input type="text" name="name" id="edit_staff_name" class="form-control mb-2" placeholder="Nombre" required>
                        <input type="text" name="staff_title" id="edit_staff_title" class="form-control mb-2" placeholder="Rol" required>
                        <input type="text" name="username" id="edit_staff_username" class="form-control mb-2" placeholder="Usuario" required>
                        <input type="password" name="password" class="form-control" placeholder="Nueva Contraseña (opcional)">
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0"><button class="btn btn-primary w-100">Actualizar</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 p-4 pb-0"><h5 class="fw-800">Editar Nota</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <form id="editNoteForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <textarea name="content" id="edit_note_content" class="form-control mb-3" rows="4" required></textarea>
                        <div class="mb-3">
                            <select name="tagged_pair_name" id="edit_note_pair" class="form-control mb-2">
                                <option value="">Sin Pareja</option>
                                @foreach($pairs as $pName => $u) <option value="{{ $pName }}">{{ $pName }}</option> @endforeach
                            </select>
                            <select name="tagged_user_id" id="edit_note_user" class="form-control">
                                <option value="">Sin Persona</option>
                                @foreach($center->users->where('role', 'participant') as $u) <option value="{{ $u->id }}">{{ $u->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_public" id="edit_note_public">
                            <label class="form-check-label" for="edit_note_public">Pública</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0"><button class="btn btn-primary w-100">Actualizar</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Pair Modal -->
    <div class="modal fade" id="editPairModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 p-4 pb-0"><h5 class="fw-800">Editar Pareja</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <form action="{{ route('pairs.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="hidden" name="center_id" value="{{ $center->id }}">
                    <input type="hidden" name="husband_id" id="edit_p_husband_id">
                    <input type="hidden" name="wife_id" id="edit_p_wife_id">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-12"><label class="fw-700 small">Nombre Pareja</label><input type="text" name="pair_name" id="edit_p_pair_name" class="form-control" required></div>
                            <div class="col-md-6">
                                <p class="small fw-700 text-primary mb-2">Esposo</p>
                                <input type="text" name="husband_name" id="edit_p_h_name" class="form-control mb-2" required>
                                <input type="text" name="husband_username" id="edit_p_h_user" class="form-control mb-2">
                                <input type="password" name="husband_password" class="form-control" placeholder="Clave (opcional)">
                            </div>
                            <div class="col-md-6">
                                <p class="small fw-700 text-danger mb-2">Esposa</p>
                                <input type="text" name="wife_name" id="edit_p_w_name" class="form-control mb-2" required>
                                <input type="text" name="wife_username" id="edit_p_w_user" class="form-control mb-2">
                                <input type="password" name="wife_password" class="form-control" placeholder="Clave (opcional)">
                            </div>
                            <div class="col-12"><label class="fw-700 small">Cambiar Foto</label><input type="file" name="pair_photo" class="form-control"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0"><button class="btn btn-primary w-100">Guardar Cambios</button></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mantenimiento de la pestaña activa después de recargar
        document.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash;
            if (hash) {
                const tabTrigger = document.querySelector(`button[data-bs-target="${hash}"]`);
                if (tabTrigger) bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
            }
        });

        function editStaff(id, name, title, username) {
            document.getElementById('editStaffForm').action = "/staff/" + id;
            document.getElementById('edit_staff_name').value = name;
            document.getElementById('edit_staff_title').value = title;
            document.getElementById('edit_staff_username').value = username;
            new bootstrap.Modal(document.getElementById('editStaffModal')).show();
        }

        function editNote(id, content, pair, userId, isPublic) {
            document.getElementById('editNoteForm').action = "/notes/" + id;
            document.getElementById('edit_note_content').value = content;
            document.getElementById('edit_note_pair').value = pair || "";
            document.getElementById('edit_note_user').value = userId || "";
            document.getElementById('edit_note_public').checked = isPublic;
            new bootstrap.Modal(document.getElementById('editNoteModal')).show();
        }

        function editPair(pName, hId, hName, hUser, wId, wName, wUser) {
            document.getElementById('edit_p_pair_name').value = pName;
            document.getElementById('edit_p_husband_id').value = hId;
            document.getElementById('edit_p_h_name').value = hName;
            document.getElementById('edit_p_h_user').value = hUser;
            document.getElementById('edit_p_wife_id').value = wId;
            document.getElementById('edit_p_w_name').value = wName;
            document.getElementById('edit_p_w_user').value = wUser;
            new bootstrap.Modal(document.getElementById('editPairModal')).show();
        }
    </script>
</body>
</html>
