<style>
    .ts-wrap { padding: 1.25rem 1.5rem; }
    .ts-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .ts-header-title {
        font-weight: 700;
        font-size: 0.82rem;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .ts-total-chip {
        background: #f3f4f6;
        border-radius: 6px;
        padding: 0.2rem 0.6rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: #374151;
    }

    .ts-row {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid #f9fafb;
    }
    .ts-row:last-child { border-bottom: none; }

    .ts-rank {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #f3f4f6;
        color: #6b7280;
        font-size: 0.72rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .ts-rank.top { background: #ede9fe; color: #4f46e5; }

    .ts-main { flex: 1; min-width: 0; }
    .ts-domain {
        font-weight: 700;
        font-size: 0.875rem;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ts-tags {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin-top: 0.2rem;
        flex-wrap: wrap;
    }
    .ts-tag {
        background: #f3f4f6;
        color: #6b7280;
        font-size: 0.68rem;
        font-weight: 600;
        border-radius: 4px;
        padding: 0.1rem 0.45rem;
    }
    .ts-tag.ai {
        background: #eff6ff;
        color: #2563eb;
    }

    .ts-right { text-align: right; flex-shrink: 0; }
    .ts-time {
        font-size: 0.875rem;
        font-weight: 800;
        color: #4f46e5;
    }
    .ts-sessions {
        font-size: 0.7rem;
        color: #9ca3af;
        margin-top: 0.1rem;
    }

    .ts-bar-track {
        height: 3px;
        background: #f3f4f6;
        border-radius: 99px;
        margin-top: 0.65rem;
        overflow: hidden;
    }
    .ts-bar-fill {
        height: 100%;
        border-radius: 99px;
        background: #4f46e5;
        transition: width 0.4s ease;
    }

    .ts-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #9ca3af;
    }
    .ts-empty i { font-size: 2rem; opacity: 0.3; display: block; margin-bottom: 0.75rem; }
    .ts-empty h6 { font-weight: 700; color: #374151; margin-bottom: 0.25rem; }
    .ts-empty p  { font-size: 0.82rem; margin: 0; }
</style>

@if($sites->isEmpty())
    <div class="ts-empty">
        <i class="bi bi-bar-chart"></i>
        <h6>No data yet</h6>
        <p>Browsing activity appears once the extension is active.</p>
    </div>
@else
    <div class="ts-wrap">
        <div class="ts-header">
            <span class="ts-header-title">Top {{ $sites->count() }} Tools &amp; Sites</span>
            <span class="ts-total-chip">Total: {{ $ms($totalActiveMs) }}</span>
        </div>

        @foreach($sites as $i => $site)
            @php $pct = $totalActiveMs > 0 ? ($site->active_ms / $totalActiveMs) * 100 : 0; @endphp
            <div class="ts-row">
                <div class="ts-rank {{ $i < 3 ? 'top' : '' }}">#{{ $i + 1 }}</div>
                <div class="ts-main">
                    <div class="ts-domain">{{ $site->domain }}</div>
                    <div class="ts-tags">
                        <span class="ts-tag">{{ $site->category }}</span>
                        @if($site->is_ai_tool)
                            <span class="ts-tag ai"><i class="bi bi-stars" style="font-size:0.6rem;"></i> AI Tool</span>
                        @endif
                    </div>
                    <div class="ts-bar-track">
                        <div class="ts-bar-fill" style="width: {{ number_format($pct, 1) }}%;"></div>
                    </div>
                </div>
                <div class="ts-right">
                    <div class="ts-time">{{ $ms($site->active_ms) }}</div>
                    <div class="ts-sessions">
                        @if(isset($site->visitors_count))
                            {{ $site->visitors_count }} {{ Str::plural('visitor', $site->visitors_count) }}
                        @else
                            {{ $site->sessions_count }} {{ Str::plural('session', $site->sessions_count) }}
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
