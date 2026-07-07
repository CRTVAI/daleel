@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Dynamic Blog & Resource Manager</h1>
            <p class="text-muted">Manage SEO-optimized blogs, training resources, and guides for Daleel AI portal users.</p>
        </div>
        <button class="btn btn-primary rounded-4 fw-bold shadow-sm py-2.5 px-4" data-bs-toggle="modal" data-bs-target="#addBlogPostModal">
            <i class="bi bi-plus-lg me-2"></i> Add New Blog Post
        </button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Errors detected:</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Blog Posts Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted fw-bold small">Cover image & Title</th>
                        <th class="py-3 text-muted fw-bold small">Slug & Route</th>
                        <th class="py-3 text-muted fw-bold small">Category</th>
                        <th class="py-3 text-muted fw-bold small">SEO Status</th>
                        <th class="py-3 text-muted fw-bold small">Status</th>
                        <th class="py-3 text-muted fw-bold small">Published At</th>
                        <th class="pe-4 py-3 text-end text-muted fw-bold small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 60px; height: 40px; background: #e2e8f0; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        @if($blog->cover_image)
                                            <img src="{{ $blog->cover_image }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="bi bi-image text-muted" style="font-size: 14px;"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ \Illuminate\Support\Str::limit($blog->title, 40) }}</div>
                                        <span class="small text-muted">Author: {{ $blog->author_name }} · {{ $blog->read_time_minutes }}m read</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><code class="small text-primary">/blog/{{ $blog->slug }}</code></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-3 fw-bold">{{ $blog->category ?? 'General' }}</span>
                            </td>
                            <td>
                                @if($blog->meta_title || $blog->meta_description)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-3 small">
                                        <i class="bi bi-search me-1"></i> SEO Configured
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-3 small">
                                        <i class="bi bi-exclamation-circle me-1"></i> Missing Meta
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($blog->status === 'published')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1" style="font-size: 11px;">Published</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1" style="font-size: 11px;">Draft</span>
                                @endif
                                @if($blog->is_featured)
                                    <span class="badge bg-primary rounded-pill px-2.5 py-1" style="font-size: 10px;"><i class="bi bi-star-fill me-1"></i>Featured</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $blog->published_at ? $blog->published_at->format('M d, Y h:i A') : 'Not Published' }}
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-2">
                                    <button class="btn btn-sm btn-light rounded-3 fw-bold px-3 border-0" data-bs-toggle="modal" data-bs-target="#editBlogPostModal{{ $blog->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger rounded-3 fw-bold border-0">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('public.blog.show', $blog->slug) }}" target="_blank" class="btn btn-sm btn-light text-primary rounded-3 fw-bold border-0">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Blog Post Modal -->
                        <div class="modal fade" id="editBlogPostModal{{ $blog->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                    <div class="modal-header bg-light border-0 p-4 pb-0">
                                        <div>
                                            <h5 class="modal-title fw-800 text-dark mb-1">Edit Blog Post</h5>
                                            <p class="text-muted small mb-0">Modify details, content structure, and SEO tags.</p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label class="form-label small fw-700 text-muted">Post Title</label>
                                                    <input type="text" name="title" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="{{ $blog->title }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-700 text-muted">Route Slug</label>
                                                    <input type="text" name="slug" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="{{ $blog->slug }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-700 text-muted">Category</label>
                                                    <input type="text" name="category" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="{{ $blog->category }}" placeholder="e.g. Guides, Insights">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-700 text-muted">Author Name</label>
                                                    <input type="text" name="author_name" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="{{ $blog->author_name }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-700 text-muted">Read Time (minutes)</label>
                                                    <input type="number" name="read_time_minutes" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="{{ $blog->read_time_minutes }}">
                                                </div>
                                                
                                                <div class="col-12">
                                                    <label class="form-label small fw-700 text-muted">Excerpt / Summary (useful for listing cards & meta description)</label>
                                                    <textarea name="excerpt" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" rows="2">{{ $blog->excerpt }}</textarea>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label small fw-700 text-muted">Markdown / Html Body Content</label>
                                                    <textarea name="content" class="form-control rounded-3 border-light bg-light p-3 fw-semibold font-monospace" rows="8" required>{{ $blog->content }}</textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">Change Cover Image (JPG, PNG, WebP)</label>
                                                    <input type="file" name="cover_image" class="form-control rounded-3 border-light bg-light p-2.5">
                                                    @if($blog->cover_image)
                                                        <span class="small text-muted mt-1 d-block">Current: <a href="{{ $blog->cover_image }}" target="_blank">View image</a></span>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small fw-700 text-muted">Status</label>
                                                    <select name="status" class="form-select rounded-3 border-light bg-light p-3 fw-semibold">
                                                        <option value="published" {{ $blog->status == 'published' ? 'selected' : '' }}>Published</option>
                                                        <option value="draft" {{ $blog->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 d-flex align-items-center pt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeaturedEdit{{ $blog->id }}" {{ $blog->is_featured ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold small text-muted" for="isFeaturedEdit{{ $blog->id }}">
                                                            Featured Post
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-4">
                                                    <hr class="opacity-50">
                                                    <h6 class="fw-bold text-dark"><i class="bi bi-search me-1 text-primary"></i> SEO Meta Configuration</h6>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">Meta Title</label>
                                                    <input type="text" name="meta_title" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="{{ $blog->meta_title }}" placeholder="If empty, falls back to post title">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-700 text-muted">Meta Keywords</label>
                                                    <input type="text" name="meta_keywords" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="{{ $blog->meta_keywords }}" placeholder="e.g. training, productivity, AI workflows">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-700 text-muted">Meta Description</label>
                                                    <textarea name="meta_description" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" rows="2" placeholder="If empty, falls back to excerpt">{{ $blog->meta_description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-0 p-4">
                                            <button type="button" class="btn btn-light rounded-3 fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">Post Updates</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-file-earmark-text-fill fs-2 opacity-50 mb-3 d-block text-primary"></i>
                                    <h5 class="fw-bold">No blog posts found</h5>
                                    <p class="small mb-0">Create your first blog post by clicking the "Add New Blog Post" button above.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $blogs->links() }}
    </div>
