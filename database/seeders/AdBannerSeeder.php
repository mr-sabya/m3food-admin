<?php

namespace Database\Seeders;

use App\Models\AdBanner;
use Illuminate\Database\Seeder;

class AdBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'slug'       => 'home-banner-1',
                'title'      => 'Summer Collection 2026',
                'image_path' => 'banners/banner-1.jpg',
                'link'       => '/shop?category=summer',
                'is_active'  => true,
            ],
            [
                'slug'       => 'home-banner-2',
                'title'      => 'Up to 50% Off Footwear',
                'image_path' => 'banners/banner-2.jpg',
                'link'       => '/shop?category=footwear',
                'is_active'  => true,
            ],
            [
                'slug'       => 'home-banner-3',
                'title'      => 'New Arrivals: Accessories',
                'image_path' => 'banners/banner-3.jpg',
                'link'       => '/shop?category=accessories',
                'is_active'  => true,
            ],
        ];

        foreach ($banners as $banner) {
            // Using updateOrCreate prevents duplicates if you run the seeder twice
            AdBanner::updateOrCreate(
                ['slug' => $banner['slug']],
                $banner
            );
        }
    }
}
