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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('users')->onDelete('set null'); // Null if admin product
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();

            // Product Type
            $table->enum('type', ['normal', 'variable', 'affiliate', 'digital'])->default('normal');

            // General fields applicable to normal/digital/affiliate
            $table->string('sku')->unique()->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable(); // For internal profit tracking
            $table->integer('quantity')->default(0); // Stock for normal/digital. 0 for variable parent.
            $table->decimal('weight', 8, 2)->nullable(); // For shipping

            $table->boolean('is_active')->default(false); // Needs admin/vendor approval/publishing
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(true); // Can be set by logic later
            $table->boolean('is_manage_stock')->default(true);
            $table->integer('min_order_quantity')->default(1);
            $table->integer('max_order_quantity')->nullable();

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            // Affiliate Product Specific Fields
            $table->string('affiliate_url')->nullable();

            // Digital Product Specific Fields
            $table->string('digital_file')->nullable(); // Path to the actual file
            $table->integer('download_limit')->nullable(); // Max downloads per purchase
            $table->integer('download_expiry_days')->nullable(); // Days until download link expires

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
