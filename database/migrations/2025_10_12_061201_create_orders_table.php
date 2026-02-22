<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null'); // Customer
                
            $table->foreignId('vendor_id')->nullable()->constrained('users')->onDelete('set null'); // Optional: If order is primarily from one vendor (e.g., for single-vendor mode)
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null'); // Applied coupon

            $table->string('order_number')->unique(); // E.g., #ORD-123456

            // Billing Address Snapshot (denormalized for historical accuracy)
            $table->string('billing_first_name');
            $table->string('billing_last_name');
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_address_line_1');
            $table->string('billing_address_line_2')->nullable();
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_zip_code');
            $table->string('billing_country');

            // Shipping Address Snapshot (denormalized)
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address_line_1');
            $table->string('shipping_address_line_2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_zip_code');
            $table->string('shipping_country');

            // Financials
            $table->decimal('subtotal', 10, 2); // Sum of all order item subtotals
            $table->decimal('discount_amount', 10, 2)->default(0.00); // Total discount from coupon/offers
            $table->decimal('shipping_cost', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2); // Final amount paid
            $table->string('currency', 3)->default('৳');

            // Payment & Status
            $table->string('payment_method'); // E.g., 'stripe', 'paypal', 'cash_on_delivery'
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('pending');
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'])->default('pending');

            // Shipping Details
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();

            $table->text('notes')->nullable(); // Customer notes

            // Timestamps for status tracking
            $table->timestamp('placed_at')->nullable(); // When the order was confirmed/paid
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
