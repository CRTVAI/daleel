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
            <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.5rem;">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <h4 class="fw-800 text-dark mb-0">{{ $department->name }}</h4>
                <div class="text-muted small">
                    Group Department Analytics &bull; Connected to <span class="fw-bold text-dark">{{ $totalMembers }} Employees</span>
                </div>
            </div>
        </div>
    </div>
    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold small">
        Department Insights
    </span>
</div>

<!-- Telemetry Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 bg-light rounded-4 p-3 h-100 text-center">
            <i class="bi bi-people-fill text-primary fs-3 mb-2"></i>
            <h5 class="fw-800 text-dark mb-1">{{ $totalMembers }}</h5>
            <p class="text-muted mb-0 small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Members</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 bg-light rounded-4 p-3 h-100 text-center">
            <i class="bi bi-clock-fill text-success fs-3 mb-2"></i>
            <h5 class="fw-800 text-dark mb-1">{{ $msFormat($totalActiveMs) }}</h5>
            <p class="text-muted mb-0 small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Total Active Time</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 bg-light rounded-4 p-3 h-100 text-center">
            <i class="bi bi-calculator-fill text-warning fs-3 mb-2"></i>
            @php
                $avgActive = $totalMembers > 0 ? $totalActiveMs / $totalMembers : 0;
            @endphp
            <h5 class="fw-800 text-dark mb-1">{{ $msFormat($avgActive) }}</h5>
            <p class="text-muted mb-0 small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Avg. Per Member</p>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 bg-light rounded-4 p-3 h-100 text-center">
            <i class="bi bi-hdd-network-fill text-info fs-3 mb-2"></i>
            <h5 class="fw-800 text-dark mb-1">{{ number_format($totalSessionsCount) }}</h5>
            <p class="text-muted mb-0 small fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">Tracking Logs</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Category Doughnut Chart -->
    <div class="col-lg-5">
        <div class="card border border-light shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-800 text-dark mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i> Department Category Distribution</h6>
            @if($categoryBreakdown->isEmpty())
                <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5">
                    <i class="bi bi-slash-circle text-muted fs-2 mb-2"></i>
                    <p class="text-muted small mb-0">No category breakdown data available.</p>
                </div>
            @else
                <div class="position-relative" style="height: 220px;">
                    <canvas id="deptCategoryChart"></canvas>
                </div>
            @endif
        </div>
    </div>

    <!-- Leaders List -->
    <div class="col-lg-7">
        <div class="card border border-light shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-800 text-dark mb-3"><i class="bi bi-person-lines-fill text-success me-2"></i> Member Leaderboard</h6>
            @if($membersStats->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted small mb-0">No members in this department yet.</p>
                </div>
            @else
                <div class="table-responsive" style="max-height: 240px; overflow-y: auto;">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small text-uppercase fw-bold" style="font-size: 0.65rem; border-bottom: 2px solid #f1f5f9;">
                                <th class="py-2 border-0">Member Name</th>
                                <th class="py-2 border-0 text-center">Tracked Hours</th>
                                <th class="py-2 border-0 text-end">Top Domain Tool</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($membersStats as $m)
                            <tr style="border-bottom: 1px solid #f8fafc;">
                                <td class="py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar bg-light text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            {{ strtoupper(substr($m->name, 0, 1)) }}
                                        </div>
                                        <div class="fw-800 text-dark" style="font-size: 13px;">{{ $m->name }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark small">{{ $msFormat($m->active_ms) }}</span>
                                    <div class="progress rounded-pill bg-light mx-auto" style="width: 50px; height: 3px;">
                                        @php
                                            $pct = $totalActiveMs > 0 ? ($m->active_ms / $totalActiveMs) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-success" style="width: {{ $pct }}%"></div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light text-dark rounded small fw-bold" style="font-size: 11px;">{{ $m->top_domain }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Department Top Domains List -->
<div class="card border border-light shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-800 text-dark mb-0"><i class="bi bi-globe-americas text-info me-2"></i> Department-Wide Top Visited Domains</h6>
        <span class="badge bg-light text-secondary border small">{{ $topDomains->count() }} Domains Tracked</span>
    </div>
    
    @if($topDomains->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-info-circle text-muted fs-2 mb-2 d-block"></i>
            <h6 class="fw-800 text-muted">No Department Telemetry</h6>
            <p class="text-muted small mb-0">Department telemetry aggregates automatically as connected employees browse.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="table-light text-secondary small text-uppercase fw-bold" style="font-size: 0.7rem; border-bottom: 2px solid #f1f5f9;">
                        <th class="py-2 border-0">Domain Name</th>
                        <th class="py-2 border-0 text-center">Category</th>
                        <th class="py-2 border-0 text-center">Active Time</th>
                        <th class="py-2 border-0 text-end">Tool Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDomains as $site)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi {{ $site->is_ai_tool ? 'bi-stars text-violet' : 'bi-globe text-muted' }}"></i>
                                </div>
                                <div class="fw-800 text-dark" style="font-size: 13px;">{{ $site->domain }}</div>
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
                        <td class="text-end">
                            @if($site->is_ai_tool)
                                <span class="badge bg-violet bg-opacity-10 text-violet fw-bold small">AI System</span>
                            @else
                                <span class="text-muted small">Standard Domain</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
    // Initialize modal Category doughnut Chart
    (function() {
        const ctx = document.getElementById('deptCategoryChart');
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
