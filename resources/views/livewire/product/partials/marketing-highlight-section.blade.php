<div class="bg-light p-3 rounded border">
    <div class="alert alert-info py-2 small mb-4">
        <i class="ri-information-line me-1"></i>
        These boxes represent the high-impact **Marketing Pitches** with thick colored borders.
    </div>

    <!-- Top Highlight Box Configuration -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-2 fw-bold d-flex justify-content-between align-items-center">
            <span><i class="ri-layout-top-line me-1 text-primary"></i> Top Highlight Box</span>
            <span class="badge bg-soft-primary text-primary small">HTML Active</span>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="small fw-bold mb-1">Content (HTML Supported)</label>
                <textarea class="form-control" wire:model="formData.top_boxed_text" rows="3"
                    placeholder="e.g. মাত্র ০৫ মিনিটে সর্দি-কাশি দূর করার চ্যালেঞ্জ!"></textarea>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1">Box Border Color</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="color" class="form-control form-control-color shadow-sm"
                            wire:model="formData.top_box_border_color" title="Choose border color">
                        <code class="small text-muted">{{ $formData['top_box_border_color'] ?? '#ff0000' }}</code>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <span class="small text-muted italic">Visual preview will show a thick 4px border.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Highlight Box Configuration -->
    <div class="card shadow-sm mb-0 border-0">
        <div class="card-header bg-white py-2 fw-bold d-flex justify-content-between align-items-center">
            <span><i class="ri-layout-bottom-line me-1 text-success"></i> Bottom Highlight Box</span>
            <span class="badge bg-soft-success text-success small">HTML Active</span>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="small fw-bold mb-1">Content (HTML Supported)</label>
                <textarea class="form-control" wire:model="formData.bottom_boxed_text" rows="3"
                    placeholder="e.g. চুইঝাল খুলনাঞ্চলের একটি ঐতিহ্যবাহী ঔষধী খাবার..."></textarea>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1">Box Border Color</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="color" class="form-control form-control-color shadow-sm"
                            wire:model="formData.bottom_box_border_color" title="Choose border color">
                        <code class="small text-muted">{{ $formData['bottom_box_border_color'] ?? '#ff0000' }}</code>
                    </div>
                </div>
                <div class="col-md-6 text-end text-muted small">
                    <i class="ri-lightbulb-line me-1"></i> Use <code>&lt;br&gt;</code> for new lines.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control-color {
        width: 50px;
        height: 35px;
        padding: 3px;
    }

    .bg-soft-primary {
        background-color: #e7f1ff;
    }

    .bg-soft-success {
        background-color: #e6fcf5;
    }
</style>