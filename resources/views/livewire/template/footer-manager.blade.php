<div class="py-4">
    <h2 class="mb-4">Footer Management</h2>

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0">Template Footers</h5>
            <button class="btn btn-primary" wire:click="createFooter">
                <i class="fas fa-plus me-1"></i> Add New Footer
            </button>
        </div>
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Search footers..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>
                <div class="col-md-8 d-flex justify-content-end align-items-center gap-2">
                    <select wire:model.live="perPage" class="form-select w-auto">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-muted">Per Page</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-top mb-0">
                    <thead class="table-light">
                        <tr>
                            <th wire:click="sortBy('id')" role="button" style="width: 80px;">ID
                                @if($sortField == 'id') <i class="fas {{ $sortDirection == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i> @endif
                            </th>
                            <th>Preview Image</th>
                            <th wire:click="sortBy('title')" role="button">Title
                                @if($sortField == 'title') <i class="fas {{ $sortDirection == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i> @endif
                            </th>
                            <th wire:click="sortBy('is_active')" role="button" style="width: 120px;">Status</th>
                            <th class="text-end" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($footers as $footer)
                        <tr>
                            <td>{{ $footer->id }}</td>
                            <td>
                                @if ($footer->preview_image)
                                <img src="{{ $footer->preview_image_url }}" alt="Preview" class="rounded border shadow-sm" style="height: 60px; width: 120px; object-fit: cover;">
                                @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="height: 60px; width: 120px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $footer->title }}</td>
                            <td>
                                @if ($footer->is_active)
                                <span class="badge bg-success-subtle text-success px-3">Active</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger px-3">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-soft-info btn-sm me-1" wire:click="editFooter({{ $footer->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-soft-danger btn-sm" wire:click="deleteFooter({{ $footer->id }})" wire:confirm="Are you sure you want to delete this footer template?" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-window-maximize fa-3x text-light mb-3"></i>
                                <p class="text-muted mb-0">No footer templates found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $footers->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade {{ $showModal ? 'show d-block' : '' }}" tabindex="-1" role="dialog" @if($showModal) style="background-color: rgba(0,0,0,.5);" @endif>
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">{{ $isEditing ? 'Edit Footer Template' : 'Create New Footer' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="saveFooter">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model.defer="title" placeholder="e.g. Corporate Footer v1">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Preview Image <span class="text-danger">*</span></label>

                            <div class="mb-3 border rounded-3 p-2 text-center bg-white shadow-sm" style="min-height: 160px; display: flex; align-items: center; justify-content: center;">
                                @if ($preview_image)
                                <img src="{{ $preview_image->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 140px;">
                                @elseif ($currentImage)
                                <img src="{{ asset('storage/' . $currentImage) }}" class="img-fluid rounded" style="max-height: 140px;">
                                @else
                                <div class="text-muted">
                                    <i class="fas fa-upload fa-2x mb-2"></i>
                                    <p class="small mb-0">Upload a screenshot of the footer design</p>
                                </div>
                                @endif
                            </div>

                            <input type="file" class="form-control @error('preview_image') is-invalid @enderror" wire:model.live="preview_image">
                            <div class="form-text small text-muted">Supported: JPG, PNG. Max 2MB.</div>
                            @error('preview_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <div wire:loading wire:target="preview_image" class="mt-2 text-primary small">
                                <div class="spinner-border spinner-border-sm me-1" role="status"></div> Uploading...
                            </div>
                        </div>

                        <div class="form-check form-switch py-2">
                            <input class="form-check-input" type="checkbox" id="footerActive" wire:model.defer="is_active">
                            <label class="form-check-label fw-semibold" for="footerActive">Active Template</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light border" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <span wire:loading wire:target="saveFooter" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $isEditing ? 'Update Footer' : 'Save Footer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>