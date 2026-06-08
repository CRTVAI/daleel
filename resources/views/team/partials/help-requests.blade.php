<div style="padding: 1.5rem;">
    @if($helpRequests->isEmpty())
        <div class="text-center py-5">
            <div style="width: 64px; height: 64px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <i class="bi bi-chat-dots text-muted" style="font-size: 1.5rem;"></i>
            </div>
            <h6 class="fw-800 text-dark">No Help Requests</h6>
            <p class="text-muted small">This employee hasn't sent any queries to the mentor yet.</p>
        </div>
    @else
        <div class="d-flex flex-column gap-4">
            @foreach($helpRequests as $req)
                <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; position: relative; transition: all 0.2s;" onmouseover="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)'" onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div style="background: #f5f3ff; color: #7c3aed; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase;">
                            Request #{{ $loop->count - $loop->index }}
                        </div>
                        <span class="text-muted small fw-600"><i class="bi bi-clock me-1"></i>{{ $req->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <h5 class="fw-800 mb-3" style="line-height: 1.4; font-size: 1rem;">
                        <a href="{{ route('ai.mentor', ['query' => $req->query]) }}" class="text-dark text-decoration-none hover-primary">
                            "{{ $req->query }}"
                        </a>
                    </h5>

                    <div style="background: #f8fafc; border-radius: 8px; padding: 0.85rem; border-left: 3px solid #cbd5e1;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-globe text-muted small"></i>
                            <span class="text-dark fw-700 small" style="letter-spacing: -0.01em;">{{ $req->domain ?: 'Unknown Site' }}</span>
                        </div>
                        @if($req->url)
                            <a href="{{ $req->url }}" target="_blank" class="text-primary text-decoration-none small fw-700 d-flex align-items-center gap-1 hover-underline">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Open Original Page
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .hover-underline:hover { text-decoration: underline !important; }
    .hover-primary:hover { color: #4f46e5 !important; text-decoration: underline !important; }
</style>
