@extends('layouts.user')

@section('content')
<div class="dashboard-content" style="color: #000;">
    <div class="row g-4">
        <!-- Main Content (Left) -->
        <div class="col-lg-9">
            <div class="mb-4">
                <h1 class="fw-800 h3 mb-1" style="color: #000;">Extension & Integrations</h1>
                <p class="small fw-800" style="color: #000; opacity: 0.8;">Connect your tools and install our extension to personalize your experience.</p>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs border-0 gap-4 mb-5" style="border-bottom: 1px solid #f1f3f5 !important;">
                <li class="nav-item">
                    <a class="tab-link active" href="#">Chrome Extension</a>
                </li>
                <li class="nav-item">
                    <a class="tab-link" href="#">Integrations</a>
                </li>
                <li class="nav-item">
                    <a class="tab-link" href="#">Connected Accounts</a>
                </li>
            </ul>

            <!-- Extension Card -->
            <div class="card border border-light-subtle rounded-4 overflow-hidden mb-5">
                <div class="row g-0">
                    <div class="col-md-6 p-4 p-lg-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="icon-purple shadow-sm">
                                <i class="bi bi-puzzle-fill"></i>
                            </div>
                            <div>
                                <h4 class="fw-800 mb-0" style="color: #000;">Dallel AI Mentor Extension</h4>
                                <span class="text-dark opacity-50 fw-800" style="font-size: 11px;">v1.2.0</span>
                            </div>
                        </div>
                        <p class="fw-800 mb-4" style="font-size: 16px; color: #000;">Your AI learning copilot across the web.</p>
                        
                        <div class="feature-list mb-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span class="small fw-700" style="color: #000;">Save any content with one click</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span class="small fw-700" style="color: #000;">Get AI explanations of any webpage</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span class="small fw-700" style="color: #000;">Discover relevant videos and resources</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span class="small fw-700" style="color: #000;">Track your learning without leaving the page</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-check-circle-fill text-primary"></i>
                                <span class="small fw-700" style="color: #000;">Ask AI questions in context</span>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button class="btn btn-primary px-4 py-2 fw-800 rounded-3 shadow-sm">Install to Chrome</button>
                            <button class="btn btn-outline-dark border-2 px-4 py-2 fw-800 rounded-3">
                                <i class="bi bi-play-fill me-1"></i> How it works
                            </button>
                        </div>
                        <div class="mt-4 pt-2 d-flex align-items-center gap-2 text-dark opacity-75" style="font-size: 11px;">
                            <i class="bi bi-patch-check-fill text-success"></i>
                            <span class="fw-600">Trusted by 4,250+ learners • 4.9 <i class="bi bi-star-fill text-warning"></i> rating</span>
                        </div>
                    </div>
                    <div class="col-md-6 bg-light d-none d-md-flex align-items-center justify-content-center p-5">
                        <!-- Browser Mockup -->
                        <div class="browser-ui shadow-2xl">
                            <div class="browser-top">
                                <div class="dots-row"><span></span><span></span><span></span></div>
                            </div>
                            <div class="browser-inner p-4">
                                <div class="line-sm mb-2 w-75"></div>
                                <div class="line-sm mb-4 w-50"></div>
                                <div class="popup-box p-3 shadow-xl rounded-4">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="logo-mini"></div>
                                            <span class="fw-800" style="font-size: 11px; color: #000;">Dallel AI Mentor</span>
                                        </div>
                                        <i class="bi bi-three-dots text-dark" style="font-size: 11px;"></i>
                                    </div>
                                    <div class="d-flex gap-3 mb-3 border-bottom pb-2">
                                        <span class="mini-tab active">Save</span>
                                        <span class="mini-tab">Ask AI</span>
                                        <span class="mini-tab">Discover</span>
                                    </div>
                                    <div class="mb-4 pt-1">
                                        <div class="fw-800 mb-1" style="font-size: 11px; color: #000;">Save this page</div>
                                        <div class="text-dark opacity-75 mb-3" style="font-size: 10px;">Add this page to your learning library</div>
                                        <button class="btn btn-primary w-100 py-2 fw-800 shadow-sm" style="font-size: 11px;">Save Page</button>
                                    </div>
                                    <div class="mb-2">
                                        <div class="fw-800 mb-1" style="font-size: 11px; color: #000;">Add to list (optional)</div>
                                        <div class="border rounded-2 p-2 text-dark opacity-50 d-flex justify-content-between align-items-center" style="font-size: 10px;">
                                            Select a list <i class="bi bi-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Integrations Grid -->
            <div class="mb-4">
                <h5 class="fw-800 mb-1" style="color: #000;">Connect Your Tools</h5>
                <p class="text-dark opacity-75 small">Connect the tools you use daily to get better recommendations and save time.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-3">
                @forelse($tools as $tool)
                    @php
                        $isConnected = is_array(auth()->user()->connections) && in_array($tool->name, auth()->user()->connections);
                    @endphp
                    <div class="col-md-3">
                        <div class="card border border-light-subtle rounded-4 p-4 h-100 shadow-sm-hover d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="bg-light p-2 rounded-3 border border-light-subtle d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <img src="{{ $tool->logo }}" alt="{{ $tool->name }}" class="img-fluid rounded-2" style="max-height: 32px; object-fit: contain;">
                                </div>
                                <span class="badge rounded-pill {{ $isConnected ? 'bg-success-subtle text-success' : 'bg-light text-muted border' }} px-3 py-2" style="font-size: 10px; font-weight: 800;">
                                    {{ $isConnected ? 'Connected' : 'Disconnected' }}
                                </span>
                            </div>
                            <h6 class="fw-800 mb-1" style="font-size: 15px; color: #000;">{{ $tool->name }}</h6>
                            <p class="text-dark opacity-75 mb-4 flex-grow-1" style="font-size: 11px; line-height: 1.5;">
                                {{ $tool->description ?? 'Integrate ' . $tool->name . ' to sync your activity.' }}
                            </p>
                            <div class="mt-auto pt-2">
                                <form action="{{ route('profile.connections.toggle') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tool_name" value="{{ $tool->name }}">
                                    @if($isConnected)
                                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-800" style="font-size: 11px; border-radius: 10px; border-width: 2px;">Disconnect</button>
                                    @else
                                        <button type="submit" class="btn btn-outline-dark border-2 text-dark w-100 py-2 fw-800" style="font-size: 11px; border-radius: 10px;">Connect</button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted small fw-600">No dynamic tools added by the administrator yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Widgets (Right) -->
        <div class="col-lg-3">
            <div class="card border border-light-subtle rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-800 mb-0" style="color: #000;">Extension Status</h6>
                    <span class="badge rounded-pill bg-success-subtle text-success px-2 py-1" style="font-size: 9px; font-weight: 800;"><i class="bi bi-check-circle-fill me-1"></i> Installed</span>
                </div>
                <div class="d-flex align-items-start gap-3 mb-4">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <div>
                        <p class="small fw-800 mb-0" style="color: #000;">Dallel AI Mentor is connected</p>
                        <p class="text-dark opacity-50" style="font-size: 11px;">Active: 2 mins ago</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-dark border-2 text-dark flex-grow-1 fw-800 small py-2 rounded-3">Open Extension</button>
                    <button class="btn btn-outline-dark border-2 text-dark px-3 py-2 rounded-3"><i class="bi bi-three-dots"></i></button>
                </div>
            </div>

            <div class="card border border-light-subtle rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-800 mb-0" style="color: #000;">Usage Analytics</h6>
                    <span class="text-dark opacity-50 fw-700" style="font-size: 10px;">Last 30 days</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-700" style="color: #000;">Pages saved</span>
                    <div class="text-end">
                        <div class="fw-800 small" style="color: #000;">58</div>
                        <div class="text-success fw-700" style="font-size: 9px;"><i class="bi bi-arrow-up"></i> 28%</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-700" style="color: #000;">AI explanations</span>
                    <div class="text-end">
                        <div class="fw-800 small" style="color: #000;">32</div>
                        <div class="text-success fw-700" style="font-size: 9px;"><i class="bi bi-arrow-up"></i> 18%</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="small fw-700" style="color: #000;">Time saved</span>
                    <div class="text-end">
                        <div class="fw-800 small" style="color: #000;">6h 42m</div>
                        <div class="text-success fw-700" style="font-size: 9px;"><i class="bi bi-arrow-up"></i> 22%</div>
                    </div>
                </div>
                <a href="#" class="text-primary text-decoration-none fw-800 small d-block text-center">View full analytics →</a>
            </div>

            <div class="card border border-light-subtle rounded-4 p-4">
                <h6 class="fw-800 mb-4" style="color: #000;">Need Help?</h6>
                <a href="#" class="d-flex align-items-center justify-content-between text-decoration-none text-dark mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-bug text-dark opacity-50"></i>
                        <span class="small fw-800">Troubleshooting</span>
                    </div>
                    <i class="bi bi-box-arrow-up-right small text-dark opacity-25"></i>
                </a>
                <a href="#" class="d-flex align-items-center justify-content-between text-decoration-none text-dark">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-headset text-dark opacity-50"></i>
                        <span class="small fw-800">Contact Support</span>
                    </div>
                    <i class="bi bi-box-arrow-up-right small text-dark opacity-25"></i>
                </a>
            </div>
            
            <!-- Privacy Banner -->
            <div class="card bg-light border-0 rounded-4 p-4 mt-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white rounded-3 p-2 border shadow-sm"><i class="bi bi-shield-lock-fill text-primary fs-4"></i></div>
                        <div>
                            <h6 class="fw-800 mb-0" style="color: #000;">Your data is private and secure</h6>
                            <p class="text-dark opacity-75 small mb-0">Connections are encrypted and you can disconnect anytime.</p>
                        </div>
                    </div>
                </div>
                <button class="btn btn-dark rounded-3 px-4 py-2 fw-800 small shadow-sm mt-4">Privacy Settings</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }

    .tab-link {
        color: #4b5563;
        text-decoration: none;
        font-weight: 800;
        font-size: 14px;
        padding-bottom: 15px;
        display: block;
        transition: all 0.2s;
        border: none;
        background: none;
    }

    .tab-link.active {
        color: #000;
        border-bottom: 3px solid var(--primary);
    }

    .icon-purple {
        width: 44px;
        height: 44px;
        background: var(--primary);
        color: #fff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    /* Browser UI */
    .browser-ui {
        width: 100%;
        max-width: 320px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .browser-top {
        background: #f1f3f5;
        padding: 10px 14px;
        border-bottom: 1px solid var(--border-color);
    }

    .dots-row { display: flex; gap: 6px; }
    .dots-row span { width: 8px; height: 8px; border-radius: 50%; background: #dee2e6; }
    
    .line-sm { height: 8px; background: #f1f3f5; border-radius: 4px; }

    .popup-box { background: #fff; border: 1.5px solid var(--border-color); position: relative; }
    .logo-mini { width: 14px; height: 14px; background: var(--primary); border-radius: 3px; }
    .mini-tab { font-size: 10px; font-weight: 800; color: #9ca3af; cursor: pointer; }
    .mini-tab.active { color: var(--primary); border-bottom: 2px solid var(--primary); }

    /* Tool Icons */
    .tool-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .shadow-sm-hover { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .shadow-sm-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px -10px rgba(0,0,0,0.1) !important;
        border-color: var(--primary) !important;
    }

    .btn-primary { background: var(--primary); border: none; }
    .btn-primary:hover { background: #3730a3; transform: translateY(-1px); }

    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
    .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
</style>
@endsection
