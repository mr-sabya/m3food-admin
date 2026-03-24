<div class="row bg-white p-4 rounded shadow-sm border">
    <!-- --- TITLE SETTINGS --- -->
    <div class="col-12 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="fw-bold text-primary"><i class="ri-text-grow me-1"></i> Main Title (HTML Supported)</label>
            <div style="width: 120px;">
                <select class="form-select form-select-sm" wire:model="formData.title_tag">
                    <option value="h1">Tag: H1</option>
                    <option value="h2">Tag: H2</option>
                    <option value="h3">Tag: H3</option>
                    <option value="h4">Tag: H4</option>
                </select>
            </div>
        </div>
        <textarea class="form-control border-primary" wire:model="formData.title" rows="3" placeholder="Enter title..."></textarea>
        <small class="text-muted">Use <code>&lt;span class="highlight"&gt;text&lt;/span&gt;</code> for emphasis.</small>
    </div>

    <div class="col-md-6 mb-4">
        <label class="small fw-bold text-muted mb-1">Title Text Color</label>
        <div class="input-group">
            <input type="color" class="form-control form-control-color w-25" wire:model.live="formData.title_color" title="Choose color">
            <input type="text" class="form-control" wire:model.live="formData.title_color" placeholder="#FFFFFF">
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <label class="small fw-bold text-muted mb-1">Title Background</label>
        <div class="input-group">
            <input type="color" class="form-control form-control-color w-25" wire:model.live="formData.title_bg" title="Choose color">
            <input type="text" class="form-control" wire:model.live="formData.title_bg" placeholder="#005A2B">
        </div>
    </div>

    <hr class="my-3 opacity-25">

    <!-- --- SUBTITLE SETTINGS --- -->
    <div class="col-12 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="fw-bold text-success"><i class="ri-subtract-line me-1"></i> Subtitle Text</label>
            <div style="width: 120px;">
                <select class="form-select form-select-sm" wire:model="formData.subtitle_tag">
                    <option value="h1">Tag: H1</option>
                    <option value="h2">Tag: H2</option>
                    <option value="h3">Tag: H3</option>
                    <option value="h4">Tag: H4</option>
                </select>
            </div>
        </div>
        <input type="text" class="form-control border-success" wire:model="formData.subtitle" placeholder="Enter subtitle...">
    </div>

    <div class="col-md-6 mb-2">
        <label class="small fw-bold text-muted mb-1">Sub Color</label>
        <div class="input-group">
            <input type="color" class="form-control form-control-color w-25" wire:model.live="formData.subtitle_color" title="Choose color">
            <input type="text" class="form-control" wire:model.live="formData.subtitle_color" placeholder="#00542B">
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <label class="small fw-bold text-muted mb-1">Sub Background</label>
        <div class="input-group">
            <input type="color" class="form-control form-control-color w-25" wire:model.live="formData.subtitle_bg" title="Choose color">
            <input type="text" class="form-control" wire:model.live="formData.subtitle_bg" placeholder="#FAD500">
        </div>
    </div>
</div>

<style>
    /* Styling to make the color input look like a professional picker */
    .form-control-color {
        max-width: 60px;
        height: 45px;
        padding: 5px;
        border-radius: 8px 0 0 8px !important;
    }

    .input-group>.form-control:not(.form-control-color) {
        border-radius: 0 8px 8px 0 !important;
    }

    .highlight {
        color: #fbd601;
    }
</style>