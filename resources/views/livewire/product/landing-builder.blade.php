<div class="landing-builder-wrapper py-4">
    <div class="row g-4">

        <!-- SIDEBAR: Section Toolset -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px; z-index: 10;">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="ri-apps-2-add-line me-2"></i>Content Blocks</h6>
                </div>
                <div class="card-body p-0">
                    <!-- Categorized Section Buttons -->
                    <div class="accordion accordion-flush" id="sectionAccordion">

                        <!-- Group 1: Hero & Headers -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#group1">
                                    HERO & HEADERS
                                </button>
                            </h2>
                            <div id="group1" class="accordion-collapse collapse show">
                                <div class="accordion-body p-2 d-grid gap-2">
                                    <button wire:click="addSection('TitleSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-text-spacing me-2 text-primary"></i> Title Banner
                                    </button>
                                    <button wire:click="addSection('VideoSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-video-line me-2 text-danger"></i> Main Intro Video
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Group 2: Marketing & Urgency -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#group2">
                                    MARKETING & URGENCY
                                </button>
                            </h2>
                            <div id="group2" class="accordion-collapse collapse">
                                <div class="accordion-body p-2 d-grid gap-2">
                                    <button wire:click="addSection('CountdownSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-timer-flash-line me-2 text-warning"></i> Countdown Timer
                                    </button>
                                    <button wire:click="addSection('MarketingHighlightSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-mark-pen-line me-2 text-danger"></i> Red Box Highlights
                                    </button>
                                    <button wire:click="addSection('FeatureCardSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-layout-grid-line me-2 text-success"></i> 4-Grid Challenge
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Group 3: Trust & Social Proof -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#group3">
                                    TRUST & SOCIAL PROOF
                                </button>
                            </h2>
                            <div id="group3" class="accordion-collapse collapse">
                                <div class="accordion-body p-2 d-grid gap-2">
                                    <button wire:click="addSection('MediaNewsSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-broadcast-line me-2 text-info"></i> Media Reports
                                    </button>
                                    <button wire:click="addSection('SocialProofSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-chat-smile-3-line me-2 text-success"></i> Chat Testimonials
                                    </button>
                                    <button wire:click="addSection('ComparisonSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-arrow-left-right-line me-2 text-primary"></i> Why Choose Us?
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Group 4: Product Details -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#group4">
                                    PRODUCT INFO
                                </button>
                            </h2>
                            <div id="group4" class="accordion-collapse collapse">
                                <div class="accordion-body p-2 d-grid gap-2">
                                    <button wire:click="addSection('BenefitSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-heart-pulse-line me-2 text-danger"></i> Health Benefits
                                    </button>
                                    <button wire:click="addSection('UsageSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-restaurant-line me-2 text-warning"></i> Usage Suggestions
                                    </button>
                                    <button wire:click="addSection('CautionSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-error-warning-line me-2 text-danger"></i> Caution Warning
                                    </button>
                                    <button wire:click="addSection('DisclaimerSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2">
                                        <i class="ri-question-answer-line me-2 text-muted"></i> Disclaimer Area
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light p-3">
                    <p class="small text-muted mb-0"><i class="ri-information-line me-1"></i> Clicking a block adds it to the bottom of the canvas.</p>
                </div>
            </div>
        </div>

        <!-- CANVAS: Drag & Drop Area -->
        <div class="col-lg-9">
            <div class="bg-white rounded shadow-sm border p-4 min-vh-100">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0">Visual Page Flow</h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-soft-info text-info"><i class="ri-drag-move-fill me-1"></i> Reorderable</span>
                        <span class="badge bg-soft-success text-success"><i class="ri-save-line me-1"></i> Auto-saved</span>
                    </div>
                </div>

                <!-- Sortable Sections Container -->
                <div wire:sortable="updateOrder" class="d-flex flex-column gap-3">
                    @forelse($sections as $section)
                    <div wire:sortable.item="{{ $section->id }}" wire:key="section-{{ $section->id }}"
                        class="builder-item card shadow-sm border-start border-4 border-primary">

                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <!-- Handle -->
                                    <div wire:sortable.handle class="me-3" style="cursor: grab">
                                        <i class="ri-drag-move-2-line fs-4 text-muted"></i>
                                    </div>

                                    <!-- Info -->
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-uppercase fw-bold text-muted small" style="font-size: 0.65rem">
                                                {{ str_replace('Section', '', class_basename($section->sectionable_type)) }}
                                            </small>
                                            @if($section->is_active)
                                            <i class="ri-checkbox-circle-fill text-success" style="font-size: 0.8rem"></i>
                                            @endif
                                        </div>
                                        <h6 class="mb-0 fw-bold text-dark">
                                            {{ $section->sectionable->title ?? $section->sectionable->heading ?? $section->sectionable->question ?? 'Untitled Block' }}
                                        </h6>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="btn-group">
                                    <button wire:click="editSection({{ $section->id }})" class="btn btn-sm btn-primary">
                                        <i class="ri-edit-line me-1"></i> Edit
                                    </button>
                                    <button wire:click="deleteSection({{ $section->id }})"
                                        wire:confirm="Permanent delete this section?"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 border border-dashed rounded bg-light">
                        <i class="ri-layout-top-line fs-1 text-muted opacity-25"></i>
                        <p class="mt-2 text-muted">Your canvas is empty. Add blocks from the sidebar to start building.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal (Dynamic Content Loader) -->
    <div class="modal fade @if($showEditModal) show d-block @endif" style="background: rgba(0,0,0,0.5); overflow-y: auto;" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="ri-settings-3-line me-2"></i> Configure {{ str_replace('Section', '', $editingType) }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showEditModal', false)"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    @if($editingType)
                    @include('livewire.product.partials.' . Str::kebab($editingType))
                    @endif
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-light" wire:click="$set('showEditModal', false)">Discard</button>
                    <button type="button" class="btn btn-success px-4" wire:click="saveContent">
                        <span wire:loading wire:target="saveContent" class="spinner-border spinner-border-sm me-2"></span>
                        <i class="ri-checkbox-circle-line me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>


    <style>
        .builder-item:hover {
            border-color: #0d6efd !important;
            background: #f8fbff;
        }

        .bg-soft-info {
            background: #e0f2ff;
            color: #0866ff;
        }

        .bg-soft-success {
            background: #e7fcf5;
            color: #008a5d;
        }

        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa;
            color: #000;
            box-shadow: none;
        }

        .btn-outline-light:hover {
            background: #f0f0f0;
        }

        .modal-lg {
            max-width: 850px;
        }
    </style>
</div>

@push('scripts')
<!-- 1. Core SortableJS Library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<!-- 2. Livewire Adapter -->
<script src="https://cdn.jsdelivr.net/npm/@nextapps-be/livewire-sortablejs@latest/dist/livewire-sortablejs.min.js"></script>
@endpush