@php
    $msFormat = function($v) {
        $v = max(0, intval($v));
        if ($v < 60000) return round($v/1000).'s';
        $m = floor($v/60000);
        if ($m < 60) return $m.'m';
        return floor($m/60).'h '.($m%60).'m';
    };
@endphp

<!-- Details Header -->
<div class="d-flex align-items-start justify-content-between mb-4 border-bottom pb-3">
    <div>
        <div class="d-flex align-items-center gap-3">
            <div class="avatar bg-primary text-white fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <div>
                <h4 class="fw-800 text-dark mb-0">{{ $employee->name }}</h4>
                <div class="text-muted small">
                    @if($employee->department)
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill px-3">{{ $employee->department->name }}</span>
                    @else
                        <span class="text-muted">No Department</span>
                    @endif
                    &bull; Connection Code: <code class="text-success fw-bold">{{ $employee->connection_code }}</code>
                </div>
            </div>
        </div>
    </div>
    <span class="badge {{ $totalSessionsCount > 0 ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 {{ $totalSessionsCount > 0 ? 'text-success' : 'text-secondary' }} px-3 py-2 rounded-pill fw-bold small">
        <i class="bi bi-circle-fill me-1 small"></i> {{ $totalSessionsCount > 0 ? 'Active Tracking' : 'No Data Yet' }}
    </span>
</div>

<!-- Telemetry Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 bg-light rounded-4 p-3 h-100 text-center">
            <i class="bi bi-clock-fill text-primary fs-3 mb-2"></i>
            <h5 class="fw-800 text-dark mb-1">{{ $msFormat($totalActiveMs) }}</h5>
            <p class="text-muted mb-0 small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Active Time</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 bg-light rounded-4 p-3 h-100 text-center">
            <i class="bi bi-cursor-fill text-success fs-3 mb-2"></i>
            <h5 class="fw-800 text-dark mb-1">{{ number_format($totalClicks) }}</h5>
            <p class="text-muted mb-0 small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Total Clicks</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 bg-light rounded-4 p-3 h-100 text-center">
            <i class="bi bi-lightning-charge-fill text-warning fs-3 mb-2"></i>
            <h5 class="fw-800 text-dark mb-1">{{ $productivityScore }}%</h5>
            <p class="text-muted mb-0 small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Productivity</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 bg-light rounded-4 p-3 h-100 text-center">
            <i class="bi bi-arrow-left-right text-info fs-3 mb-2"></i>
            <h5 class="fw-800 text-dark mb-1">{{ number_format($totalPageSwitches) }}</h5>
            <p class="text-muted mb-0 small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Tab Switches</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Category Doughnut Chart -->
    <div class="col-lg-5">
        <div class="card border border-light shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-800 text-dark mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i> Category Distribution</h6>
            @if($categoryBreakdown->isEmpty())
                <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5">
                    <i class="bi bi-slash-circle text-muted fs-2 mb-2"></i>
                    <p class="text-muted small mb-0">No category breakdown data available.</p>
                </div>
            @else
                <div class="position-relative" style="height: 220px;">
                    <canvas id="empCategoryChart"></canvas>
                </div>
            @endif
        </div>
    </div>

    <!-- Active Snapshots Progress -->
    <div class="col-lg-7">
        <div class="card border border-light shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-800 text-dark mb-3"><i class="bi bi-shield-lock-fill text-success me-2"></i> Focus Telemetry Insights</h6>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-700 small text-dark">Focus score</span>
                    <span class="fw-800 text-primary small">{{ $focusScore }}%</span>
                </div>
                <div class="progress rounded-pill" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $focusScore }}%"></div>
                </div>
                <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">Calculated based on sustained attention periods in focus apps vs entertainment sites.</p>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-700 small text-dark">Interaction Frequency</span>
                    @php
                        $interFreq = $totalActiveMs > 0 ? round(($totalInteractions / ($totalActiveMs / 60000)), 1) : 0;
                    @endphp
                    <span class="fw-800 text-success small">{{ $interFreq }} / min</span>
                </div>
                <div class="progress rounded-pill" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $interFreq * 5) }}%"></div>
                </div>
                <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">Total mouse clicks and keypress interactions per active tracking minute.</p>
            </div>

            <div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-700 small text-dark">Idle Time Ratio</span>
                    @php
                        $idleRatio = ($totalActiveMs + $totalIdleMs) > 0 ? round(($totalIdleMs / ($totalActiveMs + $totalIdleMs)) * 100, 1) : 0;
                    @endphp
                    <span class="fw-800 text-danger small">{{ $idleRatio }}%</span>
                </div>
                <div class="progress rounded-pill" style="height: 8px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $idleRatio }}%"></div>
                </div>
                <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">Percentage of time the browser tab was active but no keyboard or mouse interactions were captured.</p>
            </div>
        </div>
    </div>
</div>

