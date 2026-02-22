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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // For SEO-friendly URLs, e.g., /categories/electronics
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('set null'); // Self-referencing for hierarchical categories
            $table->string('image')->nullable(); // Path to category icon/image
            $table->boolean('is_active')->default(true); // Is this category visible/enabled?
            $table->boolean('show_on_homepage')->default(false); // Feature on homepage?
            $table->integer('sort_order')->default(0); // Manual sorting order for display
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
