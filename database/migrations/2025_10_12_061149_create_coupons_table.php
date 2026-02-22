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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // The actual coupon code
            $table->text('description')->nullable(); // Internal description
            $table->enum('type', ['percentage', 'fixed_amount', 'free_shipping']);
            $table->decimal('value', 10, 2); // Percentage (e.g., 10 for 10%), or fixed amount (e.g., 20.00)

            $table->decimal('min_spend', 10, 2)->nullable(); // Minimum order total to apply
            $table->decimal('max_discount_amount', 10, 2)->nullable(); // Max discount for percentage coupons

            $table->integer('usage_limit_per_coupon')->nullable(); // Total uses allowed for this coupon
            $table->integer('usage_count')->default(0); // How many times it has been used
            $table->integer('usage_limit_per_user')->nullable(); // How many times one user can use it

            $table->timestamp('valid_from');
            $table->timestamp('valid_until')->nullable();

            $table->boolean('is_active')->default(true); // Is the coupon currently enabled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
