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

            // Core Relationships
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('vendor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');

            $table->string('order_number')->unique(); // E.g., #ORD-123456

            // Billing Address Info
            $table->string('billing_first_name');
            $table->string('billing_last_name');
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->foreignId('billing_country_id')->nullable()->constrained('countries');
            $table->foreignId('billing_state_id')->nullable()->constrained('states');
            $table->foreignId('billing_city_id')->nullable()->constrained('cities');
            $table->string('billing_address_line_1');
            $table->string('billing_address_line_2')->nullable();
            $table->string('billing_zip_code');

            // Shipping Address Info
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->foreignId('shipping_country_id')->nullable()->constrained('countries');
            $table->foreignId('shipping_state_id')->nullable()->constrained('states');
            $table->foreignId('shipping_city_id')->nullable()->constrained('cities');
            $table->string('shipping_address_line_1');
            $table->string('shipping_address_line_2')->nullable();
            $table->string('shipping_zip_code');

            // Financials
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0.00);
            $table->decimal('shipping_cost', 15, 2)->default(0.00);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2);
            $table->string('currency', 3)->default('৳');

            // Payment Details
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_method_name')->nullable(); // Snapshot (e.g. "Stripe", "bKash")
            $table->string('transaction_id')->nullable();     // ID from payment gateway
            $table->string('payment_phone_number')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('pending');

            // Shipping Details
            $table->foreignId('shipping_method_id')->nullable()->constrained('shipping_methods')->nullOnDelete();
            $table->string('shipping_method_name')->nullable(); // Snapshot (e.g. "Express Delivery")
            $table->string('tracking_number')->nullable();
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'])->default('pending');

            $table->text('notes')->nullable();

            // Timestamps for lifecycle tracking
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->text('cancel_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
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
