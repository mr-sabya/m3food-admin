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
        Schema::create('disclaimer_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable(); // Depending on your polymorphic setup
            $table->text('question'); // e.g., "আপনি কি ভাবছেন এটা কোন ঔষধ?"
            $table->text('answer');   // e.g., "এটা কোন ঔষধ না। এটা খাবার।"
            $table->string('bg_color')->default('#f8f9fa');
            $table->string('text_color')->default('#000000');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disclaimer_sections');
    }
};
