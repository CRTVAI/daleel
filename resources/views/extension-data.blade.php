@extends('layouts.user')
@section('title', 'Extension Activity Viewer')

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
    $totalPages      = $sessions->sum('page_count');
    $aiSessions      = $sessions->where('is_ai_tool', true)->count();
    $avgFocus        = round($snapshots->avg('focus_score') ?? 0);
    $avgProductivity = round($snapshots->avg('productivity_score') ?? 0);
    $avgAI           = round($snapshots->avg('ai_adoption_score') ?? 0);
    $topDomains      = $sessions
        ->groupBy(fn($s) => $s->platform_domain ?: 'Unknown')
        ->map(fn($g,$d) => ['domain'=>$d,'active_ms'=>$g->sum('active_ms'),'count'=>$g->count(),'ai'=>$g->where('is_ai_tool',true)->count()])
        ->sortByDesc('active_ms')->take(6)->values();
    $maxMs = $topDomains->max('active_ms') ?: 1;
    $hasData = $sessions->isNotEmpty() || $snapshots->isNotEmpty() || $rollups->isNotEmpty() || $recommendations->isNotEmpty();
    $uniqueDomainCount = $domainGroups->count();
    $uniqueRecCount = $uniqueRecommendedCount ?? $recommendations->unique('content_id')->count();
@endphp

