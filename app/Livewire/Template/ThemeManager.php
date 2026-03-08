<?php

namespace App\Livewire\Template;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Theme;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ThemeManager extends Component
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
    public $themeId;
    public $title;
    public $type = 'landing_page';
    public $preview_image;
    public $currentImage;
    public $is_active = true;

    // Theme Settings (JSON mapping)
    public $primary_color = '#3490dc';
    public $secondary_color = '#6c757d';
    public $font_family = 'Inter';

    public $isEditing = false;

    // Enum Options
    public $typeOptions = [
        'home' => 'Home Page',
        'about' => 'About Us',
        'contact' => 'Contact Us',
        'shop' => 'Shop/Product List',
        'landing_page' => 'Landing Page'
    ];

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
            'type' => ['required', Rule::in(array_keys($this->typeOptions))],
            'preview_image' => $this->isEditing ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'is_active' => 'boolean',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'font_family' => 'nullable|string|max:50',
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

    public function createTheme()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->openModal();
    }

    public function editTheme(Theme $theme)
    {
        $this->isEditing = true;
        $this->themeId = $theme->id;
        $this->title = $theme->title;
        $this->type = $theme->type;
        $this->currentImage = $theme->preview_image;
        $this->is_active = $theme->is_active;

        // Load settings from JSON
        $this->primary_color = $theme->settings['primary_color'] ?? '#3490dc';
        $this->secondary_color = $theme->settings['secondary_color'] ?? '#6c757d';
        $this->font_family = $theme->settings['font_family'] ?? 'Inter';

        $this->openModal();
    }

    public function saveTheme()
    {
        $this->validate($this->getValidationRules());

        $data = [
            'title' => $this->title,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'settings' => [
                'primary_color' => $this->primary_color,
                'secondary_color' => $this->secondary_color,
                'font_family' => $this->font_family,
            ]
        ];

        if ($this->preview_image) {
            if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                Storage::disk('public')->delete($this->currentImage);
            }
            $data['preview_image'] = $this->preview_image->store('themes', 'public');
        }

        if ($this->isEditing) {
            Theme::find($this->themeId)->update($data);
            $this->dispatch('notify', message: 'Theme updated successfully.', type: 'success');
        } else {
            Theme::create($data);
            $this->dispatch('notify', message: 'Theme created successfully.', type: 'success');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function deleteTheme($id)
    {
        $theme = Theme::find($id);
        if ($theme) {
            if ($theme->pages()->count() > 0) {
                session()->flash('error', 'Cannot delete theme: It is being used by active pages.');
                return;
            }
            if ($theme->preview_image && Storage::disk('public')->exists($theme->preview_image)) {
                Storage::disk('public')->delete($theme->preview_image);
            }
            $theme->delete();
            $this->dispatch('notify', message: 'Theme deleted successfully.', type: 'success');
        }
    }

    private function resetForm()
    {
        $this->themeId = null;
        $this->title = '';
        $this->type = 'landing_page';
        $this->preview_image = null;
        $this->currentImage = null;
        $this->is_active = true;
        $this->primary_color = '#3490dc';
        $this->secondary_color = '#6c757d';
        $this->font_family = 'Inter';
        $this->isEditing = false;
    }

    public function render()
    {
        $themes = Theme::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('type', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.template.theme-manager', [
            'themes' => $themes,
        ]);
    }
}
