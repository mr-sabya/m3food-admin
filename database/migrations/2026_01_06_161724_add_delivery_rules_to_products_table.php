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
        Schema::table('products', function (Blueprint $table) {
            // Rule 1: Buy X quantity for free delivery
            $table->integer('free_delivery_threshold')->nullable()->after('max_order_quantity');

            // Rule 3: Time-based free delivery
            $table->timestamp('free_delivery_starts_at')->nullable()->after('free_delivery_threshold');
            $table->timestamp('free_delivery_ends_at')->nullable()->after('free_delivery_starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['free_delivery_threshold', 'free_delivery_starts_at', 'free_delivery_ends_at']);
        });
    }
};
