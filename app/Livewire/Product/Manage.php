<?php

namespace App\Livewire\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Enums\ProductType;
use App\Enums\UserRole;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Manage extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    // Core Product Properties
    public $vendor_id;
    public $selectedCategoryIds = [];
    public $brand_id;
    public $name;
    public $slug;
    public $short_description;
    public $long_description;
    public $thumbnail_image_path;
    public $new_thumbnail_image;
    public $type = 'normal';
    public $sku;
    public $price;
    public $compare_at_price;
    public $cost_price;
    public $quantity;
    public $weight;
    public $is_active = true;
    public $is_featured = false;
    public $is_new = false;
    public $is_manage_stock = true;
    public $min_order_quantity = 1;
    public $max_order_quantity;

    // --- NEW: Promotion & Bundle Properties ---
    public $free_delivery_threshold;
    public $free_delivery_starts_at;
    public $free_delivery_ends_at;
    public $bundleSearch = '';
    public $bundleSearchResults = [];
    public $selectedBundleIds = []; 
    public $selectedBundleProducts = []; 
    public $bundlePrices = []; 

    // Fields for specific product types
    public $affiliate_url;
    public $digital_file_path;
    public $new_digital_file;
    public $download_limit;
    public $download_expiry_days;

    public $categories;
    public $brands;
    public $productTypes;
    public $vendors;

    public function mount($productId = null)
    {
        if ($productId) {
            $this->product = Product::with(['categories', 'bundleProducts'])->find($productId);
        }

        if (!$this->product) {
            $this->product = new Product();
        }

        $this->categories = Category::active()->get();
        $this->brands = Brand::active()->get();
        $this->productTypes = ProductType::cases();
        $this->vendors = User::where('role', UserRole::Vendor)->where('is_active', true)->get();

        if ($this->product->exists) {
            $this->fill($this->product->toArray());

            $this->type = $this->product->type?->value ?? ProductType::Normal->value;
            $this->thumbnail_image_path = $this->product->thumbnail_image_path;
            $this->digital_file_path = $this->product->digital_file;
            $this->vendor_id = $this->product->vendor_id;
            $this->selectedCategoryIds = $this->product->categories->pluck('id')->toArray();

            // Format dates for input
            $this->free_delivery_starts_at = $this->product->free_delivery_starts_at?->format('Y-m-d\TH:i');
            $this->free_delivery_ends_at = $this->product->free_delivery_ends_at?->format('Y-m-d\TH:i');

            // Load Bundles
            $this->selectedBundleIds = $this->product->bundleProducts->pluck('id')->toArray();
            $this->selectedBundleProducts = $this->product->bundleProducts->toArray();
            foreach ($this->product->bundleProducts as $bundled) {
                $this->bundlePrices[$bundled->id] = $bundled->pivot->special_price;
            }
        } else {
            $this->type = ProductType::Normal->value;
            $this->vendor_id = $this->vendors->first()->id ?? null;
            $this->is_active = true;
            $this->is_manage_stock = true;
        }
    }

    // --- NEW: Bundle Logic ---
    public function updatedBundleSearch()
    {
        if (strlen($this->bundleSearch) < 2) {
            $this->bundleSearchResults = [];
            return;
        }
        $this->bundleSearchResults = Product::active()
            ->where('name', 'like', '%' . $this->bundleSearch . '%')
            ->where('id', '!=', $this->product->id ?? 0)
            ->limit(10)->get()->toArray();
    }

    public function addProductToBundle($productId)
    {
        if (!in_array($productId, $this->selectedBundleIds)) {
            $found = Product::find($productId);
            if ($found) {
                $this->selectedBundleIds[] = $productId;
                $this->selectedBundleProducts[] = $found->toArray();
                $this->bundlePrices[$productId] = $found->price;
            }
        }
        $this->bundleSearch = '';
        $this->bundleSearchResults = [];
    }

    public function removeProductFromBundle($productId)
    {
        $this->selectedBundleIds = array_filter($this->selectedBundleIds, fn($id) => $id != $productId);
        $this->selectedBundleProducts = array_filter($this->selectedBundleProducts, fn($p) => $p['id'] != $productId);
        unset($this->bundlePrices[$productId]);
    }

    protected function rules()
    {
        $rules = [
            'vendor_id' => ['nullable'],
            'selectedCategoryIds' => ['required', 'array', 'min:1'],
            'selectedCategoryIds.*' => ['exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($this->product->id)],
            'short_description' => ['nullable', 'string', 'max:500'],
            'long_description' => ['nullable', 'string'],
            'new_thumbnail_image' => ['nullable', 'image', 'max:2048'],
            'type' => ['required', Rule::enum(ProductType::class)],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->product->id)],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'cost_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'quantity' => ['required_if:is_manage_stock,true', 'nullable', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_new' => ['boolean'],
            'is_manage_stock' => ['boolean'],
            'min_order_quantity' => ['nullable', 'integer', 'min:1'],
            'max_order_quantity' => ['nullable', 'integer', 'min:1', 'gte:min_order_quantity'],
            
            // New Rules
            'free_delivery_threshold' => ['nullable', 'integer', 'min:1'],
            'free_delivery_starts_at' => ['nullable', 'date'],
            'free_delivery_ends_at' => ['nullable', 'date', 'after_or_equal:free_delivery_starts_at'],
            'bundlePrices.*' => ['nullable', 'numeric', 'min:0'],

            'affiliate_url' => ['nullable', 'url', 'max:2048'],
            'new_digital_file' => ['nullable', 'file', 'max:102400'],
            'download_limit' => ['nullable', 'integer', 'min:1'],
            'download_expiry_days' => ['nullable', 'integer', 'min:1'],
        ];

        if ($this->type === ProductType::Affiliate->value) {
            $rules['affiliate_url'][] = 'required';
            $rules['sku'] = ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->product->id)];
            $rules['quantity'] = ['nullable', 'integer', 'min:0'];
            $rules['is_manage_stock'] = ['nullable', 'boolean'];
        }
        if ($this->type === ProductType::Digital->value) {
            $rules['new_digital_file'][] = $this->product->digital_file ? 'nullable' : 'required';
            $rules['sku'] = ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->product->id)];
            $rules['quantity'] = ['nullable', 'integer', 'min:0'];
            $rules['is_manage_stock'] = ['nullable', 'boolean'];
        }
        if ($this->type === ProductType::Normal->value) {
            $rules['quantity'][] = 'required';
        }
        if ($this->type === ProductType::Variable->value) {
            $rules['quantity'] = ['nullable', 'integer', 'min:0'];
            $rules['is_manage_stock'] = ['nullable', 'boolean'];
            $rules['sku'] = ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->product->id)];
        }

        return $rules;
    }

    protected $messages = [
        'selectedCategoryIds.required' => 'At least one category must be selected.',
        'selectedCategoryIds.array' => 'Categories must be an array.',
        'selectedCategoryIds.min' => 'Please select at least one category.',
        'quantity.required_if' => 'The quantity field is required when stock is managed.',
        'new_digital_file.required' => 'A digital file is required for digital products.',
        'affiliate_url.required' => 'The affiliate URL is required for affiliate products.',
        'compare_at_price.gte' => 'The compare at price must be greater than or equal to the price.',
        'cost_price.lte' => 'The cost price must be less than or equal to the price.',
        'max_order_quantity.gte' => 'The maximum order quantity must be greater than or equal to the minimum order quantity.',
    ];


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'name' && empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
        $this->validateOnly('slug');
    }

    public function save()
    {
        $this->validate();

        if ($this->new_thumbnail_image) {
            if ($this->product->thumbnail_image_path) {
                Storage::disk('public')->delete($this->product->thumbnail_image_path);
            }
            $this->thumbnail_image_path = $this->new_thumbnail_image->store('products/thumbnails', 'public');
        }

        if ($this->type === ProductType::Digital->value && $this->new_digital_file) {
            if ($this->product->digital_file) {
                Storage::disk('public')->delete($this->product->digital_file);
            }
            $this->digital_file_path = $this->new_digital_file->store('products/digital_files', 'public');
        } elseif ($this->type !== ProductType::Digital->value && $this->product->digital_file) {
            Storage::disk('public')->delete($this->product->digital_file);
            $this->digital_file_path = null;
        }

        $quantityToSave = 0;
        if ($this->type === ProductType::Normal->value) {
            $quantityToSave = $this->quantity ?? 0;
        }

        $this->product->fill([
            'vendor_id' => $this->vendor_id,
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'slug' => $this->slug ?? Str::slug($this->name),
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'thumbnail_image_path' => $this->thumbnail_image_path,
            'type' => ProductType::from($this->type),
            'sku' => $this->sku,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'cost_price' => $this->cost_price,
            'quantity' => $quantityToSave,
            'weight' => $this->weight,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'is_new' => $this->is_new,
            'is_manage_stock' => ($this->type === ProductType::Variable->value || $this->type === ProductType::Affiliate->value || $this->type === ProductType::Digital->value) ? false : $this->is_manage_stock,
            'min_order_quantity' => $this->min_order_quantity,
            'max_order_quantity' => $this->max_order_quantity,
            'affiliate_url' => ($this->type === ProductType::Affiliate->value) ? $this->affiliate_url : null,
            'digital_file' => ($this->type === ProductType::Digital->value) ? $this->digital_file_path : null,
            'download_limit' => ($this->type === ProductType::Digital->value) ? $this->download_limit : null,
            'download_expiry_days' => ($this->type === ProductType::Digital->value) ? $this->download_expiry_days : null,
            
            // New Fields
            'free_delivery_threshold' => $this->free_delivery_threshold ?: null,
            'free_delivery_starts_at' => $this->free_delivery_starts_at ?: null,
            'free_delivery_ends_at' => $this->free_delivery_ends_at ?: null,
        ])->save();

        $this->product->categories()->sync($this->selectedCategoryIds);

        // Sync Bundles
        $bundleData = [];
        foreach ($this->selectedBundleIds as $bId) {
            $bundleData[$bId] = ['special_price' => $this->bundlePrices[$bId] ?? 0];
        }
        $this->product->bundleProducts()->sync($bundleData);

        session()->flash('message', 'Product ' . ($this->product->wasRecentlyCreated ? 'created' : 'updated') . ' successfully!');

        return $this->redirect(route('product.products.edit', $this->product->id), navigate:true);
    }

    public function render()
    {
        return view('livewire.product.manage');
    }
}