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
    /* Smart Table Pro Styling */
    .content-table-wrapper {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .content-table {
        margin-bottom: 0;
    }
    .content-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 18px 24px;
        border-top: none;
    }
    .content-table tbody td {
        padding: 16px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #1e293b;
        transition: all 0.2s;
    }
    .content-table tbody tr:last-child td {
        border-bottom: none;
    }
    .content-table tbody tr:hover td {
        background: #fdfdfe;
    }
    .table-thumb {
        width: 100px;
        aspect-ratio: 16/9;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .table-title {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .table-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }
    .action-btn-circle {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .action-btn-circle:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #000;
        transform: translateY(-2px);
    }
    .action-btn-delete:hover {
        background: #fff1f2;
        border-color: #fecaca;
        color: #e11d48;
    }
    .tool-badge-item {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Restored Stats Card Styling */
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

    /* Restored Modal Premium Upgrades */
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
    
    /* Premium Form Inputs */
    .modal-body .form-control, 
    .modal-body .form-select {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
        font-weight: 500;
        background-color: #fff;
        transition: all 0.2s ease;
    }
    .modal-body .form-control:focus, 
    .modal-body .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background-color: #fff;
    }
    .modal-body .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    .text-indigo { color: #4f46e5 !important; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Quick Stats Bar -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card-mini shadow-sm">
                <div class="stat-icon-mini bg-primary-subtle text-primary"><i class="bi bi-play-circle-fill"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Total Videos</div>
                    <div class="fw-800 fs-5">{{ $stats['total_videos'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card-mini shadow-sm">
                <div class="stat-icon-mini bg-success-subtle text-success"><i class="bi bi-collection-play-fill"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Active Courses</div>
                    <div class="fw-800 fs-5">{{ $stats['total_courses'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card-mini shadow-sm">
                <div class="stat-icon-mini bg-warning-subtle text-warning"><i class="bi bi-person-video"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Standalone</div>
                    <div class="fw-800 fs-5">{{ $stats['standalone_videos'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card-mini shadow-sm">
                <div class="stat-icon-mini bg-info-subtle text-info"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Total Lessons</div>
                    <div class="fw-800 fs-5">{{ $stats['total_videos'] - $stats['standalone_videos'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-4 mb-4">
        <div>
            <h1 class="page-title mb-1">Content Library</h1>
            <p class="text-muted small mb-0">Manage and organize your educational video resources.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <form action="{{ route('admin.contents.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center" id="filterForm">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="search" class="form-control ps-5 border-0 shadow-sm rounded-4 fw-medium" style="width: 240px; height: 45px;" placeholder="Search title..." value="{{ request('search') }}">
                </div>
                
                <select name="category_id" class="form-select border-0 shadow-sm rounded-4 fw-medium h-45 px-3" style="width: 140px;" onchange="this.form.submit()">
                    <option value="">Categories</option>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="course_id" class="form-select border-0 shadow-sm rounded-4 fw-medium h-45 px-3" style="width: 150px;" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    <option value="none" {{ request('course_id') == 'none' ? 'selected' : '' }}>Standalone</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>

                <select name="sort" class="form-select border-0 shadow-sm rounded-4 fw-medium h-45 px-3" style="width: 120px;" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                </select>

                @if(request()->anyFilled(['search', 'category', 'tool', 'skill_level', 'course_id', 'sort']))
                    <a href="{{ route('admin.contents.index') }}" class="btn btn-light rounded-4 h-45 d-flex align-items-center px-3" title="Clear All"><i class="bi bi-x-lg"></i></a>
                @endif
            </form>
            <button class="btn btn-dark rounded-4 fw-800 px-4 h-45 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addContentModal">
                <i class="bi bi-plus-lg"></i> Add New Content
            </button>
        </div>
    </div>

    <div class="content-table-wrapper">
        <div class="table-responsive">
            <table class="table content-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">Thumbnail</th>
                        <th>Content Details</th>
                        <th>Course & Category</th>
                        <th>Connected Tools</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contents as $content)
                        <tr>
                            <td>
                                <img src="{{ $content->thumbnail_url }}" class="table-thumb" alt="">
                            </td>
                            <td>
                                <div class="table-title">{{ $content->title }}</div>
                                <div class="table-subtitle">
                                    @if($content->section_part_label)
                                        <i class="bi bi-layers-half small"></i> {{ $content->section_part_label }}
                                        @if($content->sort_order) • Part {{ $content->sort_order }} @endif
                                    @else
                                        <i class="bi bi-play-circle small"></i> Video Content
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-700 small text-dark">{{ $content->category_rel->name ?? $content->category }}</div>
                                @if($content->course)
                                    <div class="small mt-1"><span class="course-badge-pill" style="font-size: 0.6rem;">{{ $content->course->title }}</span></div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1" style="max-width: 200px;">
                                    @if(is_array($content->connected_tools))
                                        @foreach($content->connected_tools as $tool)
                                            @if(trim($tool))
                                                <span class="tool-badge-item">{{ trim($tool) }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-700" style="font-size: 0.7rem;">
                                    {{ $content->skill_level }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $content->status == 'active' ? 'success' : 'secondary' }}-light text-{{ $content->status == 'active' ? 'success' : 'secondary' }} rounded-pill px-3 py-2 fw-700" style="font-size: 0.7rem;">
                                    {{ ucfirst($content->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Edit Button --}}
                                    <button class="action-btn-circle" title="Edit" data-bs-toggle="modal" data-bs-target="#editContentModal{{ $content->id }}">
                                        <i class="bi bi-pencil-fill" style="font-size: 0.8rem;"></i>
                                    </button>
                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.contents.destroy', $content) }}" method="POST" onsubmit="return confirm('Delete this content permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn-circle action-btn-delete" title="Delete">
                                            <i class="bi bi-trash-fill" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-folder-x fs-1 mb-3 d-block"></i>
                                    No content found matching your criteria.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $contents->links() }}
    </div>
</div>

@push('modals')
<!-- All Edit Modals -->
@foreach($contents as $content)
    <div class="modal fade" id="editContentModal{{ $content->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="premium-modal-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 text-white"><i class="bi bi-pencil-square me-2 text-warning"></i> Update Content Details</h5>
                        <p class="mb-0">Modify video details, categories, tools, and course sequencing.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.contents.update', $content) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4 bg-light-subtle">
                        <div class="row g-4 text-start">
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
                                            <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-cloud-upload text-indigo"></i> Video Upload</label>
                                            <input type="file" name="video_file" class="form-control" accept="video/*">
                                            <div class="small text-muted mt-1">Leave empty to keep current video.</div>
                                        </div>
                                        <input type="hidden" name="thumbnail_base64" class="auto-thumbnail-input">
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
                            <div class="col-lg-6 d-flex flex-column gap-4">
                                <div class="modal-section-card shadow-sm mb-0">
                                    <div class="modal-section-title text-success">
                                        <i class="bi bi-cpu-fill"></i> 2. Extension Matching
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-grid-fill"></i> Category</label>
                                            <select name="category_id" class="form-select" required>
                                                <option value="">Select Category</option>
                                                @foreach($allCategories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $content->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
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
                                            <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-plug-fill"></i> Connected Tools</label>
                                            <input type="text" name="connected_tools" class="form-control tagify-tools-input" value="{{ is_array($content->connected_tools) ? implode(',', $content->connected_tools) : '' }}">
                                        </div>
                                    </div>
                                </div>
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
                                            <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-hash"></i> Search Tags</label>
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
@endforeach
@endpush

<!-- Add Content Modal -->
<div class="modal fade" id="addContentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="premium-modal-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 text-white"><i class="bi bi-cloud-arrow-up-fill me-2 text-warning"></i> Upload New Content</h5>
                    <p class="mb-0">Add a new educational video or tutorial to your interactive library.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light-subtle">
                    <div class="row g-4 text-start">
                        
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
                                        <label class="form-label d-flex align-items-center gap-1"><i class="bi bi-cloud-upload text-indigo"></i> Video Upload</label>
                                        <input type="file" name="video_file" class="form-control" accept="video/*">
                                        <div class="small text-muted mt-1">Select an MP4/MOV file to upload.</div>
                                    </div>
                                    <input type="hidden" name="thumbnail_base64" class="auto-thumbnail-input">

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
                                        <select name="category_id" class="form-select" required>
                                            <option value="">Select Category</option>
                                            @foreach($allCategories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
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

        // ── PREMIUM AUTO THUMBNAIL GENERATOR ──
        function generateBanner(title) {
            const canvas = document.createElement("canvas");
            canvas.width = 1280; canvas.height = 720;
            const ctx = canvas.getContext("2d");
            
            // 1. Premium Background: Deep Indigo Mesh Gradient
            const grad = ctx.createRadialGradient(300, 200, 0, 640, 360, 1000);
            grad.addColorStop(0, "#1e1b4b"); // Deep Indigo
            grad.addColorStop(0.5, "#0f172a"); // Slate Dark
            grad.addColorStop(1, "#020617"); // Absolute Dark
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, 1280, 720);

            // 2. Add Subtle Decorative Glows
            const glow1 = ctx.createRadialGradient(1100, 100, 0, 1100, 100, 400);
            glow1.addColorStop(0, "rgba(99, 102, 241, 0.15)"); // Indigo Glow
            glow1.addColorStop(1, "transparent");
            ctx.fillStyle = glow1;
            ctx.fillRect(0, 0, 1280, 720);

            // 3. Add Modern Grid Overlay (Subtle)
            ctx.strokeStyle = "rgba(255, 255, 255, 0.03)";
            ctx.lineWidth = 1;
            for(let i = 0; i < 1280; i += 80) { ctx.beginPath(); ctx.moveTo(i, 0); ctx.lineTo(i, 720); ctx.stroke(); }
            for(let i = 0; i < 720; i += 80) { ctx.beginPath(); ctx.moveTo(0, i); ctx.lineTo(1280, i); ctx.stroke(); }

            // 4. Text Styling - Using Premium Weights
            ctx.shadowColor = "rgba(0, 0, 0, 0.5)";
            ctx.shadowBlur = 20;
            ctx.fillStyle = "#ffffff";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            
            // Dynamic Font Size based on length
            const fontSize = title.length > 50 ? 55 : 72;
            ctx.font = `900 ${fontSize}px "Inter", "system-ui", "-apple-system", sans-serif`;
            
            // 5. Wrap Text with Elegant Spacing
            const words = title.toUpperCase().split(" ");
            const lines = [];
            let line = "";
            words.forEach(w => {
                if (ctx.measureText(line + w).width < 1000) line += w + " ";
                else { lines.push(line.trim()); line = w + " "; }
            });
            lines.push(line.trim());
            
            const lineHeight = fontSize * 1.2;
            lines.forEach((l, i) => {
                // Gradient text for extra premium feel
                const textGrad = ctx.createLinearGradient(640, 360 - 100, 640, 360 + 100);
                textGrad.addColorStop(0, "#ffffff");
                textGrad.addColorStop(1, "#94a3b8"); // Slate 400
                ctx.fillStyle = textGrad;

                ctx.fillText(l, 640, 360 + (i - (lines.length-1)/2) * lineHeight);
            });

            // 6. Branding Accent
            ctx.shadowBlur = 0;
            ctx.fillStyle = "rgba(99, 102, 241, 0.8)";
            ctx.font = '800 24px "Inter", sans-serif';
            ctx.fillText("Daleel MENTOR AI", 640, 600);
            
            // Decorative line below branding
            ctx.beginPath();
            ctx.strokeStyle = "rgba(99, 102, 241, 0.4)";
            ctx.lineWidth = 2;
            ctx.moveTo(540, 620);
            ctx.lineTo(740, 620);
            ctx.stroke();
            
            return canvas.toDataURL("image/jpeg", 0.9);
        }

        function setupThumbnailAutoGeneration(modalId) {
            const modal = document.querySelector(modalId);
            if (!modal) return;

            const titleInput = modal.querySelector('input[name="title"]');
            const base64Input = modal.querySelector('.auto-thumbnail-input');
            const previewImg = modal.querySelector('.auto-thumbnail-preview');
            const emptyText = modal.querySelector('.empty-preview-text');

            if (!titleInput) return;

            titleInput.addEventListener('input', function() {
                const title = this.value.trim();
                if (title) {
                    const base64 = generateBanner(title);
                    if (base64Input) base64Input.value = base64;
                    if (previewImg) {
                        previewImg.src = base64;
                        previewImg.style.display = 'block';
                    }
                    if (emptyText) emptyText.classList.add('d-none');
                } else {
                    if (previewImg && !previewImg.getAttribute('src').startsWith('http')) {
                        previewImg.style.display = 'none';
                        if (emptyText) emptyText.classList.remove('d-none');
                    }
                }
            });
        }

        setupThumbnailAutoGeneration('#addContentModal');
        document.querySelectorAll('[id^="editContentModal"]').forEach(modal => {
            setupThumbnailAutoGeneration('#' + modal.id);
        });
    });
</script>
@endsection
