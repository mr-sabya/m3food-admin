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
        Schema::create('marketing_highlight_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->text('top_boxed_text');
            $table->string('top_box_border_color')->default('red');
            $table->text('bottom_boxed_text');
            $table->string('bottom_box_border_color')->default('red');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_highlight_sections');
    }
};
