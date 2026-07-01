@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="page-title mb-1">User Management</h2>
            <p class="text-muted mb-0">Manage your system users and their access levels.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus-fill me-2"></i> Add New User
            </button>
        </div>
    </div>

    <!-- Filter Navigation Pills -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-pills gap-2" id="user-filter-tabs">
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link py-2 px-3 rounded-pill fw-bold small {{ !request('account_type') ? 'active bg-dark text-white' : 'text-secondary bg-light' }}">
                    All Users
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index', ['account_type' => 'Pro']) }}" class="nav-link py-2 px-3 rounded-pill fw-bold small {{ request('account_type') === 'Pro' ? 'active bg-primary text-white' : 'text-secondary bg-light' }}">
                    <i class="bi bi-stars text-warning me-1"></i> Pro Users
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index', ['account_type' => 'Free Plan']) }}" class="nav-link py-2 px-3 rounded-pill fw-bold small {{ request('account_type') === 'Free Plan' ? 'active bg-secondary text-white' : 'text-secondary bg-light' }}">
                    Free Users
                </a>
            </li>
        </ul>
        <div class="small text-muted fw-semibold">
            Total: {{ $users->total() }} Users
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 border-0 text-muted small fw-bold py-3">USER NAME</th>
                        <th class="border-0 text-muted small fw-bold py-3">EMAIL ADDRESS</th>
                        <th class="border-0 text-muted small fw-bold py-3">PLAN / TYPE</th>
                        <th class="border-0 text-muted small fw-bold py-3">JOINED DATE</th>
                        <th class="border-0 text-muted small fw-bold py-3">STATUS</th>
                        <th class="border-0 text-muted small fw-bold py-3 text-center">TEAM ACCESS</th>
                        <th class="border-0 text-muted small fw-bold py-3 text-end pe-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $user->name }}</div>
                                        <div class="text-muted" style="font-size: 11px;">UID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                             <td>
                                <span class="text-muted small fw-500">{{ $user->email }}</span>
                            </td>
                            <td>
                                @if($user->account_type === 'Pro' || $user->account_type === 'Pro Plan')
                                    <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill fw-bold small"><i class="bi bi-stars text-warning me-1"></i> Pro Trial</span>
                                @else
                                    <span class="badge bg-secondary text-white px-3 py-1.5 rounded-pill fw-semibold small">Free Plan</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small fw-500">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success-light text-success px-3 py-2 rounded-pill">Active</span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.users.toggle-team', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch d-flex justify-content-center mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               style="width: 2.5em; height: 1.3em; cursor: pointer;"
                                               {{ $user->can_access_team ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               title="{{ $user->can_access_team ? 'Revoke Team Access' : 'Grant Team Access' }}">
                                    </div>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.profile', $user) }}" class="btn btn-sm btn-light rounded-3 text-info">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <button class="btn btn-sm btn-light rounded-3 text-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal{{ $user->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-light rounded-3 text-danger">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                <div class="modal-content border-0 rounded-4 shadow-lg">
                                    <div class="modal-header border-0 bg-white pt-4 px-4 pb-2">
                                        <div>
                                            <h5 class="fw-800 mb-0" style="color: #000;">Edit User Details</h5>
                                            <p class="text-muted small mb-0">Update account information and preferences for {{ $user->name }}.</p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body px-4 py-4">
                                            <div class="row g-4">
                                                <!-- Profile Details -->
                                                <div class="col-12">
                                                    <h6 class="fw-800 small text-uppercase letter-spacing-1 mb-3 text-primary">1. Basic Information</h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">Full Name</label>
                                                    <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">Email Address</label>
                                                    <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}" required>
                                                </div>

                                                <!-- Preferences -->
                                                <div class="col-12 mt-2">
                                                    <h6 class="fw-800 small text-uppercase letter-spacing-1 mb-2 text-primary">2. Learning & Preferences</h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">Account Type</label>
                                                    <select name="account_type" class="form-select rounded-3">
                                                        <option value="Free Plan" {{ $user->account_type == 'Free Plan' ? 'selected' : '' }}>Free Plan</option>
                                                        <option value="Pro" {{ $user->account_type == 'Pro' || $user->account_type == 'Pro Plan' ? 'selected' : '' }}>Pro Plan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">Experience Level</label>
                                                    <select name="experience_level" class="form-select rounded-3">
                                                        @foreach($experienceLevels as $level)
                                                            <option value="{{ $level->title }}" {{ $user->experience_level == $level->title ? 'selected' : '' }}>{{ $level->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-700 text-muted">Primary Goal</label>
                                                    <select name="primary_goal" class="form-select rounded-3">
                                                        @foreach($learningGoals as $goal)
                                                            <option value="{{ $goal->title }}" {{ $user->learning_goal == $goal->title ? 'selected' : '' }}>{{ $goal->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <h6 class="fw-800 small text-uppercase letter-spacing-1 mb-2 text-primary">3. Work & Interests</h6>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-700 text-muted">Work & Tools (Press enter after each)</label>
                                                    <input type="text" name="tools" class="form-control tagify-input rounded-3" value="{{ is_array($user->tools) ? implode(', ', $user->tools) : '' }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-700 text-muted">Interests (Press enter after each)</label>
                                                    <input type="text" name="interests" class="form-control tagify-input rounded-3" value="{{ is_array($user->interests) ? implode(', ', $user->interests) : '' }}">
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <h6 class="fw-800 small text-uppercase letter-spacing-1 mb-2 text-danger">4. Security</h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">New Password (leave blank to keep current)</label>
                                                    <input type="password" name="password" class="form-control rounded-3" placeholder="••••••••">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">Confirm New Password</label>
                                                    <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="••••••••">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-light py-3 px-4">
                                            <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary px-4 rounded-3 fw-800">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted mb-3">No users found in the system.</div>
                                <button class="btn btn-sm btn-outline-primary rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    Create First User
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 bg-white pt-4 px-4 pb-2">
                <div>
                    <h5 class="fw-800 mb-0" style="color: #000;">Add New User</h5>
                    <p class="text-muted small mb-0">Enter the user's details and preferences.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-4">
                    <div class="row g-4">
                        <!-- Profile Details -->
                        <div class="col-12">
                            <h6 class="fw-800 small text-uppercase letter-spacing-1 mb-3 text-primary">Account Information</h6>
                            <p class="text-muted small">Register a new user with basic credentials. They can complete their profile later.</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-700 text-muted">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Ahmed Hassan" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-700 text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="e.g. ahmed@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-700 text-muted">Password</label>
                            <input type="password" name="password" class="form-control rounded-3" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-700 text-muted">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3 px-4">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-800">
                        <i class="bi bi-person-plus-fill me-2"></i> Create User Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        color: #6366f1;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .bg-success-light { background: rgba(34, 197, 94, 0.1); }
    .fw-500 { font-weight: 500; }
    .fw-600 { font-weight: 600; }
    .fw-700 { font-weight: 700; }
    .fw-800 { font-weight: 800; }
    
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
        background: white !important;
    }
    
    .modal-content {
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    
    .tagify {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.25rem 0.5rem;
        --tags-disabled-bg: #F1F1F1;
        --tags-border-color: #DDD;
        --tags-hover-border-color: #CCC;
        --tags-focus-border-color: #6366f1;
        --tag-bg: #f1f5f9;
        --tag-hover: #e2e8f0;
        --tag-text-color: #1e293b;
        --tag-text-color--edit: #1e293b;
        --tag-pad: 0.3em 0.5em;
        --tag-inset-shadow-size: 1.1em;
        --tag-invalid-color: #D39494;
        --tag-invalid-bg: rgba(211, 148, 148, 0.5);
        --tag-remove-bg: rgba(211, 148, 148, 0.3);
        --tag-remove-btn-color: #1e293b;
        --tag-remove-btn-bg--hover: #ff4b4b;
    }
    
    .tagify__tag {
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    /* ── Pagination ───────────────────────────────────────────── */
    .pagination {
        gap: 4px;
        margin: 0;
    }
    .page-item .page-link {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px !important;
        border: 1.5px solid #e5e7eb;
        color: #374151;
        font-weight: 700;
        font-size: 13px;
        background: #fff;
        transition: all 0.2s;
        padding: 0;
        line-height: 1;
        text-decoration: none;
    }
    .page-item .page-link:hover {
        background: #f9fafb;
        border-color: #000;
        color: #000;
        box-shadow: none;
    }
    .page-item.active .page-link {
        background: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #f1f3f5;
        color: #d1d5db;
        pointer-events: none;
    }
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        width: auto;
        padding: 0 14px;
        font-size: 12px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Tagify for all tags inputs
        const tagInputs = document.querySelectorAll('.tagify-input');
        tagInputs.forEach(input => {
            new Tagify(input, {
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
            });
        });
    });
</script>
@endsection
