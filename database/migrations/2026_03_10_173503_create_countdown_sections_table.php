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
        Schema::create('countdown_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('offer_title');
            $table->string('offer_title_color')->default('yellow');
            $table->string('stock_count_text');
            $table->integer('stock_count');
            $table->dateTime('end_time');
            $table->string('bg_color')->default('#004d26');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countdown_sections');
    }
};
