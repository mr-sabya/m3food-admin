<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Theme Templates</h2>
        <button class="btn btn-primary shadow-sm" wire:click="createTheme">
            <i class="fas fa-magic me-1"></i> Create New Theme
        </button>
    </div>

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control" placeholder="Search themes..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>
            </div>

            <!-- Grid Display (demonstrating a Card layout for themes) -->
            <div class="row g-4">
                @forelse ($themes as $theme)
                <div class="col-lg-4">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden theme-card">
                        <div class="position-relative">
                            <img src="{{ $theme->preview_image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge {{ $theme->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $theme->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title fw-bold mb-0">{{ $theme->title }}</h6>
                                <span class="badge bg-info-subtle text-info border border-info-subtle">
                                    {{ $typeOptions[$theme->type] ?? $theme->type }}
                                </span>
                            </div>
                            <div class="small text-muted mb-3">
                                <i class="fas fa-palette me-1"></i> Primary:
                                <span class="badge border" style="background-color: {{ $theme->settings['primary_color'] ?? '#eee' }}; width: 12px; height: 12px; padding: 0;">&nbsp;</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-soft-info flex-grow-1" wire:click="editTheme({{ $theme->id }})">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-soft-danger" wire:click="deleteTheme({{ $theme->id }})" wire:confirm="Delete this theme?">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-lg-12">
                    <div class="card border border-dashed shadow-none py-5 bg-light-subtle">
                        <div class="card-body">
                            <div class="text-center py-4">
                                <!-- Icon with themed background -->
                                <div class="mb-4">
                                    <div class="avatar-lg mx-auto">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1 display-4">
                                            <i class="bi bi-palette"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Text Content -->
                                <h4 class="fw-bold text-dark">No Themes Found</h4>
                                <p class="text-muted mx-auto mb-4" style="max-width: 450px;">
                                    @if($search)
                                    We couldn't find any themes matching "<strong>{{ $search }}</strong>". Try checking your spelling or using different keywords.
                                    @else
                                    It looks like you haven't created any theme templates yet. Themes define the visual personality of your pages, including colors and fonts.
                                    @endif
                                </p>

                                <!-- Action Button -->
                                @if($search)
                                <button type="button" wire:click="$set('search', '')" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times me-1"></i> Clear Search
                                </button>
                                @else
                                <button type="button" wire:click="createTheme" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-plus me-1"></i> Create Your First Theme
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $themes->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade {{ $showModal ? 'show d-block' : '' }}" tabindex="-1" @if($showModal) style="background-color: rgba(0,0,0,.5);" @endif>
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title text-white">{{ $isEditing ? 'Edit Theme Template' : 'New Theme Template' }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="saveTheme">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Left Col: General -->
                            <div class="col-md-7 border-end">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Theme Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model.defer="title">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Page Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" wire:model.defer="type">
                                        @foreach($typeOptions as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Preview Screenshot <span class="text-danger">*</span></label>
                                    <div class="border rounded p-2 mb-2 bg-light text-center" style="min-height: 150px;">
                                        @if ($preview_image)
                                        <img src="{{ $preview_image->temporaryUrl() }}" class="img-fluid rounded shadow-sm" style="max-height: 140px;">
                                        @elseif ($currentImage)
                                        <img src="{{ asset('storage/' . $currentImage) }}" class="img-fluid rounded shadow-sm" style="max-height: 140px;">
                                        @else
                                        <div class="py-4 text-muted small"><i class="fas fa-image fa-2x mb-2"></i><br>No Image Selected</div>
                                        @endif
                                    </div>
                                    <input type="file" class="form-control @error('preview_image') is-invalid @enderror" wire:model.live="preview_image">
                                    @error('preview_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Right Col: JSON Settings -->
                            <div class="col-md-5">
                                <h6 class="fw-bold mb-3"><i class="fas fa-sliders-h me-1"></i> Theme Settings (JSON)</h6>

                                <div class="mb-3">
                                    <label class="form-label small">Primary Color</label>
                                    <div class="d-flex gap-2">
                                        <input type="color" class="form-control form-control-color" wire:model.defer="primary_color">
                                        <input type="text" class="form-control form-control-sm" wire:model.defer="primary_color">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Secondary Color</label>
                                    <div class="d-flex gap-2">
                                        <input type="color" class="form-control form-control-color" wire:model.defer="secondary_color">
                                        <input type="text" class="form-control form-control-sm" wire:model.defer="secondary_color">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Default Font Family</label>
                                    <select class="form-select form-select-sm" wire:model.defer="font_family">
                                        <option value="Inter">Inter (Default)</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="Poppins">Poppins</option>
                                        <option value="Montserrat">Montserrat</option>
                                    </select>
                                </div>

                                <hr>

                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" type="checkbox" id="themeActive" wire:model.defer="is_active">
                                    <label class="form-check-label fw-bold" for="themeActive">Published & Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <span wire:loading wire:target="saveTheme" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $isEditing ? 'Update Template' : 'Create Template' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>