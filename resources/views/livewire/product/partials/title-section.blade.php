<div class="row bg-light p-3 rounded border">
    <div class="col-12 mb-3">
        <label class="fw-bold">Title (HTML Supported)</label>
        <textarea class="form-control" wire:model="formData.title" rows="2"></textarea>
    </div>
    <div class="col-md-3 mb-3"><label>Title Color</label><input type="color" class="form-control h-auto" wire:model="formData.title_color"></div>
    <div class="col-md-3 mb-3"><label>Title BG</label><input type="color" class="form-control h-auto" wire:model="formData.title_bg"></div>
    <div class="col-md-3 mb-3">
        <label>Tag</label>
        <select class="form-select" wire:model="formData.title_tag">
            <option value="h1">H1</option>
            <option value="h2">H2</option>
            <option value="h3">H3</option>
            <option value="h4">H4</option>
        </select>
    </div>
    <hr>
    <div class="col-12 mb-3">
        <label class="fw-bold">Subtitle</label>
        <input type="text" class="form-control" wire:model="formData.subtitle">
    </div>
    <div class="col-md-3"><label>Sub Color</label><input type="color" class="form-control h-auto" wire:model="formData.subtitle_color"></div>
    <div class="col-md-3"><label>Sub BG</label><input type="color" class="form-control h-auto" wire:model="formData.subtitle_bg"></div>
    <div class="col-md-3 mb-3">
        <label>Tag</label>
        <select class="form-select" wire:model="formData.subtitle_tag">
            <option value="h1">H1</option>
            <option value="h2">H2</option>
            <option value="h3">H3</option>
            <option value="h4">H4</option>
        </select>
    </div>
</div>