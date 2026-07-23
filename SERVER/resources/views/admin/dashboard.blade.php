<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Centro de Descubrimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #1e293b; color: white; }
        .nav-link { color: #cbd5e1; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.1); }
        .active-link { background: #3b82f6; color: white !important; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar p-3">
                <h4 class="text-center mb-4 py-2 border-bottom">Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active-link rounded mb-2" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    </li>
                </ul>
                <div class="mt-auto pt-5">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger w-100 btn-sm">Cerrar Sesión</button>
                    </form>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Panel Administrativo</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-primary p-2">{{ Auth::user()->name }}</span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Crear Centro -->
                    <div class="col-md-6 mb-4">
                        <div class="card p-4 h-100">
                            <h5><i class="bi bi-plus-circle me-2"></i> Crear Nuevo Centro</h5>
                            <form action="{{ route('centers.create') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Centro</label>
                                    <input type="text" name="name" class="form-control" placeholder="Ej: Centro Discovery Sur" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tiempo del Test (segundos)</label>
                                    <input type="number" name="quiz_timer" class="form-control" value="15">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Foto de Portada (Banner)</label>
                                    <input type="file" name="banner_photo" class="form-control">
                                </div>
                                <button class="btn btn-primary w-100">Crear Centro</button>
                            </form>
                        </div>
                    </div>

                    <!-- Gestión de Admins para Master -->
                    @if(Auth::user()->role === 'master')
                    <div class="col-md-6 mb-4">
                        <div class="card p-4 h-100">
                            <h5><i class="bi bi-shield-lock me-2"></i> Registrar Administrador</h5>
                            <form action="{{ route('participants.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="role" value="admin">
                                <div class="mb-3">
                                    <label class="form-label">Nombre Completo</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Usuario</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button class="btn btn-outline-primary w-100">Crear Acceso Admin</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Lista de Centros -->
                <div class="card p-4 mb-4">
                    <h5><i class="bi bi-building me-2"></i> Centros de Descubrimiento</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Portada</th>
                                    <th>Nombre</th>
                                    <th>Timer</th>
                                    <th>Participantes</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($centers as $center)
                                <tr>
                                    <td>{{ $center->id }}</td>
                                    <td>
                                        @if($center->banner_photo)
                                            <img src="{{ asset('storage/' . $center->banner_photo) }}" height="40" width="60" class="rounded object-fit-cover">
                                        @else
                                            <span class="text-muted small">Sin foto</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $center->name }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $center->quiz_timer }}s</span></td>
                                    <td>{{ $center->users->count() }}</td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('centers.show', $center->id) }}" class="btn btn-sm btn-info text-white" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editCenter({{ $center->id }}, '{{ $center->name }}', {{ $center->quiz_timer }})" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('centers.delete', $center->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este centro? Se borrarán todos los participantes y fotos asociados.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
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

                <!-- Lista de Admins (Solo Master) -->
                @if(Auth::user()->role === 'master')
                <div class="card p-4">
                    <h5><i class="bi bi-people me-2"></i> Administradores del Sistema</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td><code>{{ $admin->username }}</code></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editAdmin({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->username }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admins.delete', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este administrador?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </main>
        </div>
    </div>

    <!-- Modal Editar Centro -->
    <div class="modal fade" id="editCenterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Centro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCenterForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Centro</label>
                            <input type="text" name="name" id="edit_center_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tiempo del Test (s)</label>
                            <input type="number" name="quiz_timer" id="edit_center_timer" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Actualizar Portada</label>
                            <input type="file" name="banner_photo" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Admin -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAdminForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" id="edit_admin_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="username" id="edit_admin_username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña (opcional)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editCenter(id, name, timer) {
            document.getElementById('editCenterForm').action = "/centers/" + id;
            document.getElementById('edit_center_name').value = name;
            document.getElementById('edit_center_timer').value = timer;
            new bootstrap.Modal(document.getElementById('editCenterModal')).show();
        }
        function editAdmin(id, name, username) {
            document.getElementById('editAdminForm').action = "/admins/" + id;
            document.getElementById('edit_admin_name').value = name;
            document.getElementById('edit_admin_username').value = username;
            new bootstrap.Modal(document.getElementById('editAdminModal')).show();
        }
    </script>
</body>
</html>
