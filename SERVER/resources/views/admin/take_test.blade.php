<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Test - {{ strtoupper($type) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --indigo-600: #4f46e5;
            --indigo-700: #4338ca;
            --bg-canvas: #f8fafc;
        }
        body { background-color: var(--bg-canvas); font-family: 'Inter', system-ui, sans-serif; }
        .test-container { max-width: 800px; margin: 40px auto; padding-bottom: 100px; }
        .question-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .question-text { font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 1.5rem; }

        .scale-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        .scale-item { text-align: center; flex: 1; }
        .scale-radio { display: none; }
        .scale-label {
            display: block;
            padding: 10px;
            background: #f1f5f9;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.2s;
            border: 2px solid transparent;
            color: #64748b;
        }
        .scale-radio:checked + .scale-label {
            background: var(--indigo-600);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        .scale-hint { font-size: 0.75rem; margin-top: 5px; color: #94a3b8; font-weight: 600; }

        .submit-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -10px 15px -3px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }
        .btn-flutter {
            background: var(--indigo-600);
            color: white;
            border-radius: 15px;
            padding: 12px 30px;
            font-weight: 700;
            border: none;
            width: 100%;
            max-width: 400px;
        }
        .btn-flutter:hover { background: var(--indigo-700); color: white; }

        .header-section { margin-bottom: 3rem; text-align: center; }
        .type-badge {
            background: var(--indigo-50);
            color: var(--indigo-600);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('pairs.show', [$user->center_id, $user->pair_name]) }}">
                <i class="bi bi-x-circle me-2"></i> Cancelar Test
            </a>
            <span class="navbar-text text-white fw-bold">Evaluando a: {{ $user->name }}</span>
        </div>
    </nav>

    <div class="container test-container">
        <div class="header-section">
            <span class="type-badge mb-2 d-inline-block">{{ $type == 'mbti' ? 'Test de Personalidad' : 'Test de Dones' }}</span>
            <h1 class="fw-800">{{ $type == 'mbti' ? 'Perfil MBTI' : 'Dones Espirituales' }}</h1>
            <p class="text-muted">Responde con total sinceridad según tu realidad actual.</p>
        </div>

        <form action="{{ route('tests.save') }}" method="POST" id="testForm">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="test_type" value="{{ $type }}">

            @foreach($questions as $q)
            <div class="question-card">
                <div class="question-text">
                    <span class="text-primary me-2">#{{ $q['number'] }}</span> {{ $q['text'] }}
                </div>

                <div class="scale-container">
                    @for($i = 0; $i <= 5; $i++)
                    <div class="scale-item">
                        <input type="radio" name="answers[{{ $q['number'] }}]" value="{{ $i }}" id="q{{ $q['number'] }}_{{ $i }}" class="scale-radio" required>
                        <label for="q{{ $q['number'] }}_{{ $i }}" class="scale-label">{{ $i }}</label>
                        @if($i == 0) <div class="scale-hint">Nada</div> @endif
                        @if($i == 5) <div class="scale-hint">Mucho</div> @endif
                    </div>
                    @endfor
                </div>
            </div>
            @endforeach

            <div class="submit-bar text-center">
                <button type="submit" class="btn btn-flutter btn-lg">
                    GUARDAR RESULTADOS <i class="bi bi-check-circle-fill ms-2"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('testForm').onsubmit = function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> GUARDANDO...';
        };
    </script>
</body>
</html>
