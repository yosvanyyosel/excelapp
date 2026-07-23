<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $center->name }} - Detalles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .object-fit-cover { object-fit: cover; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
            <span class="navbar-text text-white">Centro: {{ $center->name }}</span>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                                <label class="form-label small fw-bold">Nombre de la Pareja</label>
                                <input type="text" name="pair_name" class="form-control" placeholder="Ej: Familia Pérez" required>
                            </div>

                            <hr>
                            <p class="text-primary small fw-bold mb-1">Datos del Esposo</p>
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
                            <p class="text-primary small fw-bold mb-1">Datos de la Esposa</p>
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
                                <label class="form-label small fw-bold">Foto de la Pareja (Común)</label>
                                <input type="file" name="pair_photo" class="form-control form-control-sm">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Registrar Pareja</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Listado de Parejas -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Parejas y Participantes</h5>
                        <span class="badge bg-indigo text-primary border border-primary">{{ $pairs->count() }} Parejas</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Foto</th>
                                        <th>Pareja</th>
                                        <th>Integrantes (Usuario)</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pairs as $pairName => $users)
                                    @php
                                        $husband = $users->first();
                                        $wife = $users->count() > 1 ? $users->last() : null;
                                        $photoUrl = $husband->pair_photo ? asset('storage/' . $husband->pair_photo) : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($photoUrl)
                                                <img src="{{ $photoUrl }}" width="50" height="50" class="rounded object-fit-cover shadow-sm">
                                            @else
                                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $pairName }}</strong>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <i class="bi bi-gender-male text-primary"></i> {{ $husband->name }} (<code>{{ $husband->username }}</code>)<br>
                                                @if($wife)
                                                <i class="bi bi-gender-female text-danger"></i> {{ $wife->name }} (<code>{{ $wife->username }}</code>)
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('pairs.show', ['centerId' => $center->id, 'pairName' => $pairName]) }}" class="btn btn-sm btn-outline-info" title="Ver Detalles">
                                                    <i class="bi bi-person-lines-fill"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-primary"
                                                        onclick="editPair('{{ $pairName }}', '{{ $husband->id }}', '{{ $husband->name }}', '{{ $husband->username }}', '{{ $wife ? $wife->id : '' }}', '{{ $wife ? $wife->name : '' }}', '{{ $wife ? $wife->username : '' }}', '{{ $photoUrl }}')"
                                                        title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <a href="{{ route('pdf.pair', $husband->id) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="PDF Portada">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                                <form action="{{ route('pairs.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar a esta pareja? Se borrarán ambos usuarios.')">
                                                    @csrf
                                                    <input type="hidden" name="pair_name" value="{{ $pairName }}">
                                                    <input type="hidden" name="center_id" value="{{ $center->id }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-dark" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($pairs->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No hay parejas registradas.</td>
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

    <!-- Modal Editar Pareja -->
    <div class="modal fade" id="editPairModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Pareja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pairs.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="center_id" value="{{ $center->id }}">
                    <input type="hidden" name="old_pair_name" id="edit_old_pair_name">
                    <input type="hidden" name="husband_id" id="edit_husband_id">
                    <input type="hidden" name="wife_id" id="edit_wife_id">

                    <div class="modal-body">
                        <div class="mb-3 text-center" id="current_photo_container" style="display: none;">
                            <label class="form-label d-block small">Foto Actual</label>
                            <img id="edit_photo_preview" src="" width="120" height="120" class="rounded object-fit-cover shadow-sm mb-2">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre de la Pareja</label>
                            <input type="text" name="pair_name" id="edit_pair_name" class="form-control" required>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Esposo</label>
                            <input type="text" name="husband_name" id="edit_husband_name" class="form-control mb-2" placeholder="Nombre completo" required>
                            <input type="text" name="husband_username" id="edit_husband_username" class="form-control mb-2" placeholder="Usuario">
                            <input type="password" name="husband_password" class="form-control" placeholder="Nueva Contraseña (dejar en blanco)">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Esposa</label>
                            <input type="text" name="wife_name" id="edit_wife_name" class="form-control mb-2" placeholder="Nombre completo" required>
                            <input type="text" name="wife_username" id="edit_wife_username" class="form-control mb-2" placeholder="Usuario">
                            <input type="password" name="wife_password" class="form-control" placeholder="Nueva Contraseña (dejar en blanco)">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">Actualizar Foto (opcional)</label>
                            <input type="file" name="pair_photo" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editPair(pairName, hId, hName, hUser, wId, wName, wUser, photoUrl) {
            document.getElementById('edit_old_pair_name').value = pairName;
            document.getElementById('edit_pair_name').value = pairName;
            document.getElementById('edit_husband_id').value = hId;
            document.getElementById('edit_husband_name').value = hName;
            document.getElementById('edit_husband_username').value = hUser;
            document.getElementById('edit_wife_id').value = wId;
            document.getElementById('edit_wife_name').value = wName;
            document.getElementById('edit_wife_username').value = wUser;

            const photoContainer = document.getElementById('current_photo_container');
            const photoPreview = document.getElementById('edit_photo_preview');
            if (photoUrl && photoUrl !== 'null') {
                photoPreview.src = photoUrl;
                photoContainer.style.display = 'block';
            } else {
                photoContainer.style.display = 'none';
            }

            var modal = new bootstrap.Modal(document.getElementById('editPairModal'));
            modal.show();
        }
    </script>
</body>
</html>
