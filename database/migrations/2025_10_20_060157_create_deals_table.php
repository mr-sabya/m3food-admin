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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Today's Featured Deal", "Summer Sale Best Deals"
            $table->string('type')->default('percentage')->comment('e.g., fixed, percentage'); // How the deal works
            $table->decimal('value', 8, 2)->nullable(); // e.g., 10 (for 10% or $10 off)
            $table->text('description')->nullable();
            $table->string('banner_image_path')->nullable(); // Image for the deal itself (if different from product)
            $table->string('link_target')->nullable(); // Where the "View All Items" button goes (e.g., /deals, /category/sale)

            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Flag for the large featured deal on your component
            $table->integer('display_order')->default(0); // To order 'best deals' if needed

            $table->timestamps();
        });

        // Pivot table for many-to-many relationship between deals and products
        Schema::create('deal_product', function (Blueprint $table) {
            $table->foreignId('deal_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->primary(['deal_id', 'product_id']); // Composite primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_product');
        Schema::dropIfExists('deals');
    }
};
