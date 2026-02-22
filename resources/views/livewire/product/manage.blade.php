<div class="py-4">
    <h2 class="mb-4">Product Management</h2>

    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form wire:submit.prevent="save">

        <div class="row mb-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">{{ $product->exists ? 'Edit Product' : 'Create New Product' }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Product Details -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model.live="name" placeholder="Enter product name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" wire:model.live="slug" placeholder="product-name-slug">
                                    <button class="btn btn-outline-secondary" type="button" wire:click="generateSlug">Generate</button>
                                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Vendor Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="vendor_id" class="form-label">Vendor</label>
                                <select class="form-select form-control @error('vendor_id') is-invalid @enderror" id="vendor_id" wire:model="vendor_id">
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->email }})</option>
                                    @endforeach
                                </select>
                                @error('vendor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-select form-control @error('brand_id') is-invalid @enderror" id="brand_id" wire:model="brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" wire:model="short_description" rows="4" placeholder="A brief summary of the product"></textarea>
                                @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Product Image & Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Thumbnail Image -->
                            <div class="col-md-12 mb-3">
                                <label for="new_thumbnail_image" class="form-label">Thumbnail Image</label>
                                <div class="image-preview">
                                    @if ($new_thumbnail_image)
                                    <img src="{{ $new_thumbnail_image->temporaryUrl() }}" alt="Current Thumbnail" class="upload-image">
                                    @elseif($thumbnail_image_path)
                                    <img src="{{ asset('storage/' . $thumbnail_image_path) }}" alt="Current Thumbnail" class="upload-image">
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('new_thumbnail_image') is-invalid @enderror" id="new_thumbnail_image" wire:model="new_thumbnail_image">
                                <div wire:loading wire:target="new_thumbnail_image" class="text-info">Uploading...</div>
                                @error('new_thumbnail_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3 ">
                                <div class="form-check form-switch border">
                                    <input class="form-check-input" type="checkbox" id="is_active" wire:model="is_active">
                                    <label class="form-check-label m-0" for="is_active">Is Active</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch border">
                                    <input class="form-check-input" type="checkbox" id="is_featured" wire:model="is_featured">
                                    <label class="form-check-label m-0" for="is_featured">Featured</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch border">
                                    <input class="form-check-input" type="checkbox" id="is_new" wire:model="is_new">
                                    <label class="form-check-label m-0" for="is_new">New Arrival</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEW: Promotion & Bundle Row -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="m-0">Delivery Rules (Promotions)</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Free Delivery Threshold (Qty)</label>
                            <input type="number" class="form-control" wire:model="free_delivery_threshold" placeholder="e.g. 2">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Starts At</label>
                            <input type="datetime-local" class="form-control" wire:model="free_delivery_starts_at">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">Ends At</label>
                            <input type="datetime-local" class="form-control" wire:model="free_delivery_ends_at">
                            @error('free_delivery_ends_at') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="m-0">Product Combo (Bundle)</h5>
                    </div>
                    <div class="card-body position-relative">
                        <label class="form-label small fw-bold">Search Product to Add</label>
                        <input type="text" class="form-control mb-3" placeholder="Search by name..." wire:model.live.debounce.300ms="bundleSearch">

                        @if(!empty($bundleSearchResults))
                        <div class="list-group position-absolute w-100 shadow-lg" style="z-index: 1050; left: 0;">
                            @foreach($bundleSearchResults as $res)
                            <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between" wire:click="addProductToBundle({{ $res['id'] }})">
                                {{ $res['name'] }} <span class="text-primary">${{ $res['price'] }}</span>
                            </button>
                            @endforeach
                        </div>
                        @endif

                        <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr class="small text-muted">
                                        <th>Item</th>
                                        <th>Bundle Price</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($selectedBundleProducts as $sp)
                                    <tr>
                                        <td class="small">{{ $sp['name'] }}</td>
                                        <td style="width: 150px;">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">৳</span>
                                                <input type="number" class="form-control" wire:model="bundlePrices.{{ $sp['id'] }}">
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm text-danger" wire:click="removeProductFromBundle({{ $sp['id'] }})"><i class="fas fa-times"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted small py-3">No combo items added.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Detailed Description</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <label for="long_description" class="form-label">Long Description</label>
                            <livewire:quill-text-editor
                                wire:model.live="long_description"
                                theme="snow" />
                            @error('long_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <label class="form-label d-block">Categories <span class="text-danger">*</span></label>
                            <div class="border p-3 rounded @error('selectedCategoryIds') is-invalid-border @enderror" style="max-height: 250px; overflow-y: auto;">
                                @forelse ($categories as $category)
                                <div class="form-check">
                                    {{-- FIX: Use Array Syntax for $category --}}
                                    <input class="form-check-input" type="checkbox" id="category-{{ $category['id'] }}" value="{{ $category['id'] }}" wire:model.live="selectedCategoryIds">
                                    <label class="form-check-label" for="category-{{ $category['id'] }}">
                                        {{ $category['name'] }}
                                    </label>
                                </div>
                                @empty
                                <p class="text-muted">No categories available. Please create some first.</p>
                                @endforelse
                            </div>
                            @error('selectedCategoryIds') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Pricing, Inventory & Shipping</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Price and SKU -->
                    <div class="col-md-12 mb-3">
                        <label for="sku" class="form-label">SKU (Stock Keeping Unit)</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" wire:model="sku" placeholder="Unique SKU">
                        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" wire:model="price" placeholder="0.00">
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="compare_at_price" class="form-label">Compare At Price</label>
                        <input type="number" step="0.01" class="form-control @error('compare_at_price') is-invalid @enderror" id="compare_at_price" wire:model="compare_at_price" placeholder="0.00">
                        @error('compare_at_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" id="cost_price" wire:model="cost_price" placeholder="0.00">
                        @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Shipping Details -->
                    <div class="col-md-4 mb-3">
                        <label for="weight" class="form-label">Weight (kg/lbs)</label>
                        <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" wire:model="weight" placeholder="0.00">
                        @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Order Quantities -->
                    <div class="col-md-4 mb-3">
                        <label for="min_order_quantity" class="form-label">Minimum Order Quantity</label>
                        <input type="number" class="form-control @error('min_order_quantity') is-invalid @enderror" id="min_order_quantity" wire:model="min_order_quantity" placeholder="1">
                        @error('min_order_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="max_order_quantity" class="form-label">Maximum Order Quantity</label>
                        <input type="number" class="form-control @error('max_order_quantity') is-invalid @enderror" id="max_order_quantity" wire:model="max_order_quantity" placeholder="No limit">
                        @error('max_order_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h5 class="mb-0 mb-md-0 me-md-4">Product Type & Inventory</h5>
                        <div class="d-flex align-items-center flex-wrap flex-md-nowrap product-type-group ms-md-auto">
                            <label for="type" class="form-label mb-0 me-2 text-nowrap">Product Type <span class="text-danger">*</span></label>
                            <div class="flex-grow-1" style="min-width: 150px;">
                                <select class="form-select @error('type') is-invalid @enderror" id="type" wire:model.live="type">
                                    @foreach ($productTypes as $productType)
                                    <option value="{{ $productType->value }}">{{ Str::title($productType->value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            @if ($type === \App\Enums\ProductType::Normal->value)
                            <div class="col-md-6 mb-3">
                                <label for="is_manage_stock" class="form-label">Manage Stock</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_manage_stock" wire:model.live="is_manage_stock" {{ in_array($type, ['variable', 'affiliate', 'digital']) ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="is_manage_stock">Enable stock tracking</label>
                                </div>
                                @error('is_manage_stock') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            @if ($is_manage_stock)
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" wire:model="quantity" placeholder="0">
                                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif
                            @else
                            <div class="col-md-12 mb-3">
                                <p>Stock is managed by {{ $type === \App\Enums\ProductType::Variable->value ? 'product variants' : ($type === \App\Enums\ProductType::Affiliate->value ? 'the external vendor' : 'the digital product system') }}.</p>
                            </div>
                            @endif

                            @if ($type === \App\Enums\ProductType::Affiliate->value)
                            <div class="col-md-12 mb-3">
                                <label for="affiliate_url" class="form-label">Affiliate URL <span class="text-danger">*</span></label>
                                <input type="url" class="form-control @error('affiliate_url') is-invalid @enderror" id="affiliate_url" wire:model="affiliate_url" placeholder="https://example.com/affiliate-link">
                                @error('affiliate_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            @if ($type === \App\Enums\ProductType::Digital->value)
                            <div class="col-md-12 mb-3">
                                <label for="new_digital_file" class="form-label">Digital File</label>
                                @if ($digital_file_path)
                                <div class="mb-2">
                                    <span class="badge bg-secondary">Current File: {{ basename($digital_file_path) }}</span>
                                </div>
                                @endif
                                <input type="file" class="form-control @error('new_digital_file') is-invalid @enderror" id="new_digital_file" wire:model="new_digital_file">
                                <div wire:loading wire:target="new_digital_file" class="text-info">Uploading...</div>
                                @error('new_digital_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="download_limit" class="form-label">Download Limit</label>
                                <input type="number" class="form-control @error('download_limit') is-invalid @enderror" id="download_limit" wire:model="download_limit" placeholder="Unlimited">
                                @error('download_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="download_expiry_days" class="form-label">Download Expiry (Days)</label>
                                <input type="number" class="form-control @error('download_expiry_days') is-invalid @enderror" id="download_expiry_days" wire:model="download_expiry_days" placeholder="Never">
                                @error('download_expiry_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                {{ $product->exists ? 'Update Product' : 'Create Product' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    @if ($product->exists)
    <div class="card mt-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3 pb-0">
            <h5 class="mb-3">Additional Product Management</h5>
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#images" type="button"><i class="fas fa-images me-1"></i> Images</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#specs" type="button"><i class="fas fa-list-ul me-1"></i> Specifications</button></li>
                @if ($product->isVariable())
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#variants" type="button"><i class="fas fa-tags me-1"></i> Variants</button></li>
                @endif
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tags" type="button"><i class="fas fa-tag me-1"></i> Tags</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#seo" type="button"><i class="fas fa-search me-1"></i> SEO</button></li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="images"><livewire:product.images-manager :product="$product" /></div>
                <div class="tab-pane fade" id="specs"><livewire:product.specifications-manager :product="$product" /></div>
                @if ($product->isVariable())
                <div class="tab-pane fade" id="variants"><livewire:product.variants-manager :product="$product" /></div>
                @endif
                <div class="tab-pane fade" id="tags"><livewire:product.tags-manager :product="$product" /></div>
                <div class="tab-pane fade" id="seo"><livewire:product.seo-manager :product="$product" /></div>
            </div>
        </div>
    </div>
    @endif
</div>