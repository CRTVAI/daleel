@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Platform Settings</h1>
            <p class="text-muted">Configure your platform preferences and notification controls.</p>
        </div>
        <div class="position-relative">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" id="settingsSearch" class="form-control ps-5 border-0 bg-white shadow-sm rounded-4 fw-medium" style="width: 300px;" placeholder="Search settings...">
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Navigation for Settings -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="list-group list-group-flush border-0 p-2" id="settings-tabs" role="tablist">
                    <a class="list-group-item list-group-item-action active border-0 rounded-3 py-3 mb-1" id="general-tab" data-bs-toggle="pill" href="#general-settings" role="tab">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-gear-wide-connected fs-5"></i>
                            <div>
                                <div class="fw-bold">General Settings</div>
                                <div class="small text-muted d-none d-xl-block">Site info and core configs</div>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item list-group-item-action border-0 rounded-3 py-3 mb-1" id="notification-tab" data-bs-toggle="pill" href="#notification-settings" role="tab">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <div>
                                <div class="fw-bold">Notifications</div>
                                <div class="small text-muted d-none d-xl-block">System and user alerts</div>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item list-group-item-action border-0 rounded-3 py-3 mb-1" id="security-tab" data-bs-toggle="pill" href="#security-settings" role="tab">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-shield-lock-fill fs-5"></i>
                            <div>
                                <div class="fw-bold">Security</div>
                                <div class="small text-muted d-none d-xl-block">Access and authentication</div>
                            </div>
                        </div>
                    </a>
                    <a class="list-group-item list-group-item-action border-0 rounded-3 py-3 mb-1" id="api-tab" data-bs-toggle="pill" href="#api-settings" role="tab">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-code-slash fs-5"></i>
                            <div>
                                <div class="fw-bold">Integrations & API</div>
                                <div class="small text-muted d-none d-xl-block">Third-party connections</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mt-4 bg-primary text-white overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-relative z-1">
                        <h6 class="fw-bold opacity-75 mb-3 text-uppercase small letter-spacing-1">Support Plan</h6>
                        <h4 class="fw-800 mb-2">Premium Enterprise</h4>
                        <p class="small opacity-75 mb-4">Your platform is currently on the highest tier with 24/7 priority support.</p>
                        <button class="btn btn-white w-100 rounded-3 fw-bold text-primary">Manage Plan</button>
                    </div>
                    <i class="bi bi-lightning-charge-fill position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-10" style="font-size: 120px;"></i>
                </div>
            </div>
        </div>

        <!-- Settings Panels -->
        <div class="col-lg-9">
            <div class="tab-content" id="settings-tabContent">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general-settings" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 p-4 pb-0">
                            <h5 class="fw-bold mb-1">General Platform Configurations</h5>
                            <p class="text-muted small">Update your basic platform information and behavior.</p>
                        </div>
                        <div class="card-body p-4">
                            <form class="settings-form">
                                <div class="row g-4">
                                    <div class="col-md-6 setting-item" data-search="platform name site title">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Platform Name</label>
                                        <input type="text" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="Daleel AI">
                                    </div>
                                    <div class="col-md-6 setting-item" data-search="support email contact">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Support Email</label>
                                        <input type="email" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="support@Daleel.ai">
                                    </div>
                                    <div class="col-md-12 setting-item" data-search="site description meta tags">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Site Description</label>
                                        <textarea class="form-control rounded-3 border-light bg-light p-3 fw-semibold" rows="3">The leading AI-powered content tracking and analytics dashboard for professional creators.</textarea>
                                    </div>
                                    <div class="col-md-6 setting-item" data-search="maintenance mode offline">
                                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-4 bg-light bg-opacity-50">
                                            <div>
                                                <h6 class="fw-bold mb-1">Maintenance Mode</h6>
                                                <p class="text-muted small mb-0">Disable public access during updates</p>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" style="width: 2.5em; height: 1.25em;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 setting-item" data-search="user registration sign up">
                                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-4 bg-light bg-opacity-50">
                                            <div>
                                                <h6 class="fw-bold mb-1">New User Registration</h6>
                                                <p class="text-muted small mb-0">Allow new users to sign up</p>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" checked style="width: 2.5em; height: 1.25em;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-top d-flex justify-content-end">
                                    <button type="button" class="btn btn-light rounded-3 fw-bold px-4 me-2">Reset Changes</button>
                                    <button type="button" class="btn btn-primary rounded-3 fw-bold px-4">Save General Settings</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="tab-pane fade" id="notification-settings" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 p-4 pb-0">
                            <h5 class="fw-bold mb-1">Notification Preferences</h5>
                            <p class="text-muted small">Control which alerts are sent to administrators and users.</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-5">
                                <h6 class="fw-800 text-primary small text-uppercase mb-4 letter-spacing-1">Administrative Alerts</h6>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center justify-content-between setting-item" data-search="new user signup notification email">
                                        <div>
                                            <div class="fw-bold">New User Signup</div>
                                            <div class="text-muted small">Get notified whenever a new user registers on the platform.</div>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </div>
                                    <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center justify-content-between setting-item" data-search="system error alerts crash">
                                        <div>
                                            <div class="fw-bold">System Error Alerts</div>
                                            <div class="text-muted small">Critical notifications for system-wide failures or bugs.</div>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </div>
                                    <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center justify-content-between setting-item" data-search="payment success billing">
                                        <div>
                                            <div class="fw-bold">Payment Success Notifications</div>
                                            <div class="text-muted small">Daily summary of successful subscription payments.</div>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h6 class="fw-800 text-primary small text-uppercase mb-4 letter-spacing-1">Global User Defaults</h6>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center justify-content-between setting-item" data-search="marketing email newsletters">
                                        <div>
                                            <div class="fw-bold">Marketing & Newsletters</div>
                                            <div class="text-muted small">Enable marketing emails by default for new users.</div>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </div>
                                    <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center justify-content-between setting-item" data-search="progress updates weekly summary">
                                        <div>
                                            <div class="fw-bold">Weekly Progress Summaries</div>
                                            <div class="text-muted small">Default opt-in for users to receive their weekly stats.</div>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-top d-flex justify-content-end">
                                <button type="button" class="btn btn-primary rounded-3 fw-bold px-4">Save Preferences</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="tab-pane fade" id="security-settings" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 p-4 pb-0">
                            <h5 class="fw-bold mb-1">Security & Access Control</h5>
                            <p class="text-muted small">Manage authentication protocols and system security.</p>
                        </div>
                        <div class="card-body p-4 text-center py-5">
                            <div class="mb-4">
                                <div class="bg-light p-4 rounded-circle d-inline-flex mb-3">
                                    <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="fw-bold">Advanced Security Panel</h5>
                                <p class="text-muted mx-auto" style="max-width: 400px;">Modify multi-factor authentication, password policies, and session management settings.</p>
                            </div>
                            <div class="row g-3 justify-content-center">
                                <div class="col-auto">
                                    <button class="btn btn-outline-primary fw-bold rounded-3 px-4">View Login Logs</button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary fw-bold rounded-3 px-4">Configure MFA</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Settings -->
                <div class="tab-pane fade" id="api-settings" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 p-4 pb-0">
                            <h5 class="fw-bold mb-1">API & Integration Keys</h5>
                            <p class="text-muted small">Manage keys for external services and platform API access.</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="bg-light p-4 rounded-4 border border-dashed border-2 mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold mb-0">OpenAI API Key</h6>
                                    <span class="badge bg-success small">Connected</span>
                                </div>
                                <div class="input-group">
                                    <input type="password" class="form-control border-0 bg-white" value="sk-........................48f2" readonly>
                                    <button class="btn btn-white border-0 text-primary fw-bold px-3">Reveal</button>
                                </div>
                            </div>
                            <div class="bg-light p-4 rounded-4 border border-dashed border-2">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold mb-0">YouTube Data API</h6>
                                    <span class="badge bg-warning text-dark small">Check Connection</span>
                                </div>
                                <div class="input-group">
                                    <input type="password" class="form-control border-0 bg-white" value="AIzaSy........................Xz0" readonly>
                                    <button class="btn btn-white border-0 text-primary fw-bold px-3">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<style>
    .letter-spacing-1 { letter-spacing: 1px; }
    .fw-800 { font-weight: 800; }
    .btn-white { background: white; color: var(--accent-color); }
    .btn-white:hover { background: #f8fafc; color: #4f46e5; }
    
    .list-group-item-action.active {
        background: rgba(99, 102, 241, 0.08);
        color: var(--accent-color);
        border-right: 3px solid var(--accent-color) !important;
    }
    
    .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .setting-item.hidden {
        display: none !important;
    }

    .highlight {
        background-color: #fff3cd;
        border-radius: 2px;
    }
</style>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('settingsSearch');
        const settingItems = document.querySelectorAll('.setting-item');
        const tabPanes = document.querySelectorAll('.tab-pane');
        const tabLinks = document.querySelectorAll('[data-bs-toggle="pill"]');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            if (query.length > 0) {
                // Show all panes to search across them
                tabPanes.forEach(pane => {
                    pane.classList.add('show', 'active');
                });
                
                settingItems.forEach(item => {
                    const searchText = item.getAttribute('data-search') + " " + item.innerText.toLowerCase();
                    if (searchText.includes(query)) {
                        item.classList.remove('hidden');
                        // Expand parents if they are hidden
                    } else {
                        item.classList.add('hidden');
                    }
                });
            } else {
                // Restore original tab state
                const activeTabLink = document.querySelector('[data-bs-toggle="pill"].active');
                const targetId = activeTabLink.getAttribute('href').substring(1);
                
                tabPanes.forEach(pane => {
                    if (pane.id === targetId) {
                        pane.classList.add('show', 'active');
                    } else {
                        pane.classList.remove('show', 'active');
                    }
                });

                settingItems.forEach(item => {
                    item.classList.remove('hidden');
                });
            }
        });

        // Reset search when clicking on a tab
        tabLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (searchInput.value.length > 0) {
                    searchInput.value = '';
                    settingItems.forEach(item => {
                        item.classList.remove('hidden');
                    });
                }
            });
        });
    });
</script>
@endsection
