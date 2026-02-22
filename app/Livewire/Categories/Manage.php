<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class Manage extends Component
{
    use WithFileUploads;

    // Form state
    public $categoryId;
    public $name;
    public $slug;
    public $description;
    public $parent_id;

    // Image properties
    public $image;
    public $currentImage;

    // Icon properties
    public $icon; // Temporary file for upload
    public $currentIcon; // Existing path

    public $is_active = true;
    public $show_on_homepage = false;
    public $sort_order = 0;
    public $seo_title;
    public $seo_description;

    public $isEditing = false;
    public $pageTitle = 'Create New Category';

    public function mount($categoryId = null)
    {
        if ($categoryId) {
            $category = Category::find($categoryId);
        } else {
            $category = null;
        }

        if ($category && $category->exists) {
            $this->isEditing = true;
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description;
            $this->parent_id = $category->parent_id;

            // Media
            $this->currentImage = $category->image;
            $this->currentIcon = $category->icon; // Load existing icon

            $this->is_active = $category->is_active;
            $this->show_on_homepage = $category->show_on_homepage;
            $this->sort_order = $category->sort_order;
            $this->seo_title = $category->seo_title;
            $this->seo_description = $category->seo_description;
            $this->pageTitle = 'Edit Category: ' . $category->name;
        } else {
            // Default values
            $this->is_active = true;
            $this->sort_order = 0;
            $this->pageTitle = 'Create New Category';
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('categories')->ignore($this->categoryId),
            ],
            'description' => 'nullable|string|max:1000',
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id'),
                function ($attribute, $value, $fail) {
                    if ($this->categoryId && $value == $this->categoryId) {
                        $fail('A category cannot be its own parent.');
                    }
                },
            ],
            'image' => 'nullable|image|max:1024',
            // Icon rule: Allow images AND SVGs
            'icon' => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:1024',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ];
    }

    // Auto-generate slug when name changes
    public function updatedName($value)
    {
        if (empty($this->slug) || Str::slug($value) === $this->slug) {
            $this->slug = Str::slug($value);
        }
    }

    // Clear validation when new files are selected
    public function updatedImage()
    {
        $this->resetValidation('image');
    }
    public function updatedIcon()
    {
        $this->resetValidation('icon');
    }

    public function saveCategory()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
            'show_on_homepage' => $this->show_on_homepage,
            'sort_order' => $this->sort_order,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
        ];

        // 1. Handle Image Upload
        if ($this->image) {
            if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                Storage::disk('public')->delete($this->currentImage);
            }
            $data['image'] = $this->image->store('categories', 'public');
        } elseif (!$this->image && $this->currentImage) {
            $data['image'] = $this->currentImage;
        } else {
            $data['image'] = null;
        }

        // 2. Handle Icon Upload
        if ($this->icon) {
            if ($this->currentIcon && Storage::disk('public')->exists($this->currentIcon)) {
                Storage::disk('public')->delete($this->currentIcon);
            }
            // Store icons in a subfolder or same folder
            $data['icon'] = $this->icon->store('categories/icons', 'public');
        } elseif (!$this->icon && $this->currentIcon) {
            $data['icon'] = $this->currentIcon;
        } else {
            $data['icon'] = null;
        }

        if ($this->isEditing) {
            $category = Category::find($this->categoryId);
            $category->update($data);
            session()->flash('message', 'Category updated successfully!');
        } else {
            Category::create($data);
            session()->flash('message', 'Category created successfully!');
        }

        return redirect()->route('product.categories.index'); // Ensure route name is correct in your web.php
    }

    public function render()
    {
        $parentCategories = Category::query()
            ->whereNull('parent_id')
            ->when($this->categoryId, function ($query) {
                $query->where('id', '!=', $this->categoryId);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.categories.manage', [
            'parentCategories' => $parentCategories,
        ]);
    }
}
