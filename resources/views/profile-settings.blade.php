@extends('layouts.user')

@section('title', 'Profile Settings — Daleel AI')

@section('content')
<div class="profile-settings-wrapper">
    <div class="row g-4">
        <!-- Settings Sidebar -->
        <div class="col-lg-3">
            <div class="settings-nav card border-0 shadow-sm rounded-4 p-3 mb-4">
                <div class="d-flex align-items-center gap-3 mb-4 px-2">
                    <div class="avatar-box" style="width: 48px; height: 48px; font-size: 18px;">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ auth()->user()->name }}</h6>
                        <p class="text-muted small mb-0">Student Account</p>
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
                    <a href="#connections" class="nav-link-custom" data-bs-toggle="pill">
                        <i class="bi bi-link-45deg"></i> Connected Apps
                    </a>
                    <a href="#bookmarks" class="nav-link-custom" data-bs-toggle="pill">
                        <i class="bi bi-bookmark-star"></i> My Bookmarks
                    </a>
                    <hr class="my-3 border-light">
                    <button class="nav-link-custom text-danger border-0 bg-transparent w-100 text-start" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash"></i> Delete Account
                    </button>
                </nav>
            </div>

            <!-- Stats Mini Widget -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-dark text-white mb-4">
                <h6 class="fw-bold small mb-3 opacity-75">LEARNING STREAK</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-1 fw-bold">{{ auth()->user()->streak_count }}</div>
                    <div class="lh-1">
                        <div class="fw-bold">Days</div>
                        <div class="small opacity-75">
                            @if(auth()->user()->streak_count >= 5) Keep it up! 🔥 @else Start your habit! 🚀 @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Settings Form Area -->
        <div class="col-lg-9">
            @if(session('status') === 'profile-updated')
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>Profile updated successfully!</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('status') === 'welcome-complete-profile')
                <div class="alert border-0 shadow-sm rounded-4 mb-5 p-4 text-center" style="background-color: #fff5f5; border: 1px solid #feb2b2 !important; color: #c53030;">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width: 60px; height: 60px; border: 1px solid #feb2b2;">
                            <i class="bi bi-stars fs-3"></i>
                        </div>
                    </div>
                    <h4 class="fw-800 mb-2" style="letter-spacing: -0.5px;">Welcome to Daleel AI!</h4>
                    <p class="mb-0 mx-auto opacity-90 fw-500" style="max-width: 500px; font-size: 1.05rem; line-height: 1.6;">
                        Your account has been created successfully. Please take a moment to **complete your learning profile** below to help us tailor your experience.
                    </p>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="tab-content">
                    <!-- Account Section -->
                    <div class="tab-pane fade show active" id="account">
                        <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                            <div class="mb-4">
                                <h4 class="fw-800 text-dark mb-1">Account Information</h4>
                                <p class="text-muted small">Update your basic account details and contact information.</p>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label-minimal">Full Name</label>
                                    <input type="text" name="name" class="form-control-minimal" value="{{ auth()->user()->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-minimal">Email Address</label>
                                    <input type="email" name="email" class="form-control-minimal" value="{{ auth()->user()->email }}" required>
                                </div>
                                <div class="col-md-12">
                                    <div class="p-4 bg-light bg-opacity-50 rounded-4 border border-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">Premium Subscription</h6>
                                            <p class="text-muted small mb-0">Your account is currently on the <strong>Free Plan</strong>.</p>
                                        </div>
                                        <a href="#" class="btn btn-dark rounded-3 px-4 fw-bold small">Upgrade to Pro</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Learning Profile Section -->
                    <div class="tab-pane fade" id="learning">
                        <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                            <div class="mb-4">
                                <h4 class="fw-800 text-dark mb-1">Learning Profile</h4>
                                <p class="text-muted small">Help us personalize your content feed based on your goals and expertise.</p>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label-minimal">Primary Learning Goal</label>
                                    <select name="learning_goal" class="form-select-minimal">
                                        @foreach($learningGoals as $goal)
                                            <option value="{{ $goal->title }}" {{ auth()->user()->learning_goal == $goal->title ? 'selected' : '' }}>{{ $goal->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-minimal">Experience Level</label>
                                    <div class="d-flex gap-2">
                                        @foreach($experienceLevels as $level)
                                        <div class="flex-grow-1">
                                            <input type="radio" class="btn-check" name="experience_level" id="edit_level_{{ Str::slug($level->title) }}" value="{{ $level->title }}" {{ auth()->user()->experience_level == $level->title ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary w-100 rounded-3 py-2 small fw-bold" for="edit_level_{{ Str::slug($level->title) }}">{{ $level->title }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-minimal">Interests (Press Enter after each)</label>
                                    <input type="text" name="interests" id="interests-tagify" class="form-control-minimal" value="{{ is_array(auth()->user()->interests) ? implode(',', auth()->user()->interests) : '' }}">
                                    <p class="text-muted mt-2 mb-0" style="font-size: 11px;">Select topics you want to see more of in your dashboard.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bookmarks Section -->
                    <div class="tab-pane fade" id="bookmarks">
                        <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="fw-800 text-dark mb-1">My Bookmarks</h4>
                                    <p class="text-muted small">Your saved learning materials and tutorials.</p>
                                </div>
                                <a href="{{ route('bookmarks') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold">Open Full Manager</a>
                            </div>

                            <div class="bookmark-mini-list d-flex flex-column gap-3">
                                @php
                                    $profile_bookmarks = auth()->user()->bookmarkedContents()->latest()->take(5)->get();
                                @endphp
                                @forelse($profile_bookmarks as $item)
                                    <div class="card border border-light-subtle rounded-4 p-3 bg-white shadow-sm transition-hover">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="flex-shrink-0" style="width: 100px; height: 60px; border-radius: 12px; overflow: hidden;">
                                                <img src="{{ $item->thumbnail_url }}" class="w-100 h-100 object-fit-cover" alt="Thumb">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-800 text-dark mb-1 small">{{ $item->title }}</h6>
                                                <div class="d-flex gap-2">
                                                    <span class="text-muted fw-700" style="font-size: 10px;">{{ $item->category }}</span>
                                                    <span class="text-muted fw-700" style="font-size: 10px;">•</span>
                                                    <span class="text-muted fw-700" style="font-size: 10px;">{{ $item->skill_level }}</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('learn.watch', $item) }}" class="btn btn-light btn-sm rounded-circle"><i class="bi bi-play-fill"></i></a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <p class="text-muted small fw-600">No bookmarks yet.</p>
                                        <a href="{{ route('learn.explore') }}" class="btn btn-link btn-sm text-dark fw-800">Explore Content</a>
                                    </div>
                                @endforelse
                                
                                @if(auth()->user()->bookmarkedContents()->count() > 5)
                                    <div class="text-center mt-2">
                                        <a href="{{ route('bookmarks') }}" class="text-muted small fw-800 text-decoration-none">View all {{ auth()->user()->bookmarkedContents()->count() }} bookmarks <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="tab-pane fade" id="security">
                        <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                            <div class="mb-4">
                                <h4 class="fw-800 text-dark mb-1">Update Password</h4>
                                <p class="text-muted small">Ensure your account is using a long, random password to stay secure.</p>
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

                    <!-- Connections Section -->
                    <div class="tab-pane fade" id="connections">
                        <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                            <div class="mb-4">
                                <h4 class="fw-800 text-dark mb-1">Connected Apps</h4>
                                <p class="text-muted small">Sync your account with daily tools and corporate applications.</p>
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label-minimal">Active Connections</label>
                                    <input type="text" name="connections" id="connections-tagify" class="form-control-minimal" value="{{ is_array(auth()->user()->connections) ? implode(',', auth()->user()->connections) : '' }}">
                                    <p class="text-muted mt-2 mb-0" style="font-size: 11px;">Search and select apps like Gmail, Slack, Excel, etc. to link with your profile.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-2">
                    <button type="reset" class="btn btn-light rounded-3 px-4 fw-bold">Discard Changes</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-5 fw-bold shadow-sm">Save All Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="fw-800 text-danger mb-0">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. This action <strong>cannot be undone</strong>.</p>
                    
                    <div class="p-3 bg-light rounded-3 mb-4 border border-danger-subtle">
                        <p class="small mb-0 text-danger fw-600">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            To confirm, please type your email address (<strong>{{ auth()->user()->email }}</strong>) in the field below:
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-minimal text-dark">Confirm Email Address</label>
                        <input type="text" name="email_confirmation" id="email_confirmation" class="form-control-minimal" required placeholder="{{ auth()->user()->email }}" onkeyup="toggleDeleteButton(this.value)">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <button type="button" class="btn btn-white fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="deleteAccountBtn" class="btn btn-danger px-4 rounded-3 fw-bold" disabled>Delete Account Permanently</button>
                </div>
            </form>
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
    .nav-link-custom.active { background: #000; color: #fff; }

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
        border-color: #000;
        box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        outline: none;
    }

    .btn-outline-primary { border-color: #e2e8f0; color: #64748b; }
    .btn-check:checked + .btn-outline-primary { background-color: #000; border-color: #000; color: #fff; }
    .btn-outline-primary:hover { border-color: #000; color: #000; }

    .tagify {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        --tags-focus-border-color: #000;
        --tag-bg: #fff;
        --tag-text-color: #000;
        padding: 8px;
    }
    .tagify__tag { border-radius: 8px; font-weight: 600; padding: 4px 8px; margin: 4px; border: 1px solid #e2e8f0; }
    .tagify__tag > div { display: flex; align-items: center; gap: 8px; }
    .tagify__tag img { width: 18px; height: 18px; object-fit: contain; }
    
    /* Complete high-fidelity style for Tagify dropdown items */
    .tagify__dropdown {
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        background: #ffffff !important;
        overflow: hidden;
        z-index: 9999 !important;
    }
    .tagify__dropdown__wrapper {
        border: none !important;
        background: #ffffff !important;
    }
    .tagify__dropdown__item {
        color: #1e293b !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        padding: 10px 16px !important;
        background: transparent !important;
        transition: all 0.15s ease !important;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .tagify__dropdown__item img {
        width: 20px;
        height: 20px;
        object-fit: contain;
    }
    .tagify__dropdown__item--active,
    .tagify__dropdown__item:hover {
        background: #000000 !important;
        color: #ffffff !important;
    }
    .tagify__dropdown__item--active *,
    .tagify__dropdown__item:hover * {
        color: #ffffff !important;
    }

    .tagify__tag:hover .tagify__tag-text { color: #000 !important; }
    .tagify__tag--active .tagify__tag-text { color: #000 !important; }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.querySelector('#interests-tagify');
        if(input) {
            new Tagify(input, {
                whitelist: [
                    @foreach($interestsList as $interest)
                        "{{ $interest }}",
                    @endforeach
                ],
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "tags-look",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
        }

var connInput = document.querySelector('#connections-tagify');
if(connInput) {
    const appsList = [
        @foreach($tools as $tool)
            { value: "{{ $tool->name }}", icon: "{{ $tool->logo }}" },
        @endforeach
    ];

    // Safely pre-populate initial value
    const rawValue = connInput.value.trim();
    if (rawValue) {
        const names = rawValue.split(',').map(v => v.trim()).filter(Boolean);
        const mapped = names.map(name => {
            const match = appsList.find(a => a.value === name);
            return match ? match : { value: name };
        });
        connInput.value = JSON.stringify(mapped);
    }

    new Tagify(connInput, {
        whitelist: appsList,
        tagTextProp: 'value',
        enforceWhitelist: true,
        skipInvalid: true,
        maxTags: 20,
        dropdown: {
            closeOnSelect: false,
            enabled: 0,
            classname: 'tags-look',
            searchKeys: ['value'],
            maxItems: 40
        },
        templates: {
            tag(tagData) {
                return `
                    <tag title="${tagData.value}"
                         contenteditable="false"
                         spellcheck="false"
                         tabIndex="-1"
                         class="tagify__tag"
                         value="${tagData.value}">
                        <x title="" class="tagify__tag__removeBtn" role="button" aria-label="remove tag"></x>
                        <div>
                            ${tagData.icon ? `<img src="${tagData.icon}" onerror="this.style.display='none'">` : ''}
                            <span class="tagify__tag-text">${tagData.value}</span>
                        </div>
                    </tag>`;
            },
            dropdownItem(tagData) {
                return `
                    <div class="tagify__dropdown__item ${tagData.class || ''}"
                         tabindex="0"
                         role="option"
                         value="${tagData.value}">
                        ${tagData.icon ? `<img src="${tagData.icon}" onerror="this.style.display='none'">` : ''}
                        <span>${tagData.value}</span>
                    </div>`;
            }
        }
    });
}

        // Pill Navigation Logic
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

    function toggleDeleteButton(value) {
        const userEmail = "{{ auth()->user()->email }}";
        const btn = document.getElementById('deleteAccountBtn');
        if(value === userEmail) {
            btn.removeAttribute('disabled');
        } else {
            btn.setAttribute('disabled', 'true');
        }
    }

    function toggleBookmark(contentId, btn) {
        const url = `/bookmarks/${contentId}/toggle`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'removed') {
                showToast('Bookmark removed', 'info');
                // Optional: remove card from UI
                const card = btn.closest('.card');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 300);
                }
            } else {
                showToast('Bookmark added!', 'success');
            }
        })
        .catch(err => console.error('Bookmark toggle failed', err));
    }
</script>
@endsection
