<div class="py-4">
    <h2 class="mb-4">Header Management</h2>

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Template Headers</h5>
            <button class="btn btn-primary" wire:click="createHeader">
                <i class="fas fa-plus"></i> Add New Header
            </button>
        </div>
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search headers..." wire:model.live.debounce.300ms="search">
                </div>
                <div class="col-md-8 d-flex justify-content-end align-items-center gap-2">
                    <select wire:model.live="perPage" class="form-select w-auto">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>Per Page</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th wire:click="sortBy('id')" role="button" style="width: 80px;">ID
                                @if($sortField == 'id') <i class="fas {{ $sortDirection == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i> @endif
                            </th>
                            <th>Preview</th>
                            <th wire:click="sortBy('title')" role="button">Title
                                @if($sortField == 'title') <i class="fas {{ $sortDirection == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i> @endif
                            </th>
                            <th wire:click="sortBy('is_active')" role="button" style="width: 120px;">Status</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($headers as $header)
                        <tr>
                            <td>{{ $header->id }}</td>
                            <td>
                                @if ($header->preview_image)
                                <img src="{{ $header->preview_image_url }}" alt="Preview" class="img-thumbnail" style="height: 50px; width: 100px; object-fit: cover;">
                                @else
                                <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $header->title }}</td>
                            <td>
                                @if ($header->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info me-1" wire:click="editHeader({{ $header->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="deleteHeader({{ $header->id }})" wire:confirm="Are you sure you want to delete this header?" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-secondary opacity-50 mb-2">
                                    <i class="fas fa-images fa-3x"></i>
                                </div>
                                <p class="mb-0">No headers found. Create one to get started.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $headers->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade {{ $showModal ? 'show d-block' : '' }}" tabindex="-1" role="dialog" @if($showModal) style="background-color: rgba(0,0,0,.5);" @endif>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEditing ? 'Edit Header' : 'Create New Header' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="saveHeader">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Header Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model.defer="title" placeholder="e.g. Modern Dark Header">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preview Image <span class="text-danger">*</span></label>

                            <!-- Image Preview Area -->
                            <div class="mb-2 border rounded p-2 text-center bg-light" style="min-height: 150px;">
                                @if ($preview_image)
                                <img src="{{ $preview_image->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 140px;">
                                @elseif ($currentImage)
                                <img src="{{ asset('storage/' . $currentImage) }}" class="img-fluid rounded" style="max-height: 140px;">
                                @else
                                <div class="py-4 text-muted">
                                    <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                    <p class="mb-0">No image selected</p>
                                </div>
                                @endif
                            </div>

                            <input type="file" class="form-control @error('preview_image') is-invalid @enderror" wire:model.live="preview_image">
                            <div class="form-text">Recommended: 1200x400px. Max 2MB.</div>
                            @error('preview_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <div wire:loading wire:target="preview_image" class="mt-1 text-primary small">
                                <span class="spinner-border spinner-border-sm" role="status"></span> Uploading...
                            </div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="headerActive" wire:model.defer="is_active">
                            <label class="form-check-label" for="headerActive">Active (Available for pages)</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading wire:target="saveHeader" class="spinner-border spinner-border-sm" role="status"></span>
                            {{ $isEditing ? 'Update Header' : 'Create Header' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>