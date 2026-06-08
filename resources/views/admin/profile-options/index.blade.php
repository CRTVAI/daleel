@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Profile Dropdowns & Parameters</h1>
            <p class="text-muted">Manage the learning goal and technical experience options displayed during user onboarding and profile editing.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Whoops! Please fix the errors below:</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="fw-bold small"><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Primary Learning Goals Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-1 text-dark">Primary Learning Goals</h5>
                    <p class="text-muted small">Add or delete the custom learning objective items available for student registration.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile-options.learning-goals.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="title" class="form-control rounded-start-3 border-light bg-light py-2.5 px-3 fw-semibold text-dark" placeholder="e.g. Pivot my career" required>
                            <button type="submit" class="btn btn-primary px-4 rounded-end-3 fw-bold"><i class="bi bi-plus-lg"></i> Add</button>
                        </div>
                    </form>

                    <div class="list-group list-group-flush border-top border-light-subtle pt-2" style="max-height: 400px; overflow-y: auto;">
                        @forelse($learningGoals as $goal)
                            <div class="list-group-item border-0 px-0 py-3.5 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-1.5 d-inline-flex">
                                        <i class="bi bi-mortarboard-fill small"></i>
                                    </div>
                                    <span class="fw-bold text-dark small">{{ $goal->title }}</span>
                                </div>
                                <form action="{{ route('admin.profile-options.learning-goals.destroy', $goal) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this goal?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-3 p-2 border-0"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-mortarboard fs-3 mb-2 d-block"></i>
                                <p class="small mb-0">No goals defined yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Experience Levels Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-1 text-dark">Technical Experience Levels</h5>
                    <p class="text-muted small">Add or delete the technical expertise tiers used across user profiles and content filter rules.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile-options.experience-levels.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="title" class="form-control rounded-start-3 border-light bg-light py-2.5 px-3 fw-semibold text-dark" placeholder="e.g. Masterclass Expert" required>
                            <button type="submit" class="btn btn-primary px-4 rounded-end-3 fw-bold"><i class="bi bi-plus-lg"></i> Add</button>
                        </div>
                    </form>

                    <div class="list-group list-group-flush border-top border-light-subtle pt-2" style="max-height: 400px; overflow-y: auto;">
                        @forelse($experienceLevels as $level)
                            <div class="list-group-item border-0 px-0 py-3.5 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success-subtle text-success rounded-circle p-1.5 d-inline-flex">
                                        <i class="bi bi-shield-shaded small"></i>
                                    </div>
                                    <span class="fw-bold text-dark small">{{ $level->title }}</span>
                                </div>
                                <form action="{{ route('admin.profile-options.experience-levels.destroy', $level) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this level?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-3 p-2 border-0"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-shield fs-3 mb-2 d-block"></i>
                                <p class="small mb-0">No experience levels defined yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
