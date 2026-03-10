<div class="bg-light p-3 rounded border border-info">
    <div class="mb-3">
        <label class="fw-bold"><i class="ri-question-line"></i> The Question</label>
        <textarea class="form-control" wire:model="formData.question" rows="2"></textarea>
    </div>

    <div class="mb-3">
        <label class="fw-bold"><i class="ri-chat-check-line"></i> The Answer</label>
        <textarea class="form-control" wire:model="formData.answer" rows="3"></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="small fw-bold">Background Color</label>
            <input type="color" class="form-control form-control-color w-100" wire:model="formData.bg_color">
        </div>
        <div class="col-md-6 mb-3">
            <label class="small fw-bold">Text Color</label>
            <input type="color" class="form-control form-control-color w-100" wire:model="formData.text_color">
        </div>
    </div>
</div>