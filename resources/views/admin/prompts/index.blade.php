@extends('layouts.admin')

@section('content')
@php
    $promptGroups = [
        'Dashboard Search' => [
            'description' => '/search/advanced',
            'steps' => [
                'User types a search query from the dashboard search page.',
                'The controller sends that query and the available tool names to GPT.',
                'GPT decides if the user wants a video, a course, or a roadmap.',
                'If it is a roadmap, GPT picks the matching tool IDs.',
                'If it is a video or course, the app checks database matches first.',
                'If the app still needs help, GPT picks the best result from the candidate list.',
            ],
            'keys' => [
                'search_parser_system',
                'search_parser_user',
                'search_tool_matching',
                'search_hybrid_pick',
            ],
        ],
        'AI Mentor Search' => [
            'description' => '/ai-mentor and extension mentor suggestions',
            'steps' => [
                'User asks a mentor question from the AI Mentor page or extension.',
                'The controller reads the active domain or page URL.',
                'The app finds matching lessons from the database using that page context.',
                'The controller sends the user question and candidate lesson titles/descriptions to GPT.',
                'GPT returns the best matching lesson IDs.',
                'The mentor page shows those selected lessons to the user.',
            ],
            'keys' => [
                'ai_mentor_system',
                'ai_mentor_user',
            ],
        ],
        'Inspector Report' => [
            'description' => '/team/inspector-report',
            'steps' => [
                'Inspector fills session parameters (sessions count, hours, mode, notes) on the report page.',
                'The controller collects the top tools used by learners.',
                'GPT receives the inspector inputs + top tools and generates a structured teaching plan.',
                'The plan is displayed in the report and can be included in the PDF download.',
            ],
            'keys' => [
                'inspector_plan_generator',
            ],
        ],
    ];

    $promptDetails = [
        'search_parser_system' => [
            'role' => 'Sets the AI role for dashboard search before the user query is parsed.',
        ],
        'search_parser_user' => [
            'role' => 'Sends the actual dashboard search text to GPT so GPT can decide the result type and matching tools.',
        ],
        'search_tool_matching' => [
            'role' => 'Selects tool IDs when dashboard search decides the user wants a roadmap.',
        ],
        'search_hybrid_pick' => [
            'role' => 'Chooses the best course or lesson when normal matching cannot confidently pick one.',
        ],
        'ai_mentor_system' => [
            'role' => 'Sets the AI mentor role so it ranks lessons for the current page or extension context.',
        ],
        'ai_mentor_user' => [
            'role' => 'Sends the user question and candidate lesson titles/descriptions so GPT can return the best lesson IDs.',
        ],
        'inspector_plan_generator' => [
            'role' => 'Generates a structured teaching plan from inspector inputs (sessions, hours, mode, notes) combined with the top tools learners are using.',
        ],
    ];

    $promptsByKey = $prompts->keyBy('key');
@endphp

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">AI Prompt Manager</h1>
            <p class="text-muted">Manage prompts for all AI-powered features.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Whoops! Please check the fields below:</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $groupStyles = [
            'Dashboard Search' => ['icon' => 'bi-search', 'accent' => '#0d6efd', 'bg' => '#f0f7ff'],
            'AI Mentor Search' => ['icon' => 'bi-robot', 'accent' => '#6f42c1', 'bg' => '#f5f0ff'],
            'Inspector Report' => ['icon' => 'bi-file-earmark-bar-graph', 'accent' => '#0E6B5C', 'bg' => '#f0fdf4'],
        ];
    @endphp

    <div class="d-flex flex-column gap-5">
        @foreach($promptGroups as $groupTitle => $group)
            @php($gs = $groupStyles[$groupTitle] ?? ['icon' => 'bi-gear', 'accent' => '#6c757d', 'bg' => '#f8f9fa'])
            <section>
                {{-- Section header with accent bar --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:4px;height:28px;border-radius:2px;background:{{ $gs['accent'] }};flex-shrink:0;"></div>
                    <div style="width:36px;height:36px;border-radius:8px;background:{{ $gs['bg'] }};color:{{ $gs['accent'] }};display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                        <i class="bi {{ $gs['icon'] }}"></i>
                    </div>
                    <div>
                        <h4 class="fw-800 text-dark mb-0" style="font-size:1.05rem;">{{ $groupTitle }}</h4>
                        <p class="text-muted small mb-0">{{ $group['description'] }}</p>
                    </div>
                </div>

                {{-- Flow steps --}}
                <div style="background:{{ $gs['bg'] }};border:1px solid {{ $gs['accent'] }}15;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1rem;">
                    <p class="small fw-700 text-muted mb-2" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.04em;">How it works</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($group['steps'] as $i => $step)
                        <div class="d-flex align-items-center gap-2 small" style="color:#475569;">
                            <span style="width:20px;height:20px;border-radius:50%;background:{{ $gs['accent'] }};color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0;">{{ $i+1 }}</span>
                            <span>{{ $step }}</span>
                            @if(!$loop->last)<span style="color:#ccc;font-size:.7rem;margin:0 2px;"><i class="bi bi-chevron-right"></i></span>@endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Prompt cards --}}
                <div class="d-flex flex-column gap-3">
                    @foreach($group['keys'] as $promptKey)
                        @php($prompt = $promptsByKey->get($promptKey))
                        @continue(!$prompt)
                        @php($details = $promptDetails[$prompt->key] ?? null)

                        <div style="border:1px solid #e9ecef;border-left:3px solid {{ $gs['accent'] }};border-radius:10px;background:#fff;">
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $prompt->title }}</h6>
                                        <code class="small" style="color:{{ $gs['accent'] }};font-size:.72rem;">{{ $prompt->key }}</code>
                                    </div>
                                    <button class="btn btn-sm rounded-3 fw-bold border px-3 py-1.5 flex-shrink-0" style="border-color:#dee2e6!important;background:#fff;color:{{ $gs['accent'] }};" data-bs-toggle="modal" data-bs-target="#editPromptModal{{ $prompt->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                </div>

                                @if($details)
                                    <p class="small text-secondary mb-2"><strong class="text-dark">Role:</strong> {{ $details['role'] }}</p>
                                @endif

                                <div style="background:#f8f9fa;border:1px solid #e9ecef;border-radius:8px;padding:.75rem;max-height:140px;overflow:auto;font-family:monospace;font-size:.72rem;white-space:pre-wrap;color:#475569;line-height:1.5;">{{ $prompt->prompt }}</div>
                            </div>
                        </div>

                        <!-- Edit Prompt Modal -->
                        <div class="modal fade" id="editPromptModal{{ $prompt->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                    <div class="modal-header bg-light border-0 p-4 pb-0">
                                        <div>
                                            <h5 class="modal-title fw-800 text-dark mb-1">Edit AI Prompt</h5>
                                            <p class="text-muted small mb-0">{{ $prompt->title }} ({{ $prompt->key }})</p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.prompts.update', $prompt) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-700 text-muted">Prompt Template</label>
                                                <textarea name="prompt" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" style="font-family: monospace; font-size: 13px;" rows="14" required>{{ $prompt->prompt }}</textarea>
                                                <div class="form-text text-muted mt-2">
                                                    <i class="bi bi-info-circle me-1"></i> This prompt is dynamic and is used directly by its controller.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-0 p-4">
                                            <button type="button" class="btn btn-light rounded-3 fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">Update Prompt</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</div>
@endsection
