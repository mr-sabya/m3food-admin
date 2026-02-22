<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'image' => 'images/banner1.jpg',
            'title' => 'Summer Sale!',
            'subtitle' => 'Up to 50% Off All Items',
            'link' => '/products?category=sale',
            'button' => 'Shop Now',
            'is_active' => true,
            'order' => 1,
        ]);

        Banner::create([
            'image' => 'images/banner2.png',
            'title' => 'New Collection',
            'subtitle' => 'Explore our latest arrivals',
            'link' => '/products?category=new',
            'button' => 'Discover',
            'is_active' => true,
            'order' => 2,
        ]);

         Banner::create([
            'image' => 'images/banner3-inactive.jpg',
            'title' => 'Winter Clearance',
            'subtitle' => 'Last chance for great deals',
            'link' => '/products?category=clearance',
            'button' => 'Shop Now',
            'is_active' => false, // This banner will not be returned by default
            'order' => 3,
        ]);
    }
}