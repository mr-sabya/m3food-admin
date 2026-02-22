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
        Schema::create('why_chooses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // "Why we are the best"
            $table->string('image')->nullable();
            $table->json('items')->nullable(); // Stores Icon, Title, and Description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('why_chooses');
    }
};
