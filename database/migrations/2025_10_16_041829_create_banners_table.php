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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // Path to the banner image
            $table->string('title', 500); // Title, can contain HTML for styling like <span>
            $table->string('subtitle')->nullable(); // Optional subtitle
            $table->string('link')->nullable(); // URL for the button/banner
            $table->string('button')->nullable(); // Text for the button
            $table->boolean('is_active')->default(true); // To activate/deactivate the banner
            $table->integer('order')->default(0); // To define the order of banners
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
