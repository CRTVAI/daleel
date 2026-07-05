<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AI Mentor Workspace')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dashboard/fav.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; }
        .mentor-header { background: #000; color: #fff; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; }
        .mentor-main-container { padding: 2rem; max-width: 1400px; margin: 0 auto; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
    @yield('styles')
</head>
<body>

<header class="mentor-header">
    <div class="d-flex align-items-center gap-3">
        <div style="width: 32px; height: 32px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-robot text-dark fs-5"></i>
        </div>
        <span class="fw-800 fs-5" style="letter-spacing: -0.05em;">AI MENTOR <span style="color: #6366f1;">WORKSPACE</span></span>
    </div>
</header>

<main class="mentor-main-container">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
