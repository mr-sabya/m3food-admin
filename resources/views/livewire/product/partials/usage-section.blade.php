<div class="bg-light p-3 rounded border">
    <!-- Top Part: Rich Text Description -->
    <div class="mb-4">
        <label class="fw-bold mb-2 text-primary"><i class="ri-edit-box-line me-1"></i> Usage Description (HTML)</label>
        <textarea class="form-control" wire:model="formData.description" rows="4"
            placeholder="Describe how to use the product or general serving suggestions..."></textarea>
        <small class="text-muted italic">Note: You can use HTML tags like &lt;b&gt; or &lt;br&gt; for styling.</small>
    </div>

    <hr class="my-4">

    <!-- Bottom Part: Food Suggestions (Repeater) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0 text-success"><i class="ri-restaurant-2-line me-1"></i> What to eat with? (Food Items)</h6>
        <button type="button" wire:click="addSubItem" class="btn btn-sm btn-success">
            <i class="ri-add-fill"></i> Add Food Item
        </button>
    </div>

    <div class="row g-3">
        @foreach($subItems as $index => $item)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-success small">Item #{{ $index + 1 }}</span>
                        <button type="button" wire:click="removeSubItem({{ $index }})" class="btn btn-link text-danger p-0">
                            <i class="ri-delete-bin-6-line"></i>
                        </button>
                    </div>

                    <!-- Image Upload & Preview -->
                    <div class="text-center mb-3 border rounded p-2 bg-white position-relative">
                        @if (isset($tempFiles[$index]))
                        <img src="{{ $tempFiles[$index]->temporaryUrl() }}" class="img-fluid rounded mb-2" style="height: 80px; width: 80px; object-fit: cover;">
                        @elseif (!empty($item['image_path']))
                        <img src="{{ asset('storage/' . $item['image_path']) }}" class="img-fluid rounded mb-2" style="height: 80px; width: 80px; object-fit: cover;">
                        @else
                        <div class="py-3 text-muted small border-dashed border-2 rounded mb-2">
                            <i class="ri-image-add-line fs-2 d-block"></i>
                            Upload Photo
                        </div>
                        @endif

                        <input type="file" wire:model="tempFiles.{{ $index }}" class="form-control form-control-sm">

                        <!-- Loading Spinner -->
                        <div wire:loading wire:target="tempFiles.{{ $index }}" class="position-absolute top-50 start-50 translate-middle">
                            <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                        </div>
                    </div>

                    <!-- Food Name -->
                    <div class="mb-2">
                        <label class="small fw-bold">Food Name</label>
                        <input type="text" wire:model="subItems.{{ $index }}.food_name"
                            class="form-control form-control-sm" placeholder="e.g. গরুর মাংস">
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="small fw-bold text-muted">Sort Order</label>
                        <input type="number" wire:model="subItems.{{ $index }}.sort_order"
                            class="form-control form-control-sm" style="width: 80px;">
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(empty($subItems))
    <div class="text-center py-4 bg-white border-dashed rounded">
        <p class="text-muted mb-0 small">No food items added. Click the green button to add suggestions.</p>
    </div>
    @endif
</div>