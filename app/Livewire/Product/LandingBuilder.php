<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\ProductPageSection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class LandingBuilder extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    // UI State
    public $showEditModal = false;
    public $editingSectionId;
    public $editingType;

    // Form Data
    public $formData = [];
    public $subItems = []; // This will hold our "Repeater" data (Videos, Points, etc.)
    public $tempFiles = []; // Temporary storage for uploaded images

    protected $listeners = ['updateOrder'];

    public function mount($productId = null)
    {
        if ($productId) {
            $this->product = Product::findOrFail($productId);
        } else {
            abort(404, 'Product not found.');
        }
    }

    /**
     * Add Section Logic - Full Factory
     */
    public function addSection($type)
    {
        $className = "App\\Models\\" . $type;
        $contentData = ['product_id' => $this->product->id];

        switch ($type) {
            case 'TitleSection':
                $contentData = array_merge($contentData, [
                    'title' => 'চুইঝালের মিষ্টি মসলায় ম্যাজিকের মত দূর করবে ইনশাআল্লাহ।',
                    'title_bg' => '#005a2b',
                    'title_color' => '#ffffff',
                    'title_tag' => 'h2',
                    'subtitle' => 'বাংলাদেশে আমরাই সর্বপ্রথম উদ্ভাবক',
                    'subtitle_bg' => '#ffcc00',
                    'subtitle_color' => '#000000'
                ]);
                break;
            case 'MediaNewsSection':
                $contentData['title'] = 'দেশের সংবাদ মাধ্যমে আমাদের নিয়ে প্রতিবেদন গুলো দেখুন';
                break;
            case 'MarketingHighlightSection':
                $contentData['top_boxed_text'] = 'মাত্র ০৫ মিনিটে সর্দি-কাশি দূর করার চ্যালেঞ্জ!';
                $contentData['bottom_boxed_text'] = 'চুইঝাল খুলনাঞ্চলের একটি ঐতিহ্যবাহী ঔষধী খাবার।';
                break;
            case 'DisclaimerSection':
                $contentData['question'] = 'আপনি কি ভাবছেন এটা কোন ঔষধ ?';
                $contentData['answer'] = 'এটা কোন ঔষধ না। এটা সম্পূর্ণ প্রাকৃতিক খাবার। এটি নিয়মিত খেলে শরীরের রোগ প্রতিরোধ ক্ষমতা বাড়ে।';
                $contentData['bg_color'] = '#e3f2fd';
                break;
            case 'MarketingHighlightSection':
                $contentData = array_merge($contentData, [
                    'top_boxed_text' => 'মাত্র ০৫ মিনিটে সর্দি-কাশি, শরীরের ক্লান্তি ও অবসাদ দূর করার চ্যালেঞ্জ!',
                    'top_box_border_color' => '#ff0000',
                    'bottom_boxed_text' => 'চুইঝাল খুলনাঞ্চলের একটি ঐতিহ্যবাহী ঔষধী খাবার। এটি মশলা হিসেবেও ব্যবহৃত হয়।',
                    'bottom_box_border_color' => '#ff0000',
                ]);
                break;
            case 'CountdownSection':
                $contentData = array_merge($contentData, [
                    'offer_title' => 'অফারটি আজই শেষ!',
                    'stock_count_text' => 'আর মাত্র ১১ জন নিতে পারবেন।',
                    'stock_count' => 11,
                    'end_time' => now()->addHours(5),
                    'bg_color' => '#004d26'
                ]);
                break;
            case 'BenefitSection':
                $contentData['heading'] = 'দেশীয় চুইঝালের স্বাস্থ্য উপকারিতা';
                $contentData['infographic_image'] = 'defaults/infographic.png';
                break;
            case 'CautionSection':
                $contentData['title'] = 'পাহাড়ী চুইঝাল থেকে সাবধান!';
                $contentData['description'] = 'বাজারে সয়লাব হওয়া নকল চুইঝাল চেনার উপায়...';
                break;
            case 'VideoSection':
                $contentData['video_url'] = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
                break;
        }

        $content = $className::create($contentData);

        $this->product->pageSections()->create([
            'sectionable_id'   => $content->id,
            'sectionable_type' => $className,
            'sort_order'       => $this->product->pageSections()->count() + 1,
        ]);

        $this->dispatch('notify', 'Section added successfully!');
    }

    /**
     * Helper to identify which relationship to load
     */
    private function getRelationName($type)
    {
        return match ($type) {
            'MediaNewsSection' => 'videos',
            'BenefitSection'   => 'items',
            'UsageSection'     => 'items',
            'ComparisonSection' => 'items',
            'SocialProofSection' => 'testimonials',
            'FeatureCardSection' => 'cards',
            default => null
        };
    }

    /**
     * Reset temp files when opening modal
     */
    public function editSection($id)
    {
        $this->tempFiles = []; // Clear previous uploads
        // ... existing logic to load $formData and $subItems

        $this->editingSectionId = $id;
        $section = ProductPageSection::with('sectionable')->findOrFail($id);
        $this->editingType = class_basename($section->sectionable_type);
        $this->formData = $section->sectionable->toArray();

        $relation = $this->getRelationName($this->editingType);
        if ($relation && method_exists($section->sectionable, $relation)) {
            $this->subItems = $section->sectionable->$relation()->get()->toArray();
        } else {
            $this->subItems = [];
        }

        $this->showEditModal = true;
    }

    /**
     * Repeater Logic: Add Row
     */
    public function addSubItem()
    {
        $this->subItems[] = ['id' => null, 'sort_order' => count($this->subItems)];
    }

    /**
     * Repeater Logic: Remove Row
     */
    public function removeSubItem($index)
    {
        unset($this->subItems[$index]);
        $this->subItems = array_values($this->subItems);
    }

    /**
     * Save Master Data + Related Items
     */
    /**
     * Updated Save Method with Image Upload Logic
     */
    public function saveContent()
    {
        $section = ProductPageSection::findOrFail($this->editingSectionId);
        $contentModel = $section->sectionable;

        // 1. Handle Main Section Image (e.g., BenefitSection infographic)
        if (isset($this->tempFiles['main']) && is_object($this->tempFiles['main'])) {
            $path = $this->tempFiles['main']->store('sections', 'public');
            // Update the correct field name based on model
            $fieldName = isset($this->formData['infographic_image']) ? 'infographic_image' : 'image';
            $this->formData[$fieldName] = $path;
        }

        $contentModel->update($this->formData);

        // 2. Handle Sub-Items Images (e.g., Feature Cards, Testimonials)
        $relation = $this->getRelationName($this->editingType);
        if ($relation) {
            $contentModel->$relation()->delete();
            foreach ($this->subItems as $index => $item) {
                unset($item['id']);

                // Check if a new file was uploaded for this specific index
                if (isset($this->tempFiles[$index]) && is_object($this->tempFiles[$index])) {
                    $path = $this->tempFiles[$index]->store('sections/items', 'public');
                    $item['image_path'] = $path;
                }

                $contentModel->$relation()->create($item);
            }
        }

        $this->showEditModal = false;
        $this->tempFiles = []; // Reset
        $this->dispatch('notify', 'Saved with images!');
    }

    public function updateOrder($items)
    {
        foreach ($items as $item) {
            // We update the ProductPageSection (the Orchestrator)
            \App\Models\ProductPageSection::where('id', $item['value'])
                ->update(['sort_order' => $item['order']]);
        }

        // Optional: Flash a message to the user
        $this->dispatch('notify', 'Layout order saved!');
    }

    public function deleteSection($id)
    {
        $section = ProductPageSection::findOrFail($id);
        $section->sectionable->delete();
        $section->delete();
        $this->dispatch('notify', 'Section removed.');
    }

    public function render()
    {
        return view('livewire.product.landing-builder', [
            'sections' => $this->product->pageSections()->with('sectionable')->get()
        ]);
    }
}
