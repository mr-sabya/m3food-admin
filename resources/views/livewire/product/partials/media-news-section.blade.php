<div>
    <div class="mb-3">
        <label>Section Heading</label>
        <input type="text" class="form-control" wire:model="formData.title">
    </div>
    <p class="small text-muted">Videos are managed via the MediaVideos relationship.</p>

    <!-- Inside the Edit Modal for MediaNewsSection -->
    @foreach($subItems as $index => $item)
    <div class="row mb-2">
        <div class="col-6">
            <input type="text" wire:model="subItems.{{ $index }}.video_title" class="form-control" placeholder="Video Title">
        </div>
        <div class="col-5">
            <input type="text" wire:model="subItems.{{ $index }}.video_link" class="form-control" placeholder="YouTube URL">
        </div>
        <div class="col-1">
            <button type="button" wire:click="removeSubItem({{ $index }})" class="btn btn-danger">X</button>
        </div>
    </div>
    @endforeach
    <button type="button" wire:click="addSubItem" class="btn btn-primary mt-2">+ Add Video</button>
</div>