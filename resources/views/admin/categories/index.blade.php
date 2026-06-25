@extends('layouts.admin')

@section('styles')
<style>
    .category-card {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        border-color: #6366f1;
    }
    .category-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 16px;
    }
    .category-name {
        font-weight: 800;
        font-size: 1.15rem;
        color: #0f172a;
        margin-bottom: 8px;
        font-family: 'Outfit', sans-serif;
    }
    .category-stats {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        gap: 12px;
    }
    .category-status-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .action-btn-group {
        display: flex;
        gap: 8px;
        margin-top: 20px;
    }
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        transition: all 0.2s;
    }
    .action-btn:hover {
        background: #f8fafc;
        color: #000;
        border-color: #cbd5e1;
    }
    .action-btn-delete:hover {
        background: #fff1f2;
        color: #e11d48;
        border-color: #fecaca;
    }
    .premium-modal-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: #ffffff;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem 2rem;
    }
    .modal-content {
        border: none;
        border-radius: 16px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
        <div>
            <h1 class="page-title mb-1">Category Management</h1>
            <p class="text-muted small mb-0">Organize and classify your course and video content.</p>
        </div>
        <button class="btn btn-dark rounded-4 fw-800 px-4 h-45 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg"></i> Add New Category
        </button>
    </div>

    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="category-card">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="category-icon bg-primary-subtle text-primary">
                                <i class="bi bi-folder2-open"></i>
                            </div>
                            <span class="category-status-badge bg-{{ $category->status == 'active' ? 'success' : 'secondary' }}-light text-{{ $category->status == 'active' ? 'success' : 'secondary' }}">
                                {{ $category->status }}
                            </span>
                        </div>
                        <h3 class="category-name">{{ $category->name }}</h3>
                        <div class="category-stats">
                            <span><i class="bi bi-play-circle me-1"></i> {{ $category->contents_count }} Videos</span>
                            <span><i class="bi bi-collection-play me-1"></i> {{ $category->courses_count }} Courses</span>
                        </div>
                    </div>
                    
                    <div class="action-btn-group">
                        <button class="action-btn" title="Edit" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Note: This will un-categorize all associated videos. Continue?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn action-btn-delete" title="Delete">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content overflow-hidden">
                        <div class="premium-modal-header">
                            <h5 class="mb-0">Edit Category</h5>
                        </div>
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-body p-4 text-start">
                                <div class="mb-3">
                                    <label class="form-label fw-800 text-uppercase small text-muted">Category Name</label>
                                    <input type="text" name="name" class="form-control rounded-3" value="{{ $category->name }}" required>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-800 text-uppercase small text-muted">Status</label>
                                    <select name="status" class="form-select rounded-3">
                                        <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="draft" {{ $category->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0">
                                <button type="button" class="btn btn-light rounded-3 fw-bold" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border border-light shadow-sm">
                    <i class="bi bi-folder-x fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="fw-800">No categories found</h5>
                    <p class="text-muted small">Start by creating your first dynamic category.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $categories->links() }}
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="premium-modal-header">
                <h5 class="mb-0">Add New Category</h5>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label fw-800 text-uppercase small text-muted">Category Name</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Graphic Design" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-800 text-uppercase small text-muted">Status</label>
                        <select name="status" class="form-select rounded-3">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