<!-- Expandable Visited Domains List -->
<div class="card border border-light shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-800 text-dark mb-0"><i class="bi bi-globe-americas text-info me-2"></i> Visited Domains & Pages</h6>
        <span class="badge bg-light text-secondary border small">{{ $topDomains->count() }} Domains Tracked</span>
    </div>
    
    @if($topDomains->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-info-circle text-muted fs-2 mb-2 d-block"></i>
            <h6 class="fw-800 text-muted">No Browsing Sessions Yet</h6>
            <p class="text-muted small mb-0">The extension will record visited domains once the employee connects.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="table-light text-secondary small text-uppercase fw-bold" style="font-size: 0.7rem; border-bottom: 2px solid #f1f5f9;">
                        <th class="py-2 border-0">Domain Name</th>
                        <th class="py-2 border-0 text-center">Category</th>
                        <th class="py-2 border-0 text-center">Activity Time</th>
                        <th class="py-2 border-0 text-end">Telemetry</th>
                        <th class="py-2 border-0 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDomains as $idx => $site)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi {{ $site->is_ai_tool ? 'bi-stars text-violet' : 'bi-globe text-muted' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-800 text-dark" style="font-size: 13px;">{{ $site->domain }}</div>
                                    @if($site->is_ai_tool)
                                        <span class="badge bg-violet bg-opacity-10 text-violet" style="font-size: 0.65rem;">AI Technology</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark rounded px-2 py-1 small fw-bold">{{ $site->category }}</span>
                        </td>
                        <td class="text-center">
                            <div class="fw-bold text-dark small">{{ $msFormat($site->active_ms) }}</div>
                            <div class="progress rounded-pill bg-light mx-auto" style="width: 60px; height: 4px;">
                                @php
                                    $pct = $totalActiveMs > 0 ? ($site->active_ms / $totalActiveMs) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-info" style="width: {{ $pct }}%"></div>
                            </div>
                        </td>
                        <td class="text-end small">
                            <div class="fw-bold text-dark">{{ number_format($site->clicks) }} clicks</div>
                            <div class="text-muted" style="font-size: 10px;">{{ number_format($site->pages_count) }} interaction sessions</div>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-white border px-2 py-1" type="button" data-bs-toggle="collapse" data-bs-target="#domainPages{{ $idx }}">
                                <i class="bi bi-chevron-down"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="collapse-row">
                        <td colspan="5" class="p-0 border-0">
                            <div class="collapse bg-light bg-opacity-50" id="domainPages{{ $idx }}">
                                <div class="p-3 border-bottom">
                                    <div class="fw-800 text-secondary mb-2 small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Detailed Subpages Visited</div>
                                    @if(empty($site->pages))
                                        <div class="text-muted small">No detailed path views recorded.</div>
                                    @else
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($site->pages as $page)
                                            <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-2xs">
                                                <div class="text-truncate me-3" style="max-width: 70%; font-size: 0.75rem;" title="{{ $page['title'] }}">
                                                    <i class="bi bi-file-text-fill text-muted me-1"></i> {{ $page['title'] }}
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="badge bg-light text-secondary rounded small fw-bold">{{ $page['visits'] }} visits</span>
                                                    <span class="fw-bold text-dark small">{{ $msFormat($page['active_ms']) }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Chronological session history Timeline -->
<div class="card border border-light shadow-sm rounded-4 p-4">
    <h6 class="fw-800 text-dark mb-4"><i class="bi bi-clock-history text-secondary me-2"></i> Recent Session Log</h6>
    @if($recentSessions->isEmpty())
        <div class="text-center py-5">
            <p class="text-muted small mb-0">No chronological sessions found.</p>
        </div>
    @else
        <div class="d-flex flex-column gap-3 ps-2" style="border-left: 2px solid #f1f5f9; position: relative;">
            @foreach($recentSessions as $sess)
            <div style="position: relative; padding-left: 1rem;">
                <div style="position: absolute; left: -21px; top: 3px; width: 10px; height: 10px; border-radius: 50%;" class="{{ $sess->is_ai_tool ? 'bg-violet' : 'bg-primary' }}"></div>
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-800 text-dark small">{{ $sess->platform_domain ?: 'Unknown Domain' }}</div>
                        <div class="text-muted" style="font-size: 11px;">
                            <span class="badge bg-light text-dark rounded px-2 py-0.5 small me-1">{{ $sess->platform_category }}</span>
                            &bull; {{ $sess->started_at ? $sess->started_at->format('M d, H:i') : 'Unknown Date' }}
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold text-dark small">{{ $msFormat($sess->active_ms) }}</span>
                        <div class="text-muted" style="font-size: 10px;">{{ $sess->click_count }} clicks</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    // Initialize modal Category doughnut Chart
    (function() {
        const ctx = document.getElementById('empCategoryChart');
        if (!ctx) return;
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryBreakdown->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($categoryBreakdown->pluck('active_ms')->map(fn($v) => round($v / 60000))) !!}, // in minutes
                    backgroundColor: ['#4f46e5', '#0d9488', '#f59e0b', '#2563eb', '#ec4899', '#8b5cf6', '#64748b'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { family: 'Plus Jakarta Sans', weight: '600', size: 10 }
                        }
                    }
                }
            }
        });
    })();
</script>
