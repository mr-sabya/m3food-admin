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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            // Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable(); // Rich Text
            $table->string('banner_image')->nullable();

            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Settings
            $table->enum('template', ['default', 'full-width', 'contact'])->default('default');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // For header/footer menu ordering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
