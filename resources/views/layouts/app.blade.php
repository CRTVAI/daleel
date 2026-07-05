<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daleel AI Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dashboard/fav.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 70px;
            --accent: #7c6fff;
            --accent-gradient: linear-gradient(135deg, #7c6fff 0%, #a78bfa 100%);
            --bg-body: #f8f9fc;
            --sidebar-bg: #0d0f1c;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-body); 
            color: #334155; 
            margin: 0; 
            opacity: 0; 
            transition: opacity 0.4s ease-in-out; 
        }
        body.loaded { opacity: 1; }

        /* Layout Structure */
        .wrapper { display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #fff;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }
        .sidebar-brand { padding: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-nav { flex: 1; padding: 20px 15px; }
        .nav-link {
            color: #94a3b8;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .nav-link.active { background: rgba(124, 111, 255, 0.15); color: #c4b5fd; font-weight: 600; }

        /* Main Content */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 30px;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .content-body { flex: 1; overflow-y: auto; padding: 30px; }

        /* Generic Components */
        .text-gradient { background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    </style>
    @yield('styles')
</head>
<body>
    @auth
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="d-flex align-items-center gap-2">
                    <x-application-logo style="height: 32px; width: auto;" />
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
                <a href="{{ route('activity.history') }}" class="nav-link {{ request()->routeIs('activity.history') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Activity History
                </a>
                <a href="{{ route('roadmap') }}" class="nav-link {{ request()->routeIs('roadmap') ? 'active' : '' }}">
                    <i class="bi bi-map"></i> My Roadmaps
                </a>
                @if(Auth::user()->is_admin)
                <a href="#" class="nav-link">
                    <i class="bi bi-people"></i> Users
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-bar-chart"></i> Reports
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-gear"></i> Settings
                </a>
                @endif
            </nav>
            <div class="p-3 border-top border-secondary-subtle">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-3">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Area -->
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-list fs-4 text-muted d-md-none"></i>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item text-muted small">{{ Auth::user()->is_admin ? 'Admin' : 'User' }}</li>
                            <li class="breadcrumb-item active small" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown">
                            <div class="text-end d-none d-sm-block">
                                <div class="fw-bold small lh-1">{{ Auth::user()->name }}</div>
                                <div class="text-muted" style="font-size: 10px;">{{ Auth::user()->is_admin ? 'Administrator' : 'User' }}</div>
                            </div>
                            <div class="bg-light rounded-circle p-2 border" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-item small" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item small text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="content-body">
                @yield('content')
            </main>
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('loaded');
        });
    </script>
    @yield('scripts')
</body>
</html>
