<div class="row">
    <div class="col-md-6 mb-3">
        <label>Offer Title</label>
        <input type="text" class="form-control" wire:model="formData.offer_title">
    </div>
    <div class="col-md-6 mb-3">
        <label>End Time (For Timer)</label>
        <input type="datetime-local" class="form-control" wire:model="formData.end_time">
    </div>
    <div class="col-md-6 mb-3">
        <label>Stock Count Text</label>
        <input type="text" class="form-control" wire:model="formData.stock_count_text">
    </div>
    <div class="col-md-6 mb-3">
        <label>BG Color</label>
        <input type="color" class="form-control" wire:model="formData.bg_color">
    </div>
</div>