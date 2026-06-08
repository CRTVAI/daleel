<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dallel AI Admin | Management Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-bg: #f8fafc;
            --sidebar-bg: #ffffff;
            --accent-color: #6366f1;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --sidebar-width: 260px;
            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --card-shadow-hover: 0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-main);
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .brand-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Modern Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .brand-logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--accent-color), #818cf8);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
            color: var(--text-main);
        }

        .sidebar-menu {
            flex: 1;
            padding: 0 16px;
            list-style: none;
            margin: 0;
        }

        .menu-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            padding: 0 16px 8px;
            letter-spacing: 1px;
        }

        .menu-item {
            margin-bottom: 4px;
        }

        .menu-item a {
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .menu-item a:hover {
            background: #f1f5f9;
            color: var(--accent-color);
        }

        .menu-item.active a {
            background: rgba(99, 102, 241, 0.08);
            color: var(--accent-color);
            font-weight: 600;
        }

        .menu-item a i {
            font-size: 1.1rem;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .admin-header {
            height: 72px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .admin-content {
            padding: 32px;
            flex: 1;
        }

        .page-title {
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 24px;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        /* Cards & Components */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            background: white;
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .btn-primary {
            background: var(--accent-color);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-1px);
        }

        .user-badge {
            background: white;
            padding: 6px 12px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-badge:hover {
            background: #f8fafc;
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: var(--accent-color);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Custom Pagination */
        .pagination {
            gap: 6px;
        }
        .page-link {
            border: none;
            border-radius: 10px !important;
            padding: 8px 16px;
            color: var(--text-muted);
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .page-item.active .page-link {
            background: var(--accent-color);
            color: white;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }
        .page-item.disabled .page-link {
            background: #f1f5f9;
            color: #cbd5e1;
        }
        .page-link:hover:not(.active) {
            background: #f1f5f9;
            color: var(--accent-color);
            transform: translateY(-1px);
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <div class="brand-logo"><i class="bi bi-rocket-takeoff-fill"></i></div>
                <span class="brand-text">DALLEL AI ADMIN</span>
            </div>

            <div class="menu-label">Main Menu</div>
            <ul class="sidebar-menu">
                <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Overview</a>
                </li>
                 <li class="menu-item {{ request()->routeIs('admin.tools.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tools.index') }}"><i class="bi bi-cpu-fill"></i> Connected Tools</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.profile-options.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.profile-options.index') }}"><i class="bi bi-list-check"></i> Profile Options</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill"></i> User Management</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.contents.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.contents.index') }}"><i class="bi bi-play-circle-fill"></i> Content Library</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.courses.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Course Library</a>
                </li>
               
            </ul>

            <!-- <div class="menu-label mt-4">System</div>
            <ul class="sidebar-menu">
                <li class="menu-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <a href="{{ route('admin.analytics') }}"><i class="bi bi-bar-chart-line-fill"></i> Analytics</a>
                </li>
                <li class="menu-item {{ request()->routeIs('activity.history') ? 'active' : '' }}">
                    <a href="{{ route('activity.history') }}"><i class="bi bi-journal-text"></i> System Logs</a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings') }}"><i class="bi bi-gear-fill"></i> Settings</a>
                </li>
            </ul> -->

            <div class="p-4 mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-light w-100 rounded-3 py-2 fw-bold text-danger border-0">
                        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-link p-0 text-muted d-lg-none"><i class="bi bi-list fs-4"></i></button>
                    <h6 class="mb-0 fw-bold d-none d-md-block">Admin Control Panel</h6>
                </div>
                
                <div class="dropdown">
                    <div class="user-badge" data-bs-toggle="dropdown">
                        <div class="text-end d-none d-sm-block">
                            <div class="fw-bold" style="font-size: 0.85rem;">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">Root Admin</div>
                        </div>
                        <div class="avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 rounded-4 p-2" style="min-width: 200px;">
                        <!-- <li><a class="dropdown-item rounded-3 small fw-semibold py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>My Profile</a></li> -->
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item rounded-3 text-danger small fw-semibold py-2"><i class="bi bi-box-arrow-right me-2"></i>Log Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <div class="admin-content fade-in">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
