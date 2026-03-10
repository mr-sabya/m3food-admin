<div class="py-4 px-3" style="background: #f8f9fa;">
    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border-start border-primary border-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('product.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ $product->exists ? 'Edit' : 'Create' }}</li>
                </ol>
            </nav>
            <h2 class="h4 mb-0 fw-bold text-dark">
                @if($product->exists)
                <span class="text-muted">Editing:</span> {{ $product->name }}
                @else
                Create New Product
                @endif
            </h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('product.products.index') }}" wire:navigate class="btn btn-outline-secondary">
                <i class="ri-close-line"></i> Cancel
            </a>
            <button type="button" wire:click="save" class="btn btn-primary px-4 shadow-sm">
                <i class="ri-save-line me-1"></i> Save All Changes
            </button>
        </div>
    </div>

    <div class="row">
        <!-- LEFT: Core Data & Settings -->
        <div class="col-lg-8">
            <!-- Basic Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-soft-primary p-2 rounded me-3"><i class="ri-information-line text-primary"></i></div>
                        <h5 class="mb-0">Product Content</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Display Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg border-light-subtle" wire:model.live="name" placeholder="e.g. চুইঝাল মিষ্টি মসলা">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Slug (URL)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">/product/</span>
                                <input type="text" class="form-control" wire:model="slug">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">SKU / Model</label>
                            <input type="text" class="form-control form-control-sm" wire:model="sku">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Short Hook (Short Description)</label>
                        <textarea class="form-control" wire:model="short_description" rows="2" placeholder="Catchy line for the top of the page..."></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Detailed Content (Long Description)</label>
                        <div class="border rounded">
                            <livewire:quill-text-editor wire:model.live="long_description" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Stock Row -->
            <div class="row">
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="ri-price-tag-3-line me-2"></i>Pricing Architecture</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="small fw-bold">Regular Price</label>
                                    <input type="number" class="form-control border-primary bg-soft-primary" wire:model="regular_price">
                                </div>
                                <div class="col-6">
                                    <label class="small fw-bold text-success">Sale Price</label>
                                    <input type="number" class="form-control border-success" wire:model="sale_price">
                                </div>
                                <div class="col-4">
                                    <label class="small text-muted">Purchase</label>
                                    <input type="number" class="form-control form-control-sm" wire:model="purchase_price">
                                </div>
                                <div class="col-4">
                                    <label class="small text-muted">Retail</label>
                                    <input type="number" class="form-control form-control-sm" wire:model="retail_price">
                                </div>
                                <div class="col-4">
                                    <label class="small text-muted">Distributor</label>
                                    <input type="number" class="form-control form-control-sm" wire:model="distributor_price">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="ri-database-2-line me-2"></i>Stock Level</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" wire:model.live="is_manage_stock">
                                <label class="form-check-label">Track Inventory</label>
                            </div>
                            @if($is_manage_stock)
                            <label class="small fw-bold">Available Quantity</label>
                            <input type="number" class="form-control form-control-lg text-center fw-bold" wire:model="quantity">
                            @else
                            <div class="alert alert-light small mb-0">Stock is set to infinite.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Visual Config & Media -->
        <div class="col-lg-4">
            <!-- Status Sidebar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small fw-bold uppercase">Product Visibility</label>
                        <div class="p-3 border rounded @if($is_active) bg-soft-success @else bg-soft-danger @endif">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" wire:model="is_active" id="active">
                                <label class="form-check-label fw-bold" for="active">
                                    @if($is_active) Published & Public @else Hidden (Draft) @endif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold">Type</label>
                        <select class="form-select bg-light" wire:model.live="type">
                            @foreach($productTypes as $typeCase)
                            <option value="{{ $typeCase->value }}">{{ $typeCase->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <div class="flex-fill border p-2 rounded text-center @if($is_featured) border-warning bg-soft-warning @endif">
                            <input type="checkbox" class="form-check-input" wire:model="is_featured" id="feat">
                            <label class="small d-block fw-bold" for="feat">Featured</label>
                        </div>
                        <div class="flex-fill border p-2 rounded text-center @if($is_new) border-info bg-soft-info @endif">
                            <input type="checkbox" class="form-check-input" wire:model="is_new" id="new">
                            <label class="small d-block fw-bold" for="new">New Arrival</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thumbnail Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between">
                    <h6 class="mb-0 fw-bold">Primary Thumbnail</h6>
                    <i class="ri-image-line text-muted"></i>
                </div>
                <div class="card-body text-center bg-light-subtle">
                    <x-image-preview
                        model="new_thumbnail_image"
                        :image="$new_thumbnail_image"
                        :existing="$thumbnail_image_path" />
                </div>
            </div>

            <!-- Categories Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Categories & Brands</h6>
                </div>
                <div class="card-body">
                    <label class="small fw-bold mb-2">Select Categories</label>
                    <div class="overflow-auto border rounded bg-white p-2 mb-3" style="max-height: 180px;">
                        @foreach($categories_list as $category)
                        <div class="form-check py-1 border-bottom border-light">
                            <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="cat-{{ $category->id }}" wire:model="selectedCategoryIds">
                            <label class="form-check-label small" for="cat-{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                        @endforeach
                    </div>

                    <label class="small fw-bold mb-1">Brand</label>
                    <select class="form-select bg-light" wire:model="brand_id">
                        <option value="">Standard (No Brand)</option>
                        @foreach($brands_list as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM SECTION: The Visual Landing Page Builder -->
    @if ($product->exists)


    <div class="row">
        <!-- Sidebar for Assets (Images/SEO/Specs) -->
        <div class="col-lg-3">
            <div class="nav flex-column nav-pills shadow-sm bg-white rounded p-2" style="top: 20px;">
                <button class="nav-link active mb-2 text-start" data-bs-toggle="tab" data-bs-target="#images"><i class="ri-gallery-line me-2"></i> Gallery Assets</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="tab" data-bs-target="#specs"><i class="ri-list-settings-line me-2"></i> Attributes/Specs</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="tab" data-bs-target="#seo"><i class="ri-search-eye-line me-2"></i> SEO & OG Tags</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="tab" data-bs-target="#tags"><i class="ri-price-tag-line me-2"></i> Filter Tags</button>
            </div>
        </div>

        <!-- Builder Canvas -->
        <div class="col-lg-9">
            <div class="tab-content mb-4">
                <div class="tab-pane fade show active bg-white p-4 rounded shadow-sm" id="images">
                    <livewire:product.images-manager :product="$product" />
                </div>
                <div class="tab-pane fade bg-white p-4 rounded shadow-sm" id="specs">
                    <livewire:product.specifications-manager :product="$product" />
                </div>
                <div class="tab-pane fade bg-white p-4 rounded shadow-sm" id="seo">
                    <livewire:product.seo-manager :product="$product" />
                </div>
                <div class="tab-pane fade bg-white p-4 rounded shadow-sm" id="tags">
                    <livewire:product.tags-manager :product="$product" />
                </div>
            </div>


        </div>
    </div>

    <div class="mt-5 mb-5">
        <div class="text-center mb-4">
            <hr class="w-25 mx-auto">
            <h3 class="fw-bold"><i class="ri-layout-masonry-line me-2"></i>Visual Landing Page Designer</h3>
            <p class="text-muted">Construct your high-conversion landing page using sections below.</p>
        </div>
        <!-- THE DYNAMIC BUILDER (DRAG & DROP AREA) -->
        <div class="bg-dark p-1 rounded shadow-lg">
            <div class="bg-white rounded-top p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Page Section Flow</h5>
                <span class="badge bg-success">Live Canvas</span>
            </div>
            <div class="p-3" style="background: #e9ecef;">
                <livewire:product.landing-builder productId="{{ $product->id }}" />
            </div>
        </div>
    </div>
    @endif

    <!-- Floating Save Button for Mobile -->
    <div class="fixed-bottom p-3 d-lg-none bg-white border-top shadow text-center">
        <button wire:click="save" class="btn btn-primary w-100">Save Product</button>
    </div>


    <style>
        .bg-soft-primary {
            background-color: #e7f1ff;
        }

        .bg-soft-success {
            background-color: #e6fcf5;
        }

        .bg-soft-danger {
            background-color: #fff5f5;
        }

        .bg-soft-warning {
            background-color: #fff9db;
        }

        .bg-soft-info {
            background-color: #e3faff;
        }

        .nav-pills .nav-link.active {
            background-color: #005a2b;
        }

        /* M3Food Green */
        .card {
            transition: all 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }
    </style>
</div>