<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    // Table state
    public $search = '';
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Listeners for events from the form component
    protected $listeners = ['categorySaved' => '$refresh', 'categoryCreated' => '$refresh'];

    protected $queryString = ['search', 'sortField', 'sortDirection', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    // Emit event to open create form in CategoryForm component
    public function createCategory()
    {
        $this->dispatch('openCategoryFormModal');
    }

    // Emit event to open edit form in CategoryForm component
    public function editCategory($categoryId)
    {
        // This will generate a URL like /categories/1/edit and redirect the browser
        return $this->redirect(route('product.categories.edit', ['category' => $categoryId]), navigate: true);
    }

    // Delete category
    public function deleteCategory($categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            session()->flash('error', 'Category not found.');
            return;
        }

        if ($category->children()->count() > 0) {
            session()->flash('error', 'Cannot delete category with subcategories. Please move or delete subcategories first.');
            return;
        }

        if ($category->products()->count() > 0) {
            session()->flash('error', 'Cannot delete category with associated products. Please reassign or delete products first.');
            return;
        }

        // Delete image file if it exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        session()->flash('message', 'Category deleted successfully!');
        $this->resetPage(); // Reset pagination in case the last item on a page was deleted
    }

    public function render()
    {
        $categories = Category::query()
            ->with('parent')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.categories.index', [
            'categories' => $categories,
        ]);
    }
}
