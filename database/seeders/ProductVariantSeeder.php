<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductVariant::truncate(); // Clear existing variants

        $dressProduct = Product::where('slug', Str::slug('Ladies Short Sleeve Dress'))->first();

        if ($dressProduct) {
            // Get relevant attribute values
            $red = AttributeValue::where('slug', 'red')->first();
            $blue = AttributeValue::where('slug', 'blue')->first();
            $green = AttributeValue::where('slug', 'green')->first();
            $small = AttributeValue::where('slug', 'small')->first();
            $medium = AttributeValue::where('slug', 'medium')->first();
            $large = AttributeValue::where('slug', 'large')->first();

            // Red, Small
            $variant1 = ProductVariant::create([
                'product_id' => $dressProduct->id,
                'sku' => 'DRESS-R-S',
                'price' => 32.00,
                'compare_at_price' => 40.00,
                'cost_price' => 15.00,
                'quantity' => 30,
                'weight' => 0.4,
                'is_active' => true,
            ]);
            $variant1->attributeValues()->attach([$red->id, $small->id]);

            // Red, Medium
            $variant2 = ProductVariant::create([
                'product_id' => $dressProduct->id,
                'sku' => 'DRESS-R-M',
                'price' => 32.00,
                'quantity' => 50,
                'weight' => 0.4,
                'is_active' => true,
            ]);
            $variant2->attributeValues()->attach([$red->id, $medium->id]);

            // Blue, Small
            $variant3 = ProductVariant::create([
                'product_id' => $dressProduct->id,
                'sku' => 'DRESS-B-S',
                'price' => 30.00,
                'compare_at_price' => 38.00,
                'quantity' => 25,
                'weight' => 0.4,
                'is_active' => true,
            ]);
            $variant3->attributeValues()->attach([$blue->id, $small->id]);

            // Blue, Medium
            $variant4 = ProductVariant::create([
                'product_id' => $dressProduct->id,
                'sku' => 'DRESS-B-M',
                'price' => 30.00,
                'quantity' => 40,
                'weight' => 0.4,
                'is_active' => true,
            ]);
            $variant4->attributeValues()->attach([$blue->id, $medium->id]);

            // Green, Large
            $variant5 = ProductVariant::create([
                'product_id' => $dressProduct->id,
                'sku' => 'DRESS-G-L',
                'price' => 35.00,
                'compare_at_price' => 42.00,
                'quantity' => 20,
                'weight' => 0.45,
                'is_active' => true,
            ]);
            $variant5->attributeValues()->attach([$green->id, $large->id]);

            // Update parent product's quantity to reflect variants
            $dressProduct->update(['quantity' => $dressProduct->variants()->sum('quantity')]);
        }
    }
}
