<div class="py-4">
    <h2 class="mb-4">Warehouse Management</h2>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Warehouses List</h5>
            <button class="btn btn-primary" wire:click="createWarehouse">
                <i class="fas fa-plus"></i> Add New Warehouse
            </button>
        </div>
        <div class="card-body">
            <!-- Search and Pagination Controls -->
            <div class="row align-items-center mb-3">
                <div class="col-md-6 col-lg-4">
                    <input type="text" class="form-control" placeholder="Search warehouses..." wire:model.live.debounce.300ms="search">
                </div>
                <div class="col-md-6 col-lg-8 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
                    <div class="d-flex align-items-center gap-2">
                        <select wire:model.live="perPage" class="form-select w-auto">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="text-nowrap">Per Page</span>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered mb-0">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('code')" role="button">Code
                                @if ($sortField == 'code')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('name')" role="button">Name
                                @if ($sortField == 'name')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th>City</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th wire:click="sortBy('is_active')" role="button">Active
                                @if ($sortField == 'is_active')
                                <i class="fas {{ $sortDirection == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @endif
                            </th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($warehouses as $warehouse)
                        <tr>
                            <td><span class="badge bg-light text-dark">{{ $warehouse->code }}</span></td>
                            <td>{{ $warehouse->name }}</td>
                            <td>{{ $warehouse->city->name ?? 'N/A' }}</td>
                            <td>{{ $warehouse->contact_person }}</td>
                            <td>{{ $warehouse->phone }}</td>
                            <td>
                                @if ($warehouse->is_active)
                                <span class="badge bg-success">Yes</span>
                                @else
                                <span class="badge bg-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info me-1" wire:click="editWarehouse({{ $warehouse->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="deleteWarehouse({{ $warehouse->id }})" wire:confirm="Are you sure you want to delete this warehouse?" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center">
                                <div class="mb-3">
                                    <i class="fas fa-warehouse fa-3x text-secondary opacity-25"></i>
                                </div>
                                <h4 class="fw-bold text-dark">No Warehouses Found</h4>
                                <p class="text-muted">Start by adding your first warehouse location.</p>
                                <button type="button" wire:click="createWarehouse" class="btn btn-primary shadow-sm">
                                    <i class="fas fa-plus me-1"></i> Add New Warehouse
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $warehouses->links() }}
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade {{ $showModal ? 'show d-block' : '' }}" id="warehouseModal" tabindex="-1" role="dialog" @if($showModal) style="background-color: rgba(0,0,0,.5);" @endif>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEditing ? 'Edit Warehouse' : 'Create New Warehouse' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveWarehouse">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.live="name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model.defer="code">
                                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <select class="form-select @error('city_id') is-invalid @enderror" wire:model.defer="city_id">
                                    <option value="">Select a City</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" wire:model.defer="contact_person">
                                @error('contact_person') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" rows="2" wire:model.defer="address"></textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" class="form-control @error('lat') is-invalid @enderror" wire:model.defer="lat" placeholder="e.g. 23.8103">
                                @error('lat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" class="form-control @error('long') is-invalid @enderror" wire:model.defer="long" placeholder="e.g. 90.4125">
                                @error('long') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3 form-check form-switch d-flex align-items-center">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" wire:model.defer="is_active">
                            <label class="form-check-label ms-2 mb-0" for="is_active">Is Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading wire:target="saveWarehouse" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            {{ $isEditing ? 'Update Warehouse' : 'Create Warehouse' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>