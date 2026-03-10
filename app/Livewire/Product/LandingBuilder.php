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

    // Fix: Either make it nullable OR initialize it in mount
    public ?Product $product = null;

    // UI State
    public $showEditModal = false;
    public $editingSectionId;
    public $editingType;

    // Form Data (Generic array to hold any section's fields)
    public $formData = [];
    public $subItems = []; // For repeaters like Media Videos or Benefit Items

    protected $listeners = ['updateOrder'];

    public function mount($productId = null)
    {
        if ($productId) {
            // Initialize the property here
            $this->product = Product::findOrFail($productId);
        } else {
            // Handle the case where no ID is provided, or redirect
            abort(404, 'Product not found.');
        }
    }

    /**
     * Add a new section to the product
     */
    public function addSection($type)
    {
        $className = "App\\Models\\" . $type;

        // Create with default data based on type
        $contentData = ['product_id' => $this->product->id];

        if (in_array($type, ['TitleSection', 'MediaNewsSection', 'BenefitSection', 'SocialProofSection', 'CautionSection', 'ComparisonSection'])) {
            $contentData[isset($contentData['heading']) ? 'heading' : 'title'] = 'New ' . $type;
        }

        if ($type === 'MarketingHighlightSection') {
            $contentData['top_boxed_text'] = 'Sample Top Text';
            $contentData['bottom_boxed_text'] = 'Sample Bottom Text';
        }

        if ($type === 'UsageSection') {
            $contentData['description'] = 'Sample Usage Description';
        }

        $content = $className::create($contentData);

        $this->product->pageSections()->create([
            'sectionable_id' => $content->id,
            'sectionable_type' => $className,
            'sort_order' => $this->product->pageSections()->count() + 1
        ]);
    }

    /**
     * Drag and Drop Sorting Logic
     */
    public function updateOrder($items)
    {
        foreach ($items as $item) {
            ProductPageSection::where('id', $item['value'])->update(['sort_order' => $item['order']]);
        }
    }

    /**
     * Load Section Data for Editing
     */
    public function editSection($id)
    {
        $this->editingSectionId = $id;
        $section = ProductPageSection::with('sectionable')->findOrFail($id);
        $this->editingType = class_basename($section->sectionable_type);

        // Fill form with model data
        $this->formData = $section->sectionable->toArray();

        // Load sub-items if they exist (e.g., Media Videos)
        if (method_exists($section->sectionable, 'items')) {
            $this->subItems = $section->sectionable->items->toArray();
        } elseif (method_exists($section->sectionable, 'videos')) {
            $this->subItems = $section->sectionable->videos->toArray();
        } else {
            $this->subItems = [];
        }

        $this->showEditModal = true;
    }

    /**
     * Save the edited content
     */
    public function saveContent()
    {
        $section = ProductPageSection::findOrFail($this->editingSectionId);
        $contentModel = $section->sectionable;

        // Save Main Content
        $contentModel->update($this->formData);

        // Logic for saving sub-items (Videos, Benefits, etc.) would go here...

        $this->showEditModal = false;
        $this->dispatch('notify', 'Content updated!');
    }

    public function deleteSection($id)
    {
        $section = ProductPageSection::findOrFail($id);
        $section->sectionable->delete();
        $section->delete();
    }

    public function render()
    {
        return view('livewire.product.landing-builder', [
            'sections' => $this->product->pageSections()->with('sectionable')->get()
        ]);
    }
}
