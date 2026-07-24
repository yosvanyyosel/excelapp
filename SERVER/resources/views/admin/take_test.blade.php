<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Test - {{ strtoupper($type) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #eef2ff;
            --bg-canvas: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        body {
            background: radial-gradient(circle at top right, #eef2ff 0%, #f1f5f9 100%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            overflow: hidden;
            height: 100vh;
            margin: 0;
        }

        .test-layout {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Header Styling */
        .test-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 10;
        }

        .progress-wrapper {
            max-width: 600px;
            margin: 0.5rem auto 0;
        }

        .progress-container {
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        #progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, #818cf8 100%);
            width: 0%;
            transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.4);
        }

        /* Timer Circular */
        .timer-box {
            position: relative;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .timer-svg {
            position: absolute;
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .timer-svg circle {
            fill: none;
            stroke-width: 4;
            stroke-linecap: round;
        }

        .timer-bg { stroke: var(--primary-light); }
        .timer-progress {
            stroke: var(--primary);
            stroke-dasharray: 176; /* 2 * PI * r (r=28) */
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 1s linear, stroke 0.3s;
        }

        #timer-text {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--primary);
            z-index: 1;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
        }

        .question-page {
            width: 100%;
            max-width: 850px;
            display: none;
            margin: 0 auto; /* Centrado horizontal dentro del form */
        }

        .question-page.active {
            display: block;
            animation: fadeInScale 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .question-card {
            background: white;
            border-radius: 32px;
            padding: 3rem 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.7);
            text-align: center;
        }

        .question-meta {
            color: var(--primary);
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1.5rem;
            background: var(--primary-light);
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 100px;
        }

        .question-text {
            font-size: clamp(1.4rem, 4vw, 2.1rem);
            font-weight: 800;
            line-height: 1.25;
            color: var(--text-main);
            margin-bottom: 3rem;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Options */
        .options-container {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .option-radio { display: none; }

        .option-label {
            min-width: 100px;
            padding: 1.5rem;
            background: #ffffff;
            border: 2px solid #f1f5f9;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .option-label:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .option-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .option-text {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .option-radio:checked + .option-label {
            background: var(--primary);
            border-color: var(--primary);
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.3);
        }

        .option-radio:checked + .option-label .option-value,
        .option-radio:checked + .option-label .option-text {
            color: white;
        }

        /* Navigation */
        .nav-footer {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 850px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }

        .btn-nav-back {
            background: transparent;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-nav-back:hover:not(:disabled) {
            background: white;
            color: var(--primary);
            border-color: var(--primary);
        }

        /* MBTI specific */
        .mbti-container { gap: 20px; }
        .mbti-option-label {
            min-width: 180px;
            padding: 2.5rem;
        }

        /* Loading */
        .loading-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        @media (max-width: 576px) {
            .question-card { padding: 2rem 1rem; border-radius: 24px; }
            .options-container { gap: 8px; }
            .option-label { min-width: 80px; padding: 1rem; }
            .option-value { font-size: 1.4rem; }
            .mbti-option-label { min-width: 140px; }
            .main-content { padding: 1rem; }
        }
    </style>
</head>
<body>

    <div id="loading" class="loading-overlay">
        <div class="spinner-border text-primary mb-4" style="width: 4rem; height: 4rem; border-width: 0.25em;" role="status"></div>
        <h4 class="fw-800 text-indigo-900">Enviando respuestas...</h4>
        <p class="text-muted">Estamos calculando tus resultados</p>
    </div>

    <div class="test-layout">
        <div class="test-header">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-3 col-md-4">
                        <a href="{{ route('pairs.show', [$user->center_id, $user->pair_name]) }}" class="btn-nav-back d-inline-flex align-items-center gap-2">
                            <i class="bi bi-x-lg"></i> <span class="d-none d-md-inline">Salir</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 text-center">
                        <div class="timer-box">
                            <svg class="timer-svg">
                                <circle class="timer-bg" cx="30" cy="30" r="28"></circle>
                                <circle id="timer-progress" class="timer-progress" cx="30" cy="30" r="28"></circle>
                            </svg>
                            <span id="timer-text">00</span>
                        </div>
                    </div>
                    <div class="col-3 col-md-4 text-end">
                        <span class="badge rounded-pill bg-white border text-primary px-3 py-2 fw-700 shadow-sm">
                            {{ $type == 'mbti' ? 'Personalidad' : 'Dones' }}
                        </span>
                    </div>
                </div>
                <div class="progress-wrapper">
                    <div class="progress-container">
                        <div id="progress-fill"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <form action="{{ route('tests.save') }}" method="POST" id="testForm" class="w-100">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="test_type" value="{{ $type }}">

                @foreach($questions as $index => $q)
                <div class="question-page" id="page-{{ $index + 1 }}">
                    <div class="question-card">
                        <div class="question-meta">Pregunta {{ $index + 1 }} de {{ count($questions) }}</div>
                        <div class="question-text">{{ $q['text'] }}</div>

                        <div class="options-container {{ $type == 'mbti' ? 'mbti-container' : '' }}">
                            @if($type == 'mbti')
                                <div class="option-item">
                                    <input type="radio" name="answers[{{ $q['number'] }}]" value="1" id="q{{ $q['number'] }}_1" class="option-radio" onchange="autoNext()">
                                    <label for="q{{ $q['number'] }}_1" class="option-label mbti-option-label">
                                        <span class="option-value">SÍ</span>
                                    </label>
                                </div>
                                <div class="option-item">
                                    <input type="radio" name="answers[{{ $q['number'] }}]" value="0" id="q{{ $q['number'] }}_0" class="option-radio" onchange="autoNext()">
                                    <label for="q{{ $q['number'] }}_0" class="option-label mbti-option-label">
                                        <span class="option-value">NO</span>
                                    </label>
                                </div>
                            @else
                                @for($i = 0; $i <= 4; $i++)
                                <div class="option-item">
                                    <input type="radio" name="answers[{{ $q['number'] }}]" value="{{ $i }}" id="q{{ $q['number'] }}_{{ $i }}" class="option-radio" onchange="autoNext()">
                                    <label for="q{{ $q['number'] }}_{{ $i }}" class="option-label">
                                        <span class="option-value">{{ $i }}</span>
                                        <span class="option-text">
                                            @if($i == 0) Nada @elseif($i == 1) Poco @elseif($i == 2) Regular @elseif($i == 3) Mucho @else Total @endif
                                        </span>
                                    </label>
                                </div>
                                @endfor
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </form>

            <div class="nav-footer">
                <button type="button" id="prevBtn" class="btn-nav-back" onclick="prevQuestion()" style="visibility: hidden;">
                    <i class="bi bi-chevron-left"></i> Anterior
                </button>
                <div class="text-muted small fw-600">
                    <i class="bi bi-person-circle me-1"></i> Evaluando a: <span class="text-dark">{{ $user->name }}</span>
                </div>
                <div style="width: 80px"></div> {{-- Spacer --}}
            </div>
        </div>
    </div>

    <script>
        const totalQuestions = {{ count($questions) }};
        const maxTime = {{ $timer }};
        let currentTime = maxTime;
        let currentIdx = 1;
        let timerInterval;
        const dashArray = 176;

        function startTimer() {
            clearInterval(timerInterval);
            currentTime = maxTime;
            updateTimerDisplay();

            timerInterval = setInterval(() => {
                currentTime--;
                updateTimerDisplay();

                if (currentTime <= 0) {
                    autoNext();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const display = document.getElementById('timer-text');
            const circle = document.getElementById('timer-progress');

            display.innerText = currentTime;

            // Update circular progress
            const offset = dashArray - (currentTime / maxTime) * dashArray;
            circle.style.strokeDashoffset = offset;

            // Visual urgency
            if (currentTime <= 3) {
                circle.style.stroke = '#ef4444';
                display.style.color = '#ef4444';
            } else {
                circle.style.stroke = '#6366f1';
                display.style.color = '#6366f1';
            }
        }

        function showPage(index) {
            if (index > totalQuestions) {
                submitTest();
                return;
            }

            document.querySelectorAll('.question-page').forEach(p => p.classList.remove('active'));
            const activePage = document.getElementById('page-' + index);
            activePage.classList.add('active');

            // Visibility of back button
            document.getElementById('prevBtn').style.visibility = (index === 1) ? 'hidden' : 'visible';

            currentIdx = index;
            updateProgress();
            startTimer();
        }

        function updateProgress() {
            const progress = ((currentIdx - 1) / totalQuestions) * 100;
            document.getElementById('progress-fill').style.width = progress + '%';
        }

        function autoNext() {
            setTimeout(() => {
                const currentInputs = document.querySelectorAll('#page-' + currentIdx + ' input');
                let answered = false;
                currentInputs.forEach(input => { if (input.checked) answered = true; });

                if (!answered) {
                    // Valor por defecto si no responde (0 en Dones, NO en MBTI)
                    const firstOption = document.querySelector('#page-' + currentIdx + ' input[value="0"]');
                    if (firstOption) firstOption.checked = true;
                }

                showPage(currentIdx + 1);
            }, 400);
        }

        function prevQuestion() {
            if (currentIdx > 1) {
                showPage(currentIdx - 1);
            }
        }

        function submitTest() {
            clearInterval(timerInterval);
            document.getElementById('progress-fill').style.width = '100%';
            document.getElementById('loading').style.display = 'flex';
            document.getElementById('testForm').submit();
        }

        window.onload = () => {
            showPage(1);
        };
    </script>
</body>
</html>