</div>

<!-- Add Blog Post Modal -->
<div class="modal fade" id="addBlogPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-0 p-4 pb-0">
                <div>
                    <h5 class="modal-title fw-800 text-dark mb-1">Create Blog Post</h5>
                    <p class="text-muted small mb-0">Draft or publish a new search-optimized article.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-700 text-muted">Post Title</label>
                            <input type="text" name="title" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" placeholder="e.g. 5 Ways to Scale Team Workflows with AI" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">Route Slug (Optional)</label>
                            <input type="text" name="slug" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" placeholder="auto-generated if empty">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">Category</label>
                            <input type="text" name="category" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" placeholder="Insights, Guides, Tutorials" value="Insights">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">Author Name</label>
                            <input type="text" name="author_name" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="Admin">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">Read Time (minutes)</label>
                            <input type="number" name="read_time_minutes" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="5">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small fw-700 text-muted">Excerpt / Summary (ideal for meta tags & cards)</label>
                            <textarea name="excerpt" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" rows="2" placeholder="Brief summary of the article..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-700 text-muted">Article Body (Supports HTML formatting)</label>
                            <textarea name="content" class="form-control rounded-3 border-light bg-light p-3 fw-semibold font-monospace" rows="8" placeholder="Type here..." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-700 text-muted">Cover Image (JPG, PNG, WebP)</label>
                            <input type="file" name="cover_image" class="form-control rounded-3 border-light bg-light p-2.5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-700 text-muted">Status</label>
                            <select name="status" class="form-select rounded-3 border-light bg-light p-3 fw-semibold">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-center pt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeaturedAdd">
                                <label class="form-check-label fw-bold small text-muted" for="isFeaturedAdd">
                                    Featured Post
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="opacity-50">
                            <h6 class="fw-bold text-dark"><i class="bi bi-search me-1 text-primary"></i> SEO Meta Configuration</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-700 text-muted">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" placeholder="Optional SEO title override">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-700 text-muted">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" placeholder="e.g. AI onboarding, prompt libraries">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-700 text-muted">Meta Description</label>
                            <textarea name="meta_description" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" rows="2" placeholder="Google search result description snippet..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-4">
                    <button type="button" class="btn btn-light rounded-3 fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">Publish Article</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
