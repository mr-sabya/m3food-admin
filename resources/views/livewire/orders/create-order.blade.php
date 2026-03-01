<div>
    <div class="row g-3">

        <!-- LEFT COLUMN: CUSTOMER SECTION -->
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <h6 class="fw-bold mb-3">Create Sales Order</h6>

                <label class="small text-muted mb-1">Search Customer (Phone)</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" placeholder="+88017..." wire:model.live.debounce.500ms="customerSearch">
                </div>

                <!-- If not found, show Add button -->
                @if(!$selectedCustomer && strlen(preg_replace('/[^0-9]/', '', $customerSearch)) >= 11)
                <div class="alert alert-info py-2 small d-flex justify-content-between align-items-center">
                    <span>Not found?</span>
                    <button class="btn btn-sm btn-info text-white fw-bold" wire:click="$set('showCreateCustomerModal', true)">
                        <i class="fas fa-user-plus me-1"></i> Add New
                    </button>
                </div>
                @endif

                @if($selectedCustomer)
                <!-- Delivery Success Rate Card -->
                <div class="border rounded p-2 mb-3 bg-white shadow-sm mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold small text-muted">Delivery Success Rate</span>
                        <i class="fas fa-sync-alt text-info cursor-pointer" style="font-size: 10px;"></i>
                    </div>

                    <!-- Dynamic Success Rate calculation -->
                    @php
                    $total = $selectedCustomer->deliveryStats->sum('total_count') ?? 1;
                    $rate = $total > 0 ? ($selectedCustomer->deliveryStats->sum('delivered_count') / $total) * 100 : 0;
                    @endphp

                    <div class="progress mb-1" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: {{ $rate }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="fw-bold text-dark">{{ number_format($rate, 2) }}%</span>
                        <a href="#" class="text-info text-decoration-none">Details <i class="fas fa-caret-down"></i></a>
                    </div>

                    <table class="table table-sm table-bordered mt-2 mb-0 text-center" style="font-size: 10px;">
                        <thead class="bg-light">
                            <tr>
                                <th>Courier</th>
                                <th>Total</th>
                                <th>Deli</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($selectedCustomer->deliveryStats as $stat)
                            <tr>
                                <td>{{ $stat->deliveryPartner->name ?? 'N/A' }}</td>
                                <td>{{ $stat->total_count }}</td>
                                <td class="text-success">{{ $stat->delivered_count }}</td>
                                <td>{{ number_format($stat->success_rate, 0) }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-muted py-2">No courier history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Customer Profile Info -->
                <div class="customer-info border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Customer ID</span>
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2" style="font-size: 10px;">REGULAR</span>
                    </div>
                    <div class="fw-bold text-info mb-3 fs-5">C - {{ $selectedCustomer->id }}</div>

                    <div class="mb-2">
                        <label class="text-muted small d-block">Customer Name</label>
                        <p class="fw-bold mb-0 text-dark">{{ $selectedCustomer->name }}</p>
                    </div>

                    <div class="mb-2">
                        <label class="text-muted small d-block">Mobile Number</label>
                        <p class="fw-bold mb-0 text-dark">{{ $selectedCustomer->phone }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small d-block">Address</label>
                        <p class="small mb-0 text-secondary lh-sm">{{ $selectedCustomer->address }}</p>
                    </div>

                    <button class="btn btn-light border btn-sm w-100 fw-bold py-2">
                        <i class="fas fa-history me-1"></i> Order History
                        <span class="badge bg-secondary ms-1">{{ $selectedCustomer->orders()->count() }}</span>
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- MIDDLE COLUMN: PRODUCT SEARCH -->
        <div class="col-md-5">
            <div class="card p-3">
                <div class="d-flex gap-2 mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Search product or SKU..." wire:model.live.debounce.300ms="productSearch">
                    </div>

                    <!-- PRODUCT FILTER DROPDOWN -->
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="fas fa-sliders-h text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-4" style="width: 350px; border-radius: 12px;">
                            <h6 class="fw-bold mb-4">Product Filter</h6>

                            <!-- Categories -->
                            <div class="mb-4">
                                <label class="small fw-bold mb-2 d-block">Categories</label>
                                <select class="form-select bg-light border-0 py-3 rounded" wire:model="filterCategory">
                                    <option value="">Select Category or Sub Categories...</option>
                                    @foreach($allCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <label class="small fw-bold mb-2 d-block">Price Range</label>
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <label class="text-muted d-block" style="font-size: 10px;">Min</label>
                                        <div class="d-flex align-items-center">
                                            <span class="small me-1 text-muted">BDT</span>
                                            <input type="number" class="form-control form-control-sm bg-transparent border-0 p-0 fw-bold" wire:model="filterMinPrice" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <label class="text-muted d-block" style="font-size: 10px;">Max</label>
                                        <div class="d-flex align-items-center">
                                            <span class="small me-1 text-muted">BDT</span>
                                            <input type="number" class="form-control form-control-sm bg-transparent border-0 p-0 fw-bold" wire:model="filterMaxPrice" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Attributes -->
                            <div class="mb-4">
                                <label class="small fw-bold mb-2 d-block">Attributes</label>
                                <select class="form-select bg-light border-0 py-3 rounded" wire:model="filterAttribute">
                                    <option value="">Select Attributes</option>
                                </select>
                            </div>

                            <!-- Filter Actions -->
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary flex-grow-1 border-0 py-2 fw-bold" wire:click="resetFilter" style="background-color: #cccccc;">Reset Filter</button>
                                <button type="button" class="btn btn-info text-white flex-grow-1 border-0 py-2 fw-bold" wire:click="applyFilter">Apply Filter</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-grid pe-1" style="height: 78vh; overflow-y: auto;">
                    <div class="row row-cols-1 row-cols-lg-2 g-2">
                        @foreach($products as $product)
                        <div class="col">
                            <div class="card h-100 border shadow-none p-2 product-card">
                                <div class="d-flex gap-2">
                                    <div style="width: 80px;">
                                        <img src="{{ $product->thumbnail_url }}" class="img-fluid rounded border shadow-sm">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1" style="font-size: 0.8rem;">{{ Str::limit($product->name, 25) }}</h6>
                                        <span class="badge bg-info bg-opacity-10 text-info py-0 mb-2" style="font-size: 9px;">Variant</span>
                                        <div class="text-muted small" style="font-size: 10px;">SKU: <span class="text-info">{{ $product->sku }}</span></div>

                                        <div class="mt-1">
                                            <div class="text-muted" style="font-size: 9px; text-decoration: line-through;">Regular BDT {{ number_format($product->regular_price, 0) }}</div>
                                            <div class="fw-bold text-dark">Sale BDT {{ number_format($product->sale_price ?? $product->regular_price, 0) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                    <span class="badge bg-light text-dark border fw-normal" style="font-size: 10px;">{{ $product->weight }} {{ $product->weight_unit->value ?? '' }}</span>
                                    <button class="btn btn-sm btn-info text-white fw-bold py-0 px-2" wire:click="addToCart({{ $product->id }})">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: CART SECTION -->
        <div class="col-md-4">
            <div class="card h-100 d-flex flex-column">
                <div class="card-header bg-white fw-bold py-3">Cart ({{ count($cart) }})</div>
                <div class="card-body p-0 overflow-auto flex-grow-1" style="max-height: 60vh;">
                    @if(count($cart) > 0)
                    @foreach($cart as $key => $item)
                    <div class="d-flex align-items-center p-2 border-bottom mx-2">
                        <img src="{{ $item['image'] }}" width="40" class="rounded border shadow-sm me-2">
                        <div class="flex-grow-1">
                            <div class="fw-bold" style="font-size: 0.75rem;">{{ $item['name'] }}</div>
                            <div class="text-info fw-bold">BDT {{ number_format($item['price'], 0) }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <input type="number" class="form-control form-control-sm p-1 text-center" style="width: 45px;" wire:model="cart.{{ $key }}.qty">
                            <button class="btn btn-sm text-danger" wire:click="removeFromCart('{{ $key }}')"><i class="fas fa-times-circle"></i></button>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-5 mt-5 opacity-25">
                        <i class="fas fa-shopping-basket fa-4x mb-3"></i>
                        <h6 class="fw-bold">No Products in Cart</h6>
                    </div>
                    @endif
                </div>

                @if(count($cart) > 0)
                <div class="p-3 border-top bg-white">
                    <div class="d-flex justify-content-between fw-bold mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span>BDT {{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3 border-top pt-2">
                        <span>Total Payable:</span>
                        <span class="text-info">BDT {{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    <button class="btn btn-info text-white w-100 fw-bold py-2 shadow">CONFIRM ORDER</button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL: ADD NEW CUSTOMER -->
    <div class="modal fade {{ $showCreateCustomerModal ? 'show d-block' : '' }}" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white border-0 p-3">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Create New Customer</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showCreateCustomerModal', false)"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="newCustomer.name" placeholder="John Doe">
                        @error('newCustomer.name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="newCustomer.phone" placeholder="017...">
                        @error('newCustomer.phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" wire:model="newCustomer.address" rows="2" placeholder="Full delivery address"></textarea>
                        @error('newCustomer.address') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 bg-white">
                    <button type="button" class="btn btn-light border px-4" wire:click="$set('showCreateCustomerModal', false)">Cancel</button>
                    <button type="button" class="btn btn-info text-white px-5 fw-bold shadow-sm" wire:click="saveCustomer">
                        Save & Select
                    </button>
                </div>
            </div>
        </div>
    </div>


    <style>
        .btn-info {
            background-color: #00bcd4;
            border-color: #00bcd4;
        }

        .text-info {
            color: #00bcd4 !important;
        }

        .product-grid::-webkit-scrollbar {
            width: 3px;
        }

        .product-grid::-webkit-scrollbar-thumb {
            background: #00bcd4;
            border-radius: 10px;
        }

        .product-card:hover {
            border-color: #00bcd4 !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>

</div>