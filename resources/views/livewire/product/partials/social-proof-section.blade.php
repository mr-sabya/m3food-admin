<div class="bg-light p-3 rounded border">
    <!-- Main Settings -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <label class="fw-bold mb-1"><i class="ri-chat-quote-line me-1"></i> Section Heading</label>
            <input type="text" class="form-control" wire:model="formData.heading" placeholder="e.g. আমাদের সম্মানিত ক্লায়েন্টদের মতামত">
        </div>
        <div class="col-md-4 mb-3">
            <label class="fw-bold mb-1">Background Color</label>
            <div class="d-flex align-items-center gap-2">
                <input type="color" class="form-control form-control-color shadow-sm" wire:model="formData.bg_color" title="Choose background color">
                <span class="small text-muted">{{ $formData['bg_color'] ?? '#004d26' }}</span>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <!-- Testimonials Repeater -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0 text-primary"><i class="ri-screenshot-2-line me-1"></i> Proof Items (Screenshots / Videos)</h6>
        <button type="button" wire:click="addSubItem" class="btn btn-sm btn-primary shadow-sm">
            <i class="ri-add-circle-line"></i> Add Testimonial
        </button>
    </div>

    <div class="row g-3">
        @foreach($subItems as $index => $item)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-soft-primary text-primary px-3 py-2">Item #{{ $index + 1 }}</span>
                        <button type="button" wire:click="removeSubItem({{ $index }})" class="btn btn-sm btn-outline-danger border-0">
                            <i class="ri-delete-bin-line fs-5"></i>
                        </button>
                    </div>

                    <!-- Selector: Image or Video -->
                    <div class="mb-3">
                        <label class="small fw-bold d-block mb-2">Proof Type</label>
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <input type="radio" class="btn-check" wire:model="subItems.{{ $index }}.type" value="image" id="type-img-{{ $index }}" checked>
                            <label class="btn btn-outline-secondary" for="type-img-{{ $index }}"><i class="ri-image-line me-1"></i> Screenshot</label>

                            <input type="radio" class="btn-check" wire:model="subItems.{{ $index }}.type" value="video" id="type-vid-{{ $index }}">
                            <label class="btn btn-outline-secondary" for="type-vid-{{ $index }}"><i class="ri-video-chat-line me-1"></i> Video Link</label>
                        </div>
                    </div>

                    <!-- Content Based on Type -->
                    @if(($subItems[$index]['type'] ?? 'image') === 'image')
                    <!-- Image Upload -->
                    <div class="text-center p-2 border rounded bg-light mb-2">
                        @if (isset($tempFiles[$index]))
                        <img src="{{ $tempFiles[$index]->temporaryUrl() }}" class="img-fluid rounded mb-2" style="max-height: 120px; width: 100%; object-fit: contain;">
                        @elseif (!empty($item['image_path']))
                        <img src="{{ asset('storage/' . $item['image_path']) }}" class="img-fluid rounded mb-2" style="max-height: 120px; width: 100%; object-fit: contain;">
                        @else
                        <div class="py-4 text-muted small"><i class="ri-image-add-line fs-1 d-block opacity-50"></i> Upload Chat Screenshot</div>
                        @endif

                        <input type="file" wire:model="tempFiles.{{ $index }}" class="form-control form-control-sm mt-1">
                        <div wire:loading wire:target="tempFiles.{{ $index }}" class="small text-primary mt-1">
                            <div class="spinner-border spinner-border-sm me-1" role="status"></div> Uploading...
                        </div>
                    </div>
                    @else
                    <!-- Video URL Input -->
                    <div class="mb-3">
                        <label class="small fw-bold">YouTube / Vimeo URL</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="ri-link"></i></span>
                            <input type="text" wire:model="subItems.{{ $index }}.video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                        </div>
                    </div>
                    @endif

                    <div class="row align-items-center mt-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Display Order</label>
                            <input type="number" wire:model="subItems.{{ $index }}.sort_order" class="form-control form-control-sm" style="width: 80px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(empty($subItems))
    <div class="text-center py-5 bg-white border-dashed rounded mt-3">
        <i class="ri-chat-history-line fs-1 text-muted opacity-25"></i>
        <p class="text-muted mb-0 small mt-2">No testimonials added yet. Click "Add Testimonial" to start.</p>
    </div>
    @endif

    <style>
        .bg-soft-primary {
            background-color: #e7f1ff;
        }

        .form-control-color {
            width: 60px;
            height: 38px;
            padding: 4px;
        }

        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
        }
    </style>

</div>