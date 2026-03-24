<div class="row g-3">
    <!-- Main Section Settings -->
    <div class="col-md-8">
        <label class="form-label fw-bold">Heading (টাইটেল)</label>
        <input type="text" class="form-control" wire:model="formData.title" placeholder="কেন আমরা সবার থেকে আলাদা?">
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold">Title Tag</label>
        <select class="form-select" wire:model="formData.title_tag">
            <option value="h1">H1</option>
            <option value="h2">H2</option>
            <option value="h3">H3</option>
            <option value="h4">H4</option>
            <option value="p">Paragraph</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Title Color (টাইটেল কালার)</label>
        <div class="d-flex gap-2">
            <input type="color" class="form-control form-control-color" wire:model="formData.title_color" title="Choose color">
            <input type="text" class="form-control" wire:model="formData.title_color" placeholder="#000000">
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Border Color (বর্ডার কালার)</label>
        <div class="d-flex gap-2">
            <input type="color" class="form-control form-control-color" wire:model="formData.border_color" title="Choose color">
            <input type="text" class="form-control" wire:model="formData.border_color" placeholder="#C41E3A">
        </div>
    </div>

    <hr class="my-4">

    <!-- Comparison Items Repeater -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-primary">
                <i class="ri-list-check-2 me-1"></i> Comparison Points (পয়েন্ট সমূহ)
            </h6>
            <button type="button" class="btn btn-sm btn-dark" wire:click="addSubItem">
                <i class="ri-add-line"></i> Add Point
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 60px;">Order</th>
                        <th>Point Text (পয়েন্ট বর্ণনা)</th>
                        <th style="width: 50px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subItems as $index => $item)
                    <tr wire:key="comp-item-{{ $index }}">
                        <td>
                            <input type="number" class="form-control form-control-sm text-center"
                                wire:model="subItems.{{ $index }}.sort_order">
                        </td>
                        <td>
                            <input type="text" class="form-control"
                                wire:model="subItems.{{ $index }}.point_text"
                                placeholder="১০০% অর্গানিক ও ফ্রেশ মসলা...">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm"
                                wire:click="removeSubItem({{ $index }})">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted small py-4">
                            কোনো পয়েন্ট যুক্ত করা হয়নি। "Add Point" বাটনে ক্লিক করুন।
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>