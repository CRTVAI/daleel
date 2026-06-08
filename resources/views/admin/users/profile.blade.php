@extends('layouts.admin')

@section('title', 'User Profile Settings — Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="page-title mb-1">Manage User Profile</h2>
                <p class="text-muted">Updating profile details for <strong>{{ $user->name }}</strong></p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-3 fw-bold">
                <i class="bi bi-arrow-left me-2"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="profile-settings-wrapper">
        <div class="row g-4">
            <!-- Settings Sidebar -->
            <div class="col-lg-3">
                <div class="settings-nav card border-0 shadow-sm rounded-4 p-3 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-4 px-2">
                        <div class="avatar-box" style="width: 48px; height: 48px; font-size: 18px; background: #f1f5f9; color: #6366f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $user->name }}</h6>
                            <p class="text-muted small mb-0">{{ $user->account_type ?? 'Student Account' }}</p>
                        </div>
                    </div>
                    
                    <nav class="nav flex-column gap-2">
                        <a href="#account" class="nav-link-custom active" data-bs-toggle="pill">
                            <i class="bi bi-person-circle"></i> Account Information
                        </a>
                        <a href="#learning" class="nav-link-custom" data-bs-toggle="pill">
                            <i class="bi bi-mortarboard"></i> Learning Profile
                        </a>
                        <a href="#security" class="nav-link-custom" data-bs-toggle="pill">
                            <i class="bi bi-shield-lock"></i> Password & Security
                        </a>
                    </nav>
                </div>

                <!-- Stats Mini Widget -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-dark text-white mb-4">
                    <h6 class="fw-bold small mb-3 opacity-75">LEARNING STREAK</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-1 fw-bold">{{ $user->streak_count }}</div>
                        <div class="lh-1">
                            <div class="fw-bold">Days</div>
                            <div class="small opacity-75">
                                @if($user->streak_count >= 5) Active Learner 🔥 @else Getting started 🚀 @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Settings Form Area -->
            <div class="col-lg-9">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="tab-content">
                        <!-- Account Section -->
                        <div class="tab-pane fade show active" id="account">
                            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                                <div class="mb-4">
                                    <h4 class="fw-800 text-dark mb-1">Account Information</h4>
                                    <p class="text-muted small">Official account details and subscription status.</p>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label-minimal">Full Name</label>
                                        <input type="text" name="name" class="form-control-minimal" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-minimal">Email Address</label>
                                        <input type="email" name="email" class="form-control-minimal" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-minimal">Account Type</label>
                                        <select name="account_type" class="form-select-minimal">
                                            <option value="Free Plan" {{ $user->account_type == 'Free Plan' ? 'selected' : '' }}>Free Plan</option>
                                            <option value="Premium" {{ $user->account_type == 'Premium' ? 'selected' : '' }}>Premium</option>
                                            <option value="Enterprise" {{ $user->account_type == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-minimal">Member Since</label>
                                        <input type="text" class="form-control-minimal" value="{{ $user->created_at->format('M d, Y') }}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Learning Profile Section -->
                        <div class="tab-pane fade" id="learning">
                            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                                <div class="mb-4">
                                    <h4 class="fw-800 text-dark mb-1">Learning Profile</h4>
                                    <p class="text-muted small">Personalized learning paths and interests.</p>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label-minimal">Primary Learning Goal</label>
                                        <select name="primary_goal" class="form-select-minimal">
                                            <option value="">Select a Goal</option>
                                            @foreach($learningGoals as $goal)
                                                <option value="{{ $goal->title }}" {{ $user->learning_goal == $goal->title ? 'selected' : '' }}>{{ $goal->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-minimal">Experience Level</label>
                                        <select name="experience_level" class="form-select-minimal">
                                            <option value="">Select Level</option>
                                            @foreach($experienceLevels as $level)
                                                <option value="{{ $level->title }}" {{ $user->experience_level == $level->title ? 'selected' : '' }}>{{ $level->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-minimal">Interests</label>
                                        <input type="text" name="interests" id="interests-tagify" class="form-control-minimal" value="{{ is_array($user->interests) ? implode(',', $user->interests) : '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-minimal">Used Tools</label>
                                        <input type="text" name="tools" id="tools-tagify" class="form-control-minimal" value="{{ is_array($user->tools) ? implode(',', $user->tools) : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="tab-pane fade" id="security">
                            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                                <div class="mb-4">
                                    <h4 class="fw-800 text-dark mb-1">Reset Password</h4>
                                    <p class="text-muted small">Manually reset the user's password if requested.</p>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label-minimal">New Password</label>
                                        <input type="password" name="password" class="form-control-minimal" autocomplete="new-password">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-minimal">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control-minimal" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-2">
                        <button type="reset" class="btn btn-light rounded-3 px-4 fw-bold">Discard Changes</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-5 fw-bold shadow-sm">Save User Updates</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- Tagify CSS -->
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    .profile-settings-wrapper { animation: fadeIn 0.3s ease-out; }
    
    .nav-link-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #64748b;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.2s;
    }
    .nav-link-custom i { font-size: 1.1rem; }
    .nav-link-custom:hover { background: #f1f5f9; color: #000; }
    .nav-link-custom.active { background: #6366f1; color: #fff; }

    .form-label-minimal {
        font-size: 11px;
        font-weight: 800;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-control-minimal, .form-select-minimal {
        width: 100%;
        padding: 12px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .form-control-minimal:focus, .form-select-minimal:focus {
        background: #fff;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .tagify {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 8px;
    }

    .fw-800 { font-weight: 800; }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var interestsInput = document.querySelector('#interests-tagify');
        if(interestsInput) {
            new Tagify(interestsInput, {
                whitelist: [
                    @foreach($interestsList as $interest)
                        "{{ $interest }}",
                    @endforeach
                ],
                dropdown: { enabled: 0 }
            });
        }

        var toolsInput = document.querySelector('#tools-tagify');
        if(toolsInput) {
            new Tagify(toolsInput, {
                whitelist: [
                    @foreach($tools as $tool)
                        "{{ $tool->name }}",
                    @endforeach
                ],
                dropdown: { enabled: 0 }
            });
        }

        // Pill Navigation
        const navLinks = document.querySelectorAll('.nav-link-custom[data-bs-toggle="pill"]');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const target = document.querySelector(this.getAttribute('href'));
                document.querySelectorAll('.tab-pane').forEach(tp => {
                    tp.classList.remove('show', 'active');
                });
                target.classList.add('show', 'active');
            });
        });
    });
</script>
@endsection
