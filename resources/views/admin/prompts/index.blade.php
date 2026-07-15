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
    ];

    $promptsByKey = $prompts->keyBy('key');
@endphp

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">AI Prompt Manager</h1>
            <p class="text-muted">Manage the dynamic prompts used by Dashboard Search and AI Mentor Search.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Whoops! Please check the fields below:</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 align-items-start">
        @foreach($promptGroups as $groupTitle => $group)
            <div class="col-12 col-xl-6">
                <section>
                    <div class="bg-white border border-light-subtle rounded-3 p-4 mb-3">
                        <h4 class="fw-800 text-dark mb-1">{{ $groupTitle }}</h4>
                        <p class="text-muted small mb-3">{{ $group['description'] }}</p>
                        <ol class="small text-secondary ps-3 mb-0">
                            @foreach($group['steps'] as $step)
                                <li class="mb-1">{{ $step }}</li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @foreach($group['keys'] as $promptKey)
                            @php($prompt = $promptsByKey->get($promptKey))
                            @continue(!$prompt)
                            @php($details = $promptDetails[$prompt->key] ?? null)

                            <div class="bg-white border border-light-subtle rounded-3 p-3">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">{{ $prompt->title }}</h6>
                                            <p class="text-muted small mb-0">{{ $prompt->key }}</p>
                                        </div>
                                        <button class="btn btn-light rounded-3 fw-bold border px-3 py-2" data-bs-toggle="modal" data-bs-target="#editPromptModal{{ $prompt->id }}">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </button>
                                    </div>

                                    @if($details)
                                        <p class="small text-secondary mb-0"><strong class="text-dark">Role:</strong> {{ $details['role'] }}</p>
                                    @endif

                                    <div class="bg-light p-3 rounded-3 border border-light-subtle" style="max-height: 160px; overflow: auto; font-family: monospace; font-size: 12px; white-space: pre-wrap; color: #475569;">{{ $prompt->prompt }}</div>
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
                                                        <i class="bi bi-info-circle me-1"></i> This prompt is dynamic and is used directly by its search controller.
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
            </div>
        @endforeach
    </div>
</div>
@endsection
