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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();

            // The User who owns the wishlist item
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // The Product added to the wishlist
            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade');

            /**
             * Composite Unique Index:
             * This prevents the same user from adding the same product 
             * to their wishlist multiple times.
             */
            $table->unique(['user_id', 'product_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
