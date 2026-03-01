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
        Schema::create('user_delivery_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Link directly to the DeliveryPartner model
            $table->foreignId('delivery_partner_id')->constrained()->onDelete('cascade');

            $table->integer('total_count')->default(0);
            $table->integer('delivered_count')->default(0);
            $table->integer('undelivered_count')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0.00);
            $table->timestamps();

            // Ensure one stat record per user per delivery partner
            $table->unique(['user_id', 'delivery_partner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_delivery_stats');
    }
};
