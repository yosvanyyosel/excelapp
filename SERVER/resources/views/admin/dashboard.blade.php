@extends('layouts.admin')

@section('title', 'Dashboard - Discovery')
@section('page_title', 'Panel de Control')

@section('sidebar_menu')
    <a href="{{ route('dashboard') }}" class="nav-link active">
        <i class="bi bi-grid-fill"></i>
        <span class="sidebar-text-hide">Dashboard</span>
    </a>
@endsection

@section('content')
    <div class="row g-4 mb-5">
        <!-- Crear Centro -->
        <div class="col-lg-6">
            <div class="card h-100 p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3">
                        <i class="bi bi-plus-lg fs-4"></i>
                    </div>
                    <h5 class="fw-800 mb-0">Crear Nuevo Centro</h5>
                </div>

                <form action="{{ route('centers.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-700">Nombre del Centro</label>
                        <input type="text" name="name" class="form-control" placeholder="Ej: Centro Discovery Sur" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-700">Lugar</label>
                            <input type="text" name="location" class="form-control" placeholder="Ej: Ciudad de México">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-700">Fecha del Evento</label>
                            <input type="text" name="event_date" class="form-control" placeholder="Ej: 8 al 11 de Julio 2026">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-700">Tiempo del Test (s)</label>
                            <input type="number" name="quiz_timer" class="form-control" value="15">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-700">Banner del Centro</label>
                            <input type="file" name="banner_photo" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 mt-2">Crear Centro</button>
                </form>
            </div>
        </div>

        <!-- Gestión de Admins para Master -->
        @if(Auth::user()->role === 'master')
        <div class="col-lg-6">
            <div class="card h-100 p-4 border-start border-primary border-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-dark bg-opacity-10 text-dark rounded-4 p-3 dark:bg-white dark:bg-opacity-10 dark:text-white">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <h5 class="fw-800 mb-0">Registrar Administrador</h5>
                </div>

                <form action="{{ route('participants.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="admin">
                    <div class="mb-3">
                        <label class="form-label small fw-700">Nombre Completo</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-700">Usuario</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-700">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-dark w-100 dark:btn-light">Crear Acceso Admin</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Lista de Centros -->
    <div class="card overflow-hidden">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light bg-opacity-50 dark:bg-dark dark:bg-opacity-50">
            <h5 class="fw-800 mb-0">Centros Registrados</h5>
            <span class="badge bg-primary rounded-pill px-3">{{ $centers->count() }} centros</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light dark:bg-dark">
                    <tr>
                        <th class="ps-4 border-0 py-3">Portada</th>
                        <th class="border-0">Nombre / Ubicación</th>
                        <th class="border-0">Fecha</th>
                        <th class="border-0">Timer</th>
                        <th class="border-0">Participantes</th>
                        <th class="text-end pe-4 border-0">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($centers as $center)
                    <tr>
                        <td class="ps-4">
                            @if($center->banner_photo)
                                <img src="{{ asset('storage/' . $center->banner_photo) }}" class="rounded-3 shadow-sm" style="width: 60px; height: 40px; object-fit: cover;">
                            @else
                                <div class="rounded-3 bg-light dark:bg-dark d-flex align-items-center justify-content-center border" style="width: 60px; height: 40px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-700">{{ $center->name }}</div>
                            <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $center->location ?? 'No especificado' }}</div>
                        </td>
                        <td><div class="small fw-500">{{ $center->event_date ?? 'Sin fecha' }}</div></td>
                        <td><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">{{ $center->quiz_timer }}s</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-people text-primary"></i>
                                <span class="fw-600">{{ $center->participants->count() }}</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('centers.show', $center->id) }}" class="btn btn-sm btn-light dark:btn-dark rounded-3" title="Ver Detalles">
                                    <i class="bi bi-eye-fill text-primary"></i>
                                </a>
                                <button class="btn btn-sm btn-light dark:btn-dark rounded-3" onclick="editCenter({{ $center->id }}, '{{ addslashes($center->name) }}', '{{ addslashes($center->location) }}', '{{ addslashes($center->event_date) }}', {{ $center->quiz_timer }})" title="Editar">
                                    <i class="bi bi-pencil-square text-success"></i>
                                </button>
                                <form action="{{ route('centers.delete', $center->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar centro?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light dark:btn-dark rounded-3" title="Eliminar">
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

    <!-- Modals -->
    <div class="modal fade" id="editCenterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg dark:bg-dark">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800">Editar Centro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCenterForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-700 small">Nombre del Centro</label>
                            <input type="text" name="name" id="edit_center_name" class="form-control" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-700 small">Lugar</label>
                                <input type="text" name="location" id="edit_center_location" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-700 small">Fecha del Evento</label>
                                <input type="text" name="event_date" id="edit_center_date" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-700 small">Tiempo del Test (s)</label>
                            <input type="number" name="quiz_timer" id="edit_center_timer" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-700 small">Actualizar Banner</label>
                            <input type="file" name="banner_photo" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function editCenter(id, name, location, date, timer) {
            document.getElementById('editCenterForm').action = "/centers/" + id;
            document.getElementById('edit_center_name').value = name;
            document.getElementById('edit_center_location').value = location;
            document.getElementById('edit_center_date').value = date;
            document.getElementById('edit_center_timer').value = timer;
            new bootstrap.Modal(document.getElementById('editCenterModal')).show();
        }
    </script>
@endsection
