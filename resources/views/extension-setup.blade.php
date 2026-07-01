@extends('layouts.user')

@section('title', 'AI Extension Setup — Daleel AI')

@section('styles')
<style>
    .setup-hero {
        background: linear-gradient(135deg, #020617 0%, #1e1b4b 100%);
        border-radius: 32px;
        padding: 80px 60px;
        color: #fff;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .setup-hero::after {
        content: '';
        position: absolute;
        top: -20%;
        right: -10%;
        width: 50%;
        height: 140%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
        transform: rotate(-15deg);
    }
    .step-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 40px;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .step-card:hover {
        transform: translateY(-8px);
        border-color: #6366f1;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }
    .step-number {
        width: 44px;
        height: 44px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 18px;
        margin-bottom: 24px;
    }
    .step-card:hover .step-number {
        background: #6366f1;
        color: #fff;
    }
    .feature-badge {
        padding: 6px 14px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 10px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .install-btn-large {
        background: #fff;
        color: #020617;
        border: none;
        padding: 18px 40px;
        border-radius: 18px;
        font-weight: 800;
        font-size: 16px;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }
    .install-btn-large:hover {
        background: #f8fafc;
        transform: scale(1.02);
    }
    .connection-pill {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        padding: 12px 20px;
        border-radius: 14px;
        font-family: monospace;
        font-weight: 700;
        color: #475569;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-width: 280px;
    }
    .copy-btn {
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s;
    }
    .copy-btn:hover { background: #f1f5f9; }

    .btn-generate {
        background: #6366f1;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 800;
        transition: 0.2s;
    }
    .btn-generate:hover {
        background: #4f46e5;
        transform: scale(1.02);
    }
    .timer-badge {
        font-size: 11px;
        font-weight: 800;
        color: #ef4444;
        display: none;
    }
    .device-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        transition: 0.3s;
    }
    .device-card:hover {
        border-color: #6366f1;
    }
    /* Data Viewer styles */
    /* Data Viewer styles - Redesigned & Professional */
    .ev-section { border-top: 1px solid rgba(0,0,0,0.06); margin-top: 4rem; padding-top: 4rem; }
    .ev-tabs { display:flex;gap:1.5rem;margin-bottom:2rem;border-bottom:1px solid rgba(0,0,0,0.06);padding-bottom:0; overflow-x:auto; }
    .ev-tab { padding:0 0 1rem 0;font-size:0.9rem;font-weight:700;color:#64748b;cursor:pointer;border:none;background:none;border-bottom:2px solid transparent;margin-bottom:-1px;transition:all .2s ease;white-space:nowrap; }
    .ev-tab.active { color:#4f46e5;border-bottom-color:#4f46e5; }
    .ev-tab:hover:not(.active) { color:#0f172a; border-bottom-color:#cbd5e1; }
    .ev-tab-badge { background:rgba(79,70,229,0.1);color:#4f46e5;border-radius:20px;font-size:0.7rem;padding:2px 8px;margin-left:8px;font-weight:800; }
    .ev-panel { display:none; animation: fadeIn 0.3s ease; } .ev-panel.active { display:block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    
    .ev-stat-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-bottom:2rem; }
    .ev-stat { background:#fff;border:1px solid rgba(0,0,0,0.04);border-radius:20px;padding:1.5rem;box-shadow:0 10px 30px rgba(0,0,0,0.02); transition:transform 0.3s ease; }
    .ev-stat:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(0,0,0,0.04); }
    .ev-stat .val { font-size:2rem;font-weight:800;color:#0f172a;line-height:1;margin-bottom:0.5rem; letter-spacing:-0.03em; }
    .ev-stat .lbl { font-size:0.8rem;font-weight:700;color:#64748b; }
    .ev-stat .hint { font-size:0.75rem;color:#94a3b8;margin-top:0.4rem; }
    
    .ev-box { background:#fff;border:1px solid rgba(0,0,0,0.04);border-radius:24px;overflow:hidden;margin-bottom:1.5rem;box-shadow:0 10px 30px rgba(0,0,0,0.02); }
    .ev-box-head { padding:1.5rem 1.75rem;border-bottom:1px solid rgba(0,0,0,0.04);background:rgba(248,250,252,0.5);display:flex;justify-content:space-between;align-items:center; }
    .ev-box-head h3 { font-size:1.1rem;font-weight:800;color:#0f172a;margin:0;letter-spacing:-0.02em; } 
    .ev-box-head p { font-size:0.85rem;color:#64748b;margin:0.25rem 0 0; }
    .ev-box-body { padding:1.75rem; }
    
    .score-row { display:flex;align-items:center;gap:1rem;margin-bottom:1rem; }
    .score-lbl { font-size:0.85rem;font-weight:700;color:#334155;width:130px;flex-shrink:0; }
    .score-track { flex:1;background:#f1f5f9;border-radius:99px;height:12px;overflow:hidden; }
    .score-fill { height:100%;border-radius:99px;transition:width 1s cubic-bezier(0.4, 0, 0.2, 1); }
    .score-num { font-size:0.9rem;font-weight:800;width:40px;text-align:right; }
    
    .d-row { display:flex;align-items:center;gap:1rem;padding:1rem 0;border-bottom:1px solid rgba(0,0,0,0.04); transition:background 0.2s ease; border-radius:12px; } 
    .d-row:hover { background: rgba(248,250,252,0.8); padding-left: 0.5rem; padding-right: 0.5rem; margin-left: -0.5rem; margin-right: -0.5rem;}
    .d-row:last-child{border:none;}
    .d-icon { width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0; }
    .d-name { font-weight:800;font-size:0.95rem;color:#0f172a; } .d-sub { font-size:0.75rem;color:#64748b;margin-top:0.15rem; }
    .d-track { flex:1;background:#f1f5f9;border-radius:99px;height:8px;overflow:hidden; margin:0 1rem; }
    .d-fill { height:100%;border-radius:99px;background:linear-gradient(90deg,#4f46e5,#818cf8); }
    .d-time { font-weight:800;font-size:0.85rem;color:#4f46e5;width:55px;text-align:right;flex-shrink:0; }
    
    .pill { display:inline-block;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;letter-spacing:0.02em; }
    .pill-blue{background:#eff6ff;color:#2563eb;} .pill-green{background:#f0fdf4;color:#16a34a;} .pill-amber{background:#fffbeb;color:#d97706;} .pill-purple{background:#f5f3ff;color:#7c3aed;} .pill-gray{background:#f8fafc;color:#475569;border:1px solid #e2e8f0;}
    
    .rec-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.25rem; }
    .rec-card { border:1px solid rgba(0,0,0,0.05);border-radius:20px;overflow:hidden;transition:all .3s ease;background:#fff; } 
    .rec-card:hover{transform:translateY(-4px);box-shadow:0 15px 35px rgba(0,0,0,0.06);border-color:#6366f1;}
    .rec-img { width:100%;height:140px;object-fit:cover;display:block; }
    .rec-body { padding:1.25rem; } 
    .rec-title { font-size:0.95rem;font-weight:800;color:#0f172a;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:0.5rem; }
    .rec-meta { font-size:0.75rem;color:#64748b;margin-bottom:0.75rem; } 
    .rec-link { font-size:0.85rem;font-weight:800;color:#4f46e5;text-decoration:none;display:inline-flex;align-items:center;gap:0.3rem; }
    .rec-link:hover { color:#4338ca; }
    
    .sess-row { padding:1.25rem 1.75rem;border-bottom:1px solid rgba(0,0,0,0.03); } .sess-row:last-child{border:none;}
    .sess-domain { font-weight:800;font-size:1rem;color:#0f172a; } .sess-time { font-size:0.8rem;color:#64748b;margin:0.25rem 0 0; }
    
    .roll-grid { display:grid;grid-template-columns:100px 1fr 100px 1fr;gap:1rem;align-items:center;padding:1.25rem 1.75rem;border-bottom:1px solid rgba(0,0,0,0.03);font-size:0.9rem; } .roll-grid:last-child{border:none;}
    .roll-hdr { background:rgba(248,250,252,0.5);font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;padding:0.75rem 1.75rem;border-bottom:1px solid rgba(0,0,0,0.05); }
    
    .snap-row { padding:1.25rem 1.75rem;border-bottom:1px solid rgba(0,0,0,0.03); } .snap-row:last-child{border:none;}
    .snap-time { font-size:0.9rem;font-weight:800;color:#0f172a;margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem; }
    .snap-pills { display:flex;gap:0.5rem;flex-wrap:wrap; }
    .snap-pill { padding:0.35rem 0.85rem;border-radius:12px;font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:0.3rem; }
    
    .ev-empty { text-align:center;padding:4rem 2rem;color:#94a3b8; } .ev-empty i { font-size:3.5rem;color:#cbd5e1;display:block;margin-bottom:1rem; }
    .ev-empty p { font-size:1rem;font-weight:600;margin:0; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    @if($devices->isEmpty())
        <div class="alert d-flex align-items-center gap-3 mb-4 rounded-4 shadow-sm border-0" style="background: rgba(220, 38, 38, 0.05); border: 1px solid rgba(220, 38, 38, 0.15) !important; color: #b91c1c; padding: 1.25rem 1.75rem;">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
            <div>
                <strong class="fw-800" style="font-size: 1.1rem; display: block; margin-bottom: 0.2rem;">Extension Connection Required</strong>
                <span class="fw-600">Please connect your extension first before continuing. Follow the setup instructions below to generate a connection key and link your device.</span>
            </div>
        </div>
    @endif
    <!-- Hero Section -->
    <div class="setup-hero animate-slide-up">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex gap-2 mb-4">
                    <span class="feature-badge">v1.2.0 Stable</span>
                    <span class="feature-badge">Official Extension</span>
                </div>
                <h1 class="display-4 fw-800 mb-3" style="letter-spacing: -0.05em;">Take AI Intelligence Anywhere with Our Chrome Extension</h1>
                <p class="fs-5 opacity-75 mb-5 fw-500" style="max-width: 600px; line-height: 1.6;">
                    Analyze web content, extract insights, and connect your workflow directly to the Daleel AI ecosystem. Our custom-built extension brings the power of your AI Mentor to every tab you visit.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#" class="install-btn-large">
                        <i class="bi bi-download"></i> Download Extension Bundle
                    </a>
                    <a href="https://chrome.google.com/webstore" target="_blank" class="btn btn-outline-light border-2 rounded-4 px-4 fw-800 d-flex align-items-center" style="border-radius: 18px !important;">
                        View on Web Store
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <div class="position-relative d-inline-block">
                    <div class="bg-primary rounded-circle blur-3xl opacity-20 position-absolute w-100 h-100 top-0 start-0"></div>
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop" class="img-fluid rounded-5 shadow-2xl position-relative z-1" style="max-height: 400px; border: 8px solid rgba(255,255,255,0.1);">
                </div>
            </div>
        </div>
    </div>

    {{-- ========== ACTIVITY DATA VIEWER ========== --}}
    @php
        $ms = function($v) {
            $v = max(0, intval($v));
            if ($v < 60000) return round($v/1000).'s';
            $m = floor($v/60000);
            if ($m < 60) return $m.'m';
            return floor($m/60).'h '.($m%60).'m';
        };
        $pct = fn($v) => min(100, max(0, intval($v)));
        $totalActiveMs   = $sessions->sum('active_ms');
        $totalClicks     = $sessions->sum('click_count');
        $aiSessions      = $sessions->where('is_ai_tool', true)->count();
        $avgFocus        = round($snapshots->avg('focus_score') ?? 0);
        $avgProductivity = round($snapshots->avg('productivity_score') ?? 0);
        $avgAI           = round($snapshots->avg('ai_adoption_score') ?? 0);
        $topDomains      = $sessions
            ->groupBy(fn($s) => $s->platform_domain ?: 'Unknown')
            ->map(fn($g,$d) => ['domain'=>$d,'active_ms'=>$g->sum('active_ms'),'count'=>$g->count(),'ai'=>$g->where('is_ai_tool',true)->count()])
            ->sortByDesc('active_ms')->take(6)->values();
        $maxMs           = $topDomains->max('active_ms') ?: 1;
        $hasData         = $sessions->isNotEmpty() || $snapshots->isNotEmpty() || $rollups->isNotEmpty() || $recommendations->isNotEmpty();
        $uniqueDomainCount = $domainGroups->count();
        $uniqueRecCount    = $uniqueRecommendedCount ?? $recommendations->unique('content_id')->count();
    @endphp

    @if($hasData)
    <div class="ev-section" style="margin-top: 2rem; padding-top: 0; border-top: none; margin-bottom: 3rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-800 text-dark mb-2" style="font-size: 1.8rem; letter-spacing:-0.03em;">Activity Data Viewer</h3>
                <p class="text-muted fw-600 mb-0">Insights gathered securely from your Daleel AI browser extension.</p>
            </div>
            <form method="POST" action="{{ route('extension.data.reset') }}" onsubmit="return confirm('Are you sure you want to permanently wipe all your tracked extension data? This cannot be undone.');">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-700">
                    <i class="bi bi-trash3-fill me-1"></i> Reset All Data
                </button>
            </form>
        </div>

        {{-- Free Plan limitation notice --}}
        @if($isFreePlan ?? false)
        <div class="alert d-flex align-items-center gap-3 mb-4" style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 16px; padding: 1rem 1.5rem;">
            <span style="font-size: 1.4rem;">⚠️</span>
            <div>
                <strong style="color: #92400e;">Free Plan — Showing last {{ $historyLimitDays ?? 7 }} days only.</strong>
                <span style="color: #78350f; font-size: 0.88rem; margin-left: 0.3rem;">Upgrade to Pro to unlock your full history.</span>
            </div>
            <a href="/pricing" class="btn btn-sm ms-auto fw-700 rounded-pill px-3" style="background:#f59e0b;color:#fff;white-space:nowrap;">Upgrade →</a>
        </div>
        @endif

        {{-- Tab Nav --}}
        <div class="ev-tabs mb-0">
            <button class="ev-tab active" onclick="showEVTab('ev-overview',this)">📈 Overview</button>
            <button class="ev-tab" onclick="showEVTab('ev-recs',this)">⭐ Recommendations <span class="ev-tab-badge">{{ $uniqueRecCount }}</span></button>
            <button class="ev-tab" onclick="showEVTab('ev-help',this)">🤝 Help Requests <span class="ev-tab-badge">{{ $helpRequests->count() }}</span></button>
            <button class="ev-tab" onclick="showEVTab('ev-sessions',this)">🌐 Sites Visited <span class="ev-tab-badge">{{ $uniqueDomainCount }}</span></button>
            <button class="ev-tab" onclick="showEVTab('ev-daily',this)">📅 Daily Report <span class="ev-tab-badge">{{ $rollups->count() }}</span></button>
            <button class="ev-tab" onclick="showEVTab('ev-snapshots',this)">📷 Snapshots <span class="ev-tab-badge">{{ $snapshots->count() }}</span></button>
        </div>

        {{-- Overview --}}
        <div id="ev-overview" class="ev-panel active">
            <div class="ev-stat-grid">
                <div class="ev-stat">
                    <div class="val">{{ $ms($todayActiveMs ?? 0) }}</div>
                    <div class="lbl">Today's Active Time</div>
                    <div class="hint">All-time: {{ $ms($totalActiveMs) }}</div>
                </div>
                <div class="ev-stat">
                    <div class="val">{{ $uniqueDomainCount }}</div>
                    <div class="lbl">Sites Visited</div>
                    <div class="hint">{{ $sessions->count() }} total sessions</div>
                </div>
                <div class="ev-stat">
                    <div class="val">{{ number_format($totalClicks) }}</div>
                    <div class="lbl">Clicks</div>
                    <div class="hint">Total mouse interactions</div>
                </div>
                <div class="ev-stat">
                    <div class="val">{{ $aiSessions }}</div>
                    <div class="lbl">AI Tool Sessions</div>
                    <div class="hint">ChatGPT, Gemini, Claude…</div>
                </div>
                <div class="ev-stat">
                    <div class="val">{{ $uniqueRecCount }}</div>
                    <div class="lbl">Videos Suggested</div>
                    <div class="hint">{{ $recommendations->count() }} total triggers</div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-lg-5">
                    <div class="ev-box h-100">
                        <div class="ev-box-head"><div><h3>🎯 Productivity Scores</h3><p>Averages across all sessions — 0 to 100</p></div></div>
                        <div class="ev-box-body">
                            <div class="score-row"><div class="score-lbl">🎯 Focus</div><div class="score-track"><div class="score-fill" style="width:{{ $pct($avgFocus) }}%;background:linear-gradient(90deg,#4f46e5,#818cf8)"></div></div><div class="score-num" style="color:#4f46e5">{{ $avgFocus }}</div></div>
                            <div class="score-row"><div class="score-lbl">⚡ Productivity</div><div class="score-track"><div class="score-fill" style="width:{{ $pct($avgProductivity) }}%;background:linear-gradient(90deg,#10b981,#6ee7b7)"></div></div><div class="score-num" style="color:#10b981">{{ $avgProductivity }}</div></div>
                            <div class="score-row"><div class="score-lbl">🤖 AI Adoption</div><div class="score-track"><div class="score-fill" style="width:{{ $pct($avgAI) }}%;background:linear-gradient(90deg,#f59e0b,#fde68a)"></div></div><div class="score-num" style="color:#f59e0b">{{ $avgAI }}</div></div>
                            
                            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px dashed rgba(0,0,0,0.06);">
                                <h4 style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Quick Insights</h4>
                                
                                <div style="display:flex; align-items:flex-start; gap: 1rem; margin-bottom: 1.25rem;">
                                    <div style="background:{{ $avgFocus >= 50 ? '#f0fdf4' : '#fffbeb' }}; color:{{ $avgFocus >= 50 ? '#16a34a' : '#d97706' }}; width: 38px; height: 38px; border-radius: 10px; display:flex; align-items:center; justify-content:center; font-size: 1.2rem; flex-shrink: 0;"><i class="bi bi-bullseye"></i></div>
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.15rem;">{{ $avgFocus >= 50 ? 'Strong Focus' : 'Fragmented Focus' }}</div>
                                        <div style="font-size: 0.8rem; color: #64748b; line-height: 1.4;">Your average focus score is {{ $avgFocus }}, meaning you spend {{ $avgFocus >= 50 ? 'solid continuous blocks on single tasks.' : 'shorter bursts on varying tasks.' }}</div>
                                    </div>
                                </div>
                                
                                <div style="display:flex; align-items:flex-start; gap: 1rem;">
                                    <div style="background:#eff6ff; color:#2563eb; width: 38px; height: 38px; border-radius: 10px; display:flex; align-items:center; justify-content:center; font-size: 1.2rem; flex-shrink: 0;"><i class="bi bi-robot"></i></div>
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.15rem;">AI Utilization</div>
                                        <div style="font-size: 0.8rem; color: #64748b; line-height: 1.4;">You have actively engaged with AI tools across <strong>{{ $aiSessions }}</strong> distinct browsing sessions.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ev-box h-100">
                        <div class="ev-box-head"><div><h3>🌐 Most Visited Websites</h3><p>Sites where you spent the most active time</p></div></div>
                        <div class="ev-box-body">
                            @forelse($topDomains as $d)
                            <div class="d-row">
                                <div class="d-icon" style="background:{{ $d['ai'] ? '#eff6ff' : '#f3f4f6' }}">{{ $d['ai'] ? '🤖' : '🌐' }}</div>
                                <div style="min-width:130px"><div class="d-name">{{ $d['domain'] }}</div><div class="d-sub">{{ $d['count'] }} sessions{{ $d['ai'] ? ' · AI Tool' : '' }}</div></div>
                                <div class="d-track"><div class="d-fill" style="width:{{ round(($d['active_ms']/$maxMs)*100) }}%"></div></div>
                                <div class="d-time">{{ $ms($d['active_ms']) }}</div>
                            </div>
                            @empty
                            <p class="text-muted text-center py-3 mb-0">No website data yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recommendations --}}
        <div id="ev-recs" class="ev-panel">
            <div class="ev-box">
                <div class="ev-box-head"><div><h3>⭐ AI-Recommended Videos</h3><p>Videos suggested based on which websites you actively used</p></div><span class="pill pill-purple">{{ $uniqueRecCount }} unique</span></div>
                <div class="ev-box-body">
                    @if($recommendations->isEmpty())
                        <div class="ev-empty"><i class="bi bi-lightbulb"></i><p>Browse any supported site for 5+ minutes and a tutorial will appear here.</p></div>
                    @else
                    <div class="rec-grid">
                        @foreach($recommendations as $rec)
                        @if($rec->content)
                        <div class="rec-card">
                            <div style="position:relative">
                                <img class="rec-img" src="{{ $rec->content->thumbnail ?: 'https://img.youtube.com/vi/'.$rec->content->youtube_id.'/hqdefault.jpg' }}" alt="">
                                <div style="position:absolute;top:.4rem;left:.4rem"><span class="pill pill-blue" style="font-size:.65rem">🌐 {{ $rec->current_context['domain'] ?? '—' }}</span></div>
                            </div>
                            <div class="rec-body">
                                <div class="rec-title">{{ $rec->content->title }}</div>
                                <div class="rec-meta">{{ $rec->created_at->diffForHumans() }}</div>
                                <div style="display:flex;gap:.3rem;flex-wrap:wrap;margin-bottom:.5rem">
                                    @foreach($rec->events->take(2) as $ev)
                                        <span class="pill {{ $ev->event_type==='clicked'?'pill-green':($ev->event_type==='dismissed'?'pill-amber':'pill-blue') }}">{{ $ev->event_type==='shown'?'👁 Shown':($ev->event_type==='clicked'?'✅ Clicked':'❌ Dismissed') }}</span>
                                    @endforeach
                                </div>
                                <a href="{{ route('learn.watch', $rec->content) }}" class="rec-link">▶ Watch Video</a>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Help Requests --}}
        <div id="ev-help" class="ev-panel">
            <div class="ev-box">
                <div class="ev-box-head"><div><h3>🤝 Mentor Help Requests</h3><p>Questions and topics you've asked your AI Mentor for help with.</p></div><span class="pill pill-purple">{{ $helpRequests->count() }} total</span></div>
                <div class="ev-box-body">
                    @if($helpRequests->isEmpty())
                        <div class="ev-empty"><i class="bi bi-chat-dots"></i><p>No help requests yet. Right-click any page and select "Ask help to mentor" to start.</p></div>
                    @else
                        @foreach($helpRequests as $req)
                        <div class="snap-row" style="{{ auth()->id() !== $req->user_id ? 'border-left: 4px solid #7c3aed;' : '' }}">
                            <div class="snap-time">
                                <i class="bi bi-question-circle me-2 text-primary"></i> 
                                <strong>{{ $req->user->name }}:</strong> "{{ $req->query }}"
                            </div>
                            <div class="snap-pills">
                                <span class="snap-pill" style="background:#f0f9ff;color:#0369a1"><i class="bi bi-clock me-1"></i> {{ $req->created_at->diffForHumans() }}</span>
                                <span class="snap-pill" style="background:#f8fafc;color:#475569;border:1px solid #e2e8f0"><i class="bi bi-globe me-1"></i> {{ $req->domain ?: 'Unknown' }}</span>
                                @if($req->url)
                                <span class="snap-pill" style="background:#fdf2f2;color:#9b1c1c"><i class="bi bi-link-45deg me-1"></i> <a href="{{ $req->url }}" target="_blank" style="color:inherit;text-decoration:none">View Page</a></span>
                                @endif
                                @if($req->extension_device_id)
                                <span class="snap-pill" style="background:#f5f3ff;color:#7c3aed"><i class="bi bi-laptop me-1"></i> Device: {{ substr($req->extension_device_id, 0, 8) }}...</span>
                                @endif
                                @if(auth()->id() !== $req->user_id)
                                <span class="pill pill-purple" style="font-size: 10px; padding: 2px 8px;">TEAM MEMBER</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Sessions --}}
        <div id="ev-sessions" class="ev-panel">
            <div class="ev-box">
                <div class="ev-box-head"><div><h3>🌐 Websites Visited</h3><p>{{ $uniqueDomainCount }} unique sites — click any row to see individual sessions</p></div><span class="pill pill-gray">{{ $sessions->count() }} total sessions</span></div>
                @forelse($domainGroups as $dg)
                <div class="sess-row" onclick="openEVModal('{{ addslashes($dg['domain']) }}')" style="cursor:pointer;transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;border-radius:12px;background:{{ $dg['ai']?'#eff6ff':'#f8fafc' }};color:{{ $dg['ai']?'#3b82f6':'#64748b' }};border:1px solid {{ $dg['ai']?'#bfdbfe':'#e2e8f0' }};display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0">
                                <i class="bi {{ $dg['ai'] ? 'bi-robot' : 'bi-globe' }}"></i>
                            </div>
                            <div>
                                <div class="sess-domain">{{ $dg['domain'] }}</div>
                                <div class="sess-time">
                                    {{ $dg['count'] }} session{{ $dg['count']!==1?'s':'' }}{{ $dg['category'] ? ' · '.ucfirst($dg['category']) : '' }}{{ $dg['ai'] ? ' · AI Tool' : '' }}
                                    <span class="text-muted ms-1" style="font-size: 11px;">(Devices: {{ $dg['sessions']->pluck('device.device_name')->filter()->unique()->join(', ') ?: 'Unknown' }})</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="text-align:right"><div style="font-weight:800;font-size:.9rem;color:#4f46e5">{{ $ms($dg['active_ms']) }}</div><div style="font-size:.72rem;color:#9ca3af">active time</div></div>
                            <i class="bi bi-chevron-right" style="color:#d1d5db"></i>
                        </div>
                    </div>
                </div>
                @empty
                <div class="ev-empty"><i class="bi bi-window"></i><p>No sessions recorded yet.</p></div>
                @endforelse
            </div>
        </div>

        {{-- Daily Report --}}
        <div id="ev-daily" class="ev-panel">
            <div class="ev-box">
                <div class="ev-box-head"><div><h3>📅 Daily Summary</h3><p>Productivity summary for each day your extension was active.</p></div><span class="pill pill-blue">{{ $rollups->count() }} days</span></div>
                @if($rollups->isEmpty())
                    <div class="ev-empty"><i class="bi bi-calendar-x"></i><p>No daily reports yet.</p></div>
                @else
                <div class="roll-hdr" style="display:grid;grid-template-columns:100px 1fr 80px 1fr;gap:.75rem"><div>Date</div><div>Active Time</div><div>Sessions</div><div>Scores</div></div>
                @foreach($rollups->take(20) as $r)
                <div class="roll-grid">
                    <div><div style="font-weight:800;font-size:.88rem;color:#111">{{ $r->date->format('M d') }}</div><div style="font-size:.72rem;color:#6b7280">{{ $r->date->format('l') }}</div></div>
                    <div>
                        <div style="font-weight:800">{{ $ms($r->total_active_ms) }}</div>
                        <div style="font-size:.72rem;color:#6b7280" title="Source: {{ $r->device->device_name ?? 'Individual' }}">
                            via {{ Str::limit($r->device->device_name ?? 'Individual', 12) }}
                        </div>
                    </div>
                    <div style="font-weight:800">{{ $r->sessions_count }}</div>
                    <div style="display:flex;gap:.3rem;flex-wrap:wrap">
                        @if($r->focus_score_avg!==null)<span class="snap-pill" style="background:{{ $r->focus_score_avg>=70?'#f0fdf4':'#fffbeb' }};color:{{ $r->focus_score_avg>=70?'#15803d':'#b45309' }}">🎯 {{ $r->focus_score_avg }}</span>@endif
                        @if($r->productivity_score_avg!==null)<span class="snap-pill" style="background:#f0f9ff;color:#0369a1">⚡ {{ $r->productivity_score_avg }}</span>@endif
                        @if($r->ai_adoption_score!==null)<span class="snap-pill" style="background:#f5f3ff;color:#7c3aed">🤖 {{ $r->ai_adoption_score }}</span>@endif
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        {{-- Snapshots --}}
        <div id="ev-snapshots" class="ev-panel">
            <div class="ev-box">
                <div class="ev-box-head"><div><h3>📷 Metrics Snapshots</h3><p>Captured every 30 minutes while you browse.</p></div><span class="pill pill-green">{{ $snapshots->count() }} snapshots</span></div>
                @forelse($snapshots->take(30) as $snap)
                <div class="snap-row">
                    <div class="snap-time">
                        <i class="bi bi-camera me-2 text-muted"></i> 
                        {{ $snap->captured_at?->format('M d, Y · H:i') ?? '—' }} &nbsp;
                        <span class="pill pill-gray">{{ $snap->window_minutes ?? 60 }}-min window</span>
                        <span class="pill pill-purple" style="font-size: 10px;">{{ $snap->device->device_name ?? 'Unknown Device' }}</span>
                    </div>
                    <div class="snap-pills">
                        @if($snap->focus_score!==null)<span class="snap-pill" style="background:{{ $snap->focus_score>=70?'#f0fdf4':'#fffbeb' }};color:{{ $snap->focus_score>=70?'#15803d':'#b45309' }}">🎯 Focus: {{ $snap->focus_score }}/100</span>@endif
                        @if($snap->productivity_score!==null)<span class="snap-pill" style="background:#f0f9ff;color:#0369a1">⚡ Productivity: {{ $snap->productivity_score }}/100</span>@endif
                        @if($snap->ai_adoption_score!==null)<span class="snap-pill" style="background:#f5f3ff;color:#7c3aed">🤖 AI Adoption: {{ $snap->ai_adoption_score }}/100</span>@endif
                        @if($snap->context_switch_count!==null)<span class="snap-pill" style="background:#fff7ed;color:#c2410c">🔀 {{ $snap->context_switch_count }} tab switches{{ $snap->tab_switches_per_hour ? ' ('.$snap->tab_switches_per_hour.'/hr)' : '' }}</span>@endif
                    </div>
                </div>
                @empty
                <div class="ev-empty"><i class="bi bi-camera"></i><p>No snapshots yet.</p></div>
                @endforelse
            </div>
        </div>

        </div>
    </div>
    @endif {{-- end hasData --}}

    {{-- Domain Popup Modal --}}
    <div id="evDomainModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);padding:1rem;overflow-y:auto" onclick="if(event.target===this)closeEVModal()">
        <div style="background:#fff;border-radius:16px;max-width:700px;margin:2rem auto;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2)">
            <div style="background:linear-gradient(135deg,#1e1b4b,#4338ca);padding:1.25rem 1.5rem;color:#fff;display:flex;justify-content:space-between;align-items:center">
                <div><div id="evModalTitle" style="font-weight:800;font-size:1.05rem"></div><div id="evModalSub" style="font-size:.78rem;opacity:.75;margin-top:.2rem"></div></div>
                <button onclick="closeEVModal()" style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem">✕</button>
            </div>
            <div id="evModalBody" style="max-height:65vh;overflow-y:auto;padding:.5rem 0"></div>
        </div>
    </div>

    <!-- Installation Steps -->
    <div class="mb-5 animate-slide-up delay-1">
        <div class="text-center mb-5">
            <h2 class="fw-800 text-dark" style="letter-spacing: -0.03em;">Setup Instructions</h2>
            <p class="text-muted">Follow these 3 simple steps to activate your workspace connection.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5 class="fw-800 text-dark mb-3">Download & Extract</h5>
                    <p class="text-muted small fw-600 mb-0">Click the download button above to get the extension ZIP. Extract the contents to a folder on your computer where it won't be moved.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5 class="fw-800 text-dark mb-3">Load Extension</h5>
                    <p class="text-muted small fw-600 mb-0">Open Chrome and navigate to <code>chrome://extensions</code>. Enable <strong>Developer Mode</strong> at the top right, then click <strong>"Load unpacked"</strong> and select your extracted folder.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5 class="fw-800 text-dark mb-3">Connect Profile</h5>
                    <p class="text-muted small fw-600 mb-0">Open the extension from your toolbar. Use the Unique Connection Key provided below to link your account and start syncing data.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Connection Key Section -->
    <div class="card border-0 shadow-sm rounded-5 p-5 animate-slide-up delay-2 mb-5" style="background: #f8fafc; border: 1px solid #e2e8f0 !important;">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <!-- <p class="text-muted small fw-600 mb-4 mb-lg-0">This key is required to authenticate your extension with your Daleel AI account. Keep it secure.</p> -->
                <h4 class="fw-800 text-dark mb-2">Your Unique Connection Key</h4>
                @if(auth()->user()->account_type === 'Free Plan')
                <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="badge bg-warning text-dark fw-700 rounded-pill px-3 py-2" style="font-size: 11px;">
                        <i class="bi bi-shield-exclamation me-1"></i> Free Plan — 1 device limit
                    </span>
                    @if($devices->count() >= 1)
                    <span class="badge bg-danger text-white fw-700 rounded-pill px-3 py-2" style="font-size: 11px;">
                        Limit Reached
                    </span>
                    @endif
                </div>
                @endif
            </div>
            <div class="col-lg-6 text-lg-end">
                <div id="code-display-area" style="display: none;">
                    <div class="d-flex flex-column align-items-end gap-2">
                        <div class="connection-pill">
                            <span id="key-text">Daleel AI-XXXXXX</span>
                            <button class="copy-btn" onclick="copyKey()">COPY KEY</button>
                        </div>
                        <span class="timer-badge" id="expiry-timer">Expires in 10:00</span>
                    </div>
                </div>
                <div id="generate-area">
                    @if(auth()->user()->account_type === 'Free Plan' && $devices->count() >= 1)
                        {{-- Free plan limit reached: show upgrade prompt --}}
                        <div class="text-end">
                            <p class="text-danger fw-700 mb-2" style="font-size: 13px;">
                                <i class="bi bi-lock-fill me-1"></i> You already have an active device connected.
                            </p>
                            <a href="/pricing" class="btn btn-sm btn-outline-primary rounded-pill fw-700 px-4">
                                <i class="bi bi-arrow-up-circle me-1"></i> Upgrade for Unlimited Devices
                            </a>
                        </div>
                    @else
                        <button class="btn-generate" id="btn-generate-code" onclick="generateCode()">
                            <i class="bi bi-shield-lock-fill me-2"></i> Generate Connection Key
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Linked Devices -->
    <div class="animate-slide-up delay-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-800 text-dark mb-0">Linked Extension Devices</h4>
            <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-800" style="font-size: 12px;">{{ $devices->count() }} ACTIVE</span>
        </div>

        @if($devices->isEmpty())
            <div class="text-center py-5 bg-light rounded-5 border border-dashed">
                <i class="bi bi-laptop text-muted" style="font-size: 40px;"></i>
                <p class="text-muted fw-700 mt-3">No extension devices linked yet.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($devices as $device)
                <div class="col-md-6 col-xl-4">
                    <div class="device-card d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="bg-primary-subtle text-primary rounded-3 p-3">
                                <i class="bi bi-browser-chrome fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-800 text-dark mb-1">{{ $device->device_name ?? 'Unknown Device' }}</h6>
                                <p class="text-muted mb-0" style="font-size: 11px; font-weight: 600;">
                                    Last active: {{ $device->last_active_at ? $device->last_active_at->diffForHumans() : 'Never' }}
                                </p>
                            </div>
                        </div>
                        <button class="btn btn-outline-danger btn-sm border-0 rounded-3" onclick="revokeDevice('{{ $device->device_id }}', '{{ $device->id }}')" title="Revoke Access">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="text-center mt-5 pt-4 mb-5">
        <p class="text-muted small fw-700">Need help? <a href="#" class="text-primary text-decoration-none">Contact Support</a> or <a href="#" class="text-primary text-decoration-none">Read the Full Documentation</a></p>
    </div>

    

</div>
@endsection

@section('scripts')
<script>
    let timerInterval;

    function generateCode() {
        const btn = document.getElementById('btn-generate-code');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating...';

        fetch('{{ route("extension.verify-code") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(json => {
                    throw new Error(json.message || 'Failed to generate code');
                });
            }
            return response.json();
        })
        .then(data => {
            const result = data.data;
            document.getElementById('key-text').innerText = result.verification_code;
            document.getElementById('generate-area').style.display = 'none';
            document.getElementById('code-display-area').style.display = 'block';
            document.getElementById('expiry-timer').style.display = 'block';
            
            startTimer(result.expires_in_seconds);
            showToast('Verification code generated successfully', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Failed to generate code', 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-shield-lock-fill me-2"></i> Generate Connection Key';
        });
    }

    function startTimer(duration) {
        let timer = duration, minutes, seconds;
        clearInterval(timerInterval);
        
        timerInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            document.getElementById('expiry-timer').textContent = "Expires in " + minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(timerInterval);
                document.getElementById('code-display-area').style.display = 'none';
                document.getElementById('generate-area').style.display = 'block';
                showToast('Verification code expired', 'warning');
            }
        }, 1000);
    }

    function revokeDevice(deviceId, linkId) {
        if (!confirm('Are you sure you want to revoke this device? The extension will stop working immediately.')) return;

        fetch('/extension/device/' + linkId + '/revoke', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.data.unlinked) {
                showToast('Device revoked successfully', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to revoke device', 'danger');
        });
    }

    function copyKey() {
        const key = document.getElementById('key-text').innerText;
        
        // Fallback for non-HTTPS or older browsers
        if (!navigator.clipboard) {
            const textArea = document.createElement("textarea");
            textArea.value = key;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                handleCopySuccess();
            } catch (err) {
                console.error('Fallback copy failed', err);
            }
            document.body.removeChild(textArea);
            return;
        }

        navigator.clipboard.writeText(key).then(() => {
            handleCopySuccess();
        }).catch(err => {
            console.error('Async copy failed', err);
        });
    }

    function handleCopySuccess() {
        const btn = document.querySelector('.copy-btn');
        btn.innerText = 'COPIED!';
        btn.classList.add('bg-success', 'text-white', 'border-success');
        
        showToast('Connection key copied to clipboard', 'success');

        setTimeout(() => {
            btn.innerText = 'COPY KEY';
            btn.classList.remove('bg-success', 'text-white', 'border-success');
        }, 2000);
    }

    // Data Viewer tab switching
    function showEVTab(name, btn) {
        document.querySelectorAll('.ev-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.ev-tab').forEach(b => b.classList.remove('active'));
        document.getElementById(name).classList.add('active');
        btn.classList.add('active');
    }

    // Domain modal
    const evDomainData = @json($domainGroups);
    const evMs = v => { v=Math.max(0,parseInt(v)||0); if(v<60000) return Math.round(v/1000)+'s'; let m=Math.floor(v/60000); return m<60?m+'m':Math.floor(m/60)+'h '+(m%60)+'m'; };
    function openEVModal(domain) {
        const dg = evDomainData.find(d => d.domain === domain);
        if (!dg) return;
        document.getElementById('evModalTitle').textContent = (dg.ai ? '🤖 ' : '🌐 ') + dg.domain;
        document.getElementById('evModalSub').textContent = dg.count + ' sessions · ' + evMs(dg.active_ms) + ' active time';
        document.getElementById('evModalBody').innerHTML = dg.sessions.map(s => `
            <div style="padding:.85rem 1.5rem;border-bottom:1px solid #f3f4f6">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap">
                    <div>
                        <div style="font-weight:700;font-size:.88rem;color:#111">${s.started_at ? new Date(s.started_at).toLocaleString('en-GB',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) : '—'}</div>
                        <div style="font-size:.75rem;color:#6b7280;margin-top:.15rem">
                            ${s.ended_at ? '→ '+new Date(s.ended_at).toLocaleTimeString('en-GB',{hour:'2-digit',minute:'2-digit'}) : 'In progress'}
                            <span class="ms-1 pill pill-gray" style="font-size: 10px; padding: 1px 6px;">${s.device ? s.device.device_name : 'No Device'}</span>
                        </div>
                    </div>
                    <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                        <span style="font-size:.8rem;font-weight:700;color:#4f46e5">⏱ ${evMs(s.active_ms)}</span>
                        <span style="font-size:.8rem;color:#374151">${s.page_count||0} pages</span>
                        <span style="font-size:.8rem;color:#374151">${s.click_count||0} clicks</span>
                    </div>
                </div>
            </div>
        `).join('');
        document.getElementById('evDomainModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeEVModal() {
        document.getElementById('evDomainModal').style.display = 'none';
        document.body.style.overflow = '';
    }
</script>
@endsection
