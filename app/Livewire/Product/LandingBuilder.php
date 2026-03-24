<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Section\ProductPageSection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
    public $subItems = [];
    public $tempFiles = [];

    protected $listeners = ['updateOrder'];

    public function mount($productId = null)
    {
        $this->product = Product::findOrFail($productId);
    }

    /**
     * Map every Section to its Model and Relationship name
     */
    private function getSectionConfig($type)
    {
        $map = [
            'TitleSection'              => ['class' => \App\Models\Section\TitleSection::class, 'rel' => null],
            'BenefitSection'            => ['class' => \App\Models\Section\BenefitSection::class, 'rel' => 'items'],
            'MediaNewsSection'          => ['class' => \App\Models\Section\MediaNewsSection::class, 'rel' => 'videos'],
            'MarketingHighlightSection' => ['class' => \App\Models\Section\MarketingHighlightSection::class, 'rel' => null],
            'DisclaimerSection'         => ['class' => \App\Models\Section\DisclaimerSection::class, 'rel' => null],
            'CountdownSection'          => ['class' => \App\Models\Section\CountdownSection::class, 'rel' => null],
            'CautionSection'            => ['class' => \App\Models\Section\CautionSection::class, 'rel' => null],
            'UsageSection'              => ['class' => \App\Models\Section\UsageSection::class, 'rel' => 'items'],
            'ComparisonSection'         => ['class' => \App\Models\Section\ComparisonSection::class, 'rel' => 'items'],
            'VideoSection'              => ['class' => \App\Models\Section\VideoSection::class, 'rel' => null],
            'SocialProofSection'        => ['class' => \App\Models\Section\SocialProofSection::class, 'rel' => 'items'],
            'FeatureCardSection'        => ['class' => \App\Models\Section\FeatureCardSection::class, 'rel' => 'cards'],
            'VariationPriceSection'     => ['class' => \App\Models\Section\VariationPriceSection::class, 'rel' => null],
            'WhyChoose'                 => ['class' => \App\Models\Section\WhyChoose::class, 'rel' => null], // Uses JSON cast
        ];

        return $map[$type] ?? null;
    }

    /**
     * Add Section Logic - Full Factory
     */
    public function addSection($type)
    {
        // Fix 1: Correct Namespace for your models
        $className = "App\\Models\\Section\\" . $type;

        // Check if class exists before proceeding
        if (!class_exists($className)) {
            $this->dispatch('notify', 'Error: Model ' . $type . ' not found.');
            return;
        }

        $contentData = ['product_id' => $this->product->id];

        switch ($type) {
            case 'TitleSection':
                $contentData = array_merge($contentData, [
                    'title' => 'আপনার আকর্ষণীয় শিরোনাম এখানে লিখুন',
                    'title_bg' => '#005a2b',
                    'title_color' => '#ffffff',
                    'title_tag' => 'h2',
                    'subtitle' => 'উপ-শিরোনাম (ঐচ্ছিক)',
                    'subtitle_bg' => '#ffcc00',
                    'subtitle_color' => '#000000',
                    'subtitle_tag' => 'h3'
                ]);
                break;

            case 'MarketingHighlightSection':
                // Fix 2: Ensuring required fields are present to avoid SQL "No Default Value" error
                $contentData = array_merge($contentData, [
                    'top_boxed_text' => 'মাত্র ০৫ মিনিটে ক্লান্তি দূর করার চ্যালেঞ্জ!',
                    'top_box_border_color' => '#ff0000',
                    'bottom_boxed_text' => 'চুইঝাল একটি ঐতিহ্যবাহী ঔষধী খাবার।',
                    'bottom_box_border_color' => '#ff0000',
                ]);
                break;

            case 'MediaNewsSection':
                $contentData = array_merge($contentData, [
                    'title' => 'সংবাদ মাধ্যমে আমাদের নিয়ে প্রতিবেদন',
                    'title_color' => '#000000',
                    'title_bg' => '#ffffff',
                    'title_tag' => 'h2'
                ]);
                break;

            case 'DisclaimerSection':
                $contentData = array_merge($contentData, [
                    'question' => 'আপনি কি ভাবছেন এটা কোন ঔষধ?',
                    'answer' => 'এটা কোন ঔষধ না, এটি সম্পূর্ণ প্রাকৃতিক খাবার।',
                    'bg_color' => '#e3f2fd',
                    'text_color' => '#000000'
                ]);
                break;

            case 'CountdownSection':
                $contentData = array_merge($contentData, [
                    'offer_title' => 'অফারটি আজই শেষ!',
                    'offer_title_color' => '#ffffff',
                    'stock_count_text' => 'আর মাত্র কয়েক জন নিতে পারবেন।',
                    'stock_count' => 10,
                    'end_time' => now()->addHours(5),
                    'bg_color' => '#C41E3A'
                ]);
                break;

            case 'BenefitSection':
                $contentData = array_merge($contentData, [
                    'heading' => 'স্বাস্থ্য উপকারিতা সমূহ',
                    'heading_color' => '#000000',
                    'infographic_image' => null
                ]);
                break;

            case 'CautionSection':
                $contentData = array_merge($contentData, [
                    'description' => 'নকল পণ্য থেকে সাবধান থাকুন...',
                    'text_color' => '#ff0000',
                    'border_color' => '#ff0000',
                    'divider_icon' => 'warning'
                ]);
                break;

            case 'UsageSection':
                $contentData['description'] = 'কিভাবে ব্যবহার করবেন তার নির্দেশিকা...';
                break;

            case 'ComparisonSection':
                $contentData = array_merge($contentData, [
                    'title' => 'আমরা কেন সবার থেকে আলাদা?',
                    'title_tag' => 'h2',
                    'title_color' => '#000000',
                    'border_color' => '#dddddd'
                ]);
                break;

            case 'VideoSection':
                $contentData = array_merge($contentData, [
                    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                    'video_title' => 'পণ্য পরিচিতি ভিডিও'
                ]);
                break;

            case 'SocialProofSection':
                $contentData = array_merge($contentData, [
                    'heading' => 'কাস্টমারদের রিভিউ',
                    'bg_color' => '#ffffff',
                    'text_color' => '#000000'
                ]);
                break;

            case 'FeatureCardSection':
                $contentData['section_title'] = 'বিশেষ বৈশিষ্ট্য সমূহ';
                break;

            case 'VariationPriceSection':
                $contentData['footer_note'] = 'সারা বাংলাদেশে হোম ডেলিভারি ফ্রি!';
                break;

            case 'WhyChoose':
                // WhyChoose model usually doesn't have product_id in your structure
                unset($contentData['product_id']);
                $contentData['title'] = 'কেন আমাদের পছন্দ করবেন?';
                $contentData['items'] = []; // Array cast
                break;
        }

        // Create the Content Row
        $content = $className::create($contentData);

        // Create the Orchestrator Link (ProductPageSection)
        $this->product->pageSections()->create([
            'sectionable_id'   => $content->id,
            'sectionable_type' => $className,
            'sort_order'       => $this->product->pageSections()->count() + 1,
            'is_active'        => true,
        ]);

        $this->dispatch('notify', 'Section added successfully!');
    }

    public function editSection($id)
    {
        $this->editingSectionId = $id;
        $section = ProductPageSection::with('sectionable')->findOrFail($id);

        $this->editingType = class_basename($section->sectionable_type);
        $this->formData = $section->sectionable->toArray();

        $config = $this->getSectionConfig($this->editingType);
        $relation = $config['rel'];

        if ($relation && method_exists($section->sectionable, $relation)) {
            $this->subItems = $section->sectionable->$relation()->get()->toArray();
        } else {
            $this->subItems = [];
        }

        $this->tempFiles = [];
        $this->showEditModal = true;
    }

    public function addSubItem()
    {
        $this->subItems[] = ['sort_order' => count($this->subItems)];
    }

    public function removeSubItem($index)
    {
        unset($this->subItems[$index]);
        $this->subItems = array_values($this->subItems);
    }

    public function saveContent()
    {
        $section = ProductPageSection::findOrFail($this->editingSectionId);
        $contentModel = $section->sectionable;

        // Handle Infographic / Main Image
        if (isset($this->tempFiles['main'])) {
            $field = isset($this->formData['infographic_image']) ? 'infographic_image' : (isset($this->formData['image']) ? 'image' : 'image_path');
            $this->formData[$field] = $this->tempFiles['main']->store('sections', 'public');
        }

        $contentModel->update($this->formData);

        // Handle Sub-Items (Items, Cards, Videos)
        $config = $this->getSectionConfig($this->editingType);
        if ($config['rel']) {
            $relation = $config['rel'];
            $contentModel->$relation()->delete();

            foreach ($this->subItems as $index => $item) {
                unset($item['id']);
                if (isset($this->tempFiles[$index])) {
                    $item['image_path'] = $this->tempFiles[$index]->store('sections/items', 'public');
                }
                $contentModel->$relation()->create($item);
            }
        }

        $this->showEditModal = false;
        $this->dispatch('notify', 'সেভ হয়েছে!');
    }

    public function updateOrder($items)
    {
        foreach ($items as $item) {
            ProductPageSection::where('id', $item['value'])->update(['sort_order' => $item['order']]);
        }
    }

    public function deleteSection($id)
    {
        $section = ProductPageSection::findOrFail($id);
        if ($section->sectionable) $section->sectionable->delete();
        $section->delete();
    }

    public function render()
    {
        return view('livewire.product.landing-builder', [
            'sections' => ProductPageSection::where('product_id', $this->product->id)
                ->with('sectionable')
                ->orderBy('sort_order')
                ->get()
        ]);
    }
}
