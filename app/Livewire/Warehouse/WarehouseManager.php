<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ShopWarehouse as Warehouse;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WarehouseManager extends Component
{
    use WithPagination;

    // --- Table Properties ---
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // --- Form Properties ---
    public $showModal = false;
    public $warehouseId;         // Null for create, ID for edit
    public $name;
    public $code;
    public $contact_person;
    public $phone;
    public $email;
    public $address;
    public $lat;
    public $long;
    public $city_id;
    public $is_active = true;

    public $isEditing = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    // Basic validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'contact_person' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'address' => 'required|string|max:500',
        'lat' => 'nullable|numeric',
        'long' => 'nullable|numeric',
        'city_id' => 'required|exists:cities,id',
        'is_active' => 'boolean',
    ];

    // Dynamic validation rules (for unique code logic)
    protected function getValidationRules()
    {
        return array_merge($this->rules, [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('warehouses')->ignore($this->warehouseId),
            ],
        ]);
    }

    // Custom validation messages
    protected $messages = [
        'code.unique' => 'This warehouse code is already in use.',
        'city_id.required' => 'Please select a city.',
    ];

    // --- Table Methods ---

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

    public function createWarehouse()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->openModal();
    }

    public function editWarehouse(Warehouse $warehouse)
    {
        $this->isEditing = true;
        $this->warehouseId = $warehouse->id;
        $this->name = $warehouse->name;
        $this->code = $warehouse->code;
        $this->contact_person = $warehouse->contact_person;
        $this->phone = $warehouse->phone;
        $this->email = $warehouse->email;
        $this->address = $warehouse->address;
        $this->lat = $warehouse->lat;
        $this->long = $warehouse->long;
        $this->city_id = $warehouse->city_id;
        $this->is_active = $warehouse->is_active;
        $this->openModal();
    }

    public function saveWarehouse()
    {
        $this->validate($this->getValidationRules());

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'lat' => $this->lat,
            'long' => $this->long,
            'city_id' => $this->city_id,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            $warehouse = Warehouse::find($this->warehouseId);
            $warehouse->update($data);
            $this->dispatch('notify', message: 'Warehouse updated successfully.', type: 'success');
        } else {
            Warehouse::create($data);
            $this->dispatch('notify', message: 'Warehouse created successfully.', type: 'success');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function deleteWarehouse($warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);

        if (!$warehouse) {
            session()->flash('error', 'Warehouse not found.');
            return;
        }

        // Check for associated delivery partners before deleting
        if ($warehouse->deliveryPartners()->count() > 0) {
            session()->flash('error', 'Cannot delete warehouse with associated delivery partners.');
            return;
        }

        $warehouse->delete();
        session()->flash('message', 'Warehouse deleted successfully!');
        $this->resetPage();
    }

    // --- Utility Methods ---

    private function resetForm()
    {
        $this->warehouseId = null;
        $this->name = '';
        $this->code = '';
        $this->contact_person = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->lat = null;
        $this->long = null;
        $this->city_id = null;
        $this->is_active = true;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $warehouses = Warehouse::with('city')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.warehouse.warehouse-manager', [
            'warehouses' => $warehouses,
            'cities' => City::orderBy('name')->get(), // For the dropdown
        ]);
    }
}
