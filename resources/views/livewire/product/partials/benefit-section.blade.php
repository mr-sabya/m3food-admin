<div class="bg-light p-3 rounded border">
    <!-- Main Section Settings -->
    <div class="row">
        <div class="col-12 mb-3">
            <label class="fw-bold mb-1"><i class="ri-edit-2-line"></i> Section Heading</label>
            <input type="text" class="form-control shadow-sm" wire:model="formData.heading" placeholder="e.g. দেশীয় চুইঝালের স্বাস্থ্য উপকারিতা">
        </div>

        <div class="col-12 mb-4">
            <label class="fw-bold mb-1"><i class="ri-image-line"></i> Infographic Image (Vertical Side Image)</label>
            <div class="border rounded p-3 text-center bg-white shadow-sm">
                @if (isset($tempFiles['main']))
                <img src="{{ $tempFiles['main']->temporaryUrl() }}" class="img-thumbnail mb-2" style="max-height: 180px;">
                @elseif(!empty($formData['infographic_image']))
                <img src="{{ asset('storage/' . $formData['infographic_image']) }}" class="img-thumbnail mb-2" style="max-height: 180px;">
                @else
                <div class="py-4 text-muted small border-dashed border-2 rounded mb-2">
                    <i class="ri-image-add-line fs-1 d-block opacity-50"></i> Upload Vertical Infographic
                </div>
                @endif

                <input type="file" wire:model="tempFiles.main" class="form-control form-control-sm mt-2">
                <div wire:loading wire:target="tempFiles.main" class="text-info small mt-1">
                    <div class="spinner-border spinner-border-sm me-1" role="status"></div> Uploading image...
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <!-- Benefit Points Repeater (BenefitItem Relation) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0 text-success"><i class="ri-checkbox-circle-line me-1"></i> Health Benefit Points</h6>
        <button type="button" wire:click="addSubItem" class="btn btn-sm btn-success shadow-sm">
            <i class="ri-add-line"></i> Add New Point
        </button>
    </div>

    <div class="d-flex flex-column gap-2">
        @forelse($subItems as $index => $item)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-2 bg-white rounded">
                <div class="row g-2 align-items-center">
                    <!-- Drag/Sort Indicator -->
                    <div class="col-auto">
                        <span class="badge bg-light text-dark border">#{{ $index + 1 }}</span>
                    </div>

                    <!-- Benefit Text -->
                    <div class="col">
                        <input type="text" wire:model="subItems.{{ $index }}.benefit_text"
                            class="form-control form-control-sm border-0 bg-light"
                            placeholder="e.g. শরীরের রোগ প্রতিরোধ ক্ষমতা বৃদ্ধিতে কার্যকরী">
                    </div>

                    <!-- Sort Order -->
                    <div class="col-auto">
                        <input type="number" wire:model="subItems.{{ $index }}.sort_order"
                            class="form-control form-control-sm text-center" style="width: 60px;">
                    </div>

                    <!-- Delete Button -->
                    <div class="col-auto">
                        <button type="button" wire:click="removeSubItem({{ $index }})" class="btn btn-sm btn-link text-danger p-0">
                            <i class="ri-close-circle-line fs-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-4 bg-white border-dashed rounded text-muted small">
            <i class="ri-list-check fs-2 d-block opacity-25"></i>
            No benefit points added yet.
        </div>
        @endforelse
    </div>
</div>

<style>
    .border-dashed {
        border: 2px dashed #dee2e6 !important;
    }
</style>