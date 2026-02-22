<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        PaymentMethod::truncate();

        $methods = [
            [
                'name' => 'Cash on Delivery',
                'slug' => 'cod',
                'type' => 'manual',
                'instructions' => 'Pay with cash upon delivery.',
                'is_default' => true,
                'status' => true,
            ],
            [
                'name' => 'bKash',
                'slug' => 'bkash',
                'type' => 'direct',
                'instructions' => 'Please "Send Money" to 017XXXXXXXX (Personal) and provide Transaction ID.',
                'is_default' => false,
                'status' => true,
            ],
            [
                'name' => 'Nagad',
                'slug' => 'nagad',
                'type' => 'direct',
                'instructions' => 'Please "Send Money" to 018XXXXXXXX (Personal) and provide Transaction ID.',
                'is_default' => false,
                'status' => true,
            ],
            [
                'name' => 'Online Payment',
                'slug' => 'online',
                'type' => 'gateway',
                'instructions' => 'Pay securely via Credit/Debit Card or Mobile Banking (SSLCommerz).',
                'is_default' => false,
                'status' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
