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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "Minimalist Shop Theme"
            $table->string('preview_image'); // Screenshot of the whole page design

            // THEME TYPES
            $table->enum('type', [
                'home',
                'about',
                'contact',
                'shop',
                'landing_page'
            ])->default('landing_page');

            $table->json('settings')->nullable(); // Optional: Store theme-specific colors/fonts
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
