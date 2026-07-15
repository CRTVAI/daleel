@extends('layouts.user')

@section('title', 'Usage Reports - Daleel AI')

@section('styles')
<style>
    body { background: #f8f9fb; overflow-x: hidden; }
    .page-top { margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e9ecef; }
    .page-title { font-size: clamp(1.35rem, 3vw, 1.65rem); font-weight: 800; color: #111827; letter-spacing: -0.03em; margin: 0 0 0.25rem; }
    .page-sub { font-size: 0.86rem; color: #6b7280; margin: 0; max-width: 720px; }
    .btn-head-outline { background: #fff; border: 1px solid #d1d5db; color: #374151; font-weight: 700; font-size: 0.82rem; border-radius: 8px; padding: 0.5rem 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }
    .btn-head-outline:hover { border-color: #9ca3af; background: #f9fafb; color: #111827; }
    .report-tabs { display: flex; gap: 0.45rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .report-tab { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; color: #475569; font-size: 0.82rem; font-weight: 800; padding: 0.62rem 0.85rem; display: inline-flex; align-items: center; gap: 0.42rem; transition: background 0.15s, border-color 0.15s, color 0.15s; }
    .report-tab:hover { background: #f8fafc; border-color: #cbd5e1; color: #111827; }
    .report-tab.active { background: #111827; border-color: #111827; color: #fff; }
    .rpt-card { background: #fff; border: 1px solid #e9ecef; border-radius: 8px; overflow: hidden; width: 100%; box-shadow: 0 4px 18px rgba(100, 116, 139, 0.06); }
    .rpt-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .rpt-title-wrap h5 { font-size: 0.98rem; font-weight: 800; color: #111827; margin: 0 0 0.15rem; display: flex; align-items: center; gap: 0.45rem; }
    .rpt-title-wrap p { font-size: 0.78rem; color: #6b7280; margin: 0; }
    .rpt-filters { padding: 0.85rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; background: #fff; border-bottom: 1px solid #f3f4f6; flex-wrap: wrap; }
    .rpt-filter-label { font-size: 0.72rem; font-weight: 800; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; }
    .rpt-select { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.45rem 0.8rem; font-size: 0.8rem; font-weight: 650; color: #374151; outline: none; min-height: 36px; }
    .rpt-select { cursor: pointer; }
    .rpt-select:focus { border-color: #4f46e5; background: #fff; }
    .search-select { position: relative; min-width: 240px; }
    .search-select-btn { width: 100%; min-height: 36px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #374151; font-size: 0.8rem; font-weight: 700; padding: 0.45rem 2rem 0.45rem 0.8rem; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; text-align: left; }
    .search-select-btn:focus { outline: none; border-color: #4f46e5; background: #fff; }
    .search-select-btn i { position: absolute; right: 0.75rem; color: #94a3b8; pointer-events: none; }
    .search-select-menu { position: absolute; z-index: 30; top: calc(100% + 6px); left: 0; right: 0; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 16px 36px rgba(15, 23, 42, 0.12); padding: 0.45rem; display: none; }
    .search-select.open .search-select-menu { display: block; }
    .search-select-input { width: 100%; border: 1px solid #e2e8f0; border-radius: 7px; padding: 0.45rem 0.65rem; font-size: 0.8rem; font-weight: 650; color: #374151; outline: none; margin-bottom: 0.4rem; }
    .search-select-input:focus { border-color: #4f46e5; }
    .search-select-options { max-height: 230px; overflow-y: auto; }
    .search-select-option { width: 100%; border: 0; background: transparent; border-radius: 6px; padding: 0.48rem 0.6rem; color: #334155; font-size: 0.8rem; font-weight: 700; text-align: left; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; }
    .search-select-option:hover, .search-select-option.active { background: #f1f5f9; color: #111827; }
    .search-select-option .bi-check2 { color: #4f46e5; visibility: hidden; }
    .search-select-option.active .bi-check2 { visibility: visible; }
    .search-select-empty { padding: 0.7rem; color: #94a3b8; font-size: 0.78rem; font-weight: 700; text-align: center; display: none; }
    .btn-rpt-download { margin-left: auto; background: #111827; color: #fff; border: none; border-radius: 8px; padding: 0.48rem 0.9rem; font-size: 0.78rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; white-space: nowrap; }
    .btn-rpt-download:hover { background: #1f2937; color: #fff; }
    .rpt-body { min-height: 220px; overflow-x: auto; }
    .rpt-spinner { display: flex; align-items: center; justify-content: center; gap: 0.6rem; padding: 3rem; color: #9ca3af; font-size: 0.85rem; font-weight: 650; }
    .rpt-spinner .spinner-border { width: 1.2rem; height: 1.2rem; border-width: 2px; color: #4f46e5; }
    .rpt-group-block { border-bottom: 1px solid #f3f4f6; }
    .rpt-group-block:last-child { border-bottom: none; }
    .rpt-group-header { display: flex; align-items: center; gap: 0.75rem; padding: 0.9rem 1.25rem; background: #f9fafb; border-bottom: 1px solid #f3f4f6; }
    .rpt-group-dot { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.78rem; color: #fff; flex-shrink: 0; }
    .rpt-group-name { font-weight: 800; font-size: 0.9rem; color: #111827; }
    .rpt-group-meta { font-size: 0.72rem; color: #9ca3af; margin-top: 0.1rem; }
    .rpt-group-count { margin-left: auto; background: #eef2ff; color: #4f46e5; border-radius: 999px; padding: 0.2rem 0.65rem; font-size: 0.72rem; font-weight: 800; flex-shrink: 0; }
    .rpt-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .rpt-table th { background: #f8fafc; color: #64748b; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.7rem 1.1rem; border-bottom: 1px solid #e5e7eb; white-space: nowrap; text-align: left; }
    .rpt-table td { padding: 0.75rem 1.1rem; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .rpt-table tbody tr:last-child td { border-bottom: none; }
    .rpt-table tbody tr:hover td { background: #fbfdff; }
    .rpt-rank { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 6px; background: #f3f4f6; color: #6b7280; font-size: 0.7rem; font-weight: 800; flex-shrink: 0; }
    .rpt-rank.top { background: #ede9fe; color: #4f46e5; }
    .rpt-domain { font-weight: 750; color: #111827; }
    .rpt-tag { display: inline-block; background: #f3f4f6; color: #6b7280; border-radius: 4px; padding: 0.1rem 0.45rem; font-size: 0.68rem; font-weight: 650; }
    .rpt-tag.ai { background: #eff6ff; color: #2563eb; }
    .rpt-time { font-weight: 800; color: #4f46e5; }
    .rpt-sess { color: #9ca3af; font-size: 0.75rem; }
    .rpt-bar-wrap { width: 80px; height: 5px; background: #f3f4f6; border-radius: 999px; overflow: hidden; display: inline-block; vertical-align: middle; margin-right: 0.4rem; }
    .rpt-bar { height: 100%; border-radius: 999px; transition: width 0.4s ease; }
    .rpt-pct { font-size: 0.72rem; color: #9ca3af; font-weight: 650; vertical-align: middle; }
    .rpt-avatar { width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.68rem; color: #fff; flex-shrink: 0; }
    .rpt-mini-chip { background: #f1f5f9; color: #64748b; border-radius: 4px; padding: 0.15rem 0.45rem; font-size: 0.68rem; font-weight: 650; white-space: nowrap; }
    .rpt-empty { text-align: center; padding: 3rem 1.5rem; color: #9ca3af; }
    .rpt-empty i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: 0.35; }
    .rpt-empty h6 { font-weight: 750; color: #374151; margin-bottom: 0.25rem; }
    .rpt-empty p { font-size: 0.82rem; margin: 0; }
    @media (max-width: 640px) { .report-tab { width: 100%; justify-content: center; } .search-select, .rpt-select { width: 100%; } .btn-rpt-download { margin-left: 0; width: 100%; justify-content: center; } }
</style>
@endsection

@section('content')
<div class="page-top d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">Usage &amp; Training Reports</h1>
        <p class="page-sub">See top tools and sites by group or student.</p>
    </div>
    <a href="{{ route('team.index') }}" class="btn-head-outline">
        <i class="bi bi-arrow-left"></i> Back to Telemetry
    </a>
</div>

<div class="report-tabs" id="reportTabs">
    <button class="report-tab active" type="button" data-type="group-tools" data-title="Tools by Group" data-copy="Top tools for each group.">
        <i class="bi bi-grid-3x3-gap"></i> Tools by Group
    </button>
    <button class="report-tab" type="button" data-type="group-sites" data-title="Sites by Group" data-copy="Top websites for each group.">
        <i class="bi bi-globe2"></i> Sites by Group
    </button>
    <button class="report-tab" type="button" data-type="student-tools" data-title="Tools by Student" data-copy="Top tools for each student.">
        <i class="bi bi-person-badge"></i> Tools by Student
    </button>
    <button class="report-tab" type="button" data-type="student-sites" data-title="Sites by Student" data-copy="Top websites for each student.">
        <i class="bi bi-person-lines-fill"></i> Sites by Student
    </button>
</div>

<div class="rpt-card" id="usageReportsCard">
    <div class="rpt-card-head">
        <div class="rpt-title-wrap">
            <h5 id="rptTitle"><i class="bi bi-bar-chart-line" style="color:#4f46e5;"></i> Tools by Group</h5>
            <p id="rptCopy">Top tools for each group.</p>
        </div>
    </div>
    <div class="rpt-filters">
        <span class="rpt-filter-label">Period</span>
        <select class="rpt-select" id="rptRangeFilter">
            <option value="today">Today</option>
            <option value="7days">Last 7 Days</option>
            <option value="30days">Last 30 Days</option>
            <option value="all">All Time</option>
        </select>
        <span class="rpt-filter-label">Group</span>
        <div class="search-select" id="rptDeptSelect">
            <button class="search-select-btn" type="button" id="rptDeptToggle" aria-haspopup="listbox" aria-expanded="false">
                <span id="rptDeptSelected">All Groups</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="search-select-menu">
                <input class="search-select-input" id="rptDeptSearch" type="text" placeholder="Search group" autocomplete="off">
                <div class="search-select-options" id="rptDeptOptions" role="listbox">
                    <button class="search-select-option active" type="button" data-id="" data-name="All Groups">
                        <span>All Groups</span><i class="bi bi-check2"></i>
                    </button>
                    @foreach($departments as $dept)
                        <button class="search-select-option" type="button" data-id="{{ $dept->id }}" data-name="{{ $dept->name }}">
                            <span>{{ $dept->name }}</span><i class="bi bi-check2"></i>
                        </button>
                    @endforeach
                </div>
                <div class="search-select-empty" id="rptDeptEmpty">No groups found</div>
            </div>
        </div>
        <a href="#" class="btn-rpt-download" id="rptDownloadBtn">
            <i class="bi bi-download"></i> Download CSV
        </a>
    </div>
    <div class="rpt-body" id="rptBody">
        <div class="rpt-spinner"><div class="spinner-border" role="status"></div> Loading report...</div>
    </div>
</div>

<script>
(function () {
    const choices = document.querySelectorAll('.report-tab');
    const body = document.getElementById('rptBody');
    const title = document.getElementById('rptTitle');
    const copy = document.getElementById('rptCopy');
    const rangeEl = document.getElementById('rptRangeFilter');
    const deptSelect = document.getElementById('rptDeptSelect');
    const deptToggle = document.getElementById('rptDeptToggle');
    const deptSelected = document.getElementById('rptDeptSelected');
    const deptSearch = document.getElementById('rptDeptSearch');
    const deptOptions = document.querySelectorAll('.search-select-option');
    const deptEmpty = document.getElementById('rptDeptEmpty');
    const dlBtn = document.getElementById('rptDownloadBtn');
    const reportDataUrl = '{{ route("team.report-data") }}';
    const reportDownloadUrl = '{{ route("team.report.download") }}';
    let currentType = new URLSearchParams(window.location.search).get('type') || 'group-tools';
    let currentDeptId = new URLSearchParams(window.location.search).get('dept_id') || '';
    let currentEmpId = new URLSearchParams(window.location.search).get('employee_id') || '';
    let currentRange = new URLSearchParams(window.location.search).get('range') || 'today';
    if (rangeEl) rangeEl.value = currentRange;

    function selectedDeptId() {
        return currentDeptId;
    }

    function buildParams() {
        let params = { type: currentType, range: rangeEl.value, dept_id: selectedDeptId() };
        if (currentEmpId) params.employee_id = currentEmpId;
        return new URLSearchParams(params).toString();
    }

    function syncChoice() {
        const active = document.querySelector(`.report-tab[data-type="${currentType}"]`) || choices[0];
        choices.forEach(choice => choice.classList.toggle('active', choice === active));
        title.innerHTML = '<i class="bi bi-bar-chart-line" style="color:#4f46e5;"></i> ' + active.dataset.title;
        copy.textContent = active.dataset.copy;
    }

    function loadReport() {
        syncChoice();
        body.innerHTML = '<div class="rpt-spinner"><div class="spinner-border" role="status"></div> Loading report...</div>';
        dlBtn.href = reportDownloadUrl + '?' + buildParams();

        fetch(reportDataUrl + '?' + buildParams())
            .then(response => {
                if (!response.ok) throw new Error();
                return response.text();
            })
            .then(html => { body.innerHTML = html; })
            .catch(() => {
                body.innerHTML = '<div class="rpt-empty"><i class="bi bi-exclamation-triangle"></i><h6>Failed to load</h6><p>Please try again.</p></div>';
            });
    }

    choices.forEach(choice => {
        choice.addEventListener('click', function () {
            currentType = this.dataset.type;
            history.replaceState(null, '', '?' + buildParams());
    // Initialize from URL params
    if (currentDeptId) {
        const matched = Array.from(deptOptions).find(o => o.dataset.id === currentDeptId);
        if (matched) {
            setDept(matched.dataset.id, matched.dataset.name);
        }
    }
    if (currentEmpId) {
        document.querySelector('.rpt-filters').style.display = 'none';
    }
    if (currentDeptId || currentEmpId) {
        syncChoice();
    }
    loadReport();
        });
    });

    function setDept(id, name) {
        currentDeptId = id || '';
        deptSelected.textContent = name || 'All Groups';
        deptOptions.forEach(option => option.classList.toggle('active', option.dataset.id === currentDeptId));
        deptSelect.classList.remove('open');
        deptToggle.setAttribute('aria-expanded', 'false');
        deptSearch.value = '';
        filterDeptOptions('');
        loadReport();
    }

    function filterDeptOptions(query) {
        const normalized = query.trim().toLowerCase();
        let visibleCount = 0;

        deptOptions.forEach(option => {
            const isVisible = option.dataset.name.toLowerCase().includes(normalized);
            option.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });

        deptEmpty.style.display = visibleCount ? 'none' : 'block';
    }

    deptToggle.addEventListener('click', function () {
        const isOpen = deptSelect.classList.toggle('open');
        deptToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        if (isOpen) {
            setTimeout(() => deptSearch.focus(), 0);
        }
    });

    deptOptions.forEach(option => {
        option.addEventListener('click', function () {
            setDept(this.dataset.id, this.dataset.name);
        });
    });

    deptSearch.addEventListener('input', function () {
        filterDeptOptions(this.value);
    });

    document.addEventListener('click', function (event) {
        if (!deptSelect.contains(event.target)) {
            deptSelect.classList.remove('open');
            deptToggle.setAttribute('aria-expanded', 'false');
        }
    });

    rangeEl.addEventListener('change', loadReport);
    deptSearch.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const firstVisible = Array.from(deptOptions).find(option => option.style.display !== 'none');
            if (firstVisible) {
                setDept(firstVisible.dataset.id, firstVisible.dataset.name);
            }
        }

        if (event.key === 'Escape') {
            deptSelect.classList.remove('open');
            deptToggle.setAttribute('aria-expanded', 'false');
        }
    });
    loadReport();
})();
</script>
@endsection
