<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductImage::truncate(); // Clear existing images

        $plant = Product::where('slug', Str::slug('Decorative Plant For Home'))->first();
        $dress = Product::where('slug', Str::slug('Ladies Short Sleeve Dress'))->first();
        $iphone = Product::where('slug', Str::slug('iPhone New Model'))->first();
        $ebook = Product::where('slug', Str::slug('Ebook Laravel API Development'))->first();
        $cleaner = Product::where('slug', Str::slug('Oil Soap Wood Home Cleaner'))->first();

        // --- Images for Decorative Plant ---
        if ($plant) {
            ProductImage::create(['product_id' => $plant->id, 'image_path' => 'products/plant_detail_1.jpg', 'sort_order' => 1]);
            ProductImage::create(['product_id' => $plant->id, 'image_path' => 'products/plant_detail_2.jpg', 'sort_order' => 2]);
        }

        // --- Images for Ladies Short Sleeve Dress (base product) ---
        if ($dress) {
            ProductImage::create(['product_id' => $dress->id, 'image_path' => 'products/dress_main_view.jpg', 'sort_order' => 1]);
            ProductImage::create(['product_id' => $dress->id, 'image_path' => 'products/dress_model_1.jpg', 'sort_order' => 2]);

            // Images for specific variants
            $redSmallVariant = ProductVariant::where('product_id', $dress->id)
                ->whereHas('attributeValues', function ($q) {
                    $q->where('slug', 'red')->orWhere('slug', 'small');
                }, '=', 2) // Ensure both attributes are present
                ->first();

            $blueMediumVariant = ProductVariant::where('product_id', $dress->id)
                ->whereHas('attributeValues', function ($q) {
                    $q->where('slug', 'blue')->orWhere('slug', 'medium');
                }, '=', 2)
                ->first();

            if ($redSmallVariant) {
                ProductImage::create(['product_id' => $dress->id, 'product_variant_id' => $redSmallVariant->id, 'image_path' => 'products/dress_red_small.jpg', 'sort_order' => 1]);
            }
            if ($blueMediumVariant) {
                ProductImage::create(['product_id' => $dress->id, 'product_variant_id' => $blueMediumVariant->id, 'image_path' => 'products/dress_blue_medium.jpg', 'sort_order' => 1]);
            }
        }

        // --- Images for iPhone New Model ---
        if ($iphone) {
            ProductImage::create(['product_id' => $iphone->id, 'image_path' => 'products/iphone_front.jpg', 'sort_order' => 1]);
            ProductImage::create(['product_id' => $iphone->id, 'image_path' => 'products/iphone_back.jpg', 'sort_order' => 2]);
            ProductImage::create(['product_id' => $iphone->id, 'image_path' => 'products/iphone_side.jpg', 'sort_order' => 3]);
        }

        // --- Images for Ebook (if applicable, e.g., cover art) ---
        if ($ebook) {
            ProductImage::create(['product_id' => $ebook->id, 'image_path' => 'products/ebook_cover_full.jpg', 'sort_order' => 1]);
        }

        // --- Images for Wood Cleaner ---
        if ($cleaner) {
            ProductImage::create(['product_id' => $cleaner->id, 'image_path' => 'products/cleaner_bottle.jpg', 'sort_order' => 1]);
        }
    }
}
