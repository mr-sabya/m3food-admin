<div class="container-fluid py-4">
    <!-- Header -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">Landing Page Designer: {{ $product->name }}</h4>
                <p class="text-muted small mb-0">Drag sections to reorder them on the live page.</p>
            </div>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="ri-add-line"></i> Add Content Section
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <!-- ১. হেডলাইন ও ব্যানার -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('TitleSection')"><i class="ri-text-spacing me-2"></i>Title Banner (Colored Boxes)</a></li>

                    <!-- ২. সংবাদ ও মিডিয়া -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('MediaNewsSection')"><i class="ri-broadcast-line me-2"></i>Media/News Reports</a></li>

                    <!-- ৩. মূল ভিডিও পরিচিতি -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('VideoSection')"><i class="ri-video-line me-2"></i>Main Product Video</a></li>

                    <!-- ৪. অফার ও কাউন্টডাউন টাইমার -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('CountdownSection')"><i class="ri-timer-flash-line me-2"></i>Scarcity & Countdown (Timer)</a></li>

                    <!-- ৫. মার্কেটিং টেক্সট (রেড বক্স) -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('MarketingHighlightSection')"><i class="ri-mark-pen-line me-2"></i>Marketing Highlights (Red Boxes)</a></li>

                    <!-- ৬. ম্যাজিক চ্যালেঞ্জ (৪টি কার্ড) -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('FeatureCardSection')"><i class="ri-layout-grid-line me-2"></i>Feature Cards (4 Green Images)</a></li>

                    <!-- ৭. ডিসক্লেমার (ঔষধ নাকি খাবার?) -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('DisclaimerSection')"><i class="ri-question-answer-line me-2"></i>Disclaimer (Not Medicine)</a></li>

                    <!-- ৮. স্বাস্থ্য উপকারিতা -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('BenefitSection')"><i class="ri-heart-pulse-line me-2"></i>Health Benefits (Checklist)</a></li>

                    <!-- ৯. সাবধানতা সেকশন -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('CautionSection')"><i class="ri-error-warning-line me-2"></i>Caution Warning (Red Warning)</a></li>

                    <!-- ১০. কেন আমরা সেরা (তুলনা) -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('ComparisonSection')"><i class="ri-checkbox-multiple-line me-2"></i>Comparison (Why Choose Us)</a></li>

                    <!-- ১১. ব্যবহারের নিয়ম ও খাবারের ছবি -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('UsageSection')"><i class="ri-restaurant-line me-2"></i>Usage/Serving (Food Slider)</a></li>

                    <!-- ১২. সোশ্যাল প্রুফ (স্ক্রিনশট) -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('SocialProofSection')"><i class="ri-chat-smile-3-line me-2"></i>Social Proof (Chat Testimonials)</a></li>

                    <!-- ১৩. দামের চার্ট (ভ্যারিয়েশন) -->
                    <li><a class="dropdown-item" href="#" wire:click.prevent="addSection('VariationPriceSection')"><i class="ri-price-tag-3-line me-2"></i>Price Variations (Pricing Table)</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Drag & Drop Area -->
    <div wire:sortable="updateOrder" class="row justify-content-center">
        <div class="col-lg-10">
            @foreach($sections as $section)
            <div wire:sortable.item="{{ $section->id }}" wire:key="sec-{{ $section->id }}"
                class="card mb-3 border-start border-4 border-primary shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <!-- Drag Handle -->
                            <div wire:sortable.handle style="cursor: move" class="me-3 text-muted">
                                <i class="ri-drag-move-fill fs-3"></i>
                            </div>

                            <div>
                                <span class="badge bg-light text-dark border mb-1">
                                    {{ str_replace('Section', '', class_basename($section->sectionable_type)) }}
                                </span>
                                <h6 class="mb-0">
                                    {{ $section->sectionable->title ?? $section->sectionable->heading ?? 'Untitled Section' }}
                                </h6>
                            </div>
                        </div>

                        <div class="btn-group">
                            <button wire:click="editSection({{ $section->id }})" class="btn btn-sm btn-outline-primary">
                                <i class="ri-edit-2-line"></i> Edit Data
                            </button>
                            <button wire:click="deleteSection({{ $section->id }})"
                                wire:confirm="Permanent delete this section and its content?"
                                class="btn btn-sm btn-outline-danger">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit {{ $editingType }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showEditModal', false)"></button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic Form Logic -->
                    @include('livewire.product.partials.' . Str::kebab($editingType))
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="$set('showEditModal', false)">Cancel</button>
                    <button class="btn btn-primary" wire:click="saveContent">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>