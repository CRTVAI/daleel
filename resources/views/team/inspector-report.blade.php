@extends('layouts.user')

@section('title', 'Training Intelligence Report — Daleel AI')

@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap');

*, *::before, *::after { box-sizing: border-box; }
body { overflow-x: hidden; }

:root{
    --ink:#12151C; --paper:#F6F4EE; --panel:#FFFFFF;
    --brass:#9C6B22; --brass-soft:#F1E6D2;
    --teal:#0E6B5C; --teal-soft:#DFEEE9;
    --rust:#AE4426; --rust-soft:#F6E4DC;
    --line:#E4DFD1; --muted:#767163; --ink-soft:#4A4638;
    --serif:'Fraunces',serif; --sans:'Inter',sans-serif; --mono:'IBM Plex Mono',monospace;
}

.rpt-shell { max-width: 1040px; margin: 0 auto; padding: 1.5rem 1rem 4rem; font-family:var(--sans); color:var(--ink); }

/* ── Toolbar ── */
.rpt-toolbar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--line); }
.rpt-back { display:inline-flex; align-items:center; gap:.4rem; background:var(--panel); border:1px solid var(--line); border-radius:2px; color:var(--ink-soft); font-weight:600; font-size:.78rem; padding:.45rem .9rem; text-decoration:none; }
.rpt-back:hover { border-color:var(--brass); color:var(--brass); }
.rpt-toolbar-actions { display:flex; gap:.5rem; flex-wrap:wrap; }
.btn-print { display:inline-flex; align-items:center; gap:.4rem; background:var(--ink); color:#fff; border:none; border-radius:2px; font-weight:600; font-size:.78rem; padding:.5rem 1rem; cursor:pointer; }
.btn-print:hover { background:#000; }
.rpt-range-form select { background:var(--panel); border:1px solid var(--line); border-radius:2px; padding:.45rem .8rem; font-size:.76rem; font-weight:600; color:var(--ink-soft); font-family:var(--mono); }

/* ── Cover: dossier header ── */
.rpt-cover { background:var(--ink); border-radius:4px; padding:2.1rem 2.2rem 1.7rem; margin-bottom:1.5rem; color:#fff; position:relative; overflow:hidden; border:1px solid #000; }
.rpt-cover::before { content:''; position:absolute; inset:0; background-image:repeating-linear-gradient(0deg,transparent,transparent 39px,rgba(255,255,255,.025) 40px); pointer-events:none; }
.rpt-stamp { position:absolute; top:1.6rem; right:2rem; text-align:right; font-family:var(--mono); font-size:.62rem; letter-spacing:.08em; color:#8b8577; line-height:1.6; }
.rpt-cover-eyebrow { font-family:var(--mono); font-size:.68rem; font-weight:600; color:var(--brass); text-transform:uppercase; letter-spacing:.14em; margin-bottom:.55rem; display:flex; align-items:center; gap:.5rem; }
.rpt-cover-eyebrow::before { content:''; width:18px; height:1px; background:var(--brass); }
.rpt-cover h1 { font-family:var(--serif); font-size:clamp(1.6rem,3.4vw,2.15rem); font-weight:600; letter-spacing:-.01em; margin:0 0 .3rem; line-height:1.08; }
.rpt-cover p { color:#B9B4A4; font-size:.86rem; margin:0; max-width:34rem; }
.rpt-cover-meta { display:flex; gap:0; margin-top:1.6rem; flex-wrap:wrap; border-top:1px solid rgba(255,255,255,.14); }
.rpt-cover-meta-item { display:flex; flex-direction:column; gap:.2rem; padding:.85rem 1.4rem .1rem 0; margin-right:1.4rem; border-right:1px solid rgba(255,255,255,.14); }
.rpt-cover-meta-item:last-child{ border-right:none; }
.rpt-cover-meta-label { font-family:var(--mono); font-size:.62rem; font-weight:500; color:#8b8577; text-transform:uppercase; letter-spacing:.1em; }
.rpt-cover-meta-value { font-family:var(--mono); font-size:.86rem; font-weight:600; color:#fff; }

/* ── Section Title ── */
.rpt-section-title { font-family:var(--mono); font-size:.66rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.1em; margin:1.7rem 0 .65rem; padding-bottom:.4rem; border-bottom:1px solid var(--line); display:flex; align-items:center; gap:.5rem; }
.rpt-section-title span.n{ color:var(--brass); }
.rpt-hint { font-size:.75rem; color:var(--muted); margin:-.35rem 0 .7rem; }

/* ── Card ── */
.rpt-card { background:var(--panel); border:1px solid var(--line); border-radius:3px; overflow:hidden; margin-bottom:1rem; }
.rpt-card-head { padding:.85rem 1.2rem .75rem; border-bottom:1px solid var(--line); display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.rpt-card-head h3 { font-family:var(--serif); font-size:.98rem; font-weight:600; color:var(--ink); margin:0; display:flex; align-items:center; gap:.4rem; }
.rpt-card-head p { font-size:.72rem; color:var(--muted); margin:.15rem 0 0; }
.rpt-card-body { padding:1rem 1.2rem; }

/* ── Badges ── */
.badge-measured, .badge-inferred, .badge-action { font-family:var(--mono); border-radius:2px; padding:.12rem .5rem; font-size:.6rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; border:1px solid; }
.badge-measured { background:var(--teal-soft); color:var(--teal); border-color:#b9dcd3; }
.badge-inferred { background:var(--brass-soft); color:var(--brass); border-color:#e3cfa4; }
.badge-action   { background:var(--rust-soft); color:var(--rust); border-color:#e8c2b0; }

/* ── KPI Row ── */
.kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:0; margin-bottom:1.1rem; border:1px solid var(--line); border-radius:3px; overflow:hidden; background:var(--panel); }
.kpi-tile { padding:1rem 1.15rem; border-right:1px solid var(--line); }
.kpi-tile:last-child{ border-right:none; }
.kpi-tile-label { font-family:var(--mono); font-size:.62rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.07em; margin-bottom:.4rem; }
.kpi-tile-value { font-family:var(--serif); font-size:1.7rem; font-weight:600; color:var(--ink); letter-spacing:-.02em; line-height:1; }
.kpi-tile-sub { font-size:.7rem; color:var(--muted); margin-top:.3rem; }

/* ── Dial funnel ── */
.dial-row{ display:flex; align-items:center; justify-content:space-around; gap:.5rem; flex-wrap:wrap; padding:.3rem 0; }
.dial{ text-align:center; }
.dial-ring{ width:84px; height:84px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto .5rem; position:relative; }
.dial-ring span{ font-family:var(--mono); font-weight:700; font-size:1.05rem; color:var(--ink); background:var(--panel); width:64px; height:64px; border-radius:50%; display:flex; align-items:center; justify-content:center; }
.dial-label{ font-family:var(--mono); font-size:.64rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; }
.dial-arrow{ color:var(--line); font-size:1.2rem; }

/* ── Tool List ── */
.tool-row { display:flex; align-items:center; gap:.75rem; padding:.6rem 0; border-bottom:1px solid var(--line); }
.tool-row:last-child { border-bottom:none; }
.tool-rank { font-family:var(--mono); width:22px; font-size:.68rem; font-weight:700; color:var(--muted); flex-shrink:0; }
.tool-rank.top { color:var(--brass); }
.tool-name { font-weight:700; color:var(--ink); font-size:.83rem; }
.tool-domain { font-size:.68rem; color:var(--muted); font-family:var(--mono); }
.tool-bar-wrap { width:80px; height:4px; background:var(--line); border-radius:0; overflow:hidden; flex-shrink:0; }
.tool-bar { height:100%; }
.tool-time { font-family:var(--mono); font-size:.76rem; font-weight:700; color:var(--ink); min-width:44px; text-align:right; }
.tag-ai   { font-family:var(--mono); background:var(--brass-soft); color:var(--brass); border-radius:2px; padding:.1rem .4rem; font-size:.6rem; font-weight:700; flex-shrink:0; }
.tag-tool { font-family:var(--mono); background:#EFECE2; color:var(--ink-soft); border-radius:2px; padding:.1rem .4rem; font-size:.6rem; font-weight:700; flex-shrink:0; }

/* ── Table ── */
.rpt-table { width:100%; border-collapse:collapse; font-size:.79rem; }
.rpt-table th { background:#FAF8F2; color:var(--muted); font-family:var(--mono); font-size:.63rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; padding:.55rem .9rem; border-bottom:1px solid var(--line); text-align:left; white-space:nowrap; }
.rpt-table td { padding:.6rem .9rem; border-bottom:1px solid var(--line); color:var(--ink-soft); vertical-align:middle; }
.rpt-table tbody tr:last-child td { border-bottom:none; }

/* ── Recommendation Cards ── */
.rec-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr)); gap:.85rem; }
.rec-card { border-radius:3px; border:1px solid var(--line); background:var(--panel); overflow:hidden; display: flex; flex-direction: column; }
.rec-card-top { padding:.8rem 1rem .55rem; display:flex; align-items:center; gap:.65rem; border-bottom:1px solid var(--line); }
.rec-icon { width:34px; height:34px; border-radius:2px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.rec-card-title { font-weight:700; font-size:.86rem; color:var(--ink); line-height:1.2; }
.rec-card-sub   { font-family:var(--mono); font-size:.65rem; color:var(--muted); margin-top:.1rem; }
.rec-card-body  { padding:.65rem 1rem .85rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
.rec-reason     { font-size:.8rem; color:var(--ink); font-weight:600; line-height:1.4; margin-bottom: .75rem; }
.upgrade-link-anchor:hover { color: var(--teal) !important; text-decoration: underline !important; }

/* ── Action Plan ── */
.week-badge { font-family:var(--mono); display:inline-block; border-radius:2px; padding:.15rem .5rem; font-size:.64rem; font-weight:700; letter-spacing:.03em; white-space:nowrap; border:1px solid; }
.wk1 { background:var(--teal-soft); color:var(--teal); border-color:#b9dcd3; }
.wk2 { background:var(--brass-soft); color:var(--brass); border-color:#e3cfa4; }
.wk3 { background:#EDE7F5; color:#5B3F94; border-color:#d7c9ec; }
.wk4 { background:var(--rust-soft); color:var(--rust); border-color:#e8c2b0; }

/* ── Time-Save ── */
.timesave-list { display:flex; flex-direction:column; gap:.5rem; }
.timesave-item { display:flex; align-items:center; gap:.8rem; padding:.7rem .9rem; background:var(--panel); border:1px solid var(--line); border-radius:3px; }
.timesave-icon { width:32px; height:32px; border-radius:2px; display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }
.timesave-title { font-weight:700; font-size:.82rem; color:var(--ink); }
.timesave-impact { font-family:var(--mono); font-size:.66rem; font-weight:600; color:var(--teal); }

/* ── Coaching tips ── */
.tip-row{ display:flex; gap:.7rem; align-items:flex-start; padding:.7rem 0; border-bottom:1px solid var(--line); }
.tip-row:last-child{ border-bottom:none; }
.tip-num{ font-family:var(--mono); font-size:.72rem; font-weight:700; color:var(--brass); width:22px; flex-shrink:0; padding-top:.1rem; }
.tip-title{ font-weight:700; font-size:.82rem; color:var(--ink); }
.tip-desc{ font-size:.76rem; color:var(--muted); margin-top:.1rem; line-height:1.4; }

/* ── Two-col ── */
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media (max-width:740px) { .two-col { grid-template-columns:1fr; } }

/* ── Friction flag ── */
.flag { margin-top:.75rem; padding:.6rem .85rem; border-radius:2px; font-size:.75rem; font-weight:600; display:flex; align-items:center; gap:.5rem; }
.flag.warn { background:var(--rust-soft); color:var(--rust); }
.flag.ok { background:var(--teal-soft); color:var(--teal); }

/* ── Privacy ── */
.privacy-notice { background:#FAF8F2; border:1px solid var(--line); border-radius:3px; padding:.7rem 1rem; font-size:.72rem; color:var(--muted); display:flex; gap:.55rem; align-items:flex-start; }
.privacy-notice i { color:var(--brass); font-size:.9rem; flex-shrink:0; margin-top:.1rem; }

/* ── Empty ── */
.empty-rpt { text-align:center; padding:2rem 1.5rem; color:var(--muted); }
.empty-rpt i { font-size:1.7rem; display:block; opacity:.4; margin-bottom:.5rem; color:var(--brass); }
.empty-rpt h6 { font-weight:700; color:var(--ink); font-size:.88rem; margin-bottom:.2rem; font-family:var(--serif); }
.empty-rpt p { font-size:.76rem; margin:0; }

/* ── Print ── */
@media print {
    body { background:#fff !important; }
    .rpt-toolbar { display:none !important; }
    .rpt-shell { max-width:100%; padding:0; }
    .rpt-cover { border-radius:0 !important; }
    .rpt-card, .rec-card { page-break-inside:avoid; }
    .kpi-grid { grid-template-columns:repeat(4,1fr); }
    .rec-grid { grid-template-columns:repeat(2,1fr); }
}
</style>
@endsection

@section('content')
@php
    $msLbl         = $ms;
    $totalToolMs   = $topTools->sum('active_ms');
    $activePct     = $totalLearners > 0 ? round($activeLearners / $totalLearners * 100) : 0;

    // ── Dedicated specialized AI tool upgrades with names & URLs ──
    $domainMap = [
        'sheets.google.com'   => ['tool'=>'Google Sheets',    'upgrade_name'=>'Julius AI',      'upgrade_url'=>'https://julius.ai',             'ai'=>'Julius AI — Writes spreadsheet formulas and analyzes data.', 'topic'=>'Data analyst workflows',  'save'=>'Julius AI auto-builds dashboard formulas', 'icon'=>'bi-table',          'color'=>'#0E6B5C','bg'=>'#DFEEE9'],
        'excel.office.com'    => ['tool'=>'Excel Online',     'upgrade_name'=>'Julius AI',      'upgrade_url'=>'https://julius.ai',             'ai'=>'Julius AI — Writes spreadsheet formulas and analyzes data.', 'topic'=>'Data analyst workflows',  'save'=>'Julius AI auto-builds dashboard formulas', 'icon'=>'bi-file-earmark-spreadsheet-fill','color'=>'#0E6B5C','bg'=>'#DFEEE9'],
        'docs.google.com'     => ['tool'=>'Google Docs',      'upgrade_name'=>'Lex',            'upgrade_url'=>'https://lex.page',              'ai'=>'Lex — AI word processor that drafts and refines long files.', 'topic'=>'AI-assisted drafting',    'save'=>'Lex.im generates proposal drafts locally', 'icon'=>'bi-file-earmark-text','color'=>'#2563eb','bg'=>'#dbeafe'],
        'gmail.com'           => ['tool'=>'Gmail',            'upgrade_name'=>'Shortwave',      'upgrade_url'=>'https://www.shortwave.com',     'ai'=>'Shortwave — Drafts instant email replies and summaries.', 'topic'=>'AI-assisted sales writing',   'save'=>'Lavender scores response likelihood',    'icon'=>'bi-envelope-fill',  'color'=>'#AE4426','bg'=>'#F6E4DC'],
        'mail.google.com'     => ['tool'=>'Gmail',            'upgrade_name'=>'Shortwave',      'upgrade_url'=>'https://www.shortwave.com',     'ai'=>'Shortwave — Drafts instant email replies and summaries.', 'topic'=>'AI-assisted sales writing',   'save'=>'Lavender scores response likelihood',    'icon'=>'bi-envelope-fill',  'color'=>'#AE4426','bg'=>'#F6E4DC'],
        'canva.com'           => ['tool'=>'Canva',            'upgrade_name'=>'Gamma App',      'upgrade_url'=>'https://gamma.app',             'ai'=>'Gamma App — Automatically builds styled presentations from text.', 'topic'=>'Interactive pitch design', 'save'=>'Gamma builds web slide deck from prompt', 'icon'=>'bi-palette-fill',   'color'=>'#5B3F94','bg'=>'#EDE7F5'],
        'chat.openai.com'     => ['tool'=>'ChatGPT',          'upgrade_name'=>'Claude Projects','upgrade_url'=>'https://claude.ai',             'ai'=>'Claude Projects — Pins documents to automate context.', 'topic'=>'Structured code prompting', 'save'=>'Build custom instructions to avoid re-prompting', 'icon'=>'bi-robot',      'color'=>'#0ea5e9','bg'=>'#e0f2fe'],
        'chatgpt.com'         => ['tool'=>'ChatGPT',          'upgrade_name'=>'Claude Projects','upgrade_url'=>'https://claude.ai',             'ai'=>'Claude Projects — Pins documents to automate context.', 'topic'=>'Structured code prompting', 'save'=>'Build custom instructions to avoid re-prompting', 'icon'=>'bi-robot',      'color'=>'#0ea5e9','bg'=>'#e0f2fe'],
        'notion.so'           => ['tool'=>'Notion',           'upgrade_name'=>'Notion AI',      'upgrade_url'=>'https://www.notion.so/product/ai','ai'=>'Notion AI — Summarizes project notes and team wikis.', 'topic'=>'Workspace central database','save'=>'Auto-summarise team project notes',        'icon'=>'bi-journal-richtext','color'=>'#4A4638','bg'=>'#EFECE2'],
        'figma.com'           => ['tool'=>'Figma',            'upgrade_name'=>'Relume',         'upgrade_url'=>'https://www.relume.io',         'ai'=>'Relume — Instantly builds design layouts and wireframes.', 'topic'=>'Interactive wireframing',  'save'=>'Relume builds full landing designs',       'icon'=>'bi-vector-pen',     'color'=>'#9C6B22','bg'=>'#F1E6D2'],
        'github.com'          => ['tool'=>'GitHub',           'upgrade_name'=>'Cursor IDE',     'upgrade_url'=>'https://www.cursor.com',        'ai'=>'Cursor IDE — Edits code across multiple files in seconds.', 'topic'=>'Multi-file local coding',  'save'=>'Cursor indexes directory for rapid refactor', 'icon'=>'bi-code-slash',  'color'=>'#12151C','bg'=>'#EFECE2'],
        'trello.com'          => ['tool'=>'Trello',           'upgrade_name'=>'Motion',         'upgrade_url'=>'https://www.usemotion.com',     'ai'=>'Motion — Auto-schedules calendar tasks to meet deadlines.', 'topic'=>'AI calendar prioritisation','save'=>'Motion automatically fills schedule buffers','icon'=>'bi-kanban-fill',   'color'=>'#0ea5e9','bg'=>'#e0f2fe'],
        'slack.com'           => ['tool'=>'Slack',            'upgrade_name'=>'Slack AI',       'upgrade_url'=>'https://slack.com/features/ai', 'ai'=>'Slack AI — Summarizes long channel threads and updates.', 'topic'=>'Async channel analysis',   'save'=>'Digests long discussion threads in 5s',    'icon'=>'bi-chat-dots-fill', 'color'=>'#5B3F94','bg'=>'#EDE7F5'],
        'zapier.com'          => ['tool'=>'Zapier',           'upgrade_name'=>'Skyvern',        'upgrade_url'=>'https://www.skyvern.com',        'ai'=>'Skyvern — Automates clicks and details on websites.', 'topic'=>'AI-based UI interaction Automation', 'save'=>'Extract data using LLM browser clicks',  'icon'=>'bi-lightning-fill', 'color'=>'#9C6B22','bg'=>'#F1E6D2'],
        'make.com'            => ['tool'=>'Make',             'upgrade_name'=>'Skyvern',        'upgrade_url'=>'https://www.skyvern.com',        'ai'=>'Skyvern — Automates clicks and details on websites.', 'topic'=>'AI-based UI interaction Automation', 'save'=>'Extract data using LLM browser clicks',  'icon'=>'bi-gear-wide-connected','color'=>'#5B3F94','bg'=>'#EDE7F5'],
        'zoom.us'             => ['tool'=>'Zoom',             'upgrade_name'=>'Fathom',         'upgrade_url'=>'https://fathom.video',          'ai'=>'Fathom — Transcribes calls and extracts action items.', 'topic'=>'Action item detection',    'save'=>'Fathom flags key timelines in transcript',  'icon'=>'bi-camera-video-fill','color'=>'#2563eb','bg'=>'#dbeafe'],
        'meet.google.com'     => ['tool'=>'Google Meet',      'upgrade_name'=>'Fathom',         'upgrade_url'=>'https://fathom.video',          'ai'=>'Fathom — Transcribes calls and extracts action items.', 'topic'=>'Action item detection',    'save'=>'Fathom flags key timelines in transcript',  'icon'=>'bi-camera-video-fill','color'=>'#0E6B5C','bg'=>'#DFEEE9'],
        'office.com'          => ['tool'=>'Microsoft 365',    'upgrade_name'=>'Copilot Pro',    'upgrade_url'=>'https://copilot.microsoft.com', 'ai'=>'Copilot Pro — Native AI that formats slides and documents.', 'topic'=>'Integrated slide design', 'save'=>'Copilot formats presentation layout styles',  'icon'=>'bi-microsoft',      'color'=>'#0284c7','bg'=>'#e0f2fe'],
        'stackoverflow.com'   => ['tool'=>'Stack Overflow',   'upgrade_name'=>'Cursor IDE',     'upgrade_url'=>'https://www.cursor.com',        'ai'=>'Cursor IDE — Edits code across multiple files in seconds.', 'topic'=>'Multi-file local coding',  'save'=>'Cursor indexes directory for rapid refactor', 'icon'=>'bi-stack',          'color'=>'#9C6B22','bg'=>'#F1E6D2'],
    ];

    $aiRecs = []; $topicRecs = []; $timeSaves = []; $seen = [];
    foreach($topTools as $site) {
        $domain = strtolower($site->domain ?? '');
        foreach($domainMap as $needle => $data) {
            if(str_contains($domain, $needle) && !in_array($data['tool'], $seen)) {
                $seen[] = $data['tool'];
                $aiRecs[]    = array_merge($data, ['domain'=>$domain, 'time'=>$site->active_ms, 'is_ai'=>$site->is_ai_tool]);
                $topicRecs[] = array_merge($data, ['domain'=>$domain, 'time'=>$site->active_ms]);
                $timeSaves[] = array_merge($data, ['domain'=>$domain, 'time'=>$site->active_ms]);
                if(count($aiRecs) >= 6) break 2;
            }
        }
    }

    if(empty($aiRecs)) {
        $aiRecs = [
            ['tool'=>'ChatGPT','upgrade_name'=>'Claude Projects','upgrade_url'=>'https://claude.ai','ai'=>'Claude Projects — Pins documents to automate context.','topic'=>'Structured code prompting','save'=>'Build custom instructions to avoid re-prompting','icon'=>'bi-robot','color'=>'#0ea5e9','bg'=>'#e0f2fe','is_ai'=>true],
            ['tool'=>'Google Sheets','upgrade_name'=>'Julius AI','upgrade_url'=>'https://julius.ai','ai'=>'Julius AI — Writes spreadsheet formulas and analyzes data.','topic'=>'Spreadsheet analytics','save'=>'Rows.com auto-builds dashboard formulas','icon'=>'bi-table','color'=>'#0E6B5C','bg'=>'#DFEEE9','is_ai'=>false],
            ['tool'=>'Notion','upgrade_name'=>'Notion AI','upgrade_url'=>'https://www.notion.so/product/ai','ai'=>'Notion AI — Summarizes project notes and team wikis.','topic'=>'Workspace central database','save'=>'Auto-summarise team project notes','icon'=>'bi-journal-richtext','color'=>'#4A4638','bg'=>'#EFECE2','is_ai'=>false],
        ];
        $topicRecs = $aiRecs; $timeSaves = $aiRecs;
    }

    $weekPlan = [];
    foreach($topicRecs as $i => $rec) {
        $weekNum = $i + 1;
        if($weekNum > 4) break;
        $weekPlan[] = [
            'week'     => "Week $weekNum",
            'topic'    => $rec['topic'],
            'tool'     => $rec['tool'],
            'format'   => $i === 0 ? 'Live · 60m' : ($i === 1 ? 'Workshop · 75m' : ($i === 2 ? 'Clinic · 60m' : 'Lab · 90m')),
            'outcome'  => $i === 0 ? 'Apply to a real task' : ($i === 1 ? 'Ship a dept output' : ($i === 2 ? 'Complete a workflow' : 'Automate one task')),
        ];
    }

    // Coaching principles — one line each
    $coachingTips = [
        ['title'=>'Apply within 24 hours', 'desc'=>'Assign a real task right after every session — retention nearly doubles.'],
        ['title'=>'Repeat, don\'t re-teach', 'desc'=>'Revisit the same tool briefly in Week 2 instead of one long class.'],
        ['title'=>'Pair strong with struggling', 'desc'=>'Match your top performers with lower-engagement learners on one task.'],
        ['title'=>'Ask before you teach', 'desc'=>'Open with "what did you try this week?" — teach to the real friction.'],
        ['title'=>'Check output, not quizzes', 'desc'=>'Ask for one report made with the tool — that proves the skill transferred.'],
        ['title'=>'Name the time saved', 'desc'=>'"This saves you X min/week on Y" — concrete benefit drives engagement.'],
    ];
@endphp

<div class="rpt-shell">

    {{-- Plan Training trigger --}}
    <div style="margin-bottom:.75rem;">
        <button onclick="togglePlanForm()" style="display:inline-flex;align-items:center;gap:.45rem;background:var(--ink);color:#fff;border:none;border-radius:3px;font-weight:700;font-size:.78rem;padding:.5rem .95rem;cursor:pointer;font-family:var(--sans);">
            <i class="bi bi-mortarboard-fill" style="color:var(--brass);"></i> Plan Training
            <span id="plan-toggle-icon" style="font-size:.65rem;margin-left:.1rem;color:#8b8577;"><i class="bi bi-chevron-down"></i></span>
        </button>
        <span id="plan-status" style="font-size:.72rem;color:var(--muted);font-family:var(--mono);margin-left:.75rem;"></span>
    </div>

    {{-- Teaching Plan Generator form (hidden by default) --}}
    <div id="plan-form-body" style="display:none;margin-bottom:1.25rem;">
        <div class="rpt-card">
            <div class="rpt-card-head">
                <div><h3><i class="bi bi-magic" style="color:var(--brass);"></i> Session Planner</h3><p>Set parameters — AI will build a curriculum from the top tools above</p></div>
                <span onclick="togglePlanForm()" style="cursor:pointer;color:var(--muted);font-size:1.1rem;"><i class="bi bi-x-lg"></i></span>
            </div>
            <div class="rpt-card-body">
                <form id="plan-form" onsubmit="return generatePlan(event)">
                    @csrf
                    <input type="hidden" name="range" value="{{ $range }}">
                    @if($deptId)<input type="hidden" name="dept_id" value="{{ $deptId }}">@endif
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
                        <div>
                            <label style="font-family:var(--mono);font-size:.65rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.3rem;">Total Sessions</label>
                            <input type="number" name="total_session" min="1" max="20" value="4" required style="width:100%;padding:.5rem .7rem;border:1px solid var(--line);border-radius:2px;font-family:var(--mono);font-size:.8rem;color:var(--ink);background:var(--panel);">
                        </div>
                        <div>
                            <label style="font-family:var(--mono);font-size:.65rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.3rem;">Hrs Per Session</label>
                            <input type="number" name="hr_per_session" min="0.5" max="8" step="0.5" value="2" required style="width:100%;padding:.5rem .7rem;border:1px solid var(--line);border-radius:2px;font-family:var(--mono);font-size:.8rem;color:var(--ink);background:var(--panel);">
                        </div>
                        <div>
                            <label style="font-family:var(--mono);font-size:.65rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.3rem;">Time Between Sessions</label>
                            <input type="text" name="time_between_session" value="1 week" placeholder="e.g. 1 week, 3 days" style="width:100%;padding:.5rem .7rem;border:1px solid var(--line);border-radius:2px;font-family:var(--mono);font-size:.8rem;color:var(--ink);background:var(--panel);">
                        </div>
                        <div>
                            <label style="font-family:var(--mono);font-size:.65rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.3rem;">Mode</label>
                            <div style="display:flex;gap:.5rem;">
                                <label style="flex:1;display:flex;align-items:center;justify-content:center;gap:.35rem;padding:.5rem;border:1px solid var(--line);border-radius:2px;cursor:pointer;font-size:.8rem;font-weight:600;font-family:var(--mono);background:var(--panel);" onclick="selectMode(this,'online')">
                                    <input type="radio" name="online_offline" value="Online" checked style="display:none;">
                                    <i class="bi bi-wifi"></i> Online
                                </label>
                                <label style="flex:1;display:flex;align-items:center;justify-content:center;gap:.35rem;padding:.5rem;border:1px solid var(--line);border-radius:2px;cursor:pointer;font-size:.8rem;font-weight:600;font-family:var(--mono);background:var(--panel);" onclick="selectMode(this,'offline')">
                                    <input type="radio" name="online_offline" value="Offline" style="display:none;">
                                    <i class="bi bi-wifi-off"></i> Offline
                                </label>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom:.75rem;">
                        <label style="font-family:var(--mono);font-size:.65rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.3rem;">Instructor Notes / Focus Areas</label>
                        <textarea name="notes" rows="2" placeholder="Optional: specific skills to cover, learner pain points, or focus areas..." style="width:100%;padding:.5rem .7rem;border:1px solid var(--line);border-radius:2px;font-family:var(--sans);font-size:.8rem;color:var(--ink);background:var(--panel);resize:vertical;"></textarea>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <button type="submit" id="plan-generate-btn" style="display:inline-flex;align-items:center;gap:.45rem;background:var(--teal);color:#fff;border:none;border-radius:2px;font-weight:700;font-size:.8rem;padding:.55rem 1.2rem;cursor:pointer;font-family:var(--sans);">
                            <i class="bi bi-stars"></i> Generate Teaching Plan
                        </button>
                        <span onclick="togglePlanForm()" style="font-size:.75rem;color:var(--muted);cursor:pointer;text-decoration:underline;">Cancel</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="rpt-toolbar">
        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
            <a href="{{ route('team.index') }}" class="rpt-back"><i class="bi bi-arrow-left"></i> Instructor</a>
            <span style="font-size:.74rem;color:var(--muted);font-weight:600;font-family:var(--mono);">{{ $reportDate }}</span>
        </div>
        <div class="rpt-toolbar-actions">
            <form class="rpt-range-form" method="GET" action="{{ route('team.inspector-report') }}">
                @if($deptId)<input type="hidden" name="dept_id" value="{{ $deptId }}">@endif
                <select name="range" onchange="this.form.submit()">
                    <option value="today"  {{ $range==='today'  ? 'selected':'' }}>Today</option>
                    <option value="7days"  {{ $range==='7days'  ? 'selected':'' }}>7 Days</option>
                    <option value="30days" {{ $range==='30days' ? 'selected':'' }}>30 Days</option>
                    <option value="all"    {{ $range==='all'    ? 'selected':'' }}>All Time</option>
                </select>
            </form>
            @if($departments->count())
            <form class="rpt-range-form" method="GET" action="{{ route('team.inspector-report') }}">
                <input type="hidden" name="range" value="{{ $range }}">
                <select name="dept_id" onchange="this.form.submit()">
                    <option value="">All Groups</option>
                    @foreach($departments as $d)
                    <option value="{{ $d->id }}" {{ $deptId == $d->id ? 'selected':'' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </form>
            @endif
            <a href="{{ route('team.inspector-report.download', ['range' => $range, 'dept_id' => $deptId]) }}" class="btn-print" style="background:var(--teal);"><i class="bi bi-filetype-pdf"></i> PDF</a>
        </div>
    </div>

    {{-- Cover --}}
    <div class="rpt-cover">
        <div class="rpt-stamp">DALEEL · TRAINING<br>INTELLIGENCE UNIT</div>
        <div class="rpt-cover-eyebrow">Field Brief</div>
        <h1>Inspector Report</h1>
        <p>What to teach next, what to automate, and where your learners are stuck — in one page.</p>
        <div class="rpt-cover-meta">
            <div class="rpt-cover-meta-item"><span class="rpt-cover-meta-label">Instructor</span><span class="rpt-cover-meta-value">{{ auth()->user()->name }}</span></div>
            <div class="rpt-cover-meta-item"><span class="rpt-cover-meta-label">Group</span><span class="rpt-cover-meta-value">{{ $dept?->name ?? 'All Groups' }}</span></div>
            <div class="rpt-cover-meta-item"><span class="rpt-cover-meta-label">Period</span><span class="rpt-cover-meta-value">
                @if($range==='today') Today @elseif($range==='7days') 7 Days @elseif($range==='30days') 30 Days @else All Time @endif
            </span></div>
            <div class="rpt-cover-meta-item"><span class="rpt-cover-meta-label">Learners</span><span class="rpt-cover-meta-value">{{ $activeLearners }}/{{ $totalLearners }}</span></div>
            <div class="rpt-cover-meta-item"><span class="rpt-cover-meta-label">Active</span><span class="rpt-cover-meta-value">{{ $totalActiveHours }}h</span></div>
        </div>
    </div>

    <div class="two-col">
        {{-- Funnel --}}
        <div class="rpt-card">
            <div class="rpt-card-head"><div><h3><i class="bi bi-compass" style="color:var(--brass);"></i> Engagement</h3><p>Assigned → active → tool use</p></div></div>
            <div class="rpt-card-body">
                <div class="dial-row">
                    <div class="dial"><div class="dial-ring" style="background:conic-gradient(var(--brass) 360deg,var(--line) 0);"><span>{{ $funnel['assigned'] }}</span></div><div class="dial-label">Assigned</div></div>
                    <div class="dial-arrow">→</div>
                    @php $p1 = $funnel['assigned']>0 ? round($funnel['activated']/$funnel['assigned']*360) : 0; @endphp
                    <div class="dial"><div class="dial-ring" style="background:conic-gradient(var(--teal) {{ $p1 }}deg,var(--line) 0);"><span>{{ $funnel['activated'] }}</span></div><div class="dial-label">Active</div></div>
                    <div class="dial-arrow">→</div>
                    @php $p2 = $funnel['assigned']>0 ? round($funnel['started']/$funnel['assigned']*360) : 0; @endphp
                    <div class="dial"><div class="dial-ring" style="background:conic-gradient(var(--rust) {{ $p2 }}deg,var(--line) 0);"><span>{{ $funnel['started'] }}</span></div><div class="dial-label">Used Tools</div></div>
                </div>
                @php $dropOff = $totalLearners - $activeLearners; @endphp
                @if($dropOff > 0)
                <div class="flag warn"><i class="bi bi-exclamation-triangle-fill"></i> {{ $dropOff }} learner{{ $dropOff > 1 ? 's' : '' }} inactive — send a check-in.</div>
                @else
                <div class="flag ok"><i class="bi bi-check-circle-fill"></i> Full participation this period.</div>
                @endif
            </div>
        </div>

        {{-- Top Tools --}}
        <div class="rpt-card">
            <div class="rpt-card-head"><div><h3><i class="bi bi-bar-chart-fill" style="color:var(--teal);"></i> Top Tools</h3><p>Where time actually goes</p></div><span class="badge-measured">Measured</span></div>
            <div class="rpt-card-body" style="padding-top:.5rem;padding-bottom:.3rem;">
                @forelse($topTools->take(6) as $i => $site)
                @php $pct = $totalToolMs > 0 ? round($site->active_ms / $totalToolMs * 100) : 0; $barColor = $site->is_ai_tool ? 'var(--brass)' : 'var(--teal)'; @endphp
                <div class="tool-row">
                    <div class="tool-rank {{ $i < 3 ? 'top' : '' }}">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="tool-name">{{ $site->tool_name ?? ucfirst(explode('.', $site->domain)[0] ?? $site->domain) }}</div>
                        <div class="tool-domain">{{ $site->domain }}</div>
                    </div>
                    @if($site->is_ai_tool)<span class="tag-ai">AI</span>@else<span class="tag-tool">{{ $site->category ?? 'Tool' }}</span>@endif
                    <div class="tool-bar-wrap"><div class="tool-bar" style="width:{{ $pct }}%;background:{{ $barColor }};"></div></div>
                    <div class="tool-time">{{ $msLbl($site->active_ms) }}</div>
                </div>
                @empty
                <div class="empty-rpt"><i class="bi bi-grid-3x3-gap"></i><h6>No tool data yet</h6><p>Connect the Daleel extension.</p></div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 2. AI Tool Upgrades --}}
    <p class="rpt-section-title">Recommended AI Upgrades</p>
    <p class="rpt-hint">Same tools learners already use — with an AI layer added.</p>
    @if(count($aiRecs))
    <div class="rec-grid">
        @foreach($aiRecs as $rec)
        <div class="rec-card">
            <div class="rec-card-top">
                <div class="rec-icon" style="background:{{ $rec['bg'] }};color:{{ $rec['color'] }};"><i class="bi {{ $rec['icon'] ?? 'bi-stars' }}"></i></div>
                <div><div class="rec-card-title">{{ $rec['tool'] }}</div><div class="rec-card-sub">{{ ($rec['is_ai'] ?? false) ? 'AI Tool' : 'Upgrade available' }}</div></div>
            </div>
            <div class="rec-card-body">
                <div class="rec-reason">{{ $rec['ai'] }}</div>
                @if(isset($rec['upgrade_name']) && isset($rec['upgrade_url']))
                <div style="border-top:1px dashed var(--line);padding-top:0.6rem;display:flex;align-items:center;justify-content:space-between;margin-top:0.8rem;">
                    <span style="font-size:0.68rem;color:var(--muted);font-family:var(--mono);">Upgrade Path</span>
                    <a href="{{ $rec['upgrade_url'] }}" target="_blank" class="upgrade-link-anchor" style="font-size:0.75rem;font-weight:700;color:var(--brass);text-decoration:none;display:inline-flex;align-items:center;gap:0.25rem;">
                        {{ $rec['upgrade_name'] }} <i class="bi bi-box-arrow-up-right" style="font-size:0.68rem;"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="rpt-card"><div class="empty-rpt"><i class="bi bi-robot"></i><h6>No AI recs yet</h6><p>Connect extensions to unlock this.</p></div></div>
    @endif

    {{-- 3. Teaching Plan (generated) --}}
    <div id="plan-results" style="margin-bottom:1.25rem;"></div>

    {{-- 4. Time Saves --}}
    <p class="rpt-section-title">Time-Saving Opportunities</p>
    @if(count($timeSaves))
    <div class="timesave-list">
        @foreach($timeSaves as $i => $rec)
        @if($i >= 5) @break @endif
        <div class="timesave-item">
            <div class="timesave-icon" style="background:{{ $rec['bg'] }};color:{{ $rec['color'] }};"><i class="bi {{ $rec['icon'] ?? 'bi-lightning' }}"></i></div>
            <div style="flex:1;min-width:0;"><div class="timesave-title">{{ $rec['save'] }}</div><div class="timesave-impact">~20–45 min/week saved</div></div>
            <div style="text-align:right;flex-shrink:0;font-family:var(--mono);font-size:.7rem;color:var(--muted);">{{ $rec['tool'] }}</div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- 6. Coaching --}}
    <p class="rpt-section-title">Coaching Principles</p>
    <div class="rpt-card">
        <div class="rpt-card-body">
            @foreach($coachingTips as $i => $tip)
            <div class="tip-row">
                <div class="tip-num">{{ sprintf('%02d', $i+1) }}</div>
                <div><div class="tip-title">{{ $tip['title'] }}</div><div class="tip-desc">{{ $tip['desc'] }}</div></div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- {{-- 7. Friction --}}
    @if($frictionData->count())
    <p class="rpt-section-title">Friction Signals</p>
    <div class="rpt-card">
        <div class="rpt-card-head"><div><h3><i class="bi bi-exclamation-triangle-fill" style="color:var(--rust);"></i> Repeated Sessions</h3><p>Same domain, many visits — likely confusion</p></div><span class="badge-inferred">Inferred</span></div>
        <div class="rpt-card-body" style="padding:0;">
            <div style="overflow-x:auto;">
            <table class="rpt-table">
                <thead><tr><th>Domain</th><th>Sessions</th><th>Avg Time</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($frictionData as $fd)
                    <tr>
                        <td style="font-weight:700;color:var(--ink);">{{ $fd['domain'] }}</td>
                        <td style="font-family:var(--mono);font-weight:700;color:var(--brass);">{{ $fd['frequency'] }}</td>
                        <td>{{ $msLbl($fd['avg_ms']) }}</td>
                        <td>
                            @if($fd['frequency'] > 20)<span style="color:var(--rust);font-weight:700;font-size:.76rem;">Run a clinic</span>
                            @elseif($fd['frequency'] > 10)<span style="color:var(--brass);font-weight:700;font-size:.76rem;">Add to agenda</span>
                            @else<span style="color:var(--muted);font-size:.76rem;">Monitor</span>@endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
    @endif -->

    {{-- Privacy --}}
    <div class="privacy-notice" style="margin-top:1.2rem;">
        <i class="bi bi-shield-lock-fill"></i>
        <div>Aggregated work-browser data. Inferred gaps need instructor sign-off before sharing. Groups under 3 people: no individual comparisons.</div>
    </div>

</div>{{-- /rpt-shell --}}

@section('scripts')
<script>
function togglePlanForm() {
    const body = document.getElementById('plan-form-body');
    const icon = document.getElementById('plan-toggle-icon');
    if (body.style.display === 'none') {
        body.style.display = 'block';
        icon.innerHTML = '<i class="bi bi-chevron-up"></i>';
    } else {
        body.style.display = 'none';
        icon.innerHTML = '<i class="bi bi-chevron-down"></i>';
    }
}

function selectMode(el, mode) {
    document.querySelectorAll('#plan-form-body label[onclick*="selectMode"]').forEach(l => {
        l.style.borderColor = 'var(--line)';
        l.style.background = 'var(--panel)';
    });
    el.style.borderColor = mode === 'online' ? 'var(--teal)' : 'var(--rust)';
    el.style.background = mode === 'online' ? 'var(--teal-soft)' : 'var(--rust-soft)';
    el.querySelector('input').checked = true;
}

async function generatePlan(e) {
    e.preventDefault();
    const form = document.getElementById('plan-form');
    const btn = document.getElementById('plan-generate-btn');
    const status = document.getElementById('plan-status');
    const results = document.getElementById('plan-results');

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Generating…';
    status.textContent = 'Calling AI…';
    results.style.display = 'none';

    const formData = new FormData(form);

    try {
        const resp = await fetch('{{ route('team.inspector-report.generate-plan') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
            body: formData
        });
        const data = await resp.json();

        if (data.error) {
            status.textContent = 'Error: ' + data.error;
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-stars"></i> Generate Teaching Plan';
            return;
        }

        status.textContent = '✅ Plan generated';
        const planData = {
            sessions: data.sessions,
            totalSessions: formData.get('total_session'),
            hrsPerSession: formData.get('hr_per_session'),
            timeBetween: formData.get('time_between_session'),
            mode: formData.get('online_offline'),
            notes: formData.get('notes')
        };
        renderPlan(planData, results);
        results.style.display = 'block';
        results.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Update PDF download link to include plan params
        const pdfLink = document.querySelector('a[href*="inspector-report/download"]');
        if (pdfLink) {
            const url = new URL(pdfLink.href);
            url.searchParams.set('total_session', formData.get('total_session'));
            url.searchParams.set('hr_per_session', formData.get('hr_per_session'));
            url.searchParams.set('time_between_session', formData.get('time_between_session'));
            url.searchParams.set('online_offline', formData.get('online_offline'));
            url.searchParams.set('notes', formData.get('notes'));
            pdfLink.href = url.toString();
            pdfLink.style.background = 'var(--brass)';
        }
    } catch (err) {
        status.textContent = 'Request failed: ' + err.message;
    }

    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-stars"></i> Generate Teaching Plan';
}

function renderPlan(plan, container) {
    const sessions = plan.sessions;
    const hrsPerSession = parseFloat(plan.hrsPerSession) || 2;
    const totalMinutes = Math.round(hrsPerSession * 60);

    let html = `
    <div class="rpt-card">
        <div class="rpt-card-head" style="background:var(--ink);color:#fff;border-bottom-color:#333;">
            <div><h3 style="color:#fff;"><i class="bi bi-mortarboard-fill" style="color:var(--brass);"></i> Teaching Guidance Plan</h3>
            <p style="color:#B9B4A4;">${sessions.length} session${sessions.length > 1 ? 's' : ''} · ${plan.mode} · ${totalMinutes} min each</p></div>
            <span class="badge-inferred" style="border-color:#8b8577;">AI Generated</span>
        </div>
        <div class="rpt-card-body" style="padding:0;">`;

    sessions.forEach((s, i) => {
        const topicCount = (s.items || []).length;
        const minPerTopic = topicCount > 0 ? Math.round(totalMinutes / topicCount) : 0;
        const tools = extractTools(s.items);

        html += `
        <div style="padding:1rem 1.2rem;${i > 0 ? 'border-top:1px solid var(--line);' : ''}">
            <div style="display:flex;align-items:center;gap:.65rem;margin-bottom:.3rem;">
                <span class="week-badge wk${Math.min(i, 3) + 1}" style="border-radius:50%;width:28px;height:28px;display:flex;align-items:center;justify-content:center;font-size:.72rem;padding:0;">${s.session_number}</span>
                <span style="font-weight:700;font-size:.9rem;color:var(--ink);font-family:var(--serif);">${s.title || 'Session ' + s.session_number}</span>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:.35rem .75rem;margin:0 0 .65rem 2.4rem;font-size:.7rem;font-family:var(--mono);color:var(--muted);">
                <span><span style="font-weight:700;color:var(--ink);">${totalMinutes}m</span> total time</span>
                <span><span style="font-weight:700;color:var(--ink);">${topicCount}</span> topic${topicCount > 1 ? 's' : ''}</span>
                <span><span style="font-weight:700;color:var(--teal);">~${minPerTopic}m</span> per topic</span>
                ${tools.length ? '<span>Tools: <span style="font-weight:700;color:var(--brass);">' + tools.join(', ') + '</span></span>' : ''}
            </div>
            <ul style="margin:0;padding-left:2.4rem;list-style:none;">`;
        (s.items || []).forEach(item => {
            const display = item.replace(/^\*\s*/, '');
            html += `<li style="font-size:.8rem;color:var(--ink-soft);padding:.25rem 0;position:relative;">
                <span style="position:absolute;left:-1.2rem;top:.3rem;width:6px;height:6px;border-radius:50%;background:var(--teal);"></span>
                ${display}
            </li>`;
        });
        html += `</ul></div>`;
    });

    html += `
        </div>
        <div style="padding:.6rem 1.2rem;border-top:1px solid var(--line);display:flex;flex-wrap:wrap;align-items:center;gap:.75rem;">
            <span style="font-size:.7rem;color:var(--muted);display:flex;align-items:center;gap:.35rem;"><i class="bi bi-info-circle-fill" style="color:var(--brass);"></i> Review and adjust before sharing.</span>
            ${plan.notes ? '<span style="font-size:.7rem;color:var(--muted);font-family:var(--mono);">📝 ' + plan.notes + '</span>' : ''}
        </div>
    </div>`;

    container.innerHTML = html;
}

function extractTools(items) {
    const toolSet = new Set();
    (items || []).forEach(item => {
        const clean = item.replace(/^\*\s*/, '');
        const match = clean.match(/^([^-]+?)\s*[-–—]/);
        if (match) {
            toolSet.add(match[1].trim());
        } else {
            const firstWord = clean.split(' ')[0];
            if (firstWord) toolSet.add(firstWord);
        }
    });
    return Array.from(toolSet);
}
</script>
@endsection
@endsection