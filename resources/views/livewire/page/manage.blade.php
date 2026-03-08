<div class="py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-magic me-2"></i>{{ $pageTitle }}</h3>
        <div>
            <a href="{{ route('page.index') }}" wire:navigate class="btn btn-outline-secondary me-2">Cancel</a>
            <button type="button" wire:click="savePage" class="btn btn-primary px-4 shadow">
                <i class="fas fa-save me-1"></i> Save Page
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <!-- Professional Tabbed UI -->
            <ul class="nav nav-pills p-3 bg-light border-bottom" id="pageTab">
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
                                <span class="fw-bold">Section #{{ $rowIndex + 1 }}</span>
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
                                            <button type="button" wire:click="removeColumn({{ $rowIndex }}, {{ $colIndex }})" class="btn btn-sm text-white">×</button>
                                        </div>
                                        <div class="card-body p-2">
                                            @foreach($column['elements'] as $elIndex => $element)
                                            <div class="bg-white border p-3 mb-3 rounded shadow-sm position-relative border-start border-4 border-info">
                                                <button type="button" wire:click="removeElement({{ $rowIndex }}, {{ $colIndex }}, {{ $elIndex }})" class="btn-close position-absolute top-0 end-0 m-2" style="font-size:0.6rem;"></button>

                                                @if($element['type'] === 'text')
                                                <!-- TEXT STYLE CONTROLS -->
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
                                                    <div class="col-3 mt-1"><label class="x-small">Border</label>
                                                        <select wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.border_style" class="form-select form-select-sm">
                                                            <option value="none">None</option>
                                                            <option value="solid">Solid</option>
                                                            <option value="dashed">Dashed</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-3 mt-1"><label class="x-small">B.Color</label><input type="color" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.border_color" class="form-control form-control-sm"></div>
                                                    <div class="col-3 mt-1"><label class="x-small">B.Width</label><input type="number" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.border_width" class="form-control form-control-sm"></div>
                                                    <div class="col-3 mt-1"><label class="x-small">Radius</label><input type="number" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.border_radius" class="form-control form-control-sm"></div>
                                                </div>
                                                <!-- QUILL EDITOR INTEGRATION -->
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-info mb-1">
                                                        <i class="fas fa-feather-alt me-1"></i> Rich Text Editor
                                                    </label>

                                                    {{-- Use wire:key to ensure Livewire tracks the nested editor correctly during DOM diffing --}}
                                                    @if(isset($content[$rowIndex]['columns'][$colIndex]['elements'][$elIndex]['data']['content']))
                                                    <div wire:key="editor-{{ $rowIndex }}-{{ $colIndex }}-{{ $elIndex }}">
                                                        <livewire:quill-text-editor
                                                            wire:model.live="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.content"
                                                            theme="snow" />
                                                    </div>
                                                    @endif

                                                    <small class="text-muted mt-1 d-block">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Styles applied in the editor will combine with the box styles below.
                                                    </small>
                                                </div>


                                                @elseif($element['type'] === 'image')
                                                <!-- IMAGE STYLE CONTROLS -->
                                                <div class="row g-1 mb-3 bg-white p-2 rounded border">
                                                    <div class="col-3"><label class="x-small">Width %</label><input type="number" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.width" class="form-control form-control-sm"></div>
                                                    <div class="col-3"><label class="x-small">Radius</label><input type="number" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.border_radius" class="form-control form-control-sm"></div>
                                                    <div class="col-3"><label class="x-small">Shadow</label>
                                                        <select wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.shadow" class="form-select form-select-sm">
                                                            <option value="none">None</option>
                                                            <option value="shadow-sm">Small</option>
                                                            <option value="shadow">Large</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-3"><label class="x-small">Border</label><input type="number" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.style.border_width" class="form-control form-control-sm"></div>
                                                </div>

                                                <div class="text-center">
                                                    @if(!empty($element['data']['path']))
                                                    <img src="{{ asset('storage/'.$element['data']['path']) }}" class="img-thumbnail mb-2" style="max-height:100px;">
                                                    @endif
                                                    <input type="file" wire:model="image_tmp" wire:change="uploadElementImage({{ $rowIndex }}, {{ $colIndex }}, {{ $elIndex }}, $event.target.files[0])" class="form-control form-control-sm">
                                                </div>
                                                @elseif($element['type'] === 'video')
                                                <input type="text" wire:model="content.{{ $rowIndex }}.columns.{{ $colIndex }}.elements.{{ $elIndex }}.data.id" class="form-control form-control-sm" placeholder="YouTube Video ID">
                                                @endif
                                            </div>
                                            @endforeach
                                            <div class="btn-group w-100">
                                                <button type="button" wire:click="addElement({{ $rowIndex }}, {{ $colIndex }}, 'text')" class="btn btn-xs btn-outline-success">Text</button>
                                                <button type="button" wire:click="addElement({{ $rowIndex }}, {{ $colIndex }}, 'image')" class="btn btn-xs btn-outline-success">Image</button>
                                                <button type="button" wire:click="addElement({{ $rowIndex }}, {{ $colIndex }}, 'video')" class="btn btn-xs btn-outline-success">Video</button>
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

                <!-- SETTINGS TAB (Header/Footer Selection) -->
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
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold border-bottom pb-2">Navigation</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" wire:model="is_header_visible" id="ih">
                                        <label class="form-check-label" for="ih">Show Header</label>
                                    </div>
                                    <select wire:model="header_id" class="form-select mb-3" {{ !$is_header_visible ? 'disabled' : '' }}>
                                        <option value="">Select Header Style</option>
                                        @foreach($headers as $h) <option value="{{ $h->id }}">{{ $h->title }}</option> @endforeach
                                    </select>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" wire:model="is_footer_visible" id="if">
                                        <label class="form-check-label" for="if">Show Footer</label>
                                    </div>
                                    <select wire:model="footer_id" class="form-select" {{ !$is_footer_visible ? 'disabled' : '' }}>
                                        <option value="">Select Footer Style</option>
                                        @foreach($footers as $f) <option value="{{ $f->id }}">{{ $f->title }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <select wire:model="status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
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
                                @foreach($products as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" wire:model="show_checkout_form" id="sc">
                                <label class="form-check-label" for="sc">Show Checkout Form</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="show_timer" id="st">
                                <label class="form-check-label" for="st">Enable Countdown Timer</label>
                            </div>
                        </div>
                        @if($show_timer)
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Offer Ends At</label>
                            <input type="datetime-local" wire:model="timer_ends_at" class="form-control">
                        </div>
                        @endif
                    </div>
                </div>

                <!-- SEO TAB -->
                <div class="tab-pane fade" id="seo">
                    <div class="row g-4">
                        <div class="col-md-12"><label class="fw-bold">Meta Title</label><input type="text" wire:model="meta_title" class="form-control"></div>
                        <div class="col-md-12"><label class="fw-bold">Meta Description</label><textarea wire:model="meta_description" class="form-control" rows="3"></textarea></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>