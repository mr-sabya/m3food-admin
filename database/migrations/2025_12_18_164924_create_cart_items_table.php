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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            /**
             * User Relationship:
             * Nullable to allow Guest Carts. When a user logs in, 
             * you can associate these items with their user_id.
             */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            /**
             * Session ID:
             * Used to identify a guest's cart via their browser session.
             * Indexed for faster lookups when retrieving guest carts.
             */
            $table->string('session_id')->nullable()->index();

            // Product Relationship
            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade');

            $table->integer('quantity')->default(1);

            /**
             * Options (JSON):
             * Stores selected variants (e.g., {"color": "Blue", "size": "XL"}) 
             * or any other custom product customizations.
             */
            $table->json('options')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
