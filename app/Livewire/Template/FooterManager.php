<?php

namespace App\Livewire\Template;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Footer;
use Illuminate\Support\Facades\Storage;

class FooterManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    // --- Table Properties ---
    public $search = '';
    public $sortField = 'title';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // --- Form Properties ---
    public $showModal = false;
    public $footerId;
    public $title;
    public $preview_image;    // Temporary file
    public $currentImage;     // Existing path
    public $is_active = true;

    public $isEditing = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    protected function getValidationRules()
    {
        return [
            'title' => 'required|string|max:255',
            'preview_image' => $this->isEditing ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'is_active' => 'boolean',
        ];
    }

    // --- Table Methods ---

    public function updatingSearch()
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

    // --- Form Methods ---

    public function openModal()
    {
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function createFooter()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->openModal();
    }

    public function editFooter(Footer $footer)
    {
        $this->isEditing = true;
        $this->footerId = $footer->id;
        $this->title = $footer->title;
        $this->currentImage = $footer->preview_image;
        $this->is_active = $footer->is_active;
        $this->openModal();
    }

    public function saveFooter()
    {
        $this->validate($this->getValidationRules());

        $data = [
            'title' => $this->title,
            'is_active' => $this->is_active,
        ];

        if ($this->preview_image) {
            if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                Storage::disk('public')->delete($this->currentImage);
            }
            $data['preview_image'] = $this->preview_image->store('footers', 'public');
        }

        if ($this->isEditing) {
            Footer::find($this->footerId)->update($data);
            $this->dispatch('notify', message: 'Footer updated successfully.', type: 'success');
        } else {
            Footer::create($data);
            $this->dispatch('notify', message: 'Footer created successfully.', type: 'success');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function deleteFooter($id)
    {
        $footer = Footer::find($id);

        if ($footer) {
            if ($footer->pages()->count() > 0) {
                session()->flash('error', 'Cannot delete: This footer is currently assigned to pages.');
                return;
            }

            if ($footer->preview_image && Storage::disk('public')->exists($footer->preview_image)) {
                Storage::disk('public')->delete($footer->preview_image);
            }

            $footer->delete();
            $this->dispatch('notify', message: 'Footer deleted successfully.', type: 'success');
        }
    }

    private function resetForm()
    {
        $this->footerId = null;
        $this->title = '';
        $this->preview_image = null;
        $this->currentImage = null;
        $this->is_active = true;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $footers = Footer::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.template.footer-manager', [
            'footers' => $footers,
        ]);
    }
}
