<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Inspector Report — Daleel AI</title>
<style>
body {
    margin: 0;
    padding: 18pt 22pt;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 9pt;
    color: #1a1a1a;
    line-height: 1.5;
}
h1, h2, h3, h4 { margin: 0; }
table { border-collapse: collapse; width: 100%; }

/* Cover */
.cover {
    background: #12151C;
    color: #fff;
    padding: 22pt 24pt 18pt;
    margin-bottom: 14pt;
    border-radius: 3pt;
}
.cover h1 {
    font-size: 20pt;
    font-weight: 700;
    letter-spacing: -0.3pt;
    margin: 0 0 3pt;
}
.cover .sub {
    color: #B9B4A4;
    font-size: 8.5pt;
    margin: 0 0 12pt;
}
.cover-meta { width: 100%; }
.cover-meta td {
    padding: 7pt 12pt 4pt 0;
    vertical-align: top;
    border-top: 1px solid rgba(255,255,255,0.14);
}
.cover-meta td:last-child { padding-right: 0; }
.cover-meta .lbl {
    font-size: 6.5pt;
    color: #8b8577;
    text-transform: uppercase;
    letter-spacing: 1pt;
    display: block;
}
.cover-meta .val {
    font-family: 'Courier New', monospace;
    font-size: 8pt;
    font-weight: 600;
    color: #fff;
    display: block;
    margin-top: 1pt;
}
.stamp {
    text-align: right;
    font-family: 'Courier New', monospace;
    font-size: 6pt;
    color: #8b8577;
    line-height: 1.6;
    position: absolute;
    top: 16pt;
    right: 20pt;
}

