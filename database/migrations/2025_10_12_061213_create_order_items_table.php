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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict'); // Don't delete product if it's in an order
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('restrict'); // Don't delete variant if in order

            $table->foreignId('vendor_id')->constrained('users')->onDelete('restrict'); // The vendor who sold this specific item

            // Snapshot of product data at time of order
            $table->string('item_name');
            $table->string('item_sku')->nullable();
            $table->json('item_attributes')->nullable(); // E.g., {"Color": "Red", "Size": "M"}

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Price of one item, before item-specific discount
            $table->decimal('item_discount_amount', 10, 2)->default(0.00); // Discount specific to this item
            $table->decimal('item_tax_amount', 10, 2)->default(0.00);
            $table->decimal('subtotal', 10, 2); // (quantity * unit_price) - item_discount_amount + item_tax_amount

            // Vendor commission details (snapshot at time of order)
            $table->decimal('commission_rate', 5, 2)->nullable(); // E.g., 10.00 for 10%
            $table->decimal('commission_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
