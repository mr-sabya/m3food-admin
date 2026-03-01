<?php

namespace App\Livewire\Orders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // --- Table & Search Properties ---
    public $search = '';
    public $perPage = 50;
    public $sortField = 'placed_at';
    public $sortDirection = 'desc';

    // --- Tab & Selection Logic ---
    public $activeTab = 'all';
    public $selectedOrders = [];
    public $selectAll = false;
    public $filterDuplicatePhones = false;

    // --- Dynamic Column Visibility ---
    public $columns = [
        'invoice_no' => true,
        'date' => true,
        'customer' => true,
        'pickup_address' => true,
        'payment_info' => true,
        'delivery_partner' => true,
        'delivery_fee' => true,
        'internal_notes' => true,
    ];
    public $selectAllColumns = true;

    // --- Modals ---
    public $showStatusUpdateModal = false;
    public $updateOrderId;
    public $newOrderStatus;
    public $newPaymentStatus;

    protected $queryString = ['search', 'perPage', 'activeTab'];

    // --- Column Filter Logic ---
    public function updatedSelectAllColumns($value)
    {
        foreach ($this->columns as $key => $val) {
            $this->columns[$key] = $value;
        }
    }

    public function updatedColumns()
    {
        $this->selectAllColumns = !in_array(false, $this->columns);
    }

    // --- Bulk Selection Logic ---
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedOrders = Order::query()
                ->when($this->activeTab !== 'all', fn($q) => $q->where('order_status', $this->activeTab))
                ->when($this->search, function ($q) {
                    $q->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhere('billing_phone', 'like', '%' . $this->search . '%');
                })
                ->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedOrders = [];
        }
    }

    // --- Actions ---
    public function approveSelected()
    {
        if (empty($this->selectedOrders)) return;
        Order::whereIn('id', $this->selectedOrders)->update(['order_status' => OrderStatus::Approved]);
        $this->selectedOrders = [];
        $this->selectAll = false;
        session()->flash('message', 'Selected orders have been Approved.');
    }

    public function exportCSV()
    {
        if (empty($this->selectedOrders)) return;
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order No', 'Customer', 'Phone', 'Amount', 'Status']);
            Order::whereIn('id', $this->selectedOrders)->each(function ($o) use ($handle) {
                fputcsv($handle, [$o->order_number, $o->getCustomerNameAttribute(), $o->billing_phone, $o->total_amount, $o->order_status->value]);
            });
            fclose($handle);
        }, 'orders_export.csv');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->selectedOrders = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function openStatusUpdateModal($orderId)
    {
        $order = Order::findOrFail($orderId);
        $this->updateOrderId = $orderId;
        $this->newOrderStatus = $order->order_status->value;
        $this->newPaymentStatus = $order->payment_status->value;
        $this->showStatusUpdateModal = true;
    }

    public function updateOrderStatus()
    {
        $order = Order::findOrFail($this->updateOrderId);
        $order->update([
            'order_status' => $this->newOrderStatus,
            'payment_status' => $this->newPaymentStatus,
        ]);
        $this->dispatch('close-modal-now');
        $this->showStatusUpdateModal = false;
        session()->flash('message', 'Status updated successfully.');
    }

    public function render()
    {
        // Generate counts for ALL statuses shown in image
        $counts = ['all' => Order::count()];
        foreach (OrderStatus::cases() as $status) {
            $counts[$status->value] = Order::where('order_status', $status->value)->count();
        }

        $query = Order::query()->with(['user', 'vendor', 'shippingCity']);

        if ($this->activeTab !== 'all') {
            $query->where('order_status', $this->activeTab);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('transaction_id', 'like', '%' . $this->search . '%')
                    ->orWhere('billing_phone', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        return view('livewire.orders.index', [
            'orders' => $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage),
            'counts' => $counts,
            'orderStatuses' => OrderStatus::cases(),
            'paymentStatuses' => PaymentStatus::cases(),
        ]);
    }
}
