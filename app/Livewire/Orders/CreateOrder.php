<?php

namespace App\Livewire\Orders;

use App\Models\Product;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CreateOrder extends Component
{
    use WithPagination;

    // Search & Selection
    public $customerSearch = '';
    public $productSearch = '';
    public $selectedCustomer = null;

    // 1. Add Filter Properties
    public $filterCategory = '';
    public $filterMinPrice = '';
    public $filterMaxPrice = '';
    public $filterAttribute = '';

    // Cart
    public $cart = [];

    // Modals
    public $showCreateCustomerModal = false;

    // New Customer Form
    public $newCustomer = [
        'name' => '',
        'phone' => '',
        'email' => '',
        'address' => '',
    ];

    protected $listeners = ['refreshCart' => '$refresh'];

    /**
     * Search Customer logic
     */
    public function updatedCustomerSearch()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->customerSearch);

        if (strlen($phone) >= 11) {
            $user = User::where('phone', 'like', '%' . $phone . '%')->first();
            if ($user) {
                $this->selectedCustomer = $user;
                // Auto-refresh stats if you have the refreshDeliveryStats method in User model
                if (method_exists($user, 'refreshDeliveryStats')) {
                    $user->refreshDeliveryStats();
                }
            } else {
                $this->selectedCustomer = null;
                // Auto-fill phone for new customer if not found
                $this->newCustomer['phone'] = $phone;
            }
        } else {
            $this->selectedCustomer = null;
        }
    }

    /**
     * Save New Customer and immediately select them
     */
    public function saveCustomer()
    {
        $this->validate([
            'newCustomer.name' => 'required|string|max:255',
            'newCustomer.phone' => 'required|unique:users,phone',
            'newCustomer.address' => 'required',
        ]);

        $user = User::create([
            'name' => $this->newCustomer['name'],
            'phone' => $this->newCustomer['phone'],
            'email' => $this->newCustomer['email'] ?? (Str::random(10) . '@mail.com'), // generate dummy if empty
            'address' => $this->newCustomer['address'],
            'role' => UserRole::Customer, // Using your Enum
            'password' => bcrypt('password'), // default password
            'is_active' => true,
        ]);

        $this->selectedCustomer = $user;
        $this->showCreateCustomerModal = false;
        $this->reset('newCustomer');
        session()->flash('message', 'Customer created and selected.');
    }

    // --- Product & Cart Logic ---
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        $cartKey = "p_{$productId}";
        $price = $product->sale_price ?? $product->regular_price;

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['qty']++;
        } else {
            $this->cart[$cartKey] = [
                'id' => $productId,
                'name' => $product->name,
                'price' => $price,
                'qty' => 1,
                'image' => $product->thumbnail_url
            ];
        }
    }

    public function removeFromCart($key)
    {
        unset($this->cart[$key]);
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    // 2. Add Methods
    public function applyFilter()
    {
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['filterCategory', 'filterMinPrice', 'filterMaxPrice', 'filterAttribute']);
        $this->resetPage();
    }

    // 3. Update the Query in render()
    public function render()
    {
        $products = Product::query()
            ->with(['variants', 'brand'])
            ->active()
            ->when($this->productSearch, function ($q) {
                $q->where('name', 'like', '%' . $this->productSearch . '%')
                    ->orWhere('sku', 'like', '%' . $this->productSearch . '%');
            })
            // Apply Category Filter
            ->when($this->filterCategory, function ($q) {
                $q->whereHas('categories', fn($c) => $c->where('categories.id', $this->filterCategory));
            })
            // Apply Price Range
            ->when($this->filterMinPrice, fn($q) => $q->where('regular_price', '>=', $this->filterMinPrice))
            ->when($this->filterMaxPrice, fn($q) => $q->where('regular_price', '<=', $this->filterMaxPrice))
            ->paginate(12);

        return view('livewire.orders.create-order', [
            'products' => $products,
            'allCategories' => \App\Models\Category::all(), // Fetch categories for the dropdown
        ]);
    }
}
