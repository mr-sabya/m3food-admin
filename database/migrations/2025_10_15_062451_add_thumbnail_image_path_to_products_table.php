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
        Schema::table('products', function (Blueprint $table) {
            $table->string('thumbnail_image_path')->nullable()->after('long_description');
        });

        \App\Models\Product::chunk(100, function ($products) {
            foreach ($products as $product) {
                $thumbnailImage = $product->images()->where('is_thumbnail', true)->first();
                if ($thumbnailImage) {
                    $product->update(['thumbnail_image_path' => $thumbnailImage->image_path]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('thumbnail_image_path');
        });
    }
};
