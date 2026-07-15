@php
    $msLabel = function($v) {
        $v = max(0, intval($v));
        if ($v < 60000) return round($v / 1000) . 's';
        $m = floor($v / 60000);
        if ($m < 60) return $m . 'm';
        return floor($m / 60) . 'h ' . ($m % 60) . 'm';
    };
    $colors = ['#4f46e5','#0ea5e9','#10b981','#f59e0b','#ec4899','#8b5cf6','#ef4444','#14b8a6'];
@endphp

@if(empty($rows))
    <div class="rpt-empty">
        <i class="bi bi-bar-chart-line"></i>
        <h6>No data for this period</h6>
        <p>Extension activity will appear here once students start using it.</p>
    </div>
@else

{{-- GROUP REPORTS --}}
@if($type === 'group-tools' || $type === 'group-sites')
    @foreach($rows as $gi => $group)
        @if(!empty($group['sites']))
        <div class="rpt-group-block">
            <div class="rpt-group-header">
                <span class="rpt-group-dot" style="background:{{ $colors[$gi % count($colors)] }}">
                    {{ strtoupper(substr($group['dept'], 0, 1)) }}
                </span>
                <div>
                    <div class="rpt-group-name">{{ $group['dept'] }}</div>
                    <div class="rpt-group-meta">{{ count($group['sites']) }} {{ $type === 'group-tools' ? 'tools' : 'sites' }} · Total: {{ $msLabel($group['total_ms']) }}</div>
                </div>
                <span class="rpt-group-count">{{ $group['members'] }} {{ Str::plural('member', $group['members']) }}</span>
            </div>
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ $type === 'group-tools' ? 'Tool' : 'Site' }}</th>
                        <th>Category</th>
                        <th>Time Spent</th>
                        <th>Sessions</th>
                        <th>Usage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($group['sites'] as $i => $site)
                        @php $pct = $group['total_ms'] > 0 ? ($site->active_ms / $group['total_ms']) * 100 : 0; @endphp
                        <tr>
                            <td><span class="rpt-rank {{ $i < 3 ? 'top' : '' }}">#{{ $i + 1 }}</span></td>
                            <td>
                                <div class="rpt-domain">{{ $type === 'group-tools' ? ($site->tool_name ?? $site->domain) : $site->domain }}</div>
                                @if($type === 'group-tools' && ($site->tool_name ?? null))
                                    <div class="rpt-sess">{{ $site->domain }}</div>
                                @endif
                                @if($site->is_ai_tool)
                                    <span class="rpt-tag ai"><i class="bi bi-stars"></i> AI</span>
                                @endif
                            </td>
                            <td><span class="rpt-tag">{{ $site->category ?: 'General' }}</span></td>
                            <td><span class="rpt-time">{{ $msLabel($site->active_ms) }}</span></td>
                            <td class="rpt-sess">{{ $site->sessions_count }} sess.</td>
                            <td>
                                <div class="rpt-bar-wrap">
                                    <div class="rpt-bar" style="width:{{ min(100, $pct) }}%; background:{{ $colors[$gi % count($colors)] }}"></div>
                                </div>
                                <span class="rpt-pct">{{ number_format($pct, 0) }}%</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @endforeach

{{-- STUDENT REPORTS --}}
@else
    <table class="rpt-table rpt-student-table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Group</th>
                <th>{{ $type === 'student-tools' ? 'Top Tool' : 'Top Site' }}</th>
                <th>Category</th>
                <th>Time Spent</th>
                <th>Sessions</th>
                <th>Top 3 {{ $type === 'student-tools' ? 'Tools' : 'Sites' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $gi => $row)
                @if(!empty($row['sites']))
                @php $top = $row['sites'][0]; @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            <span class="rpt-avatar" style="background:{{ $colors[$gi % count($colors)] }}">
                                {{ strtoupper(substr($row['name'], 0, 1)) }}
                            </span>
                            <span class="rpt-domain" style="font-weight:700">{{ $row['name'] }}</span>
                        </div>
                    </td>
                    <td><span class="rpt-tag">{{ $row['dept'] }}</span></td>
                    <td>
                        <div class="rpt-domain">{{ $type === 'student-tools' ? ($top->tool_name ?? $top->domain) : $top->domain }}</div>
                        @if($type === 'student-tools' && ($top->tool_name ?? null))
                            <div class="rpt-sess">{{ $top->domain }}</div>
                        @endif
                        @if($top->is_ai_tool)
                            <span class="rpt-tag ai"><i class="bi bi-stars"></i> AI</span>
                        @endif
                    </td>
                    <td><span class="rpt-tag">{{ $top->category ?: 'General' }}</span></td>
                    <td><span class="rpt-time">{{ $msLabel($top->active_ms) }}</span></td>
                    <td class="rpt-sess">{{ $top->sessions_count }} sess.</td>
                    <td>
                        <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
                            @foreach(array_slice($row['sites'], 0, 3) as $s)
                                <span class="rpt-mini-chip">{{ $type === 'student-tools' ? ($s->tool_name ?? $s->domain) : $s->domain }}</span>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endif

@endif
