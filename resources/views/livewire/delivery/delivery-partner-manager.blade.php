<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Delivery Partners</h2>
        <button class="btn btn-primary" wire:click="createPartner">
            <i class="fas fa-plus"></i> Add Partner
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search..." wire:model.live.debounce.300ms="search">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover border">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('name')" role="button">Name</th>
                            <th>Type</th>
                            <th>Warehouse</th>
                            <th>Phone</th>
                            <th>Fees (In/Out/Sub)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partners as $partner)
                        <tr>
                            <td>{{ $partner->name }}</td>
                            <td><span class="badge bg-secondary">{{ $partner->type }}</span></td>
                            <td>{{ $partner->warehouse->name ?? 'N/A' }}</td>
                            <td>{{ $partner->phone }}</td>
                            <td>{{ $partner->fee_inside }} / {{ $partner->fee_outside }} / {{ $partner->fee_suburb }}</td>
                            <td>
                                <span class="badge {{ $partner->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $partner->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" wire:click="editPartner({{ $partner->id }})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" wire:click="deletePartner({{ $partner->id }})" wire:confirm="Delete this partner?"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $partners->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade {{ $showModal ? 'show d-block' : '' }}" tabindex="-1" @if($showModal) style="background-color: rgba(0,0,0,.5);" @endif>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEditing ? 'Edit Partner' : 'New Partner' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="savePartner">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Basic Info -->
                            <div class="col-md-4 border-end">
                                <h6 class="fw-bold mb-3">General Information</h6>
                                <div class="mb-3">
                                    <label class="form-label">Partner Name*</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type (e.g. Courier, In-house)*</label>
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" wire:model.defer="type">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Warehouse*</label>
                                    <select class="form-select @error('warehouse_id') is-invalid @enderror" wire:model.defer="warehouse_id">
                                        <option value="">Select Warehouse</option>
                                        @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Person</label>
                                    <input type="text" class="form-control" wire:model.defer="contact_person">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone*</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
                                </div>
                            </div>

                            <!-- Fees Section -->
                            <div class="col-md-8">
                                <h6 class="fw-bold mb-3">Pricing & Fees</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fee (Inside City)*</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="fee_inside">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fee (Outside City)*</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="fee_outside">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fee (Suburb)*</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="fee_suburb">
                                    </div>
                                </div>

                                <div class="row bg-light p-2 rounded mb-3">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Express (Inside)</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="express_fee_inside">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Express (Outside)</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="express_fee_outside">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Express (Suburb)</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="express_fee_suburb">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Delivery Cost (Courier)</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="courier_delivery_cost">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Return Cost (Courier)</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="courier_return_cost">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">COD Charge %</label>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="cod_charge_percent">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" rows="2" wire:model.defer="address"></textarea>
                                </div>

                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="p_active" wire:model.defer="is_active">
                                    <label class="form-check-label" for="p_active">Active Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Partner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>