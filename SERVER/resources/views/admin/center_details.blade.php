@extends('layouts.admin')

@section('title', $center->name . ' - Gestión')
@section('page_title', 'Gestión del Centro')

@section('sidebar_menu')
    @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="bi bi-grid-fill"></i>
            <span class="sidebar-text-hide">Dashboard</span>
        </a>
    @endif
    <a href="#" class="nav-link active">
        <i class="bi bi-building"></i>
        <span class="sidebar-text-hide">{{ $center->name }}</span>
    </a>
@endsection

@section('styles')
<style>
    .header-banner {
        height: 250px;
        background-size: cover;
        background-position: center;
        position: relative;
        border-radius: 24px;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-end;
        padding: 2.5rem;
        overflow: hidden;
    }

    .header-banner::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    }

    .header-content {
        position: relative;
        z-index: 1;
        color: white;
    }

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
        padding: 12px 20px;
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

    .stat-card {
        padding: 1.5rem;
        text-align: center;
    }

    .note-card {
        border-left: 4px solid var(--primary);
        background: var(--bg-body);
        padding: 1.25rem;
        border-radius: 16px;
        margin-bottom: 1rem;
    }

    .tag-badge {
        background: rgba(var(--primary-rgb), 0.1);
        color: var(--primary);
        font-size: 0.75rem;
        padding: 4px 12px;
        border-radius: 100px;
        font-weight: 700;
    }

    .decision-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .bg-decision-green { background-color: #22c55e; }
    .bg-decision-yellow { background-color: #eab308; }
    .bg-decision-red { background-color: #ef4444; }

    .dark .nav-main { background: #141414; }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-800 mb-0">{{ $center->name }}</h1>
            <p class="text-muted mb-0">Panel de administración del evento</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('centers.print_pairs', $center->id) }}" target="_blank" class="btn btn-outline-dark dark:btn-outline-light rounded-pill px-4">
                <i class="bi bi-printer me-2"></i> Imprimir Gafetes
            </a>
        </div>
    </div>

    <div class="header-banner shadow-lg" style="background-image: url('{{ $center->banner_photo ? asset('storage/' . $center->banner_photo) : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200' }}')">
        <div class="header-content">
            <h2 class="fw-800 mb-2">{{ $center->name }}</h2>
            <div class="d-flex gap-4 small fw-500 opacity-90">
                <span><i class="bi bi-geo-alt-fill me-1 text-primary"></i> {{ $center->location ?? 'Ubicación no definida' }}</span>
                <span><i class="bi bi-calendar-event-fill me-1 text-primary"></i> {{ $center->event_date ?? 'Fecha no programada' }}</span>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills nav-main shadow-sm d-flex flex-nowrap overflow-auto" id="centerTabs" role="tablist">
        <li class="nav-item flex-fill"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-info"><i class="bi bi-info-circle me-2"></i>Info</button></li>
        <li class="nav-item flex-fill"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pairs"><i class="bi bi-people me-2"></i>Parejas</button></li>
        @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
            <li class="nav-item flex-fill"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-staff"><i class="bi bi-shield-lock me-2"></i>Staff</button></li>
        @endif
        <li class="nav-item flex-fill"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-notes"><i class="bi bi-journal-text me-2"></i>Notas</button></li>
        @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
            <li class="nav-item flex-fill"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-config"><i class="bi bi-gear me-2"></i>Ajustes</button></li>
        @endif
    </ul>

    <div class="tab-content mt-4">
        <!-- TAB: INFORMACIÓN -->
        <div class="tab-pane fade show active" id="tab-info">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card h-100">
                        <div class="display-6 fw-800 text-primary mb-1">{{ $pairs->count() }}</div>
                        <div class="text-muted small text-uppercase fw-700">Parejas</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card h-100">
                        <div class="display-6 fw-800 text-success mb-1">{{ $staff->count() }}</div>
                        <div class="text-muted small text-uppercase fw-700">Staff</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card h-100">
                        <div class="display-6 fw-800 text-info mb-1">{{ $center->quiz_timer }}s</div>
                        <div class="text-muted small text-uppercase fw-700">Timer</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card h-100">
                        <div class="display-6 fw-800 text-warning mb-1">{{ $notes->count() }}</div>
                        <div class="text-muted small text-uppercase fw-700">Notas</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: PAREJAS -->
        <div class="tab-pane fade" id="tab-pairs">
            <div class="row g-4">
                @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                <div class="col-lg-4">
                    <div class="card p-4">
                        <h5 class="fw-800 mb-4">Nueva Pareja</h5>
                        <form action="{{ route('pairs.add') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="center_id" value="{{ $center->id }}">
                            <div class="mb-3">
                                <label class="form-label small fw-700">Nombre de la Pareja</label>
                                <input type="text" name="pair_name" class="form-control" placeholder="Ej: Los Perez" required>
                            </div>
                            <div class="bg-light dark:bg-dark p-3 rounded-4 mb-3 border">
                                <p class="small fw-700 text-primary mb-2">Esposo</p>
                                <input type="text" name="husband_name" class="form-control mb-2" placeholder="Nombre completo" required>
                                <input type="text" name="husband_username" class="form-control mb-2" placeholder="Usuario" required>
                                <input type="password" name="husband_password" class="form-control" placeholder="Contraseña" required>
                            </div>
                            <div class="bg-light dark:bg-dark p-3 rounded-4 mb-3 border">
                                <p class="small fw-700 text-danger mb-2">Esposa</p>
                                <input type="text" name="wife_name" class="form-control mb-2" placeholder="Nombre completo" required>
                                <input type="text" name="wife_username" class="form-control mb-2" placeholder="Usuario" required>
                                <input type="password" name="wife_password" class="form-control" placeholder="Contraseña" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-700">Foto de la Pareja</label>
                                <input type="file" name="pair_photo" class="form-control">
                            </div>
                            <button class="btn btn-primary w-100">Registrar Pareja</button>
                        </form>
                    </div>
                </div>
                @endif
                <div class="{{ Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)) ? 'col-lg-8' : 'col-12' }}">
                    <div class="card overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light dark:bg-dark">
                                    <tr>
                                        <th class="ps-4 py-3">Pareja</th>
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
                                                    <img src="{{ asset('storage/' . $h->pair_photo) }}" class="rounded-3" style="width: 45px; height: 45px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light dark:bg-dark rounded-3 d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                                        <i class="bi bi-people text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-700">{{ $pairName }}</div>
                                                    <div class="text-muted small">{{ $users->pluck('name')->join(' & ') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($eval)
                                                <div class="d-flex align-items-center small fw-700">
                                                    <span class="decision-dot bg-decision-{{ $eval->decision }}"></span>
                                                    {{ $eval->decision_text }}
                                                </div>
                                            @else
                                                <span class="text-muted small">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('pairs.show', [$center->id, $pairName]) }}" class="btn btn-sm btn-light dark:btn-dark rounded-3" title="Ver Detalles">
                                                    <i class="bi bi-eye-fill text-primary"></i>
                                                </a>
                                                @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
                                                    <button class="btn btn-sm btn-light dark:btn-dark rounded-3" onclick="editPair('{{ addslashes($pairName) }}', '{{ $h->id }}', '{{ addslashes($h->name) }}', '{{ $h->username }}', '{{ $w ? $w->id : '' }}', '{{ $w ? addslashes($w->name) : '' }}', '{{ $w ? $w->username : '' }}')">
                                                        <i class="bi bi-pencil-square text-success"></i>
                                                    </button>
                                                    <a href="{{ route('pdf.pair', $h->id) }}" target="_blank" class="btn btn-sm btn-light dark:btn-dark rounded-3">
                                                        <i class="bi bi-printer-fill text-dark dark:text-light"></i>
                                                    </a>
                                                    <form action="{{ route('pairs.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta pareja?')">
                                                        @csrf @method('DELETE')
                                                        <input type="hidden" name="pair_name" value="{{ $pairName }}">
                                                        <input type="hidden" name="center_id" value="{{ $center->id }}">
                                                        <button class="btn btn-sm btn-light dark:btn-dark rounded-3">
                                                            <i class="bi bi-trash-fill text-danger"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
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
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card p-4">
                        <h5 class="fw-800 mb-4">Agregar Staff</h5>
                        <form action="{{ route('staff.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="center_id" value="{{ $center->id }}">
                            <div class="mb-3">
                                <label class="form-label small fw-700">Nombre completo</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-700">Rol (ej: Mentor, Coordinador)</label>
                                <input type="text" name="staff_title" class="form-control" required>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-700">Usuario</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-700">Contraseña</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Registrar Staff</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light dark:bg-dark">
                                    <tr>
                                        <th class="ps-4 py-3">Nombre</th>
                                        <th>Rol</th>
                                        <th>Usuario</th>
                                        <th class="text-end pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($staff as $s)
                                    <tr>
                                        <td class="ps-4 fw-700">{{ $s->name }}</td>
                                        <td><span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 border rounded-pill px-3">{{ $s->staff_title }}</span></td>
                                        <td><code>{{ $s->username }}</code></td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-sm btn-light dark:btn-dark rounded-3" onclick="editStaff({{ $s->id }}, '{{ addslashes($s->name) }}', '{{ addslashes($s->staff_title) }}', '{{ $s->username }}')">
                                                    <i class="bi bi-pencil-square text-success"></i>
                                                </button>
                                                <form action="{{ route('staff.delete', $s->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-light dark:btn-dark rounded-3">
                                                        <i class="bi bi-trash-fill text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
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
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card p-4">
                        <h5 class="fw-800 mb-4">Nueva Nota</h5>
                        <form action="{{ route('notes.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="center_id" value="{{ $center->id }}">
                            <div class="mb-3">
                                <label class="form-label small fw-700">Contenido</label>
                                <textarea name="content" class="form-control" rows="4" placeholder="Observaciones..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-700">Etiquetar Pareja</label>
                                <select name="tagged_pair_name" class="form-select">
                                    <option value="">Ninguna</option>
                                    @foreach($pairs as $pName => $u)
                                        <option value="{{ $pName }}">{{ $pName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-700">Etiquetar Persona</label>
                                <select name="tagged_user_id" class="form-select">
                                    <option value="">Ninguna</option>
                                    @foreach($center->users->where('role', 'participant') as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_public" id="isPublicAdd" checked>
                                    <label class="form-check-label small fw-600" for="isPublicAdd">Pública</label>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Guardar Nota</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card p-4">
                        <h5 class="fw-800 mb-4">Bitácora del Centro</h5>
                        <div class="notes-container">
                            @forelse($notes as $note)
                            <div class="note-card border shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="fw-800 text-primary">{{ $note->author->name }}</span>
                                        <small class="text-muted ms-2">{{ $note->author->staff_title ?? 'Admin' }}</small>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        @if($note->author_id == Auth::id() || Auth::user()->role == 'master' || (Auth::user()->role == 'admin' && is_null(Auth::user()->center_id)))
                                            <button class="btn btn-sm btn-light dark:btn-dark rounded-circle p-0" style="width: 28px; height: 28px;" onclick="editNote({{ $note->id }}, '{{ addslashes($note->content) }}', '{{ $note->tagged_pair_name }}', '{{ $note->tagged_user_id }}', {{ $note->is_public ? 'true' : 'false' }})">
                                                <i class="bi bi-pencil small"></i>
                                            </button>
                                            <form action="{{ route('notes.delete', $note->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-light dark:btn-dark rounded-circle p-0 text-danger" style="width: 28px; height: 28px;">
                                                    <i class="bi bi-trash small"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <span class="text-muted small" style="font-size: 11px;">{{ $note->created_at->format('d/m/y H:i') }}</span>
                                    </div>
                                </div>
                                <p class="mb-3" style="line-height: 1.6;">{{ $note->content }}</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($note->tagged_pair_name)
                                        <span class="tag-badge"><i class="bi bi-people me-1"></i>{{ $note->tagged_pair_name }}</span>
                                    @endif
                                    @if($note->taggedUser)
                                        <span class="tag-badge"><i class="bi bi-person me-1"></i>{{ $note->taggedUser->name }}</span>
                                    @endif
                                    @if(!$note->is_public)
                                        <span class="badge bg-warning text-dark rounded-pill px-3">Privada</span>
                                    @endif
                                </div>
                            </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-journal-x display-4 text-muted opacity-25"></i>
                                    <p class="text-muted mt-2">No hay notas registradas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: CONFIGURACIÓN -->
        @if(Auth::user()->role === 'master' || (Auth::user()->role === 'admin' && is_null(Auth::user()->center_id)))
        <div class="tab-pane fade" id="tab-config">
            <div class="card p-4">
                <h5 class="fw-800 mb-4">Ajustes del Centro</h5>
                <form action="{{ route('centers.update', $center->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label small fw-700">Nombre del Centro</label>
                            <input type="text" name="name" class="form-control" value="{{ $center->name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-700">Lugar / Ubicación</label>
                            <input type="text" name="location" class="form-control" value="{{ $center->location }}" placeholder="Ej: Ciudad de México">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-700">Fecha del Evento</label>
                            <input type="text" name="event_date" class="form-control" value="{{ $center->event_date }}" placeholder="Ej: del 8 al 11 de Julio del 2026">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-700">Timer por Pregunta (segundos)</label>
                            <input type="number" name="quiz_timer" class="form-control" value="{{ $center->quiz_timer }}">
                        </div>
                        <div class="col-md-6">
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

    <!-- Modals -->
    <!-- Edit Staff Modal -->
    <div class="modal fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg dark:bg-dark">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800">Editar Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editStaffForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-700">Nombre</label>
                            <input type="text" name="name" id="edit_staff_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Rol</label>
                            <input type="text" name="staff_title" id="edit_staff_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Usuario</label>
                            <input type="text" name="username" id="edit_staff_username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Nueva Contraseña (opcional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-primary w-100">Actualizar Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg dark:bg-dark">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800">Editar Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editNoteForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-700">Contenido</label>
                            <textarea name="content" id="edit_note_content" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Pareja Etiquetada</label>
                            <select name="tagged_pair_name" id="edit_note_pair" class="form-select">
                                <option value="">Sin Pareja</option>
                                @foreach($pairs as $pName => $u) <option value="{{ $pName }}">{{ $pName }}</option> @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-700">Persona Etiquetada</label>
                            <select name="tagged_user_id" id="edit_note_user" class="form-select">
                                <option value="">Sin Persona</option>
                                @foreach($center->users->where('role', 'participant') as $u) <option value="{{ $u->id }}">{{ $u->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_public" id="edit_note_public">
                            <label class="form-check-label small fw-600" for="edit_note_public">Pública</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-primary w-100">Actualizar Nota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Pair Modal -->
    <div class="modal fade" id="editPairModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg dark:bg-dark">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800">Editar Pareja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pairs.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="hidden" name="center_id" value="{{ $center->id }}">
                    <input type="hidden" name="husband_id" id="edit_p_husband_id">
                    <input type="hidden" name="wife_id" id="edit_p_wife_id">
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="fw-700 small mb-2">Nombre Pareja</label>
                                <input type="text" name="pair_name" id="edit_p_pair_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light dark:bg-dark p-3 rounded-4 border">
                                    <p class="small fw-700 text-primary mb-3">Esposo</p>
                                    <input type="text" name="husband_name" id="edit_p_h_name" class="form-control mb-2" required>
                                    <input type="text" name="husband_username" id="edit_p_h_user" class="form-control mb-2">
                                    <input type="password" name="husband_password" class="form-control" placeholder="Nueva clave (opcional)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light dark:bg-dark p-3 rounded-4 border">
                                    <p class="small fw-700 text-danger mb-3">Esposa</p>
                                    <input type="text" name="wife_name" id="edit_p_w_name" class="form-control mb-2" required>
                                    <input type="text" name="wife_username" id="edit_p_w_user" class="form-control mb-2">
                                    <input type="password" name="wife_password" class="form-control" placeholder="Nueva clave (opcional)">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="fw-700 small mb-2">Actualizar Foto</label>
                                <input type="file" name="pair_photo" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-primary w-100">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
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
@endsection
