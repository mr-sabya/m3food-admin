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
        Schema::create('testimonial_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_proof_section_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['image', 'video'])->default('image'); // Differentiate content
            $table->string('image_path')->nullable(); // For screenshots or video thumbnails
            $table->string('video_url')->nullable();  // For YouTube/Vimeo links
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_items');
    }
};
