<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Centro de Descubrimiento</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --dark: #0f172a;
            --sidebar-bg: #1e293b;
            --bg-body: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
        }

        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
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
            color: white;
            background: var(--primary);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .sidebar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
            color: white;
            padding: 1.5rem 1rem;
        }

        .main-content {
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: transform 0.3s ease;
        }

        .card-title {
            font-weight: 700;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .table thead th {
            background: #f1f5f9;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.05em;
            border: none;
        }

        .badge-timer {
            background: #eef2ff;
            color: #6366f1;
            font-weight: 700;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-1px);
        }

        .center-img {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
        }

        .stat-card {
            padding: 1.5rem;
            background: white;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .modal-content {
            border: none;
            border-radius: 20px;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-brand d-flex align-items-center">
                    <i class="bi bi-hexagon-fill text-primary me-2"></i>
                    DISCOVERY
                </div>
                <div class="px-3">
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid-fill me-2"></i> Dashboard
                            </a>
                        </li>
                    </ul>
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

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto main-content">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <div>
                        <h1 class="fw-800 h2 mb-1">Bienvenido, {{ Auth::user()->name }}</h1>
                        <p class="text-muted mb-0">Gestiona tus centros de descubrimiento y participantes.</p>
                    </div>
                    <div>
                        <span class="badge bg-white text-dark border p-2 px-3 rounded-pill shadow-sm">
                            <i class="bi bi-calendar-event me-1"></i> {{ date('d M, Y') }}
                        </span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="row g-4 mb-5">
                    <!-- Crear Centro -->
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-body p-4">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-plus-lg"></i>
                                </div>
                                <h5 class="card-title mb-4">Crear Nuevo Centro</h5>
                                <form action="{{ route('centers.create') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-600">Nombre del Centro</label>
                                        <input type="text" name="name" class="form-control" placeholder="Ej: Centro Discovery Sur" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-600">Tiempo del Test (s)</label>
                                            <input type="number" name="quiz_timer" class="form-control" value="15">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-600">Banner del Centro</label>
                                            <input type="file" name="banner_photo" class="form-control">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-2">Crear Centro</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Admins para Master -->
                    @if(Auth::user()->role === 'master')
                    <div class="col-lg-6">
                        <div class="card h-100 border-start border-primary border-4">
                            <div class="card-body p-4">
                                <div class="stat-icon bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <h5 class="card-title mb-4">Registrar Administrador</h5>
                                <form action="{{ route('participants.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="role" value="admin">
                                    <div class="mb-3">
                                        <label class="form-label small fw-600">Nombre Completo</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-600">Usuario</label>
                                            <input type="text" name="username" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-600">Contraseña</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary w-100">Crear Acceso Admin</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Lista de Centros -->
                <div class="card mb-5">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Centros Registrados</h5>
                            <span class="badge bg-light text-dark">{{ $centers->count() }} centros</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Portada</th>
                                        <th>Nombre del Centro</th>
                                        <th>Timer</th>
                                        <th>Participantes</th>
                                        <th class="text-end pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($centers as $center)
                                    <tr>
                                        <td class="ps-4">
                                            @if($center->banner_photo)
                                                <img src="{{ asset('storage/' . $center->banner_photo) }}" class="center-img shadow-sm">
                                            @else
                                                <div class="center-img bg-light d-flex align-items-center justify-content-center border">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-700">{{ $center->name }}</div>
                                            <div class="text-muted small">ID: #{{ $center->id }}</div>
                                        </td>
                                        <td><span class="badge badge-timer rounded-pill">{{ $center->quiz_timer }}s</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-people me-2 text-primary"></i>
                                                {{ $center->participants->count() }}
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group shadow-sm">
                                                <a href="{{ route('centers.show', $center->id) }}" class="btn btn-sm btn-white border" title="Ver Detalles">
                                                    <i class="bi bi-eye text-primary"></i>
                                                </a>
                                                <button class="btn btn-sm btn-white border" onclick="editCenter({{ $center->id }}, '{{ $center->name }}', {{ $center->quiz_timer }})" title="Editar">
                                                    <i class="bi bi-pencil-square text-success"></i>
                                                </button>
                                                <form action="{{ route('centers.delete', $center->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar centro?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-white border" title="Eliminar">
                                                        <i class="bi bi-trash text-danger"></i>
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

                <!-- Lista de Admins (Solo Master) -->
                @if(Auth::user()->role === 'master')
                <div class="card mb-4">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h5 class="card-title mb-0">Administradores del Sistema</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Nombre</th>
                                        <th>Usuario</th>
                                        <th class="text-end pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                    <tr>
                                        <td class="ps-4 fw-600">{{ $admin->name }}</td>
                                        <td><code>{{ $admin->username }}</code></td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light" onclick="editAdmin({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->username }}')">
                                                <i class="bi bi-pencil me-1"></i> Editar
                                            </button>
                                            <form action="{{ route('admins.delete', $admin->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger">
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
                </div>
                @endif
            </main>
        </div>
    </div>

    <!-- Modals (Edit Center/Admin) -->
    <div class="modal fade" id="editCenterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800">Editar Centro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCenterForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-600">Nombre del Centro</label>
                            <input type="text" name="name" id="edit_center_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Tiempo del Test (s)</label>
                            <input type="number" name="quiz_timer" id="edit_center_timer" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Actualizar Banner</label>
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

    <script>
        function editCenter(id, name, timer) {
            document.getElementById('editCenterForm').action = "/centers/" + id;
            document.getElementById('edit_center_name').value = name;
            document.getElementById('edit_center_timer').value = timer;
            new bootstrap.Modal(document.getElementById('editCenterModal')).show();
        }
    </script>
</body>
</html>
