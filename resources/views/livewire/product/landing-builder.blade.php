<div class="landing-builder-wrapper py-4">
    <div class="row g-4">
        <!-- SIDEBAR: Toolset with all your buttons -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-dark  py-3">
                    <h6 class="mb-0 fw-bold text-white"><i class="ri-apps-2-add-line me-2"></i>Content Blocks</h6>
                </div>
                <div class="card-body p-0">
                    <div class="accordion accordion-flush" id="sectionAccordion">
                        <!-- Group 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#group1">HERO & HEADERS</button></h2>
                            <div id="group1" class="accordion-collapse collapse show">
                                <div class="accordion-body p-2 d-grid gap-2">
                                    <button wire:click="addSection('TitleSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-text-spacing me-2 text-primary"></i> Title Banner</button>
                                    <button wire:click="addSection('VideoSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-video-line me-2 text-danger"></i> Main Intro Video</button>
                                </div>
                            </div>
                        </div>
                        <!-- Group 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#group2">MARKETING & URGENCY</button></h2>
                            <div id="group2" class="accordion-collapse collapse show">
                                <div class="accordion-body p-2 d-grid gap-2">
                                    <button wire:click="addSection('CountdownSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-timer-flash-line me-2 text-warning"></i> Countdown Timer</button>
                                    <button wire:click="addSection('MarketingHighlightSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-mark-pen-line me-2 text-danger"></i> Highlight Boxes</button>
                                    <button wire:click="addSection('FeatureCardSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-layout-grid-line me-2 text-success"></i> Feature Grid Cards</button>
                                    <button wire:click="addSection('VariationPriceSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-price-tag-3-line me-2 text-info"></i> Variant Pricing</button>
                                </div>
                            </div>
                        </div>
                        <!-- Group 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#group3">TRUST & SOCIAL PROOF</button></h2>
                            <div id="group3" class="accordion-collapse collapse show">
                                <div class="accordion-body p-2 d-grid gap-2">
                                    <button wire:click="addSection('MediaNewsSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-broadcast-line me-2 text-info"></i> Media Reports</button>
                                    <button wire:click="addSection('SocialProofSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-chat-smile-3-line me-2 text-success"></i> Chat Testimonials</button>
                                    <button wire:click="addSection('ComparisonSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-arrow-left-right-line me-2 text-primary"></i> Comparison Table</button>
                                    <button wire:click="addSection('WhyChoose')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-shield-check-line me-2 text-success"></i> Why Choose Us</button>
                                </div>
                            </div>
                        </div>
                        <!-- Group 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#group4">PRODUCT DETAILS</button></h2>
                            <div id="group4" class="accordion-collapse collapse show">
                                <div class="accordion-body p-2 d-grid gap-2">
                                    <button wire:click="addSection('BenefitSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-heart-pulse-line me-2 text-danger"></i> Health Benefits</button>
                                    <button wire:click="addSection('UsageSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-restaurant-line me-2 text-warning"></i> Usage Areas</button>
                                    <button wire:click="addSection('CautionSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-error-warning-line me-2 text-danger"></i> Caution Warning</button>
                                    <button wire:click="addSection('DisclaimerSection')" class="btn btn-outline-light text-dark text-start btn-sm border py-2"><i class="ri-question-answer-line me-2 text-muted"></i> FAQ / Disclaimer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CANVAS: Drag & Drop Area -->
        <div class="col-lg-9">
            <div class="bg-white rounded shadow-sm border p-4 min-vh-100">
                <div wire:sortable="updateOrder" wire:sortable.options="{ animation: 250 }" class="d-flex flex-column gap-3">
                    @forelse($sections as $section)
                    <div wire:sortable.item="{{ $section->id }}" wire:key="sec-{{ $section->id }}" class="card shadow-sm border-start border-4 border-primary">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div wire:sortable.handle class="me-3" style="cursor: grab"><i class="ri-drag-move-2-line fs-4 text-muted"></i></div>
                                <div>
                                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem">{{ str_replace('Section', '', class_basename($section->sectionable_type)) }}</small>
                                    <h6 class="mb-0 fw-bold">{!! $section->sectionable->title ?? $section->sectionable->heading ?? $section->sectionable->offer_title ?? $section->sectionable->question ?? 'Untitled' !!}</h6>
                                </div>
                            </div>
                            <div class="btn-group">
                                <button wire:click="editSection({{ $section->id }})" class="btn btn-sm btn-primary"><i class="ri-edit-line"></i></button>
                                <button wire:click="deleteSection({{ $section->id }})" wire:confirm="মুছে ফেলতে চান?" class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 border border-dashed rounded bg-light">
                        <p class="text-muted">আপনার পেজটি খালি। বামপাশ থেকে ব্লক যোগ করুন।</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade @if($showEditModal) show d-block @endif" style="background: rgba(0,0,0,0.5); overflow-y: auto;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-dark p-3">
                    <h5 class="modal-title text-white">Edit {{ $editingType }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showEditModal', false)"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    @if($editingType)
                    @include('livewire.product.partials.' . Str::kebab($editingType))
                    @endif
                </div>
                <div class="modal-footer bg-white">
                    <button class="btn btn-light" wire:click="$set('showEditModal', false)">Cancel</button>
                    <button class="btn btn-success" wire:click="saveContent">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .highlight {
            color: #fbd601;
        }
    </style>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
@endpush