/* Section headers */
.sec-title {
    font-family: 'Courier New', monospace;
    font-size: 7pt;
    font-weight: 600;
    color: #767163;
    text-transform: uppercase;
    letter-spacing: 1pt;
    border-bottom: 1px solid #E4DFD1;
    padding-bottom: 4pt;
    margin: 16pt 0 6pt;
}
.sec-title .num { color: #9C6B22; }
.sec-hint {
    font-size: 7.5pt;
    color: #767163;
    margin: -4pt 0 6pt;
}

/* Card */
.card {
    border: 1px solid #E4DFD1;
    border-radius: 3pt;
    margin-bottom: 8pt;
}
.card-hd {
    padding: 6pt 10pt;
    border-bottom: 1px solid #E4DFD1;
    font-weight: 700;
    font-size: 9pt;
}
.card-hd .sub {
    font-size: 7pt;
    color: #767163;
    font-weight: 400;
}
.card-bd {
    padding: 6pt 10pt;
}

/* KPI row */
.kpi-table { margin-bottom: 8pt; }
.kpi-table td {
    padding: 7pt 10pt;
    border: 1px solid #E4DFD1;
    text-align: center;
    width: 25%;
}
.kpi-lbl {
    font-family: 'Courier New', monospace;
    font-size: 6.5pt;
    color: #767163;
    text-transform: uppercase;
    letter-spacing: 0.7pt;
}
.kpi-val {
    font-size: 16pt;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.2;
}

/* Upgrade grid */
.upgrade-table { width: 100%; }
.upgrade-table td {
    width: 33%;
    vertical-align: top;
    padding: 4pt;
}
.upgrade-card {
    border: 1px solid #E4DFD1;
    border-radius: 3pt;
}
.upgrade-hd {
    padding: 5pt 7pt;
    border-bottom: 1px solid #E4DFD1;
    font-weight: 700;
    font-size: 8pt;
}
.upgrade-sub {
    font-family: 'Courier New', monospace;
    font-size: 6pt;
    color: #767163;
}
.upgrade-bd {
    padding: 5pt 7pt 7pt;
    font-size: 7.5pt;
    line-height: 1.4;
}
.upgrade-path {
    border-top: 1px dashed #E4DFD1;
    padding-top: 5pt;
    margin-top: 5pt;
    font-size: 6.5pt;
    color: #9C6B22;
    font-weight: 700;
}
.upgrade-path .lbl {
    color: #767163;
    font-weight: 400;
    font-family: 'Courier New', monospace;
    font-size: 6pt;
}

/* Topic list */
.topic-table td {
    padding: 4pt 8pt;
    border-bottom: 1px solid #E4DFD1;
    vertical-align: middle;
    font-size: 8pt;
}
.topic-table th {
    font-family: 'Courier New', monospace;
    font-size: 6.5pt;
    color: #767163;
    text-transform: uppercase;
    letter-spacing: 0.5pt;
    padding: 4pt 8pt;
    border-bottom: 1px solid #E4DFD1;
    text-align: left;
    font-weight: 600;
    background: #FAF8F2;
}
.topic-num {
    font-family: 'Courier New', monospace;
    display: inline-block;
    width: 18pt;
    height: 18pt;
    border-radius: 50%;
    text-align: center;
    line-height: 18pt;
    font-size: 7pt;
    font-weight: 700;
    border: 1px solid;
}
.wk1 { background: #DFEEE9; color: #0E6B5C; border-color: #b9dcd3; }
.wk2 { background: #F1E6D2; color: #9C6B22; border-color: #e3cfa4; }
.wk3 { background: #EDE7F5; color: #5B3F94; border-color: #d7c9ec; }
.wk4 { background: #F6E4DC; color: #AE4426; border-color: #e8c2b0; }
.badge-live, .badge-workshop, .badge-clinic, .badge-lab {
    font-family: 'Courier New', monospace;
    font-size: 6.5pt;
    font-weight: 700;
    padding: 1pt 6pt;
    border-radius: 2pt;
    border: 1px solid;
}
.badge-live     { background: #DFEEE9; color: #0E6B5C; border-color: #b9dcd3; }
.badge-workshop { background: #F1E6D2; color: #9C6B22; border-color: #e3cfa4; }
.badge-clinic   { background: #EDE7F5; color: #5B3F94; border-color: #d7c9ec; }
.badge-lab      { background: #F6E4DC; color: #AE4426; border-color: #e8c2b0; }

/* Time-save list */
.ts-item {
    padding: 5pt 8pt;
    border: 1px solid #E4DFD1;
    border-radius: 3pt;
    margin-bottom: 4pt;
}
.ts-title { font-weight: 700; font-size: 8pt; }
.ts-impact { font-family: 'Courier New', monospace; font-size: 6.5pt; font-weight: 600; color: #0E6B5C; }
.ts-tool {
    text-align: right;
    font-family: 'Courier New', monospace;
    font-size: 6.5pt;
    color: #767163;
}
.ts-icon {
    width: 22pt;
    height: 22pt;
    border-radius: 2pt;
    text-align: center;
    line-height: 22pt;
    font-size: 9pt;
}

/* Coaching */
.tip-table td {
    padding: 5pt 0;
    border-bottom: 1px solid #E4DFD1;
    vertical-align: top;
    font-size: 8pt;
}
.tip-table tr:last-child td { border-bottom: none; }
.tip-num {
    font-family: 'Courier New', monospace;
    font-size: 7.5pt;
    font-weight: 700;
    color: #9C6B22;
    width: 20pt;
}
.tip-title { font-weight: 700; }
.tip-desc { color: #767163; font-size: 7pt; margin-top: 1pt; }



/* Privacy */
.privacy {
    background: #FAF8F2;
    border: 1px solid #E4DFD1;
    border-radius: 3pt;
    padding: 6pt 9pt;
    font-size: 7pt;
    color: #767163;
    margin-top: 12pt;
}

/* Empty state */
.empty {
    text-align: center;
    padding: 14pt 10pt;
    color: #767163;
}
.empty h6 {
    font-weight: 700;
    color: #1a1a1a;
    font-size: 9pt;
    margin: 0 0 2pt;
}
.empty p { font-size: 7.5pt; margin: 0; }
</style>
</head>
<body>

@php
    $msLbl         = $ms;
    $totalToolMs   = $topTools->sum('active_ms');
    $activePct     = $totalLearners > 0 ? round($activeLearners / $totalLearners * 100) : 0;

    $domainMap = [
        'sheets.google.com'   => ['tool'=>'Google Sheets',    'upgrade_name'=>'Julius AI',      'upgrade_url'=>'https://julius.ai',             'ai'=>'Julius AI — Writes spreadsheet formulas and analyzes data.', 'topic'=>'Data analyst workflows',  'save'=>'Julius AI auto-builds dashboard formulas', 'color'=>'#0E6B5C','bg'=>'#DFEEE9'],
        'excel.office.com'    => ['tool'=>'Excel Online',     'upgrade_name'=>'Julius AI',      'upgrade_url'=>'https://julius.ai',             'ai'=>'Julius AI — Writes spreadsheet formulas and analyzes data.', 'topic'=>'Data analyst workflows',  'save'=>'Julius AI auto-builds dashboard formulas', 'color'=>'#0E6B5C','bg'=>'#DFEEE9'],
        'docs.google.com'     => ['tool'=>'Google Docs',      'upgrade_name'=>'Lex',            'upgrade_url'=>'https://lex.page',              'ai'=>'Lex — AI word processor that drafts and refines long files.', 'topic'=>'AI-assisted drafting',    'save'=>'Lex.im generates proposal drafts locally', 'color'=>'#2563eb','bg'=>'#dbeafe'],
        'gmail.com'           => ['tool'=>'Gmail',            'upgrade_name'=>'Shortwave',      'upgrade_url'=>'https://www.shortwave.com',     'ai'=>'Shortwave — Drafts instant email replies and summaries.', 'topic'=>'AI-assisted sales writing',   'save'=>'Lavender scores response likelihood',    'color'=>'#AE4426','bg'=>'#F6E4DC'],
        'mail.google.com'     => ['tool'=>'Gmail',            'upgrade_name'=>'Shortwave',      'upgrade_url'=>'https://www.shortwave.com',     'ai'=>'Shortwave — Drafts instant email replies and summaries.', 'topic'=>'AI-assisted sales writing',   'save'=>'Lavender scores response likelihood',    'color'=>'#AE4426','bg'=>'#F6E4DC'],
        'canva.com'           => ['tool'=>'Canva',            'upgrade_name'=>'Gamma App',      'upgrade_url'=>'https://gamma.app',             'ai'=>'Gamma App — Automatically builds styled presentations from text.', 'topic'=>'Interactive pitch design', 'save'=>'Gamma builds web slide deck from prompt', 'color'=>'#5B3F94','bg'=>'#EDE7F5'],
        'chat.openai.com'     => ['tool'=>'ChatGPT',          'upgrade_name'=>'Claude Projects','upgrade_url'=>'https://claude.ai',             'ai'=>'Claude Projects — Pins documents to automate context.', 'topic'=>'Structured code prompting', 'save'=>'Build custom instructions to avoid re-prompting', 'color'=>'#0ea5e9','bg'=>'#e0f2fe'],
        'chatgpt.com'         => ['tool'=>'ChatGPT',          'upgrade_name'=>'Claude Projects','upgrade_url'=>'https://claude.ai',             'ai'=>'Claude Projects — Pins documents to automate context.', 'topic'=>'Structured code prompting', 'save'=>'Build custom instructions to avoid re-prompting', 'color'=>'#0ea5e9','bg'=>'#e0f2fe'],
        'notion.so'           => ['tool'=>'Notion',           'upgrade_name'=>'Notion AI',      'upgrade_url'=>'https://www.notion.so/product/ai','ai'=>'Notion AI — Summarizes project notes and team wikis.', 'topic'=>'Workspace central database','save'=>'Auto-summarise team project notes',        'color'=>'#4A4638','bg'=>'#EFECE2'],
        'figma.com'           => ['tool'=>'Figma',            'upgrade_name'=>'Relume',         'upgrade_url'=>'https://www.relume.io',         'ai'=>'Relume — Instantly builds design layouts and wireframes.', 'topic'=>'Interactive wireframing',  'save'=>'Relume builds full landing designs',       'color'=>'#9C6B22','bg'=>'#F1E6D2'],
        'github.com'          => ['tool'=>'GitHub',           'upgrade_name'=>'Cursor IDE',     'upgrade_url'=>'https://www.cursor.com',        'ai'=>'Cursor IDE — Edits code across multiple files in seconds.', 'topic'=>'Multi-file local coding',  'save'=>'Cursor indexes directory for rapid refactor', 'color'=>'#12151C','bg'=>'#EFECE2'],
        'trello.com'          => ['tool'=>'Trello',           'upgrade_name'=>'Motion',         'upgrade_url'=>'https://www.usemotion.com',     'ai'=>'Motion — Auto-schedules calendar tasks to meet deadlines.', 'topic'=>'AI calendar prioritisation','save'=>'Motion automatically fills schedule buffers','color'=>'#0ea5e9','bg'=>'#e0f2fe'],
        'slack.com'           => ['tool'=>'Slack',            'upgrade_name'=>'Slack AI',       'upgrade_url'=>'https://slack.com/features/ai', 'ai'=>'Slack AI — Summarizes long channel threads and updates.', 'topic'=>'Async channel analysis',   'save'=>'Digests long discussion threads in 5s',    'color'=>'#5B3F94','bg'=>'#EDE7F5'],
        'zapier.com'          => ['tool'=>'Zapier',           'upgrade_name'=>'Skyvern',        'upgrade_url'=>'https://www.skyvern.com',        'ai'=>'Skyvern — Automates clicks and details on websites.', 'topic'=>'AI-based UI interaction Automation', 'save'=>'Extract data using LLM browser clicks',  'color'=>'#9C6B22','bg'=>'#F1E6D2'],
        'make.com'            => ['tool'=>'Make',             'upgrade_name'=>'Skyvern',        'upgrade_url'=>'https://www.skyvern.com',        'ai'=>'Skyvern — Automates clicks and details on websites.', 'topic'=>'AI-based UI interaction Automation', 'save'=>'Extract data using LLM browser clicks',  'color'=>'#5B3F94','bg'=>'#EDE7F5'],
        'zoom.us'             => ['tool'=>'Zoom',             'upgrade_name'=>'Fathom',         'upgrade_url'=>'https://fathom.video',          'ai'=>'Fathom — Transcribes calls and extracts action items.', 'topic'=>'Action item detection',    'save'=>'Fathom flags key timelines in transcript',  'color'=>'#2563eb','bg'=>'#dbeafe'],
        'meet.google.com'     => ['tool'=>'Google Meet',      'upgrade_name'=>'Fathom',         'upgrade_url'=>'https://fathom.video',          'ai'=>'Fathom — Transcribes calls and extracts action items.', 'topic'=>'Action item detection',    'save'=>'Fathom flags key timelines in transcript',  'color'=>'#0E6B5C','bg'=>'#DFEEE9'],
        'office.com'          => ['tool'=>'Microsoft 365',    'upgrade_name'=>'Copilot Pro',    'upgrade_url'=>'https://copilot.microsoft.com', 'ai'=>'Copilot Pro — Native AI that formats slides and documents.', 'topic'=>'Integrated slide design', 'save'=>'Copilot formats presentation layout styles',  'color'=>'#0284c7','bg'=>'#e0f2fe'],
        'stackoverflow.com'   => ['tool'=>'Stack Overflow',   'upgrade_name'=>'Cursor IDE',     'upgrade_url'=>'https://www.cursor.com',        'ai'=>'Cursor IDE — Edits code across multiple files in seconds.', 'topic'=>'Multi-file local coding',  'save'=>'Cursor indexes directory for rapid refactor', 'color'=>'#9C6B22','bg'=>'#F1E6D2'],
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
            ['tool'=>'ChatGPT','upgrade_name'=>'Claude Projects','upgrade_url'=>'https://claude.ai','ai'=>'Claude Projects — Pins documents to automate context.','topic'=>'Structured code prompting','save'=>'Build custom instructions to avoid re-prompting','color'=>'#0ea5e9','bg'=>'#e0f2fe','is_ai'=>true],
            ['tool'=>'Google Sheets','upgrade_name'=>'Julius AI','upgrade_url'=>'https://julius.ai','ai'=>'Julius AI — Writes spreadsheet formulas and analyzes data.','topic'=>'Spreadsheet analytics','save'=>'Rows.com auto-builds dashboard formulas','color'=>'#0E6B5C','bg'=>'#DFEEE9','is_ai'=>false],
            ['tool'=>'Notion','upgrade_name'=>'Notion AI','upgrade_url'=>'https://www.notion.so/product/ai','ai'=>'Notion AI — Summarizes project notes and team wikis.','topic'=>'Workspace central database','save'=>'Auto-summarise team project notes','color'=>'#4A4638','bg'=>'#EFECE2','is_ai'=>false],
        ];
        $topicRecs = $aiRecs; $timeSaves = $aiRecs;
    }

    $coachingTips = [
        ['title'=>'Apply within 24 hours', 'desc'=>'Assign a real task right after every session — retention nearly doubles.'],
        ['title'=>'Repeat, don\'t re-teach', 'desc'=>'Revisit the same tool briefly in Week 2 instead of one long class.'],
        ['title'=>'Pair strong with struggling', 'desc'=>'Match your top performers with lower-engagement learners on one task.'],
        ['title'=>'Ask before you teach', 'desc'=>'Open with "what did you try this week?" — teach to the real friction.'],
        ['title'=>'Check output, not quizzes', 'desc'=>'Ask for one report made with the tool — that proves the skill transferred.'],
        ['title'=>'Name the time saved', 'desc'=>'"This saves you X min/week on Y" — concrete benefit drives engagement.'],
    ];
@endphp

<div style="max-width:1000px;margin:0 auto;position:relative;">

    {{-- Cover --}}
    <div class="cover">
        <div class="stamp">DALEEL · TRAINING<br>INTELLIGENCE UNIT</div>
        <h1>Inspector Report</h1>
        <p class="sub">What to teach next, what to automate, and where your learners are stuck.</p>
        <table class="cover-meta">
            <tr>
                <td><span class="lbl">Instructor</span><span class="val">{{ auth()->user()->name }}</span></td>
                <td><span class="lbl">Group</span><span class="val">{{ $dept?->name ?? 'All Groups' }}</span></td>
                <td><span class="lbl">Period</span><span class="val">@if($range==='today') Today @elseif($range==='7days') 7 Days @elseif($range==='30days') 30 Days @else All Time @endif</span></td>
                <td><span class="lbl">Learners</span><span class="val">{{ $activeLearners }}/{{ $totalLearners }}</span></td>
                <td><span class="lbl">Active</span><span class="val">{{ $totalActiveHours }}h</span></td>
            </tr>
        </table>
    </div>

    {{-- KPI row --}}
    <table class="kpi-table">
        <tr>
            <td><div class="kpi-lbl">Total Learners</div><div class="kpi-val">{{ $totalLearners }}</div></td>
            <td><div class="kpi-lbl">Active</div><div class="kpi-val">{{ $activeLearners }}</div></td>
            <td><div class="kpi-lbl">Active Hours</div><div class="kpi-val">{{ $totalActiveHours }}h</div></td>
            <td><div class="kpi-lbl">Engagement</div><div class="kpi-val">{{ $activePct }}%</div></td>
        </tr>
    </table>

    {{-- Top Tools (full width) --}}
    <div class="card">
        <div class="card-hd">Top Tools <span class="sub">Where time actually goes</span></div>
        <div style="padding:2pt 6pt 2pt 6pt;">
            @if($topTools->count())
            <table style="width:100%;border-collapse:collapse;">
                @foreach($topTools->take(6) as $i => $site)
                @php $pct = $totalToolMs > 0 ? round($site->active_ms / $totalToolMs * 100) : 0; $barColor = $site->is_ai_tool ? '#9C6B22' : '#0E6B5C'; @endphp
                <tr>
                    <td width="16" style="font-family:'Courier New',monospace;font-size:7pt;font-weight:700;color:{{ $i < 3 ? '#9C6B22' : '#767163' }};padding:3pt 0;border-bottom:1px solid #E4DFD1;">{{ $i+1 }}</td>
                    <td style="padding:3pt 0;border-bottom:1px solid #E4DFD1;">
                        <div style="font-weight:700;font-size:8pt;">{{ $site->tool_name ?? ucfirst(explode('.', $site->domain)[0] ?? $site->domain) }}</div>
                        <div style="font-size:6.5pt;color:#767163;font-family:'Courier New',monospace;">{{ $site->domain }}</div>
                    </td>
                    <td style="white-space:nowrap;padding:3pt 0 3pt 6pt;border-bottom:1px solid #E4DFD1;vertical-align:middle;">
                        @if($site->is_ai_tool)<span style="font-family:'Courier New',monospace;font-size:6pt;font-weight:700;padding:1pt 5pt;border-radius:2pt;background:#F1E6D2;color:#9C6B22;">AI</span>
                        @else<span style="font-family:'Courier New',monospace;font-size:6pt;font-weight:700;padding:1pt 5pt;border-radius:2pt;background:#EFECE2;color:#4A4638;">{{ $site->category ?? 'Tool' }}</span>@endif
                    </td>
                    <td style="padding:3pt 0 3pt 4pt;border-bottom:1px solid #E4DFD1;vertical-align:middle;">
                        <div style="width:50pt;height:3pt;background:#E4DFD1;"><div style="width:{{ $pct }}%;height:3pt;background:{{ $barColor }};"></div></div>
                    </td>
                    <td style="font-family:'Courier New',monospace;font-size:7pt;font-weight:700;text-align:right;white-space:nowrap;padding:3pt 0 3pt 4pt;border-bottom:1px solid #E4DFD1;vertical-align:middle;">{{ $msLbl($site->active_ms) }}</td>
                </tr>
                @endforeach
            </table>
            @else
            <div class="empty"><h6>No tool data yet</h6><p>Connect the Daleel extension.</p></div>
            @endif
        </div>
    </div>

    {{-- 02. AI Upgrades --}}
    <div class="sec-title">Recommended AI Upgrades</div>
    <div class="sec-hint">Same tools learners already use — with an AI layer added.</div>
    @if(count($aiRecs))
    <table class="upgrade-table">
        <tr>
            @foreach($aiRecs as $i => $rec)
            @if($i > 0 && $i % 3 == 0)</tr><tr>@endif
            <td>
                <div class="upgrade-card">
                    <div class="upgrade-hd">
                        <!-- <span style="display:inline-block;width:18pt;height:18pt;border-radius:2pt;background:{{ $rec['bg'] }};color:{{ $rec['color'] }};text-align:center;line-height:18pt;font-size:9pt;margin-right:4pt;"></span> -->
                        {{ $rec['tool'] }}<div class="upgrade-sub">{{ ($rec['is_ai'] ?? false) ? 'AI Tool' : 'Upgrade available' }}</div>
                    </div>
                    <div class="upgrade-bd">
                        <div>{{ $rec['ai'] }}</div>
                        @if(isset($rec['upgrade_name']))
                        <div class="upgrade-path"><span class="lbl">Upgrade: </span>{{ $rec['upgrade_name'] }}</div>
                        @endif
                    </div>
                </div>
            </td>
            @endforeach
            @php $remain = 3 - (count($aiRecs) % 3); if($remain < 3) { for($j=0;$j<$remain;$j++) { echo '<td></td>'; } } @endphp
        </tr>
    </table>
    @else
    <div class="card"><div class="empty"><h6>No AI recs yet</h6><p>Connect extensions to unlock this.</p></div></div>
    @endif

    <div style="page-break-before:always;"></div>

    {{-- 03. Teaching Plan --}}
    @if(!empty($planSessions))
    <div class="sec-title">Teaching Plan</div>
    <div class="card">
        <table style="width:100%;border-collapse:collapse;">
            @foreach($planSessions as $i => $s)
            <tr>
                <td colspan="2" style="padding:5pt 8pt 3pt;border-bottom:1px solid #E4DFD1;{{ $i > 0 ? '' : '' }}">
                    <span class="topic-num wk{{ min($i,3)+1 }}">{{ $s['session_number'] }}</span>
                    <strong style="font-size:9pt;margin-left:4pt;">{{ $s['title'] ?? ('Session ' . $s['session_number']) }}</strong>
                </td>
            </tr>
            @foreach($s['items'] ?? [] as $item)
            <tr>
                <td width="20" style="padding:2pt 4pt 2pt 16pt;border-bottom:none;font-size:7pt;color:#0E6B5C;">▸</td>
                <td style="padding:2pt 8pt 2pt 0;border-bottom:none;font-size:8pt;">{{ ltrim($item, '* ') }}</td>
            </tr>
            @endforeach
            @endforeach
        </table>
    </div>
    @endif

    {{-- 04. Time Saves --}}
    <div class="sec-title">Time-Saving Opportunities</div>
    @if(count($timeSaves))
    @foreach($timeSaves as $i => $rec)
    @if($i >= 5) @break @endif
    <table class="ts-item" style="width:100%;">
        <tr>
            <td width="26" style="vertical-align:middle;padding-right:6pt;">
                <div class="ts-icon" style="background:{{ $rec['bg'] }};color:{{ $rec['color'] }};">*</div>
            </td>
            <td style="vertical-align:middle;">
                <div class="ts-title">{{ $rec['save'] }}</div>
                <div class="ts-impact">~20–45 min/week saved</div>
            </td>
            <td class="ts-tool" width="80" style="vertical-align:middle;">{{ $rec['tool'] }}</td>
        </tr>
    </table>
    @endforeach
    @endif

    {{-- 06. Coaching --}}
    <div class="sec-title">Coaching Principles</div>
    <div class="card">
        <table class="tip-table">
            @foreach($coachingTips as $i => $tip)
            <tr>
                <td class="tip-num" width="20">&nbsp;{{ sprintf('%02d', $i+1) }}</td>
                <td><div class="tip-title"> {{ $tip['title'] }}</div><div class="tip-desc">{{ $tip['desc'] }}</div></td>
            </tr>
            @endforeach
        </table>
    </div>

    {{-- Privacy --}}
    <div class="privacy">Aggregated work-browser data. Inferred gaps need instructor sign-off before sharing. Groups under 3 people: no individual comparisons.</div>

</div>
</body>
</html>
