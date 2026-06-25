@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Progress Header --}}
            <div class="text-center mb-5">
                <h6 class="text-uppercase text-primary fw-bold mb-2 ls-1" style="font-size: 0.75rem;">Step 1 of 4</h6>
                <h2 class="fw-bold h1 mb-3">Which tools do you want to learn?</h2>
                <p class="text-muted fs-5">
                    For your goal: <span class="text-dark fw-semibold italic">"{{ $goal }}"</span>, 
                    our AI recommended these tools.
                </p>
                
                {{-- Progress Bar --}}
                <div class="progress mt-4 mx-auto" style="height: 6px; width: 200px; border-radius: 10px; background: #e2e8f0;">
                    <div class="progress-bar" role="progressbar" style="width: 25%; background: var(--accent-gradient);" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            <form action="{{ route('roadmap.focus') }}" method="POST">
                @csrf
                <input type="hidden" name="goal" value="{{ $goal }}">
                
                <div class="row g-4">
                    @foreach($allTools as $tool)
                        @php $isRecommended = in_array($tool->id, $selectedIds); @endphp
                        <div class="col-md-4 col-sm-6">
                            <label class="tool-card-label w-100 h-100">
                                <input type="checkbox" name="tools[]" value="{{ $tool->id }}" class="tool-checkbox d-none" {{ $isRecommended ? 'checked' : '' }}>
                                <div class="card tool-card h-100 cursor-pointer transition-all border-2 border-transparent">
                                    <div class="card-body p-4 text-center">
                                        {{-- Recommendation Badge --}}
                                        @if($isRecommended)
                                            <div class="badge bg-success-subtle text-success rounded-pill mb-3 px-3 py-2 border border-success-subtle">
                                                <i class="bi bi-stars me-1"></i> AI Recommended
                                            </div>
                                        @else
                                            <div class="mb-3" style="height: 33px;"></div> {{-- Spacer to keep grid even --}}
                                        @endif

                                        <div class="tool-icon-wrapper mb-3 mx-auto shadow-sm">
                                            @if($tool->logo)
                                                <img src="{{ $tool->logo }}" alt="{{ $tool->name }}" class="img-fluid rounded-3" style="width: 48px; height: 48px; object-fit: contain;">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="bi bi-tools fs-4 text-muted"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <h5 class="fw-bold mb-2">{{ $tool->name }}</h5>
                                        <p class="text-muted small mb-0">{{ Str::limit($tool->description, 60) }}</p>
                                    </div>
                                    <div class="selection-overlay">
                                        <i class="bi bi-check-circle-fill text-primary"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                {{-- Action Footer --}}
                <div class="mt-5 pt-4 text-center border-top">
                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg transition-all" style="background: var(--accent-gradient); border: none;">
                        Confirm Tools & Next Step <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1.5px; }
    .tool-card {
        border: 2px solid #edf2f7;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .tool-card:hover {
        transform: translateY(-5px);
        border-color: #cbd5e1;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08) !important;
    }
    .tool-checkbox:checked + .tool-card {
        border-color: #7c6fff;
        background: #fdfdff;
        box-shadow: 0 10px 30px rgba(124, 111, 255, 0.15) !important;
    }
    .selection-overlay {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 1.2rem;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .tool-checkbox:checked + .tool-card .selection-overlay {
        opacity: 1;
    }
    .tool-icon-wrapper {
        width: 70px;
        height: 70px;
        background: #fff;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #f1f5f9;
        transition: all 0.3s;
    }
    .tool-checkbox:checked + .tool-card .tool-icon-wrapper {
        border-color: #c4b5fd;
    }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
