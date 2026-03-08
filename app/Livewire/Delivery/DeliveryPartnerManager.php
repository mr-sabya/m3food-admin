<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeliveryPartner;
use App\Models\ShopWarehouse;
use Illuminate\Validation\Rule;

class DeliveryPartnerManager extends Component
{
    use WithPagination;

    // --- Table Properties ---
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // --- Form Properties ---
    public $showModal = false;
    public $partnerId;
    public $type;
    public $name;
    public $contact_person;
    public $phone;
    public $address;
    public $warehouse_id;

    // Fee Fields
    public $fee_inside = 0;
    public $fee_outside = 0;
    public $fee_suburb = 0;
    public $express_fee_inside = 0;
    public $express_fee_outside = 0;
    public $express_fee_suburb = 0;
    public $courier_delivery_cost = 0;
    public $courier_return_cost = 0;
    public $cod_charge_percent = 0;

    public $is_active = true;
    public $isEditing = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    protected function getValidationRules()
    {
        return [
            'type' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'warehouse_id' => 'required|exists:warehouses,id', // Adjust table name if it's shop_warehouses
            'fee_inside' => 'required|numeric|min:0',
            'fee_outside' => 'required|numeric|min:0',
            'fee_suburb' => 'required|numeric|min:0',
            'express_fee_inside' => 'nullable|numeric|min:0',
            'express_fee_outside' => 'nullable|numeric|min:0',
            'express_fee_suburb' => 'nullable|numeric|min:0',
            'courier_delivery_cost' => 'nullable|numeric|min:0',
            'courier_return_cost' => 'nullable|numeric|min:0',
            'cod_charge_percent' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ];
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

    public function createPartner()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->openModal();
    }

    public function editPartner(DeliveryPartner $partner)
    {
        $this->isEditing = true;
        $this->partnerId = $partner->id;
        $this->fill($partner->toArray());
        $this->openModal();
    }

    public function savePartner()
    {
        $this->validate($this->getValidationRules());

        $data = [
            'type' => $this->type,
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'address' => $this->address,
            'warehouse_id' => $this->warehouse_id,
            'fee_inside' => $this->fee_inside,
            'fee_outside' => $this->fee_outside,
            'fee_suburb' => $this->fee_suburb,
            'express_fee_inside' => $this->express_fee_inside,
            'express_fee_outside' => $this->express_fee_outside,
            'express_fee_suburb' => $this->express_fee_suburb,
            'courier_delivery_cost' => $this->courier_delivery_cost,
            'courier_return_cost' => $this->courier_return_cost,
            'cod_charge_percent' => $this->cod_charge_percent,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            DeliveryPartner::find($this->partnerId)->update($data);
            $this->dispatch('notify', message: 'Partner updated.', type: 'success');
        } else {
            DeliveryPartner::create($data);
            $this->dispatch('notify', message: 'Partner created.', type: 'success');
        }

        $this->closeModal();
    }

    public function deletePartner($id)
    {
        DeliveryPartner::destroy($id);
        $this->dispatch('notify', message: 'Partner deleted.', type: 'success');
    }

    private function resetForm()
    {
        $this->reset([
            'partnerId',
            'type',
            'name',
            'contact_person',
            'phone',
            'address',
            'warehouse_id',
            'fee_inside',
            'fee_outside',
            'fee_suburb',
            'express_fee_inside',
            'express_fee_outside',
            'express_fee_suburb',
            'courier_delivery_cost',
            'courier_return_cost',
            'cod_charge_percent'
        ]);
        $this->is_active = true;
    }

    public function render()
    {
        $partners = DeliveryPartner::with('warehouse')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('type', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.delivery.delivery-partner-manager', [
            'partners' => $partners,
            'warehouses' => ShopWarehouse::all() // Adjust model name if needed
        ]);
    }
}
