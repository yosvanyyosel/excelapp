<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Pareja - {{ $pairName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .object-fit-cover { object-fit: cover; }
        .card-result { border-left: 5px solid #3b82f6; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('centers.show', $center->id) }}"><i class="bi bi-arrow-left"></i> Volver al Centro</a>
            <span class="navbar-text">Expediente de Pareja: <strong>{{ $pairName }}</strong></span>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row">
            <!-- Columna Izquierda: Información de Pareja -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        @if($husband->pair_photo)
                            <img src="{{ asset('storage/' . $husband->pair_photo) }}" class="rounded shadow-sm mb-3 object-fit-cover" width="200" height="200">
                        @else
                            <div class="bg-secondary text-white rounded mx-auto d-flex align-items-center justify-content-center mb-3" style="width:200px; height:200px;">
                                <i class="bi bi-people-fill display-1"></i>
                            </div>
                        @endif
                        <h4>{{ $pairName }}</h4>
                        <p class="text-muted"><i class="bi bi-building"></i> {{ $center->name }}</p>
                        <hr>
                        <div class="d-grid">
                            <a href="{{ route('pdf.pair', $husband->id) }}" target="_blank" class="btn btn-outline-danger">
                                <i class="bi bi-file-earmark-pdf"></i> Imprimir Portada PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Integrantes y Resultados -->
            <div class="col-md-8">
                <ul class="nav nav-tabs mb-3" id="pairTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#husband-tab">
                            <i class="bi bi-gender-male text-primary"></i> Esposo: {{ $husband->name }}
                        </button>
                    </li>
                    @if($wife)
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#wife-tab">
                            <i class="bi bi-gender-female text-danger"></i> Esposa: {{ $wife->name }}
                        </button>
                    </li>
                    @endif
                </ul>

                <div class="tab-content">
                    <!-- Tab Esposo -->
                    <div class="tab-pane fade show active" id="husband-tab">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6>Resultados de Tests</h6>
                                @forelse($husbandResults as $result)
                                    <div class="card card-result mb-3 bg-white">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-1 text-primary">{{ $result->test_type == 'mbti' ? 'Test de Personalidad (MBTI)' : 'Test de Dones Espirituales' }}</h5>
                                                    <small class="text-muted"><i class="bi bi-calendar-check"></i> Completado el: {{ $result->completed_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <span class="badge bg-light text-dark border">{{ count($result->answers) }} Respuestas</span>
                                            </div>
                                            <div class="mt-3">
                                                <button class="btn btn-sm btn-link p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#answers-h-{{ $result->id }}">
                                                    <i class="bi bi-list-check"></i> Ver detalles de respuestas
                                                </button>
                                                <div class="collapse mt-2" id="answers-h-{{ $result->id }}">
                                                    <div class="bg-light p-2 rounded small" style="max-height: 200px; overflow-y: auto;">
                                                        <table class="table table-sm table-borderless mb-0">
                                                            @foreach($result->answers as $ans)
                                                            <tr>
                                                                <td width="30">#{{ $ans['number'] }}</td>
                                                                <td><strong>{{ $ans['answer'] }}</strong></td>
                                                            </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-4 text-muted">Aún no ha realizado ningún test.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @if($wife)
                    <!-- Tab Esposa -->
                    <div class="tab-pane fade" id="wife-tab">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6>Resultados de Tests</h6>
                                @forelse($wifeResults as $result)
                                    <div class="card card-result mb-3 bg-white">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-1 text-primary">{{ $result->test_type == 'mbti' ? 'Test de Personalidad (MBTI)' : 'Test de Dones Espirituales' }}</h5>
                                                    <small class="text-muted"><i class="bi bi-calendar-check"></i> Completado el: {{ $result->completed_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <span class="badge bg-light text-dark border">{{ count($result->answers) }} Respuestas</span>
                                            </div>
                                            <div class="mt-3">
                                                <button class="btn btn-sm btn-link p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#answers-w-{{ $result->id }}">
                                                    <i class="bi bi-list-check"></i> Ver detalles de respuestas
                                                </button>
                                                <div class="collapse mt-2" id="answers-w-{{ $result->id }}">
                                                    <div class="bg-light p-2 rounded small" style="max-height: 200px; overflow-y: auto;">
                                                        <table class="table table-sm table-borderless mb-0">
                                                            @foreach($result->answers as $ans)
                                                            <tr>
                                                                <td width="30">#{{ $ans['number'] }}</td>
                                                                <td><strong>{{ $ans['answer'] }}</strong></td>
                                                            </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-4 text-muted">Aún no ha realizado ningún test.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
