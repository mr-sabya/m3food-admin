<div class="bg-light p-3 rounded border">
    <div class="mb-4">
        <label class="fw-bold mb-1">Section Header Title</label>
        <input type="text" class="form-control" wire:model="formData.section_title">
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Feature Cards</h6>
        <button type="button" wire:click="addSubItem" class="btn btn-sm btn-primary">+ Add Card</button>
    </div>

    <div class="row g-3">
        @foreach($subItems as $index => $item)
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-dark">Card #{{ $index + 1 }}</span>
                        <button type="button" wire:click="removeSubItem({{ $index }})" class="btn btn-sm text-danger"><i class="ri-delete-bin-line"></i></button>
                    </div>

                    <!-- Image Upload & Preview -->
                    <div class="text-center mb-2 border rounded p-2 bg-white">
                        @if (isset($tempFiles[$index]))
                        <!-- Real-time Preview of Uploaded File -->
                        <img src="{{ $tempFiles[$index]->temporaryUrl() }}" class="img-fluid rounded mb-2" style="max-height: 100px;">
                        @elseif (!empty($item['image_path']))
                        <!-- Existing Database Image -->
                        <img src="{{ asset('storage/' . $item['image_path']) }}" class="img-fluid rounded mb-2" style="max-height: 100px;">
                        @else
                        <div class="py-3 text-muted small"><i class="ri-image-add-line fs-2 d-block"></i> No Image</div>
                        @endif

                        <input type="file" wire:model="tempFiles.{{ $index }}" class="form-control form-control-sm mt-1">
                        <div wire:loading wire:target="tempFiles.{{ $index }}" class="small text-primary mt-1">Uploading...</div>
                    </div>

                    <input type="number" wire:model="subItems.{{ $index }}.sort_order" class="form-control form-control-sm" placeholder="Order">
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>