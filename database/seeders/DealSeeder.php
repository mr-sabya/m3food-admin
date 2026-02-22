<?php

namespace Database\Seeders;

use App\Models\Deal;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Deal::truncate(); // Clear existing deals

        $plant = Product::where('slug', Str::slug('Decorative Plant For Home'))->first();
        $dress = Product::where('slug', Str::slug('Ladies Short Sleeve Dress'))->first();
        $iphone = Product::where('slug', Str::slug('iPhone New Model'))->first();
        $cleaner = Product::where('slug', Str::slug('Oil Soap Wood Home Cleaner'))->first();
        $pendantLight = Product::where('slug', Str::slug('Large Pendant Light Ceiling'))->first();


        // --- Featured Deal ---
        if ($plant) {
            $featuredDeal = Deal::create([
                'name' => 'Spring Plant Sale',
                'type' => 'percentage',
                'value' => 20.00, // 20% off
                'description' => 'Get 20% off on all decorative plants this spring!',
                'banner_image_path' => 'deals/plant_sale_banner.jpg',
                'link_target' => '/category/plants',
                'starts_at' => Carbon::now()->subDays(5),
                'expires_at' => Carbon::now()->addDays(25),
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 1,
            ]);
            $featuredDeal->products()->attach($plant->id); // Link this deal to the plant
        }

        // --- Best Deal 1 (for dress) ---
        if ($dress) {
            $dressDeal = Deal::create([
                'name' => 'Summer Dress Markdown',
                'type' => 'fixed',
                'value' => 5.00, // $5 off
                'description' => 'Save $5 on all Ladies Short Sleeve Dresses!',
                'banner_image_path' => 'deals/dress_sale_banner.jpg',
                'link_target' => $dress->slug,
                'starts_at' => Carbon::now()->subDays(10),
                'expires_at' => Carbon::now()->addDays(10),
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 2,
            ]);
            // Attach the deal to all variants of the dress if ProductVariant also has deals relationship
            // For now, attach to the base product. The getEffectivePriceAttribute on Product will handle this.
            $dressDeal->products()->attach($dress->id);
        }

        // --- Best Deal 2 (for iPhone) ---
        if ($iphone) {
            $iphoneDeal = Deal::create([
                'name' => 'New iPhone Launch Offer',
                'type' => 'percentage',
                'value' => 5.00, // 5% off
                'description' => 'Pre-order the new iPhone and get 5% off!',
                'banner_image_path' => 'deals/iphone_promo_banner.jpg',
                'link_target' => $iphone->slug,
                'starts_at' => Carbon::now()->subDays(2),
                'expires_at' => Carbon::now()->addDays(7),
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 3,
            ]);
            $iphoneDeal->products()->attach($iphone->id);
        }

        // --- Best Deal 3 (for Wood Cleaner) ---
        if ($cleaner) {
            $cleanerDeal = Deal::create([
                'name' => 'Home Essentials Discount',
                'type' => 'fixed',
                'value' => 2.00, // $2 off
                'description' => 'Save on essential home cleaning products.',
                'banner_image_path' => 'deals/cleaner_promo.jpg',
                'link_target' => $cleaner->slug,
                'starts_at' => Carbon::now()->subMonths(1),
                'expires_at' => Carbon::now()->addMonths(2),
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 4,
            ]);
            $cleanerDeal->products()->attach($cleaner->id);
        }

        // --- Best Deal 4 (for Pendant Light) ---
        if ($pendantLight) {
            $lightDeal = Deal::create([
                'name' => 'Lighting Clearance',
                'type' => 'percentage',
                'value' => 15.00, // 15% off
                'description' => 'Clearance sale on selected lighting fixtures.',
                'banner_image_path' => 'deals/light_clearance.jpg',
                'link_target' => $pendantLight->slug,
                'starts_at' => Carbon::now()->subWeeks(1),
                'expires_at' => Carbon::now()->addWeeks(3),
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 5,
            ]);
            $lightDeal->products()->attach($pendantLight->id);
        }

        // --- Expired Deal (should not show up in active queries) ---
        Deal::create([
            'name' => 'Expired Flash Sale',
            'type' => 'percentage',
            'value' => 50.00,
            'description' => 'This deal has already ended.',
            'banner_image_path' => 'deals/expired_flash.jpg',
            'link_target' => '#',
            'starts_at' => Carbon::now()->subMonths(2),
            'expires_at' => Carbon::now()->subDays(1), // Expired yesterday
            'is_active' => true, // Still marked active, but will be filtered by 'expires_at'
            'is_featured' => false,
            'display_order' => 6,
        ]);

        // --- Inactive Deal (should not show up) ---
        if ($iphone) {
            Deal::create([
                'name' => 'Hidden iPhone Deal',
                'type' => 'fixed',
                'value' => 10.00,
                'description' => 'This deal is inactive.',
                'banner_image_path' => 'deals/hidden_deal.jpg',
                'link_target' => $iphone->slug,
                'starts_at' => Carbon::now()->subDays(5),
                'expires_at' => Carbon::now()->addDays(5),
                'is_active' => false, // explicitly inactive
                'is_featured' => false,
                'display_order' => 7,
            ])->products()->attach($iphone->id);
        }
    }
}
