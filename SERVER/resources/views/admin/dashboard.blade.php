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
                        <a class="nav-link active-link rounded mb-2" href="#"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    </li>
                    @if(Auth::user()->role === 'master')
                    <li class="nav-item">
                        <a class="nav-link rounded mb-2" href="#"><i class="bi bi-people me-2"></i> Gestionar Admins</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link rounded mb-2" href="#"><i class="bi bi-building me-2"></i> Centros</a>
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
                    <h1 class="h2">Bienvenido, {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</h1>
                </div>

                <div class="row">
                    <!-- Crear Centro -->
                    <div class="col-md-6 mb-4">
                        <div class="card p-4">
                            <h5><i class="bi bi-plus-circle me-2"></i> Crear Nuevo Centro</h5>
                            <form action="{{ route('centers.create') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Centro</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tiempo del Test (segundos)</label>
                                    <input type="number" name="quiz_timer" class="form-control" value="15">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Foto Masiva (Banner)</label>
                                    <input type="file" name="banner_photo" class="form-control">
                                </div>
                                <button class="btn btn-primary w-100">Crear Centro</button>
                            </form>
                        </div>
                    </div>

                    <!-- Añadir Participante -->
                    <div class="col-md-6 mb-4">
                        <div class="card p-4">
                            <h5><i class="bi bi-person-plus me-2"></i> Añadir Participante / Pareja</h5>
                            <form action="{{ route('participants.add') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Nombre Participante</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Nombre Pareja</label>
                                        <input type="text" name="pair_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Usuario</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Contraseña</label>
                                        <input type="text" name="password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Centro</label>
                                    <select name="center_id" class="form-select">
                                        @foreach($centers as $center)
                                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Foto de la Pareja</label>
                                    <input type="file" name="pair_photo" class="form-control">
                                </div>
                                <button class="btn btn-success w-100">Registrar Participante</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lista de Centros y Participantes -->
                <div class="card p-4">
                    <h5>Listado de Centros</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Timer</th>
                                    <th>Participantes</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($centers as $center)
                                <tr>
                                    <td>{{ $center->id }}</td>
                                    <td>{{ $center->name }}</td>
                                    <td>{{ $center->quiz_timer }}s</td>
                                    <td>{{ $center->users->count() }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info">Ver</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
