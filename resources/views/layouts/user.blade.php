<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dallel AI Mentor')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --blue-focus: #2563eb;
            --teal: #0d9488;
            --text-main: #020617;
            --text-muted: #475569;
            --border-color: #e2e8f0;
            --bg-main: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }

        /* High-Focus Top Navbar */
        .navbar-main {
            height: 80px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 5%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .navbar-brand-neo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-right: 60px;
        }

        .brand-mark {
            display: grid;
            width: 40px;
            height: 40px;
            place-items: center;
            border-radius: 10px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            font-size: 1rem;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .brand-copy {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-copy strong {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .brand-copy small {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .nav-links-center {
            display: flex;
            gap: 32px;
            margin-right: auto;
        }

        .nav-link-neo {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .nav-link-neo:hover { color: var(--primary); }
        .nav-link-neo.active { color: var(--primary); }
        .nav-link-neo.active::after {
            content: '';
            position: absolute;
            bottom: -28px;
            left: 0; width: 100%;
            height: 3px;
            background: var(--primary);
            border-radius: 10px 10px 0 0;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .btn-get-started {
            background: var(--blue-focus);
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 14px;
            transition: all 0.2s;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.2);
            text-decoration: none;
        }

        .btn-get-started:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .icon-btn-neo {
            color: var(--text-main);
            font-size: 20px;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .icon-btn-neo:hover { color: var(--primary); transform: scale(1.1); }

        .avatar-neo {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: #fff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.1);
            transition: 0.2s;
        }
        .avatar-neo:hover { transform: scale(1.05); }

        /* Content Container */
        .main-container-neo {
            padding: 60px 0;
            max-width: 1440px;
            margin: 0 auto;
            width: 90%;
        }

        /* Warning Bar */
        .warning-bar-neo {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 20px;
            padding: 16px 28px;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #b91c1c;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(185, 28, 28, 0.05);
        }

        .btn-complete-neo {
            background: #b91c1c;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-complete-neo:hover { background: #991b1b; transform: translateY(-1px); }

        @media (max-width: 1100px) {
            .nav-links-center { gap: 20px; }
            .brand-name-neo { display: none; }
        }

        /* Toast Notifications */
        .toast-container-neo {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .toast-neo {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 16px 24px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .toast-neo.show {
            transform: translateY(0);
            opacity: 1;
        }
        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .toast-success .toast-icon { background: #f0fdf4; color: #16a34a; }
        .toast-info .toast-icon { background: #eff6ff; color: #2563eb; }
        .toast-message { font-weight: 700; font-size: 14px; color: #1e293b; }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar-main">
        <a href="{{ route('dashboard') }}" class="navbar-brand-neo">
            <div class="brand-mark">DA</div>
            <div class="brand-copy">
                <strong>Dallel AI</strong>
                <small>by Creative AI</small>
            </div>
        </a>

        <div class="nav-links-center">
            <a href="{{ route('dashboard') }}" class="nav-link-neo {{ Route::is('dashboard') ? 'active' : '' }}">Home</a>
            <a href="{{ route('learn.explore') }}" class="nav-link-neo {{ Route::is('learn.explore') ? 'active' : '' }}">Learning Hub</a>
            <a href="{{ route('bookmarks') }}" class="nav-link-neo {{ Route::is('bookmarks') ? 'active' : '' }}">Bookmarks</a>
            <a href="{{ route('extension.install') }}" class="nav-link-neo {{ Route::is('extension.install') ? 'active' : '' }}">AI Extension</a>
            @if(auth()->user()->can_access_team)
            <a href="{{ route('team.index') }}" class="nav-link-neo {{ Route::is('team.index') ? 'active' : '' }}">My Team</a>
            @endif
        </div>

        <div class="navbar-actions">
            <a href="{{ route('learn.explore') }}" class="icon-btn-neo d-none d-md-flex"><i class="bi bi-search"></i></a>
            
            <a href="{{ route('ai.mentor') }}" class="btn-get-started d-none d-lg-block">
                <i class="bi bi-stars me-2"></i> Ask AI Mentor
            </a>

            <div class="icon-btn-neo position-relative">
                <i class="bi bi-bell-fill"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width: 10px; height: 10px;"></span>
            </div>

            <div class="dropdown">
                <div class="d-flex align-items-center gap-2" style="cursor:pointer;" data-bs-toggle="dropdown">
                    <div class="avatar-neo">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-2xl mt-3 rounded-4 p-2" style="min-width: 200px;">
                    <li><div class="dropdown-header fw-800 text-dark opacity-50 pb-2">PERSONAL</div></li>
                    <li><a class="dropdown-item rounded-3 fw-700 small py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person-fill me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item rounded-3 fw-700 small py-2" href="#"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item rounded-3 text-danger fw-700 small py-2"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-container-neo">
        @if(!auth()->user()->hasVerifiedEmail())
            <div class="warning-bar-neo mb-4">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-envelope-exclamation fs-5 text-danger"></i>
                    <span>Please verify your email to unlock full access to Dallel AI features.</span>
                </div>
                <form action="{{ route('verification.send') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-complete-neo">Resend Activation Email</button>
                </form>
            </div>
        @endif

        @yield('content')
    </main>

    <div id="onboarding-container">
        @if(auth()->user()->hasVerifiedEmail() && auth()->user()->hasIncompleteProfile())
            @include('components.onboarding-modal')
        @endif
    </div>

    <div class="toast-container-neo" id="toast-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('status') === 'verification-link-sent')
                showToast('A verification link has been sent to your email address.', 'success');
            @endif
        });

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast-neo toast-${type}`;
            
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill';
            
            toast.innerHTML = `
                <div class="toast-icon"><i class="bi ${icon}"></i></div>
                <div class="toast-message">${message}</div>
            `;
            
            container.appendChild(toast);
            
            // Show animation
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }
    </script>
    @yield('scripts')
</body>
</html>
