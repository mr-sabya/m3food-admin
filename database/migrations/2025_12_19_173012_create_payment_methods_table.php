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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., bKash, Nagad, Credit Card, COD
            $table->string('slug')->unique(); // e.g., bkash, nagad, card, cod
            $table->string('image')->nullable(); // Logo/Icon

            // type: 
            // 'manual' (COD), 
            // 'direct' (Show mobile number + ask for Transaction ID), 
            // 'gateway' (Redirect to SSLCommerz/bkash tokenized)
            $table->enum('type', ['manual', 'direct', 'gateway'])->default('manual');

            $table->text('instructions')->nullable(); // e.g., "Send money to 017xx"
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
