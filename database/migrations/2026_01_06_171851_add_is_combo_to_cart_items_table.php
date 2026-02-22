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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('main_product_id')->nullable()->after('product_id');
            // Flag to identify combo items
            $table->boolean('is_combo')->default(false)->after('main_product_id');
            // Optional: snapshot of price at time of add
            $table->decimal('price', 10, 2)->nullable()->after('is_combo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['main_product_id', 'price', 'is_combo']);
        });
    }
};
