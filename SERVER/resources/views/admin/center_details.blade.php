<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $center->name }} - Detalles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
            <span class="navbar-text text-white">Centro: {{ $center->name }}</span>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <!-- Formulario para agregar pareja -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Registrar Nueva Pareja</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pairs.add') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="center_id" value="{{ $center->id }}">

                            <div class="mb-3">
                                <label class="form-label">Nombre de la Pareja (Ej: Familia Pérez)</label>
                                <input type="text" name="pair_name" class="form-control" placeholder="Nombre identificador" required>
                            </div>

                            <hr>
                            <p class="text-muted small">Datos del Esposo</p>
                            <div class="mb-2">
                                <input type="text" name="husband_name" class="form-control form-control-sm" placeholder="Nombre completo" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="husband_username" class="form-control form-control-sm" placeholder="Usuario" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="husband_password" class="form-control form-control-sm" placeholder="Contraseña" required>
                            </div>

                            <hr>
                            <p class="text-muted small">Datos de la Esposa</p>
                            <div class="mb-2">
                                <input type="text" name="wife_name" class="form-control form-control-sm" placeholder="Nombre completo" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="wife_username" class="form-control form-control-sm" placeholder="Usuario" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="wife_password" class="form-control form-control-sm" placeholder="Contraseña" required>
                            </div>

                            <hr>
                            <div class="mb-3">
                                <label class="form-label">Foto de la Pareja (Común)</label>
                                <input type="file" name="pair_photo" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Registrar Pareja</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Listado de Participantes -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Participantes Registrados</h5>
                        <span class="badge bg-info text-dark">{{ $center->users->count() }} Usuarios</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nombre</th>
                                        <th>Pareja</th>
                                        <th>Usuario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($center->users as $user)
                                    <tr>
                                        <td>
                                            @if($user->pair_photo)
                                                <img src="{{ asset('storage/' . $user->pair_photo) }}" width="40" height="40" class="rounded-circle object-fit-cover">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-inline-block text-center" style="width:40px; height:40px; line-height:40px;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->pair_name }}</td>
                                        <td><code class="text-primary">{{ $user->username }}</code></td>
                                        <td>
                                            <a href="{{ route('pdf.pair', $user->id) }}" class="btn btn-sm btn-outline-danger" title="Imprimir Portada">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($center->users->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No hay participantes registrados en este centro.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
