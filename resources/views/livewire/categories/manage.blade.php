<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>{{ $pageTitle }}</h3>
        {{-- Ensure route exists, otherwise change to appropriate route --}}
        <a href="{{ route('product.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>

    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form wire:submit.prevent="saveCategory">
        <div class="row">
            <!-- Left Column: Main Info -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Category Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model.live="name">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" wire:model.defer="slug">
                                    <small class="form-text text-muted">SEO-friendly URL identifier.</small>
                                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="6" wire:model.defer="description"></textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-md-4">
                                <label for="sort_order" class="form-label">Sort Order <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" wire:model.defer="sort_order" min="0">
                                @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="is_active">Is Active?</label>
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" wire:model.defer="is_active">
                                    <label class="form-check-label ms-2 mb-0" for="is_active">Active</label>
                                    @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="show_on_homepage">Show on Homepage?</label>
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input @error('show_on_homepage') is-invalid @enderror" type="checkbox" id="show_on_homepage" wire:model.defer="show_on_homepage">
                                    <label class="form-check-label ms-2 mb-0" for="show_on_homepage">Show on Homepage</label>
                                    @error('show_on_homepage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Media -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Relationships & Media</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select class="form-select form-control @error('parent_id') is-invalid @enderror" id="parent_id" wire:model.defer="parent_id">
                                <option value="">No Parent</option>
                                @foreach ($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-lg-6">
                                <!-- IMAGE UPLOAD -->
                                <div class="mb-3">

                                    <x-image-preview
                                        model="image"
                                        :image="$image"
                                        :existing="$currentImage"
                                        label="Category Image" />
                                    <small class="form-text text-muted">Max 1MB. JPG, PNG.</small>
                                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- ICON UPLOAD (New Section) -->
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Category Icon</label>
                                    <div class="image-preview">
                                        @if ($icon)
                                        <img src="{{ $icon->temporaryUrl() }}">
                                        @elseif ($currentIcon)
                                        {{-- Handle both seeded assets and storage files logic implicitly via asset helper or explicit logic --}}
                                        @php
                                        $iconUrl = Str::startsWith($currentIcon, 'assets') ? asset($currentIcon) : asset('storage/' . $currentIcon);
                                        @endphp
                                        <img src="{{ $iconUrl }}" alt="Current Icon">
                                        @else
                                        <div class="icon">
                                            <i class="ri-file-image-line"></i>
                                            <span class="text-muted small">No image uploaded</span>
                                        </div>
                                        @endif
                                    </div>
                                    <input type="file" class="form-control @error('icon') is-invalid @enderror" id="icon" wire:model.live="icon">
                                    <small class="form-text text-muted">Max 1MB. PNG, SVG preferred.</small>
                                    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>

            <!-- Bottom: SEO -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">SEO Information</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="seo_title" class="form-label">SEO Title</label>
                            <input type="text" class="form-control @error('seo_title') is-invalid @enderror" id="seo_title" wire:model.defer="seo_title">
                            <small class="form-text text-muted">Title for search engines.</small>
                            @error('seo_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="seo_description" class="form-label">SEO Description</label>
                            <textarea class="form-control @error('seo_description') is-invalid @enderror" id="seo_description" rows="3" wire:model.defer="seo_description"></textarea>
                            <small class="form-text text-muted">Meta description for search engines.</small>
                            @error('seo_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('product.categories.index') }}" wire:navigate class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading wire:target="saveCategory" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                {{ $isEditing ? 'Update Category' : 'Create Category' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>