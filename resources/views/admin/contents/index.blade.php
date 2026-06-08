@extends('layouts.admin')

@section('styles')
<!-- Tagify CSS -->
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bg-dark-glass {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        color: white;
    }
    .bg-success-light { background: rgba(34, 197, 94, 0.1); }
    .bg-secondary-light { background: rgba(100, 116, 139, 0.1); }
    .btn-white {
        background: white;
        border: none;
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }
    .btn-white:hover {
        transform: scale(1.1);
        background: #f8fafc;
    }
    .h-45 { height: 45px; }
    .content-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s;
    }
    .card:hover .content-overlay {
        opacity: 1;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: none;
        border-color: var(--accent-color);
        background: white !important;
    }

    /* Tagify Professional Styling */
    .tagify {
        --tags-border-color: #e2e8f0;
        --tags-hover-border-color: #cbd5e1;
        --tags-focus-border-color: #6366f1;
        border-radius: 10px;
        padding: 4px 8px;
        background: #f8fafc;
        border-color: #f1f5f9;
    }
    .tagify__tag {
        --tag-bg: #6366f1;
        --tag-text-color: #fff;
        --tag-border-radius: 6px;
    }
    .tagify__tag__removeBtn:hover {
        background: rgba(255,255,255,0.2);
    }

    /* Professional Form UI */
    .modal-content {
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s;
        border-radius: 10px !important;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
        border-color: #6366f1 !important;
        background: white !important;
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .fw-800 { font-weight: 800; }
    
    .tagify {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.25rem 0.5rem;
        --tags-focus-border-color: #6366f1;
        --tag-bg: #f1f5f9;
        --tag-text-color: #1e293b;
    }
    
    .tagify__tag {
        border-radius: 6px;
        font-weight: 600;
    }

    /* ── Pagination ───────────────────────────────────────────── */
    .pagination {
        gap: 4px;
        margin: 0;
    }
    .page-item .page-link {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px !important;
        border: 1.5px solid #e5e7eb;
        color: #374151;
        font-weight: 700;
        font-size: 13px;
        background: #fff;
        transition: all 0.2s;
        padding: 0;
        line-height: 1;
        text-decoration: none;
    }
    .page-item .page-link:hover {
        background: #f9fafb;
        border-color: #000;
        color: #000;
        box-shadow: none;
    }
    .page-item.active .page-link {
        background: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #f1f3f5;
        color: #d1d5db;
        pointer-events: none;
    }
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        width: auto;
        padding: 0 14px;
        font-size: 12px;
    }

    /* Hide 'Showing X to Y' text */
    nav .flex-1.sm\:hidden, 
    nav .hidden.sm\:flex-1 { 
        display: flex !important; 
        justify-content: center !important; 
    }
    nav .hidden.sm\:flex-1 > div:first-child {
        display: none !important;
    }
    nav .relative.z-0.inline-flex.shadow-sm.rounded-md {
        box-shadow: none !important;
    }
    /* Professional Polish */
    .stat-card-mini {
        background: #fff;
        border: 1px solid #f1f5f9;
        padding: 12px 20px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .stat-icon-mini {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .course-badge-pill {
        background: rgba(99, 102, 241, 0.08);
        color: #6366f1;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 6px;
    }

    /* Modal Premium Upgrades */
    .premium-modal-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: #ffffff;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem 2rem 1.25rem;
        position: relative;
    }
    .premium-modal-header h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 850;
        letter-spacing: -0.02em;
    }
    .premium-modal-header p {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.8rem;
    }
    .premium-modal-header .btn-close {
        filter: invert(1) grayscale(1) brightness(2);
        opacity: 0.8;
        transition: all 0.2s;
    }
    .premium-modal-header .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }
    .modal-section-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        transition: all 0.2s;
    }
    .modal-section-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .modal-section-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #4f46e5;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .modal-section-title i {
        font-size: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Quick Stats Bar -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-mini">
                <div class="stat-icon-mini bg-primary-subtle text-primary"><i class="bi bi-play-circle-fill"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Total Videos</div>
                    <div class="fw-800">{{ $stats['total_videos'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-mini">
                <div class="stat-icon-mini bg-success-subtle text-success"><i class="bi bi-collection-play-fill"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Active Courses</div>
                    <div class="fw-800">{{ $stats['total_courses'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-mini">
                <div class="stat-icon-mini bg-warning-subtle text-warning"><i class="bi bi-person-video"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Standalone</div>
                    <div class="fw-800">{{ $stats['standalone_videos'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-mini">
                <div class="stat-icon-mini bg-info-subtle text-info"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Total Lessons</div>
                    <div class="fw-800">{{ $stats['total_videos'] - $stats['standalone_videos'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-4">
        <div>
            <h1 class="page-title mb-1">Content Library</h1>
            <p class="text-muted small mb-0">Manage and organize your educational video resources.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <form action="{{ route('admin.contents.index') }}" method="GET" class="d-flex gap-2 align-items-center" id="filterForm">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="search" class="form-control ps-5 border-0 shadow-sm rounded-4 fw-medium" style="width: 280px; height: 45px;" placeholder="Search title, tool, or tag..." value="{{ request('search') }}">
                </div>
                
                <select name="category" class="form-select border-0 shadow-sm rounded-4 fw-medium h-45 px-3" style="width: 160px;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="tool" class="form-select border-0 shadow-sm rounded-4 fw-medium h-45 px-3" style="width: 150px;" onchange="this.form.submit()">
                    <option value="">All Tools</option>
                    @foreach($usedTools as $t)
                        <option value="{{ $t }}" {{ request('tool') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>

                <select name="skill_level" class="form-select border-0 shadow-sm rounded-4 fw-medium h-45 px-3" style="width: 130px;" onchange="this.form.submit()">
                    <option value="">All Levels</option>
                    <option value="Beginner" {{ request('skill_level') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="Intermediate" {{ request('skill_level') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="Advanced" {{ request('skill_level') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                </select>



                <select name="course_id" class="form-select border-0 shadow-sm rounded-4 fw-medium h-45 px-3" style="width: 170px;" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    <option value="none" {{ request('course_id') == 'none' ? 'selected' : '' }}>Standalone Only</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>

                <select name="sort" class="form-select border-0 shadow-sm rounded-4 fw-medium h-45 px-3" style="width: 140px;" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                </select>

                @if(request()->anyFilled(['search', 'category', 'tool', 'skill_level', 'course_id', 'sort']))
                    <a href="{{ route('admin.contents.index') }}" class="btn btn-light rounded-4 h-45 d-flex align-items-center px-3" title="Clear All"><i class="bi bi-x-lg"></i></a>
                @endif
            </form>
            <button class="btn btn-dark rounded-4 fw-800 px-4 h-45 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addContentModal">
                <i class="bi bi-plus-lg"></i> Add New
            </button>
        </div>
    </div>

    <div class="row g-4">
        @forelse($contents as $content)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="position-relative">
                        <img src="{{ $content->thumbnail_url }}" 
                             class="card-img-top" 
                             alt="{{ $content->title }}"
                             style="height: 180px; object-fit: cover;">
                        <span class="badge bg-dark-glass position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill small fw-bold">
                            {{ $content->skill_level }}
                        </span>
                        <div class="content-overlay">
                            <div class="d-flex gap-2">
                                <button class="btn btn-white btn-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#editContentModal{{ $content->id }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form action="{{ route('admin.contents.destroy', $content) }}" method="POST" onsubmit="return confirm('Delete this content?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-white btn-sm rounded-circle text-danger">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-muted small fw-bold text-uppercase mb-2 d-flex align-items-center gap-2">
                            {{ $content->category }}
                            @if($content->course)
                                <span class="course-badge-pill">{{ $content->course->title }}</span>
                            @endif
                        </div>
                        <h6 class="fw-bold text-dark mb-1 line-clamp-2">{{ $content->title }}</h6>
                        @if($content->section_part_label)
                            <div class="small text-muted mb-3 fw-600 d-flex align-items-center gap-1">
                                <i class="bi bi-layers-half"></i> {{ $content->section_part_label }} 
                                @if($content->sort_order)
                                    <span class="text-accent fw-800">• Part {{ $content->sort_order }}</span>
                                @endif
                            </div>
                        @else
                            <div class="mb-3"></div>
                        @endif
                        
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @if(is_array($content->connected_tools))
                                @foreach($content->connected_tools as $tool)
                                    @if(trim($tool))
                                        <span class="badge bg-light text-primary small border-0 px-2 py-1">{{ trim($tool) }}</span>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-light">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-youtube text-danger fs-5"></i>
                                <span class="small text-muted fw-500">YouTube</span>
                            </div>
                            <span class="badge bg-{{ $content->status == 'active' ? 'success' : 'secondary' }}-light text-{{ $content->status == 'active' ? 'success' : 'secondary' }} rounded-pill small">
                                {{ ucfirst($content->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Content Modal -->
            <div class="modal fade" id="editContentModal{{ $content->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                        <div class="premium-modal-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 text-white"><i class="bi bi-pencil-square me-2 text-warning"></i> Update Content Details</h5>
                                <p class="mb-0">Modify video details, categories, smart extension tools, and course sequencing.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.contents.update', $content) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4 bg-light-subtle">
                                <div class="row g-4">
                                    
                                    <!-- Left Column: Video Details & Description -->
                                    <div class="col-lg-6">
                                        <div class="modal-section-card shadow-sm h-100 mb-0">
                                            <div class="modal-section-title text-indigo mb-4">
                                                <i class="bi bi-play-btn-fill"></i> 1. Video Details & Content
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-fonts"></i> Video Title</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $content->title }}" placeholder="e.g. Master AI Automation with ChatGPT" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-youtube text-danger"></i> YouTube URL (English)</label>
                                                    <input type="url" name="video_url" class="form-control" value="{{ $content->video_url }}" placeholder="https://youtube.com/watch?v=..." required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-youtube text-danger"></i> YouTube URL (Arabic)</label>
                                                    <input type="url" name="video_url_ar" class="form-control" value="{{ $content->video_url_ar }}" placeholder="Optional Arabic version URL...">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-eye-slash-fill"></i> Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="active" {{ $content->status == 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="draft" {{ $content->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-clock"></i> Duration (Sec)</label>
                                                    <input type="number" name="duration_seconds" class="form-control" value="{{ $content->duration_seconds }}" placeholder="e.g. 600">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-tag"></i> Content Type</label>
                                                    <select name="type" class="form-select">
                                                        <option value="video" {{ $content->type == 'video' ? 'selected' : '' }}>Video</option>
                                                        <option value="article" {{ $content->type == 'article' ? 'selected' : '' }}>Article</option>
                                                        <option value="course" {{ $content->type == 'course' ? 'selected' : '' }}>Course Part</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end mb-2">
                                                    <div class="form-check form-switch custom-switch">
                                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="editFeatured{{ $content->id }}" {{ $content->is_featured ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold small text-uppercase" for="editFeatured{{ $content->id }}">Featured Video</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-text-left"></i> Description</label>
                                                    <textarea name="description" style="height: 140px;" class="form-control" placeholder="Brief overview...">{{ $content->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column: Extensions, Mapping & Sequence -->
                                    <div class="col-lg-6 d-flex flex-column gap-4">
                                        
                                        <!-- Card 2: Extension Links -->
                                        <div class="modal-section-card shadow-sm mb-0">
                                            <div class="modal-section-title text-success">
                                                <i class="bi bi-cpu-fill"></i> 2. Extension Recommendation Matching
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-grid-fill"></i> Category</label>
                                                    <input type="text" name="category" class="form-control" value="{{ $content->category }}" placeholder="e.g. Marketing">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-activity"></i> Skill Level</label>
                                                    <select name="skill_level" class="form-select">
                                                        <option value="Beginner" {{ $content->skill_level == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                                        <option value="Intermediate" {{ $content->skill_level == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                        <option value="Advanced" {{ $content->skill_level == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-plug-fill"></i> Connected Tools (Triggers Chrome Recommendations)</label>
                                                    <input type="text" name="connected_tools" class="form-control tagify-tools-input" value="{{ is_array($content->connected_tools) ? implode(',', $content->connected_tools) : '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card 3: Course Alignment -->
                                        <div class="modal-section-card shadow-sm mb-0 flex-grow-1">
                                            <div class="modal-section-title text-warning">
                                                <i class="bi bi-collection-play-fill"></i> 3. Course Alignment & Tags
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-journal-album"></i> Associated Course</label>
                                                    <select name="course_id" class="form-select">
                                                        <option value="">Individual Video (No Course)</option>
                                                        @foreach($courses as $course)
                                                            <option value="{{ $course->id }}" {{ $content->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-tag-fill"></i> Section Label</label>
                                                    <input type="text" name="section_part_label" class="form-control" value="{{ $content->section_part_label }}" placeholder="e.g. Section 1, Part 3">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-sort-numeric-down"></i> Order #</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $content->sort_order }}" placeholder="1">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-hash"></i> Search Tags (Press Enter)</label>
                                                    <input type="text" name="tags" class="form-control tagify-input" value="{{ $content->tags }}">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer border-0 bg-light py-3 px-4 d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm fw-700 rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-dark btn-sm fw-800 rounded-pill px-4 shadow-sm" style="height:38px;">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-collection-play-fill text-light" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold">No content added yet</h5>
                    <p class="text-muted">Start building your library by uploading your first YouTube video.</p>
                    <div class="mt-2">
                        <button class="btn btn-primary px-4 rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addContentModal">
                            Upload Content
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $contents->links() }}
    </div>
</div>

<!-- Add Content Modal -->
<div class="modal fade" id="addContentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="premium-modal-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 text-white"><i class="bi bi-cloud-arrow-up-fill me-2 text-warning"></i> Upload New Content</h5>
                    <p class="mb-0">Add a new educational YouTube video or tutorial to your interactive library.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.contents.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light-subtle">
                    <div class="row g-4">
                        
                        <!-- Left Column: Video Details & Description -->
                        <div class="col-lg-6">
                            <div class="modal-section-card shadow-sm h-100 mb-0">
                                <div class="modal-section-title text-indigo mb-4">
                                    <i class="bi bi-play-btn-fill"></i> 1. Video Details & Content
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-fonts"></i> Video Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="e.g. Master AI Automation with ChatGPT" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-youtube text-danger"></i> YouTube URL (English)</label>
                                        <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=..." required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-youtube text-danger"></i> YouTube URL (Arabic)</label>
                                        <input type="url" name="video_url_ar" class="form-control" placeholder="Optional Arabic version URL...">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-eye-slash-fill"></i> Publishing Status</label>
                                        <select name="status" class="form-select">
                                            <option value="active">Active</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-clock"></i> Duration (Sec)</label>
                                        <input type="number" name="duration_seconds" class="form-control" placeholder="e.g. 600">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-tag"></i> Content Type</label>
                                        <select name="type" class="form-select">
                                            <option value="video" selected>Video</option>
                                            <option value="article">Article</option>
                                            <option value="course">Course Part</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end mb-2">
                                        <div class="form-check form-switch custom-switch">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="addFeatured">
                                            <label class="form-check-label fw-bold small text-uppercase" for="addFeatured">Featured Video</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-text-left"></i> Description / Outline</label>
                                        <textarea name="description" style="height: 140px;" class="form-control" placeholder="Provide a brief summary of what this tutorial covers..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Extensions, Mapping & Sequence -->
                        <div class="col-lg-6 d-flex flex-column gap-4">
                            
                            <!-- Card 2: Extension Links -->
                            <div class="modal-section-card shadow-sm mb-0">
                                <div class="modal-section-title text-success">
                                    <i class="bi bi-cpu-fill"></i> 2. Extension Recommendation Matching
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-grid-fill"></i> Category</label>
                                        <input type="text" name="category" class="form-control" placeholder="e.g. Artificial Intelligence">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-activity"></i> Skill Level</label>
                                        <select name="skill_level" class="form-select">
                                            <option value="Beginner">Beginner</option>
                                            <option value="Intermediate">Intermediate</option>
                                            <option value="Advanced">Advanced</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-plug-fill"></i> Connected Tools (Triggers Chrome Recommendations)</label>
                                        <input type="text" name="connected_tools" class="form-control tagify-tools-input" placeholder="e.g. Gmail, Notion, Slack">
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Course Alignment -->
                            <div class="modal-section-card shadow-sm mb-0 flex-grow-1">
                                <div class="modal-section-title text-warning">
                                    <i class="bi bi-collection-play-fill"></i> 3. Course Alignment & Tags
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-journal-album"></i> Associated Course (Optional)</label>
                                        <select name="course_id" class="form-select">
                                            <option value="">Individual Video (No Course)</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-tag-fill"></i> Section Label</label>
                                        <input type="text" name="section_part_label" class="form-control" placeholder="e.g. Section 1: Intro, Part 3">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-sort-numeric-down"></i> Order #</label>
                                        <input type="number" name="sort_order" class="form-control" placeholder="1" value="0">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-hash"></i> Search Tags (Press Enter)</label>
                                        <input type="text" name="tags" class="form-control tagify-input" placeholder="Type tags and press enter">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3 px-4 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm fw-700 rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark btn-sm fw-800 rounded-pill px-4 shadow-sm" style="height:38px;">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Content
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Tagify JS -->
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Existing simple tagify for standard tags
        var inputs = document.querySelectorAll('.tagify-input');
        inputs.forEach(function(input) {
            new Tagify(input, {
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
            });
        });

        // Advanced tagify for connected tools
        const appsList = [
            @foreach($tools as $tool)
                { value: "{{ $tool->name }}", icon: "{{ $tool->logo }}" },
            @endforeach
        ];

        var toolsInputs = document.querySelectorAll('.tagify-tools-input');
        toolsInputs.forEach(function(input) {
            const rawValue = input.value.trim();
            if (rawValue && !rawValue.startsWith('[')) {
                const names = rawValue.split(',').map(v => v.trim()).filter(Boolean);
                const mapped = names.map(name => {
                    const match = appsList.find(a => a.value.toLowerCase() === name.toLowerCase());
                    return match ? match : { value: name };
                });
                input.value = JSON.stringify(mapped);
            }

            new Tagify(input, {
                whitelist: appsList,
                tagTextProp: 'value',
                enforceWhitelist: false,
                skipInvalid: false,
                dropdown: {
                    closeOnSelect: false,
                    enabled: 0,
                    classname: 'tags-look',
                    searchKeys: ['value'],
                    maxItems: 40
                },
                templates: {
                    tag(tagData) {
                        return `
                            <tag title="${tagData.value}"
                                 contenteditable="false"
                                 spellcheck="false"
                                 tabIndex="-1"
                                 class="tagify__tag"
                                 value="${tagData.value}">
                                <x title="" class="tagify__tag__removeBtn" role="button" aria-label="remove tag"></x>
                                <div>
                                    ${tagData.icon ? `<img src="${tagData.icon}" style="width: 14px; height: 14px; object-fit: contain; margin-right: 4px;" onerror="this.style.display='none'">` : ''}
                                    <span class="tagify__tag-text">${tagData.value}</span>
                                </div>
                            </tag>`;
                    },
                    dropdownItem(tagData) {
                        return `
                            <div class="tagify__dropdown__item ${tagData.class || ''}"
                                 tabindex="0"
                                 role="option"
                                 value="${tagData.value}" style="display:flex; align-items:center; gap:8px; padding:10px;">
                                ${tagData.icon ? `<img src="${tagData.icon}" style="width: 18px; height: 18px; object-fit: contain;" onerror="this.style.display='none'">` : ''}
                                <span>${tagData.value}</span>
                            </div>`;
                    }
                },
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
            });
        });
    });
</script>
@endsection
