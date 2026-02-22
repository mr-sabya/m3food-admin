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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Corresponds to `id` in your JS data (e.g., 'sunglass', 'headphones')
            // Add the category_id column
            $table->foreignId('category_id')
                ->constrained() // Assumes 'categories' table and 'id' column
                ->onDelete('cascade'); // What to do if the parent category is deleted
            $table->string('title');         // Corresponds to `title` (e.g., "Explore The Sunglass")
            $table->text('description')->nullable(); // Corresponds to `description`
            $table->decimal('featured_price', 8, 2)->nullable(); // Corresponds to `price`, using a representative price
            $table->string('image_path')->nullable(); // Corresponds to `imageSrc`, stores path
            $table->string('image_alt')->nullable(); // Corresponds to `imageAlt`
            $table->string('tag')->nullable();       // Corresponds to `tag` (e.g., "SUNGLASS", "SALE")
            $table->integer('display_order')->default(0); // For ordering how collections appear
            $table->boolean('is_active')->default(true); // To enable/disable collections
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
