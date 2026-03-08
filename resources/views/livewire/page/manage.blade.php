<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-magic me-2"></i>{{ $pageTitle }}</h3>
        <div>
            <a href="{{ route('page.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
            <button type="button" wire:click="savePage" class="btn btn-primary px-4 shadow">
                <i class="fas fa-save me-1"></i> Save Page
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <ul class="nav nav-pills p-3 bg-light border-bottom">
                <li class="nav-item"><button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#content"><i class="fas fa-layer-group me-1"></i> Builder</button></li>
                <li class="nav-item"><button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#settings"><i class="fas fa-cog me-1"></i> Layout</button></li>
                <li class="nav-item"><button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#commerce"><i class="fas fa-shopping-cart me-1"></i> Commerce</button></li>
                <li class="nav-item"><button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#seo"><i class="fas fa-search me-1"></i> SEO</button></li>
            </ul>

            <div class="tab-content p-4">
                <!-- BUILDER TAB -->
                <div class="tab-pane fade show active" id="content">
                    @foreach($content as $rowIndex => $row)
                    <div class="card mb-4 border-primary border-2 shadow-sm">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold">Section #{{ $rowIndex+1 }}</span>
                                <div class="d-flex align-items-center gap-1 bg-white p-1 rounded">
                                    <label class="text-dark small mb-0 px-1">BG:</label>
                                    <input type="color" wire:model="content.{{ $rowIndex }}.style.bg_color" class="form-control form-control-xs p-0 border-0" style="width:25px;height:20px;">
                                </div>
                                <div class="d-flex align-items-center gap-1 bg-white p-1 rounded">
                                    <label class="text-dark small mb-0 px-1">Pad Y:</label>
                                    <input type="number" wire:model="content.{{ $rowIndex }}.style.padding_y" class="form-control form-control-xs border-0 py-0" style="width:50px;">
                                </div>
                            </div>
                            <button type="button" wire:click="removeRow({{ $rowIndex }})" class="btn btn-sm btn-danger">×</button>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row g-3">
                                @foreach($row['columns'] as $colIndex => $column)
                                <div class="{{ $column['width'] }}">
                                    <div class="card border-success border-dashed h-100 shadow-sm">
                                        <div class="card-header bg-success text-white py-1 d-flex justify-content-between">
                                            <select wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.width" class="form-select form-select-sm w-auto border-0">
                                                <option value="col-md-12">Full (12)</option>
                                                <option value="col-md-8">8/12</option>
                                                <option value="col-md-6">1/2 (6)</option>
                                                <option value="col-md-4">1/3 (4)</option>
                                            </select>
                                            <button type="button" wire:click="removeColumn({{ $rowIndex }},{{ $colIndex }})" class="btn btn-sm text-white">×</button>
                                        </div>
                                        <div class="card-body p-2">
                                            @foreach($column['elements'] as $elIndex => $element)
                                            <div class="bg-white border p-3 mb-3 rounded shadow-sm position-relative border-start border-4 border-info">
                                                <button type="button" wire:click="removeElement({{ $rowIndex }},{{ $colIndex }},{{ $elIndex }})" class="btn-close position-absolute top-0 end-0 m-2" style="font-size:0.6rem;"></button>

                                                @if($element['type']==='text')
                                                <div class="row g-1 mb-3 bg-white p-2 rounded border">
                                                    <div class="col-3"><label class="x-small">BG</label><input type="color" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.bg_color" class="form-control form-control-sm"></div>
                                                    <div class="col-3"><label class="x-small">Text</label><input type="color" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.text_color" class="form-control form-control-sm"></div>
                                                    <div class="col-3"><label class="x-small">Size(px)</label><input type="number" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.font_size" class="form-control form-control-sm"></div>
                                                    <div class="col-3"><label class="x-small">Align</label>
                                                        <select wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.text_align" class="form-select form-select-sm">
                                                            <option value="text-start">Left</option>
                                                            <option value="text-center">Center</option>
                                                            <option value="text-end">Right</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div wire:key="editor-{{ $rowIndex }}-{{ $colIndex }}-{{ $elIndex }}">
                                                    <livewire:quill-text-editor
                                                        wire:model.live="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.content"
                                                        theme="snow"
                                                        wire:key="editor-{{ $rowIndex }}-{{ $colIndex }}-{{ $elIndex }}" />
                                                </div>
                                                @elseif($element['type']==='image')
                                                <div class="text-center mb-2">
                                                    @if(!empty($element['data']['path']))
                                                    <img src="{{ asset('storage/'.$element['data']['path']) }}" class="img-thumbnail mb-2" style="max-height:100px;">
                                                    @endif
                                                    <input type="file" wire:model="image_tmp" wire:change="uploadElementImage({{ $rowIndex }},{{ $colIndex }},{{ $elIndex }})" class="form-control form-control-sm">
                                                </div>
                                                @elseif($element['type']==='video')
                                                <input type="text" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.id" class="form-control form-control-sm" placeholder="YouTube Video ID">
                                                @endif
                                            </div>
                                            @endforeach

                                            <div class="btn-group w-100">
                                                <button type="button" wire:click="addElement({{ $rowIndex }},{{ $colIndex }},'text')" class="btn btn-xs btn-outline-success">Text</button>
                                                <button type="button" wire:click="addElement({{ $rowIndex }},{{ $colIndex }},'image')" class="btn btn-xs btn-outline-success">Image</button>
                                                <button type="button" wire:click="addElement({{ $rowIndex }},{{ $colIndex }},'video')" class="btn btn-xs btn-outline-success">Video</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-auto d-flex align-items-center">
                                    <button type="button" wire:click="addColumn({{ $rowIndex }})" class="btn btn-outline-success rounded-circle"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <button type="button" wire:click="addRow" class="btn btn-primary w-100 border-dashed py-3"><i class="fas fa-plus-circle me-1"></i> Add Section (Row)</button>
                </div>

                <!-- SETTINGS TAB -->
                <div class="tab-pane fade" id="settings">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Page Title</label>
                            <input type="text" wire:model.live="title" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Theme</label>
                            <select wire:model="theme_id" class="form-select">
                                <option value="">Select Theme</option>
                                @foreach($themes as $theme) <option value="{{ $theme->id }}">{{ $theme->title }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- COMMERCE TAB -->
                <div class="tab-pane fade" id="commerce">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Main Product</label>
                            <select wire:model="main_product_id" class="form-select">
                                <option value="">None</option>
                                @foreach($products as $product) <option value="{{ $product->id }}">{{ $product->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex flex-column gap-2">
                            <div class="form-check">
                                <input type="checkbox" wire:model="show_checkout_form" class="form-check-input" id="checkout_form">
                                <label class="form-check-label" for="checkout_form">Show Checkout Form</label>
                            </div>
                            <input type="text" wire:model="checkout_form_title" class="form-control" placeholder="Checkout Form Title">
                        </div>
                    </div>
                </div>

                <!-- SEO TAB -->
                <div class="tab-pane fade" id="seo">
                    <div class="row g-3">
                        <div class="col-md-12"><label>Meta Title</label><input type="text" wire:model="meta_title" class="form-control"></div>
                        <div class="col-md-12"><label>Meta Description</label><textarea wire:model="meta_description" class="form-control"></textarea></div>
                        <div class="col-md-12"><label>Meta Keywords</label><input type="text" wire:model="meta_keywords" class="form-control"></div>
                        <div class="col-md-12"><label>OG Image</label><input type="file" wire:model="temp_og_image" class="form-control"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>