@section('styles')
<style>
.ev-wrap { max-width:1100px;margin:0 auto;padding:1.5rem; }
.ev-hero { background:linear-gradient(135deg,#1e1b4b,#4338ca);border-radius:16px;padding:1.5rem 2rem;color:#fff;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap; }
.ev-hero h1 { font-size:1.4rem;font-weight:800;margin:0 0 .25rem; }
.ev-hero p  { opacity:.7;margin:0;font-size:.85rem; }
/* Tabs */
.ev-tabs { display:flex;gap:.5rem;margin-bottom:1.25rem;border-bottom:2px solid #e5e7eb;padding-bottom:0; }
.ev-tab  { padding:.6rem 1.25rem;font-size:.85rem;font-weight:700;color:#6b7280;cursor:pointer;border:none;background:none;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .2s;border-radius:6px 6px 0 0; }
.ev-tab.active { color:#4f46e5;border-bottom-color:#4f46e5;background:#f5f3ff; }
.ev-tab:hover:not(.active) { color:#374151;background:#f9fafb; }
.ev-tab-badge { background:#e0e7ff;color:#4f46e5;border-radius:99px;font-size:.65rem;padding:.1rem .45rem;margin-left:.35rem;font-weight:800; }
.ev-panel { display:none; }
.ev-panel.active { display:block; }
/* Cards */
.ev-stat-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:.75rem;margin-bottom:1.25rem; }
.ev-stat { background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem 1.25rem; }
.ev-stat .val { font-size:1.6rem;font-weight:800;color:#111;line-height:1; }
.ev-stat .lbl { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;margin-top:.3rem; }
.ev-stat .hint { font-size:.72rem;color:#9ca3af;margin-top:.15rem; }
/* Score bars */
.score-row { display:flex;align-items:center;gap:.75rem;margin-bottom:.6rem; }
.score-lbl  { font-size:.82rem;font-weight:700;color:#374151;width:120px;flex-shrink:0; }
.score-track{ flex:1;background:#f3f4f6;border-radius:99px;height:10px;overflow:hidden; }
.score-fill { height:100%;border-radius:99px; }
.score-num  { font-size:.85rem;font-weight:800;width:36px;text-align:right; }
/* Domain bars */
.d-row  { display:flex;align-items:center;gap:.75rem;padding:.65rem 0;border-bottom:1px solid #f3f4f6; }
.d-row:last-child { border:none; }
.d-icon { width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0; }
.d-name { font-weight:800;font-size:.88rem;color:#111; }
.d-sub  { font-size:.72rem;color:#6b7280; }
.d-track{ flex:1;background:#f3f4f6;border-radius:99px;height:8px;overflow:hidden; }
.d-fill { height:100%;border-radius:99px;background:linear-gradient(90deg,#4f46e5,#818cf8); }
.d-time { font-weight:700;font-size:.82rem;color:#4f46e5;width:44px;text-align:right;flex-shrink:0; }
/* Section box */
.ev-box { background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;margin-bottom:1rem; }
.ev-box-head { padding:.9rem 1.25rem;border-bottom:1px solid #f3f4f6;background:#fafafa;display:flex;justify-content:space-between;align-items:center; }
.ev-box-head h3 { font-size:.92rem;font-weight:800;color:#111;margin:0; }
.ev-box-head p  { font-size:.75rem;color:#6b7280;margin:.15rem 0 0; }
.ev-box-body { padding:1.25rem; }
/* Pill badge */
.pill { display:inline-block;padding:.2rem .6rem;border-radius:99px;font-size:.72rem;font-weight:700; }
.pill-blue   { background:#eff6ff;color:#1d4ed8; }
.pill-green  { background:#f0fdf4;color:#15803d; }
.pill-amber  { background:#fffbeb;color:#b45309; }
.pill-purple { background:#f5f3ff;color:#7c3aed; }
.pill-gray   { background:#f3f4f6;color:#374151; }
.pill-red    { background:#fef2f2;color:#b91c1c; }
/* Rec grid */
.rec-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.9rem; }
.rec-card { border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;transition:box-shadow .2s; }
.rec-card:hover { box-shadow:0 6px 20px rgba(0,0,0,.1); }
.rec-img  { width:100%;height:110px;object-fit:cover;display:block; }
.rec-body { padding:.8rem; }
.rec-title{ font-size:.82rem;font-weight:800;color:#111;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:.4rem; }
.rec-meta { font-size:.73rem;color:#6b7280;margin-bottom:.4rem; }
.rec-link { font-size:.77rem;font-weight:700;color:#4f46e5;text-decoration:none; }
.rec-link:hover { color:#4338ca; }
/* Sessions */
.sess-row { padding:.85rem 1.25rem;border-bottom:1px solid #f9fafb; }
.sess-row:last-child { border:none; }
.sess-domain { font-weight:800;font-size:.9rem;color:#111; }
.sess-time   { font-size:.75rem;color:#6b7280;margin:.1rem 0 .4rem; }
.sess-facts  { display:flex;gap:.75rem;flex-wrap:wrap; }
.sess-fact   { font-size:.78rem;font-weight:700;color:#374151;display:flex;align-items:center;gap:.25rem; }
.sess-fact i { color:#9ca3af;font-size:.8rem; }
/* Rollup table */
.roll-grid { display:grid;grid-template-columns:100px 1fr 80px 1fr;gap:.75rem;align-items:center;padding:.75rem 1.25rem;border-bottom:1px solid #f9fafb;font-size:.82rem; }
.roll-grid:last-child { border:none; }
.roll-hdr { background:#fafafa;font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;padding:.5rem 1.25rem;border-bottom:1px solid #e5e7eb; }
/* Snap row */
.snap-row { padding:.75rem 1.25rem;border-bottom:1px solid #f9fafb; }
.snap-row:last-child { border:none; }
.snap-time { font-size:.78rem;font-weight:700;color:#374151;margin-bottom:.4rem; }
.snap-pills { display:flex;gap:.35rem;flex-wrap:wrap; }
.snap-pill  { padding:.25rem .65rem;border-radius:8px;font-size:.76rem;font-weight:700; }
/* Empty */
.ev-empty { text-align:center;padding:3rem 2rem;color:#6b7280; }
.ev-empty i { font-size:2.5rem;opacity:.25;display:block;margin-bottom:.75rem; }
</style>
@endsection

@section('content')
<div class="ev-wrap">

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center gap-2" role="alert" style="background:#e6fdf4;color:#0e6245;font-weight:700;font-size:0.88rem;">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Hero --}}
    <div class="ev-hero">
        <div>
            <h1>📊 Extension Activity Viewer</h1>
            <p>All data your Daleel AI browser extension has tracked — private to your account only.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if($hasData)
                <form method="POST" action="{{ route('extension.data.reset') }}" onsubmit="return confirm('WARNING: Are you absolutely sure you want to permanently wipe out ALL your tracked extension activity, sessions, daily reports, metrics, and video recommendations? This action CANNOT be undone.');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm fw-700 rounded-pill px-3" style="border-color: rgba(255, 99, 99, 0.4); color: #ff8b8b; background: transparent;">
                        <i class="bi bi-trash3-fill me-1"></i> Reset All Data
                    </button>
                </form>
            @endif
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm fw-700 rounded-pill px-4">← Dashboard</a>
        </div>
    </div>

    @if(!$hasData)
        <div class="ev-box"><div class="ev-empty">
            <i class="bi bi-database-x"></i>
            <h5 class="fw-800">No Data Yet</h5>
            <p>Make sure your extension is linked and tracking is enabled.</p>
        </div></div>
    @else

    {{-- Tab Nav --}}
    <div class="ev-tabs">
        <button class="ev-tab active" onclick="showTab('overview',this)">
            📈 Overview
        </button>
        <button class="ev-tab" onclick="showTab('recs',this)">
            ⭐ Recommendations <span class="ev-tab-badge">{{ $uniqueRecCount }}</span>
        </button>
        <button class="ev-tab" onclick="showTab('sessions',this)">
            🌐 Sites Visited <span class="ev-tab-badge">{{ $uniqueDomainCount }}</span>
        </button>
        <button class="ev-tab" onclick="showTab('daily',this)">
            📅 Daily Report <span class="ev-tab-badge">{{ $rollups->count() }}</span>
        </button>
        <button class="ev-tab" onclick="showTab('snapshots',this)">
            📷 Snapshots <span class="ev-tab-badge">{{ $snapshots->count() }}</span>
        </button>
    </div>

    {{-- TAB: Overview --}}
    <div id="tab-overview" class="ev-panel active">
        <div class="ev-stat-grid">
            <div class="ev-stat">
                <div class="val">{{ $ms($todayActiveMs ?? 0) }}</div>
                <div class="lbl">Today's Active Time</div>
                <div class="hint">All-time total: {{ $ms($totalActiveMs) }}</div>
            </div>
            <div class="ev-stat">
                <div class="val">{{ $uniqueDomainCount }}</div>
                <div class="lbl">Sites Visited</div>
                <div class="hint">{{ $sessions->count() }} total page sessions recorded</div>
            </div>
            <div class="ev-stat">
                <div class="val">{{ number_format($totalClicks) }}</div>
                <div class="lbl">Clicks</div>
                <div class="hint">Total mouse interactions</div>
            </div>
            <div class="ev-stat">
                <div class="val">{{ $aiSessions }}</div>
                <div class="lbl">AI Tool Sessions</div>
                <div class="hint">ChatGPT, Gemini, Claude, etc.</div>
            </div>
            <div class="ev-stat">
                <div class="val">{{ $uniqueRecCount }}</div>
                <div class="lbl">Unique Videos Suggested</div>
                <div class="hint">{{ $recommendations->count() }} total recommendation triggers</div>
            </div>
        </div>

        <div class="row g-3">
            {{-- Scores --}}
            <div class="col-lg-5">
                <div class="ev-box h-100">
                    <div class="ev-box-head">
                        <div><h3>🎯 Your Productivity Scores</h3><p>Averages across all your sessions — scored 0 to 100</p></div>
                    </div>
                    <div class="ev-box-body">
                        <div class="score-row">
                            <div class="score-lbl">🎯 Focus</div>
                            <div class="score-track"><div class="score-fill" style="width:{{ $pct($avgFocus) }}%;background:linear-gradient(90deg,#4f46e5,#818cf8)"></div></div>
                            <div class="score-num" style="color:#4f46e5">{{ $avgFocus }}</div>
                        </div>
                        <div class="score-row">
                            <div class="score-lbl">⚡ Productivity</div>
                            <div class="score-track"><div class="score-fill" style="width:{{ $pct($avgProductivity) }}%;background:linear-gradient(90deg,#10b981,#6ee7b7)"></div></div>
                            <div class="score-num" style="color:#10b981">{{ $avgProductivity }}</div>
                        </div>
                        <div class="score-row">
                            <div class="score-lbl">🤖 AI Adoption</div>
                            <div class="score-track"><div class="score-fill" style="width:{{ $pct($avgAI) }}%;background:linear-gradient(90deg,#f59e0b,#fde68a)"></div></div>
                            <div class="score-num" style="color:#f59e0b">{{ $avgAI }}</div>
                        </div>
                        <div class="mt-3 p-3 rounded-3" style="background:#f0f9ff;border:1px solid #bae6fd;font-size:.78rem;color:#0369a1">
                            <strong>Focus</strong> = staying on one task. <strong>Productivity</strong> = focus + AI usage + depth. <strong>AI Adoption</strong> = how often you use AI tools.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Domains --}}
            <div class="col-lg-7">
                <div class="ev-box h-100">
                    <div class="ev-box-head">
                        <div><h3>🌐 Most Visited Websites</h3><p>Sites where you spent the most active time — these trigger your video recommendations</p></div>
                    </div>
                    <div class="ev-box-body">
                        @forelse($topDomains as $d)
                        <div class="d-row">
                            <div class="d-icon" style="background:{{ $d['ai'] ? '#eff6ff' : '#f3f4f6' }}">{{ $d['ai'] ? '🤖' : '🌐' }}</div>
                            <div style="min-width:130px">
                                <div class="d-name">{{ $d['domain'] }}</div>
                                <div class="d-sub">{{ $d['count'] }} sessions{{ $d['ai'] ? ' · AI Tool' : '' }}</div>
                            </div>
                            <div class="d-track"><div class="d-fill" style="width:{{ round(($d['active_ms']/$maxMs)*100) }}%"></div></div>
                            <div class="d-time">{{ $ms($d['active_ms']) }}</div>
                        </div>
                        @empty
                        <p class="text-muted text-center py-3 mb-0">No website data yet.</p>
                        @endforelse
                        <div class="mt-3 p-3 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0;font-size:.78rem;color:#15803d">
                            💡 Visit any supported site for <strong>5+ minutes</strong> (ChatGPT, Slack, Notion, YouTube…) and you'll get a relevant tutorial recommendation automatically.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: Recommendations --}}
    <div id="tab-recs" class="ev-panel">
        <div class="ev-box">
            <div class="ev-box-head">
                <div><h3>⭐ AI-Recommended Videos</h3><p>Videos suggested based on which websites you actively used for 5+ minutes</p></div>
                <span class="pill pill-purple">{{ $recommendations->count() }} total</span>
            </div>
            <div class="ev-box-body">
                @if($recommendations->isEmpty())
                    <div class="ev-empty"><i class="bi bi-lightbulb"></i><p>Browse ChatGPT, Slack, Notion, YouTube or any supported site for 5+ minutes and a tutorial will appear here.</p></div>
                @else
                <div class="rec-grid">
                    @foreach($recommendations as $rec)
                    @if($rec->content)
                    <div class="rec-card">
                        <div style="position:relative">
                            <img class="rec-img" src="{{ $rec->content->thumbnail ?: 'https://img.youtube.com/vi/'.$rec->content->youtube_id.'/hqdefault.jpg' }}" alt="">
                            <div style="position:absolute;top:.4rem;left:.4rem">
                                <span class="pill pill-blue" style="font-size:.65rem">🌐 {{ $rec->current_context['domain'] ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="rec-body">
                            <div class="rec-title">{{ $rec->content->title }}</div>
                            <div class="rec-meta">{{ $rec->created_at->diffForHumans() }}</div>
                            <div style="display:flex;gap:.3rem;flex-wrap:wrap;margin-bottom:.5rem">
                                @if($rec->events->isEmpty())
                                    <span class="pill pill-blue">Generated</span>
                                @else
                                    @foreach($rec->events->take(2) as $ev)
                                        <span class="pill {{ $ev->event_type==='clicked'?'pill-green':($ev->event_type==='dismissed'?'pill-amber':'pill-blue') }}">
                                            {{ $ev->event_type==='shown'?'👁 Shown':($ev->event_type==='clicked'?'✅ Clicked':'❌ Dismissed') }}
                                        </span>
                                    @endforeach
                                @endif
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

    {{-- TAB: Sessions (Domain-Grouped) --}}
    <div id="tab-sessions" class="ev-panel">
        <div class="ev-box">
            <div class="ev-box-head">
                <div><h3>🌐 Websites Visited</h3><p>{{ $uniqueDomainCount }} unique sites — click any row to see individual sessions</p></div>
                <span class="pill pill-gray">{{ $sessions->count() }} total sessions</span>
            </div>
            @forelse($domainGroups as $dg)
            <div class="sess-row" onclick="openDomainModal('{{ addslashes($dg['domain']) }}')" style="cursor:pointer;transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;border-radius:8px;background:{{ $dg['ai']?'#eff6ff':'#f3f4f6' }};display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">{{ $dg['ai'] ? '🤖' : '🌐' }}</div>
                        <div>
                            <div class="sess-domain">{{ $dg['domain'] }}</div>
                            <div class="sess-time">{{ $dg['count'] }} session{{ $dg['count']!==1?'s':'' }}{{ $dg['category'] ? ' · '.ucfirst($dg['category']) : '' }}{{ $dg['ai'] ? ' · AI Tool' : '' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="text-align:right">
                            <div style="font-weight:800;font-size:.9rem;color:#4f46e5">{{ $ms($dg['active_ms']) }}</div>
                            <div style="font-size:.72rem;color:#9ca3af">active time</div>
                        </div>
                        <i class="bi bi-chevron-right" style="color:#d1d5db"></i>
                    </div>
                </div>
            </div>
            @empty
            <div class="ev-empty"><i class="bi bi-window"></i><p>No sessions recorded yet.</p></div>
            @endforelse
        </div>
    </div>

    {{-- Domain Sessions Popup Modal --}}
    <div id="domainModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);padding:1rem;overflow-y:auto" onclick="if(event.target===this)closeDomainModal()">
        <div style="background:#fff;border-radius:16px;max-width:700px;margin:2rem auto;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2)">
            <div style="background:linear-gradient(135deg,#1e1b4b,#4338ca);padding:1.25rem 1.5rem;color:#fff;display:flex;justify-content:space-between;align-items:center">
                <div>
                    <div id="modalDomainTitle" style="font-weight:800;font-size:1.05rem"></div>
                    <div id="modalDomainSub" style="font-size:.78rem;opacity:.75;margin-top:.2rem"></div>
                </div>
                <button onclick="closeDomainModal()" style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem">✕</button>
            </div>
            <div id="modalDomainBody" style="max-height:65vh;overflow-y:auto;padding:.5rem 0"></div>
        </div>
    </div>

    {{-- TAB: Daily Report --}}
    <div id="tab-daily" class="ev-panel">
        <div class="ev-box">
            <div class="ev-box-head">
                <div><h3>📅 Daily Summary</h3><p>A complete summary of your productivity for each day your extension was active.</p></div>
                <span class="pill pill-blue">{{ $rollups->count() }} days</span>
            </div>
            @if($rollups->isEmpty())
                <div class="ev-empty"><i class="bi bi-calendar-x"></i><p>No daily reports yet.</p></div>
            @else
            <div class="roll-hdr" style="display:grid;grid-template-columns:100px 1fr 80px 1fr;gap:.75rem">
                <div>Date</div><div>Active Time</div><div>Sessions</div><div>Scores</div>
            </div>
            @foreach($rollups->take(20) as $r)
            <div class="roll-grid">
                <div>
                    <div style="font-weight:800;font-size:.88rem;color:#111">{{ $r->date->format('M d') }}</div>
                    <div style="font-size:.72rem;color:#6b7280">{{ $r->date->format('l') }}</div>
                </div>
                <div>
                    <div style="font-weight:800">{{ $ms($r->total_active_ms) }}</div>
                    <div style="font-size:.72rem;color:#6b7280">of {{ $ms($r->total_open_ms) }} open · avg {{ $r->sessions_count>0?$ms(intval($r->total_active_ms/max(1,$r->sessions_count))):'—' }}/session</div>
                </div>
                <div style="font-weight:800">{{ $r->sessions_count }}</div>
                <div style="display:flex;gap:.3rem;flex-wrap:wrap">
                    @if($r->focus_score_avg!==null)
                        <span class="snap-pill" style="background:{{ $r->focus_score_avg>=70?'#f0fdf4':'#fffbeb' }};color:{{ $r->focus_score_avg>=70?'#15803d':'#b45309' }}">🎯 {{ $r->focus_score_avg }}</span>
                    @endif
                    @if($r->productivity_score_avg!==null)
                        <span class="snap-pill" style="background:#f0f9ff;color:#0369a1">⚡ {{ $r->productivity_score_avg }}</span>
                    @endif
                    @if($r->ai_adoption_score!==null)
                        <span class="snap-pill" style="background:#f5f3ff;color:#7c3aed">🤖 {{ $r->ai_adoption_score }}</span>
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    {{-- TAB: Snapshots --}}
    <div id="tab-snapshots" class="ev-panel">
        <div class="ev-box">
            <div class="ev-box-head">
                <div><h3>📷 Metrics Snapshots</h3><p>Captured every 30 minutes while you browse — shows your scores at that exact moment.</p></div>
                <span class="pill pill-green">{{ $snapshots->count() }} snapshots</span>
            </div>
            @forelse($snapshots->take(30) as $snap)
            <div class="snap-row">
                <div class="snap-time">📸 {{ $snap->captured_at?->format('M d, Y · H:i') ?? '—' }} &nbsp;<span class="pill pill-gray" style="font-size:.65rem">{{ $snap->window_minutes ?? 60 }}-min window</span></div>
                <div class="snap-pills">
                    @if($snap->focus_score!==null)
                        <span class="snap-pill" style="background:{{ $snap->focus_score>=70?'#f0fdf4':'#fffbeb' }};color:{{ $snap->focus_score>=70?'#15803d':'#b45309' }}">🎯 Focus: {{ $snap->focus_score }}/100</span>
                    @endif
                    @if($snap->productivity_score!==null)
                        <span class="snap-pill" style="background:#f0f9ff;color:#0369a1">⚡ Productivity: {{ $snap->productivity_score }}/100</span>
                    @endif
                    @if($snap->ai_adoption_score!==null)
                        <span class="snap-pill" style="background:#f5f3ff;color:#7c3aed">🤖 AI Adoption: {{ $snap->ai_adoption_score }}/100</span>
                    @endif
                    @if($snap->context_switch_count!==null)
                        <span class="snap-pill" style="background:#fff7ed;color:#c2410c">🔀 {{ $snap->context_switch_count }} tab switches{{ $snap->tab_switches_per_hour ? ' ('.$snap->tab_switches_per_hour.'/hr)' : '' }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="ev-empty"><i class="bi bi-camera"></i><p>No snapshots yet.</p></div>
            @endforelse
        </div>
    </div>

    @endif
</div>

<script>
function showTab(name, btn) {
    document.querySelectorAll('.ev-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.ev-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

// Domain groups data for popup
const domainData = @json($domainGroups);
const msFormat = function(v) {
    v = Math.max(0, parseInt(v)||0);
    if (v < 60000) return Math.round(v/1000)+'s';
    let m = Math.floor(v/60000);
    if (m < 60) return m+'m';
    return Math.floor(m/60)+'h '+(m%60)+'m';
};

function openDomainModal(domain) {
    const dg = domainData.find(d => d.domain === domain);
    if (!dg) return;
    document.getElementById('modalDomainTitle').textContent = (dg.ai ? '🤖 ' : '🌐 ') + dg.domain;
    document.getElementById('modalDomainSub').textContent = dg.count + ' sessions · ' + msFormat(dg.active_ms) + ' active time';
    const body = document.getElementById('modalDomainBody');
    body.innerHTML = dg.sessions.map(s => `
        <div style="padding:.85rem 1.5rem;border-bottom:1px solid #f3f4f6">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap">
                <div>
                    <div style="font-weight:700;font-size:.88rem;color:#111">${s.started_at ? new Date(s.started_at).toLocaleString('en-GB',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) : '—'}</div>
                    <div style="font-size:.75rem;color:#6b7280;margin-top:.15rem">${s.ended_at ? '→ ' + new Date(s.ended_at).toLocaleTimeString('en-GB',{hour:'2-digit',minute:'2-digit'}) : 'In progress'}</div>
                </div>
                <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                    <span style="font-size:.8rem;font-weight:700;color:#4f46e5"><i class="bi bi-clock"></i> ${msFormat(s.active_ms)}</span>
                    <span style="font-size:.8rem;color:#374151">${s.page_count||0} pages</span>
                    <span style="font-size:.8rem;color:#374151">${s.click_count||0} clicks</span>
                </div>
            </div>
        </div>
    `).join('');
    document.getElementById('domainModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeDomainModal() {
    document.getElementById('domainModal').style.display = 'none';
    document.body.style.overflow = '';
}
</script>
@endsection
