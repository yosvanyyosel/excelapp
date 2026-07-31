<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Descubrimiento - Gestión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #f8f9fa; }
        .hero { background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; padding: 100px 0; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="hero text-center">
        <div class="container">
            <h1 class="display-3 fw-bold">Centro de Descubrimiento</h1>
            <p class="lead">Plataforma de gestión administrativa y resultados</p>
        </div>
    </div>

    <div class="container mt-n5" style="margin-top: -50px;">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4">
                    <h3 class="text-center mb-4">Acceso Administrativo</h3>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif

                        <button type="submit" class="btn btn-primary w-100 py-2">Ingresar al Panel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5 text-muted">
        &copy; {{ date('Y') }} Excel Centro de Descubrimiento
    </footer>
</body>
</html>
