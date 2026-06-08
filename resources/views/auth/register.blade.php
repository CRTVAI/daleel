@extends('layouts.public')

@section('content')
<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Left Side: Register Form -->
        <div class="auth-form-section">
            <div class="auth-form-inner">
                <a class="brand mb-5" href="/" style="display: flex; gap: 10px; text-decoration: none; align-items: center;">
                    <span class="brand-mark">DA</span>
                    <span class="brand-copy"><strong>Dallel AI</strong><small>by Creative AI</small></span>
                </a>

                <div class="auth-header mb-4">
                    <h1 class="auth-title">Create account</h1>
                    <p class="auth-subtitle">Start your 30-day free trial today.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-group-custom mb-3">
                        <label for="name">Name</label>
                        <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your name">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group-custom mb-3">
                        <label for="email">Email address</label>
                        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row-custom mb-3">
                        <div class="form-group-custom flex-1">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Create a password">
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group-custom flex-1">
                            <label for="password-confirm">Confirm password</label>
                            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                        </div>
                    </div>
                    <p class="input-hint mb-4">Must be at least 8 characters.</p>

                    <button type="submit" class="btn-submit w-100 mb-3">
                        Get started
                    </button>

                    <a href="{{ route('auth.google') }}" class="btn-google w-100 mb-4">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" width="20">
                        Sign up with Google
                    </a>

                    <p class="auth-footer-text">
                        Already have an account? <a href="{{ route('login') }}">Log in</a>
                    </p>
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
                    <h2>Join the AI Revolution</h2>
                    <p>Unlock personalized learning paths designed to help you master the tools of tomorrow. Get real-time feedback and track your growth.</p>
                </div>
                
                <div class="feature-list-mini">
                    <div class="feature-tag">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Personalized Curriculum</span>
                    </div>
                    <div class="feature-tag">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Interactive AI Mentor</span>
                    </div>
                    <div class="feature-tag">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Skill Progress Tracking</span>
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

    .form-row-custom {
        display: flex;
        gap: 16px;
    }

    .flex-1 { flex: 1; }

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

    .input-hint {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin: 0;
    }

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

    .feature-list-mini {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .feature-tag {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: #475467;
        font-weight: 500;
    }

    .feature-tag i {
        color: #12B76A;
    }

    .error-message {
        color: #D92D20;
        font-size: 0.75rem;
        margin-top: 4px;
    }
</style>
@endsection
