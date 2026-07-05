@extends('layouts.public')

@section('content')
<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Left Side: Login Form -->
        <div class="auth-form-section">
            <div class="auth-form-inner">
                <div class="auth-header mb-4">
                    <h1 class="auth-title">Sign In</h1>
                    <p class="auth-subtitle">Welcome back! Please enter your details.</p>
                </div>

                @if (session('status'))
                    <div class="verification-status mb-4">
                        @if (session('status') === 'verification-link-sent-register')
                            <div class="alert-neo success">
                                <i class="bi bi-envelope-check-fill me-3"></i>
                                <div>
                                    <strong>Verify Your Email</strong>
                                    <p class="mb-0 small">Success! We've sent a verification link to your email address. Please check your inbox and click the link to activate your account.</p>
                                </div>
                            </div>
                        @elseif (session('status') === 'registration-success')
                            <div class="alert-neo success">
                                <i class="bi bi-patch-check-fill me-3"></i>
                                <div>
                                    <strong>Account Created</strong>
                                    <p class="mb-0 small">Your Gmail address is verified automatically. You can sign in now.</p>
                                </div>
                            </div>
                        @elseif (session('status') === 'verified-success')
                            <div class="alert-neo success">
                                <i class="bi bi-patch-check-fill me-3"></i>
                                <div>
                                    <strong>Email Verified!</strong>
                                    <p class="mb-0 small">Great! Your account is now active. Please sign in below to start your personalization journey.</p>
                                </div>
                            </div>
                        @elseif (session('status') === 'already-verified')
                            <div class="alert-neo info">
                                <i class="bi bi-info-circle-fill me-3"></i>
                                <div>
                                    <strong>Already Verified</strong>
                                    <p class="mb-0 small">Your email is already verified. You can sign in right now.</p>
                                </div>
                            </div>
                        @endif
                        @if (session('status') === 'verification-required')
                            <div class="alert alert-warning border-0 rounded-4 p-4 mb-4 shadow-sm animate__animated animate__fadeIn">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="alert-icon bg-warning-subtle text-warning rounded-circle p-2">
                                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-title fw-800 mb-1">Action Required</h5>
                                        <p class="alert-text text-muted mb-0">You must verify your email before you can log in. Did you check your spam folder?</p>
                                    </div>
                                </div>
                                @if (session('unverified_email'))
                                    <div class="mt-3 ps-5">
                                        <form method="POST" action="{{ route('verification.resend') }}">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ session('unverified_email') }}">
                                            <button type="submit" class="btn btn-warning btn-sm rounded-pill fw-700 px-4">
                                                Resend Verification Link
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-soft-error mb-4">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ $type === 'admin' ? route('admin.login') : route('login') }}" class="auth-form">
                    @csrf

                    <div class="form-group-custom mb-3">
                        <label for="email">Email address</label>
                        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group-custom mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password">Password</label>
                            @if (Route::has('password.request'))
                                <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>
                            @endif
                        </div>
                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <span class="label-text">Remember for 30 days</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit w-100 mb-3">
                        {{ $type === 'admin' ? 'Operator Login' : 'Sign in' }}
                    </button>

                    @if($type !== 'admin')
                    <a href="{{ route('auth.google') }}" class="btn-google w-100 mb-4">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" width="20">
                        Sign in with Google
                    </a>

                    <p class="auth-footer-text">
                        Don't have an account? <a href="{{ route('register') }}">Sign up</a>
                    </p>
                    @endif
                </form>
            </div>
        </div>

        <!-- Right Side: Visual Content -->
        <div class="auth-visual-section">
            <div class="visual-card">
                <div class="image-wrapper">
                    <img src="{{ asset('images/dashboard/hero.png') }}" alt="Hero Illustration">
                </div>
                <div class="visual-text">
                    <h2>Master AI-Powered Skills</h2>
                    <p>Track your progress, get personalized mentorship, and accelerate your career with our intelligent learning platform.</p>
                </div>
                <div class="stats-mini">
                    <div class="stat-item">
                        <span class="stat-val">12k+</span>
                        <span class="stat-label">Learners</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-val">450+</span>
                        <span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --primary: #000000;
        --text-main: #101828;
        --text-muted: #667085;
        --border: #D0D5DD;
        --bg-soft: #F9FAFB;
    }

    body {
        background-color: #FFFFFF;
        font-family: 'Inter', sans-serif;
        margin: 0;
    }

    .auth-wrapper {
        display: flex;
    }

    .auth-container {
        display: flex;
        width: 100%;
    }

    /* Form Section */
    .auth-form-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px 40px;
        background: #fff;
    }

    .auth-form-inner {
        width: 100%;
        max-width: 360px;
    }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-icon {
        width: 32px;
        height: 32px;
        background: #f2f4f7;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-name {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text-main);
        letter-spacing: -0.02em;
    }

    .brand-name u {
        text-decoration-color: var(--primary);
        text-underline-offset: 4px;
    }

    .auth-title {
        font-weight: 700;
        font-size: 1.875rem;
        color: var(--text-main);
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }

    .auth-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
    }

    /* Form Elements */
    .form-group-custom {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group-custom label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #344054;
    }

    .form-group-custom input {
        width: 100%;
        padding: 10px 14px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 1rem;
        color: var(--text-main);
        transition: all 0.2s;
        box-shadow: 0px 1px 2px rgba(16, 24, 40, 0.05);
    }

    .form-group-custom input:focus {
        border-color: #98A2B3;
        outline: none;
        box-shadow: 0px 1px 2px rgba(16, 24, 40, 0.05), 0px 0px 0px 4px #F2F4F7;
    }

    .forgot-link {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        color: #475467;
    }

    .custom-checkbox input { display: none; }

    .checkmark {
        width: 16px;
        height: 16px;
        border: 1px solid var(--border);
        border-radius: 4px;
        position: relative;
        transition: all 0.2s;
    }

    .custom-checkbox input:checked ~ .checkmark {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .custom-checkbox input:checked ~ .checkmark:after { display: block; }

    /* Buttons */
    .btn-submit {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-submit:hover { background: #1d2939; }

    .btn-google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #fff;
        color: #344054;
        font-weight: 600;
        text-decoration: none;
        font-size: 1rem;
        transition: background 0.2s;
    }

    .btn-google:hover { background: #f9fafb; }

    .auth-footer-text {
        text-align: center;
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .auth-footer-text a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
    }

    /* Visual Section */
    .auth-visual-section {
        flex: 1;
        background-color: var(--bg-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    @media (max-width: 900px) {
        .auth-visual-section { display: none; }
    }

    .visual-card {
        width: 100%;
        max-width: 580px;
        background: #fff;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0px 48px 80px -12px rgba(16, 24, 40, 0.12);
        border: 1px solid #F2F4F7;
        text-align: center;
    }

    .image-wrapper {
        background: #f9fafb;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .image-wrapper img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        /* Radius effect as requested */
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .visual-text h2 {
        font-weight: 700;
        font-size: 2rem;
        color: var(--text-main);
        margin-bottom: 16px;
        letter-spacing: -0.03em;
    }

    .visual-text p {
        color: var(--text-muted);
        font-size: 1.125rem;
        line-height: 1.6;
        margin-bottom: 48px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .stats-mini {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 40px;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .stat-val {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text-main);
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background: #EAECF0;
    }

    /* Alerts */
    .alert-soft-error {
        background: #FFFBFA;
        border: 1px solid #FDA29B;
        color: #B42318;
        padding: 12px;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .error-message {
        color: #D92D20;
        font-size: 0.75rem;
        margin-top: 4px;
    }

    /* Premium Alerts */
    .alert-neo {
        display: flex;
        align-items: flex-start;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid transparent;
        font-size: 0.95rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .alert-neo.success {
        background: #f0fdf4;
        border-color: #b9f6ca;
        color: #1b5e20;
    }
    .alert-neo.success i { color: #2e7d32; font-size: 1.5rem; }

    .alert-neo.info {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e40af;
    }
    .alert-neo.info i { color: #2563eb; font-size: 1.5rem; }

    .alert-neo.warning {
        background: #fffbeb;
        border-color: #fef3c7;
        color: #92400e;
    }
    .alert-neo.warning i { color: #d97706; font-size: 1.5rem; }

    .alert-neo strong {
        display: block;
        font-weight: 800;
        font-size: 1.05rem;
        margin-bottom: 4px;
    }
</style>
@endsection
