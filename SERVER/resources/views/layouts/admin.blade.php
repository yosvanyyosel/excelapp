<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Discovery Admin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #22c55e;
            --primary-rgb: 34, 197, 94;
            --dark: #000000;
            --bg-body: #f4f7f6;
            --bg-card: #ffffff;
            --text-main: #1a1a1a;
            --text-muted: #6b7280;
            --sidebar-bg: #000000;
            --sidebar-text: #9ca3af;
            --border-color: #e5e7eb;
        }

        .dark {
            --bg-body: #0a0a0a;
            --bg-card: #141414;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --border-color: #262626;
            --sidebar-bg: #000000;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            transition: all 0.3s ease;
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            z-index: 1050;
            box-shadow: 10px 0 30px rgba(0,0,0,0.05);
        }

        #sidebar.collapsed {
            width: 80px;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-brand span {
            transition: opacity 0.3s;
        }

        #sidebar.collapsed .sidebar-brand span {
            opacity: 0;
            pointer-events: none;
        }

        .nav-link {
            color: var(--sidebar-text);
            padding: 0.8rem 1.25rem;
            margin: 0.2rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            font-weight: 500;
            text-decoration: none;
        }

        .nav-link:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.05);
        }

        .nav-link.active {
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
        }

        #sidebar.collapsed .nav-link span {
            display: none;
        }

        #sidebar.collapsed .nav-link {
            justify-content: center;
            margin: 0.2rem 0.5rem;
            padding: 0.8rem;
        }

        /* Content Area */
        #main-wrapper {
            margin-left: 260px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #main-wrapper.expanded {
            margin-left: 80px;
        }

        header.top-bar {
            background: rgba(var(--bg-card), 0.8);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
        }

        /* Cards and Elements */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #1dae50;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.2);
        }

        .form-control, .form-select {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: var(--text-main);
        }

        .form-control:focus {
            background: var(--bg-card);
            color: var(--text-main);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
        }

        /* Responsive */
        @media (max-width: 992px) {
            #sidebar {
                margin-left: -260px;
            }
            #sidebar.show-mobile {
                margin-left: 0;
            }
            #main-wrapper {
                margin-left: 0 !important;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-body);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-brand">
                <img src="/logoexcel.png" style="width: 40px; height: 40px" class="fs-3 me-2"/>
                <img src="/logotipo.webp"  style="width: 140px; height: 40px" class="fw-800 fs-4"/>
            </div>

            <div class="mt-2">
                @yield('sidebar_menu')
            </div>

            <div class="position-absolute bottom-0 w-100 p-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger border-0 w-100 d-flex align-items-center justify-content-center gap-2 rounded-3">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="sidebar-text-hide">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Wrapper -->
        <div id="main-wrapper" class="w-100">
            <header class="top-bar">
                <div class="d-flex align-items-center gap-3">
                    <button id="toggleSidebar" class="btn btn-light rounded-3 p-2">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h5 class="mb-0 fw-700 d-none d-md-block">@yield('page_title')</h5>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!---<button id="darkModeToggle" class="btn btn-light rounded-circle p-2" style="width: 40px; height: 40px;">
                        <i class="bi bi-moon-fill" id="themeIcon"></i>
                    </button>--->

                    <div class="dropdown">
                        <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="fw-600 d-none d-sm-inline">{{ Auth::user()->name }}</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 rounded-4">
                            <li class="px-3 py-2">
                                <div class="small text-muted">Conectado como</div>
                                <div class="fw-700">{{ Auth::user()->role }}</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Perfil</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Salir</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="p-4 p-md-5">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('main-wrapper');
        const toggleBtn = document.getElementById('toggleSidebar');
        const darkModeBtn = document.getElementById('darkModeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        // Sidebar Toggle
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('show-mobile');
            } else {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('expanded');

                // Hide text for smooth collapse
                document.querySelectorAll('.sidebar-text-hide').forEach(el => {
                    el.style.display = sidebar.classList.contains('collapsed') ? 'none' : 'inline';
                });
            }
        });

        // Dark Mode Logic
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            html.classList.add('dark');
            html.classList.remove('light');
            themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
        }

        darkModeBtn.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
                themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
            }
        });

        // Close mobile sidebar on click outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('show-mobile');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
