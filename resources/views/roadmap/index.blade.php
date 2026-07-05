@extends('layouts.user')

@section('title', 'My Learning Journeys — Daleel AI')

@section('styles')
<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #818cf8;
        --bg-body: #f8fafc;
    }

    .page-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        color: #fff;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -10px rgba(30, 27, 75, 0.3);
    }
    .page-header::after {
        content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px;
        background: rgba(99, 102, 241, 0.1); border-radius: 50%;
    }

    .roadmap-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .roadmap-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border-color: var(--primary-light);
    }

    .roadmap-card-header {
        padding: 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tool-stack {
        display: flex;
        align-items: center;
    }
    .tool-circle {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex; align-items: center; justify-content: center;
        margin-left: -12px;
        position: relative;
    }
    .tool-circle:first-child { margin-left: 0; }

    .progress-pill {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        padding: 4px 12px;
        border-radius: 50px;
    }

    .btn-journey {
        background: var(--primary);
        color: #fff;
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 0;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }
    .btn-journey:hover {
        background: var(--primary-dark);
        color: #fff;
        transform: scale(1.02);
    }

    .create-card {
        border: 2px dashed #e2e8f0;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 300px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .create-card:hover {
        border-color: var(--primary);
        background: #f5f3ff;
    }

    @keyframes pulse-soft {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .pulse-icon { animation: pulse-soft 3s infinite ease-in-out; }
    .bg-roadmap-primary { background: var(--primary) !important; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    @if(isset($pendingData) && $pendingData)
        <div class="alert alert-info border-0 shadow-lg p-4 rounded-4 mb-4" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 5px solid #2563eb !important;">
            <div class="d-flex align-items-center flex-wrap flex-md-nowrap gap-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center p-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                    <i class="bi bi-stars text-warning fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-800 text-dark mb-1">New Tool Usage Detected!</h5>
                    <p class="text-secondary mb-0 small text-muted">We noticed you have been active on <strong>{{ $pendingData['tool']->name }}</strong>. We have matching tutorial content and video lessons. Would you like to append it to your Auto-Generated Roadmap?</p>
                </div>
                <div class="d-flex gap-2 ms-md-auto mt-3 mt-md-0">
                    <form action="{{ route('roadmap.auto.add-tool') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="roadmap_id" value="{{ $pendingData['roadmap_id'] }}">
                        <input type="hidden" name="tool_id" value="{{ $pendingData['tool']->id }}">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-800 btn-sm">Yes, Add to Path</button>
                    </form>
                    <form action="{{ route('roadmap.auto.dismiss-tool') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="roadmap_id" value="{{ $pendingData['roadmap_id'] }}">
                        <input type="hidden" name="tool_id" value="{{ $pendingData['tool']->id }}">
                        <button type="submit" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold btn-sm">Dismiss</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-patch-check-fill fs-5 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-white bg-opacity-20 rounded-pill px-3 py-1 mb-3 fw-700" style="font-size: 10px;">MY LEARNING ECOSYSTEM</span>
                <h1 class="fw-900 display-5 mb-2">Smart Roadmaps</h1>
                <p class="opacity-75 lead mb-0">Track your progress and master the tools required for your goals.</p>
            </div>
            <div class="col-lg-4 text-end">
                @if(auth()->user()->account_type === 'Free Plan' && $manualRoadmapsCount >= 2)
                    <a href="{{ url('/pricing') }}" class="btn btn-danger rounded-pill px-4 py-3 fw-900 shadow-lg border-0" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); text-decoration: none;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Upgrade for More Roadmaps
                    </a>
                @else
                    <a href="#" data-bs-toggle="modal" data-bs-target="#newRoadmapModal" class="btn btn-primary rounded-pill px-4 py-3 fw-900 shadow-lg border-0" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); text-decoration: none;">
                        <i class="bi bi-rocket-takeoff-fill me-2"></i> Launch New AI Curriculum
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">


        @forelse($roadmaps as $item)
            @php
                $toolsCount = count($item->tools);
                $isCompleted = $item->progress >= 100;
            @endphp
            <div class="col-xl-4 col-md-6">
                <div class="roadmap-card">
                    <div class="roadmap-card-header">
                        <div class="tool-stack">
                            @foreach(array_slice($item->tools, 0, 3) as $toolId)
                                @php $tool = \App\Models\Tool::find($toolId); @endphp
                                <div class="tool-circle" title="{{ $tool?->name }}">
                                    @if($tool?->logo)
                                        <img src="{{ asset($tool->logo) }}" width="18">
                                    @else
                                        <i class="bi bi-box-seam-fill text-muted" style="font-size: 10px;"></i>
                                    @endif
                                </div>
                            @endforeach
                            @if($toolsCount > 3)
                                <div class="tool-circle small fw-900 text-muted" style="font-size: 10px;">+{{ $toolsCount - 3 }}</div>
                            @endif
                        </div>
                        <div class="ms-auto d-flex align-items-center gap-2">
                            <span class="text-muted small fw-700">{{ $item->created_at->format('M d, Y') }}</span>
                            <button type="button" class="btn p-1 border-0 delete-roadmap-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-title="{{ $item->title }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteRoadmapModal" 
                                    title="Delete Roadmap"
                                    style="font-size: 1rem; line-height: 1; background: #fee2e2; color: #dc2626; border-radius: 6px;">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4 d-flex flex-column h-100">
                        <h4 class="fw-800 text-dark mb-3 line-clamp-2" style="font-size: 1.25rem;">
                            {{ $item->title }}
                            @if($item->is_auto_generated)
                                <span class="badge bg-info text-white fw-bold rounded-pill ms-2" style="font-size: 10px; background-color: #0ea5e9 !important; padding: 4px 8px; vertical-align: middle;"><i class="bi bi-cpu-fill"></i> Auto-Generated</span>
                            @endif
                        </h4>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-700 text-muted uppercase">CURRICULUM PROGRESS</span>
                                <span class="badge {{ $isCompleted ? 'bg-success' : 'bg-primary' }} bg-opacity-10 {{ $isCompleted ? 'text-success' : 'text-primary' }} progress-pill">
                                    {{ $item->progress }}%
                                </span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                                <div class="progress-bar rounded-pill bg-roadmap-primary" style="width: {{ $item->progress }}%;" role="progressbar"></div>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded-3 text-center">
                                        <div class="text-muted small fw-700" style="font-size: 9px; text-transform: uppercase;">Focus</div>
                                        <div class="small fw-800 text-dark">{{ Str::limit($item->focus, 12) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded-3 text-center">
                                        <div class="text-muted small fw-700" style="font-size: 9px; text-transform: uppercase;">Level</div>
                                        <div class="small fw-800 text-dark">{{ ucfirst($item->level) }}</div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('roadmap.show', $item->id) }}" class="btn btn-journey w-100">
                                <i class="bi bi-play-fill me-1"></i> Continue Journey
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 mt-5">
                <div class="bg-primary bg-opacity-10 text-primary w-100 h-100 rounded-circle d-flex align-items-center justify-content-center mb-4 mx-auto pulse-icon" style="width: 100px !important; height: 100px !important;">
                    <i class="bi bi-map-fill" style="font-size: 3rem;"></i>
                </div>
                <h2 class="fw-900 text-dark mb-3">No learning journeys yet</h2>
                <p class="text-muted mx-auto mb-5" style="max-width: 450px;">
                    Search for a career goal or a tool you want to master, and our AI will build a personalized curriculum for you.
                </p>
                <a href="{{ route('learn.explore') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-800 shadow">
                    Create Your First Roadmap
                </a>
            </div>
        @endforelse
    </div>
</div>

<!-- Create Roadmap Modal -->
<div class="modal fade" id="newRoadmapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg position-relative overflow-hidden" style="border-radius: 35px; background: #ffffff;">
            {{-- Decorative Background Elements --}}
            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-05 pointer-events-none" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 20px 20px;"></div>
            
            <div class="modal-body p-5 position-relative">
                <div class="text-center mb-5">
                    <div class="blueprint-icon-wrapper pulse-blue mx-auto mb-4">
                        <div class="pulse-aura"></div>
                        <i class="bi bi-rocket-takeoff-fill fs-1 text-primary"></i>
                    </div>
                    <h2 class="fw-900 text-dark mb-2" style="letter-spacing: -0.03em;">Dream your goal</h2>
                    <p class="text-muted px-4">Type your career objective or the skill you want to master. AI will blueprint the perfect path for you.</p>
                </div>

                <form action="{{ route('roadmap.wizard') }}" method="GET">
                    <div class="mb-4">
                        <div class="goal-input-wrapper position-relative">
                            <i class="bi bi-bullseye position-absolute top-50 start-0 translate-middle-y ms-4 text-primary opacity-50"></i>
                            <input type="text" 
                                   name="query" 
                                   class="form-control form-control-xl bg-light border-0 ps-5 py-4 fw-700" 
                                   style="border-radius: 20px; font-size: 1.1rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);" 
                                   placeholder="e.g. Become a Senior AI Architect"
                                   required
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="d-grid mt-2">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-900 shadow-lg btn-launch-journey transition-all">
                            LAUNCH MY JOURNEY <i class="bi bi-arrow-right-short ms-2 fs-4"></i>
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <span class="badge bg-light text-muted rounded-pill px-3 py-2 fw-700" style="font-size: 10px; letter-spacing: 1px; text-transform: uppercase;">Powered by GPT-4o Mini</span>
                </div>
            </div>
            <button type="button" class="btn-close position-absolute top-0 end-0 m-4 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
</div>

<style>
    .architect-create-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border: none;
        border-radius: 30px;
        position: relative;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .architect-create-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.5), 0 0 40px rgba(79, 70, 229, 0.1);
    }
    .architect-icon-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.3);
        transition: all 0.4s ease;
    }
    .architect-create-card:hover .architect-icon-circle {
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 15px 35px rgba(6, 182, 212, 0.5);
    }
    .architect-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .architect-create-card:hover .architect-glow {
        opacity: 1;
    }
    
    .btn-launch-journey {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        letter-spacing: 1px;
    }
    .btn-launch-journey:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3) !important;
    }
    
    .opacity-05 { opacity: 0.05; }
</style>

<!-- Delete Roadmap Modal -->
<div class="modal fade" id="deleteRoadmapModal" tabindex="-1" aria-labelledby="deleteRoadmapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                </div>
                <h5 class="fw-800 text-dark mb-2">Delete Roadmap?</h5>
                <p class="text-secondary small mb-4" id="deleteRoadmapName" style="line-height: 1.5;">Are you sure you want to permanently delete this roadmap curriculum?</p>
                <form id="deleteRoadmapForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 fw-bold py-2 rounded-3" data-bs-dismiss="modal" style="font-size: 0.9rem;">Cancel</button>
                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2 rounded-3" style="font-size: 0.9rem;">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForm = document.getElementById('deleteRoadmapForm');
        const deleteName = document.getElementById('deleteRoadmapName');
        
        document.querySelectorAll('.delete-roadmap-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                
                // Set the dynamic action route URL
                deleteForm.action = `{{ url('/roadmap') }}/${id}`;
                deleteName.innerHTML = `Are you sure you want to permanently delete <strong class="text-dark">"${title}"</strong>? This action cannot be undone.`;
            });
        });
    });
</script>
@endsection
