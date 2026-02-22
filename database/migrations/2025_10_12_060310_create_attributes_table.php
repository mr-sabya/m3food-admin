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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Color", "Size", "RAM", "Screen Size"
            $table->string('slug')->unique(); // For internal identification/filtering
            $table->enum('display_type', ['text', 'color_swatch', 'image_swatch', 'dropdown', 'radio', 'checkbox'])->default('text'); // How it's displayed on frontend
            $table->boolean('is_filterable')->default(true); // Can this attribute be used in frontend filters?
            $table->boolean('is_active')->default(true); // Is this attribute currently in use?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
