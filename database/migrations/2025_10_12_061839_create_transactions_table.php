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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method'); // e.g., 'stripe', 'paypal', 'cash_on_delivery'
            $table->string('transaction_id')->unique()->nullable(); // ID from payment gateway
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'successful', 'failed', 'refunded', 'partial_refund'])->default('pending');
            $table->enum('type', ['payment', 'refund'])->default('payment'); // Type of transaction
            $table->json('gateway_response')->nullable(); // Raw JSON response from payment gateway for debugging/records
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
