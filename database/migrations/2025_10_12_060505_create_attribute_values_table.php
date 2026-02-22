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
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->string('value'); // e.g., "Red", "16 GB", "Intel Core i7"
            $table->string('slug')->nullable(); // Optional slug for the value itself
            $table->json('metadata')->nullable(); // e.g., {"hex_code": "#FF0000"} for a color attribute, or {"image_path": "path/to/swatch.jpg"}
            $table->timestamps();

            $table->unique(['attribute_id', 'value']); // Ensure unique value for a given attribute
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};
