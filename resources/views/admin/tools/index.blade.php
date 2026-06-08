@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Connected Tools Directory</h1>
            <p class="text-muted">Manage corporate integrations and daily tools available to learning paths and creators.</p>
        </div>
        <button class="btn btn-primary rounded-4 fw-bold shadow-sm py-2.5 px-4" data-bs-toggle="modal" data-bs-target="#addToolModal">
            <i class="bi bi-plus-lg me-2"></i> Add New Tool
        </button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Whoops! Please fix the errors below:</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Connected Tools Grid -->
    <div class="row g-4">
        @forelse($tools as $tool)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative">
                    <!-- Status Badge -->
                    <span class="badge position-absolute top-0 end-0 mt-3 me-3 py-1.5 px-2.5 rounded-3 fw-bold {{ $tool->status == 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                        {{ ucfirst($tool->status) }}
                    </span>

                    <div class="card-body p-4 text-center d-flex flex-column h-100">
                        <!-- Tool Logo / Image -->
                        <div class="d-inline-flex align-items-center justify-content-center bg-light p-3 rounded-4 mx-auto mb-4 border border-light-subtle shadow-sm" style="width: 80px; height: 80px;">
                            <img src="{{ $tool->logo }}" alt="{{ $tool->name }}" class="img-fluid rounded-3" style="max-height: 50px; object-fit: contain;">
                        </div>

                        <!-- Tool Info -->
                        <h5 class="fw-800 text-dark mb-1">{{ $tool->name }}</h5>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.5;">
                            {{ $tool->description ?? 'No description provided.' }}
                        </p>

                        <!-- Actions Row -->
                        <div class="d-flex gap-2 mt-auto">
                            <button class="btn btn-light rounded-3 fw-bold flex-grow-1 border-0" data-bs-toggle="modal" data-bs-target="#editToolModal{{ $tool->id }}">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </button>
                            <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to delete this tool?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light text-danger rounded-3 fw-bold w-100 border-0">
                                    <i class="bi bi-trash-fill me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Tool Modal -->
            <div class="modal fade" id="editToolModal{{ $tool->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="modal-header bg-light border-0 p-4 pb-0">
                            <div>
                                <h5 class="modal-title fw-800 text-dark mb-1">Edit Connected Tool</h5>
                                <p class="text-muted small mb-0">Modify configuration details for {{ $tool->name }}.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.tools.update', $tool) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label small fw-700 text-muted">Tool Name</label>
                                        <input type="text" name="name" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" value="{{ $tool->name }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-700 text-muted">Description</label>
                                        <textarea name="description" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" rows="3">{{ $tool->description }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-700 text-muted">Tool Status</label>
                                        <select name="status" class="form-select rounded-3 border-light bg-light p-3 fw-semibold">
                                            <option value="active" {{ $tool->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $tool->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-700 text-muted">Change Logo Image</label>
                                        <input type="file" name="logo" class="form-control rounded-3 border-light bg-light p-2.5">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light border-0 p-4">
                                <button type="button" class="btn btn-light rounded-3 fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-light p-5 rounded-4 d-inline-block border border-dashed border-2">
                    <i class="bi bi-cpu text-muted" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold mt-3">No tools added yet</h5>
                    <p class="text-muted">Start by adding your first dynamic tool integration!</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $tools->links() }}
    </div>
</div>

<!-- Add Tool Modal -->
<div class="modal fade" id="addToolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-0 p-4 pb-0">
                <div>
                    <h5 class="modal-title fw-800 text-dark mb-1">Add Connected Tool</h5>
                    <p class="text-muted small mb-0">Create a dynamic corporate tool or integration option.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.tools.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label small fw-700 text-muted">Tool Name</label>
                            <input type="text" name="name" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" placeholder="e.g. ChatGPT" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-700 text-muted">Description</label>
                            <textarea name="description" class="form-control rounded-3 border-light bg-light p-3 fw-semibold" rows="3" placeholder="Explain the main focus or API utility..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-700 text-muted">Logo Image</label>
                            <input type="file" name="logo" class="form-control rounded-3 border-light bg-light p-2.5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-4">
                    <button type="button" class="btn btn-light rounded-3 fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">Add Tool</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
