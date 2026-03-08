<?php

namespace App\Livewire\Template;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Header;
use Illuminate\Support\Facades\Storage;

class HeaderManager extends Component
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
    public $headerId;         // Null for create, ID for edit
    public $title;
    public $preview_image;    // Temporary file for upload
    public $currentImage;     // Path to existing image for display
    public $is_active = true;

    public $isEditing = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    // Validation rules
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

    public function createHeader()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->openModal();
    }

    public function editHeader(Header $header)
    {
        $this->isEditing = true;
        $this->headerId = $header->id;
        $this->title = $header->title;
        $this->currentImage = $header->preview_image;
        $this->is_active = $header->is_active;
        $this->openModal();
    }

    public function saveHeader()
    {
        $this->validate($this->getValidationRules());

        $data = [
            'title' => $this->title,
            'is_active' => $this->is_active,
        ];

        // Handle Image Upload
        if ($this->preview_image) {
            // Delete old image if it exists
            if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                Storage::disk('public')->delete($this->currentImage);
            }
            $data['preview_image'] = $this->preview_image->store('headers', 'public');
        }

        if ($this->isEditing) {
            Header::find($this->headerId)->update($data);
            $this->dispatch('notify', message: 'Header updated successfully.', type: 'success');
        } else {
            Header::create($data);
            $this->dispatch('notify', message: 'Header created successfully.', type: 'success');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function deleteHeader($id)
    {
        $header = Header::find($id);

        if ($header) {
            // Check if any pages are using this header
            if ($header->pages()->count() > 0) {
                session()->flash('error', 'Cannot delete: This header is currently used by pages.');
                return;
            }

            // Delete image file
            if ($header->preview_image && Storage::disk('public')->exists($header->preview_image)) {
                Storage::disk('public')->delete($header->preview_image);
            }

            $header->delete();
            $this->dispatch('notify', message: 'Header deleted successfully.', type: 'success');
        }
    }

    private function resetForm()
    {
        $this->headerId = null;
        $this->title = '';
        $this->preview_image = null;
        $this->currentImage = null;
        $this->is_active = true;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $headers = Header::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.template.header-manager', [
            'headers' => $headers,
        ]);
    }
}
