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
        Schema::create('title_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->text('title');
            $table->string('title_color')->default('#ffffff');
            $table->string('title_bg')->default('#005a2b');
            $table->string('title_tag')->default('h2');
            $table->text('subtitle')->nullable();
            $table->string('subtitle_color')->default('#000000');
            $table->string('subtitle_bg')->default('#ffcc00');
            $table->string('subtitle_tag')->default('h3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_sections');
    }
};
