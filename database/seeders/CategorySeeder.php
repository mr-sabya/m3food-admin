<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parent categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => Str::slug('Electronics'),
            'description' => 'Gadgets, devices, and more.',
            'image' => 'categories/electronics.jpg',
            'is_active' => true,
            'show_on_homepage' => true,
            'sort_order' => 1,
            'seo_title' => 'Shop Electronics',
            'seo_description' => 'Browse our wide range of electronics products.',
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => Str::slug('Fashion'),
            'description' => 'Trendy clothing and accessories.',
            'image' => 'categories/fashion.jpg',
            'is_active' => true,
            'show_on_homepage' => true,
            'sort_order' => 2,
            'seo_title' => 'Latest Fashion',
            'seo_description' => 'Discover the latest fashion trends and styles.',
        ]);

        $books = Category::create([
            'name' => 'Books',
            'slug' => Str::slug('Books'),
            'description' => 'Worlds of literature, fiction, and non-fiction.',
            'image' => 'categories/books.jpg',
            'is_active' => true,
            'show_on_homepage' => false, // Not on homepage
            'sort_order' => 3,
            'seo_title' => 'Buy Books Online',
            'seo_description' => 'Find your next great read.',
        ]);

        // Child categories for Electronics
        Category::create([
            'name' => 'Smartphones',
            'slug' => Str::slug('Smartphones'),
            'description' => 'The latest mobile phones.',
            'parent_id' => $electronics->id,
            'image' => 'categories/smartphones.jpg',
            'is_active' => true,
            'show_on_homepage' => false,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Laptops',
            'slug' => Str::slug('Laptops'),
            'description' => 'Powerful computing devices.',
            'parent_id' => $electronics->id,
            'image' => 'categories/laptops.jpg',
            'is_active' => true,
            'show_on_homepage' => false,
            'sort_order' => 2,
        ]);

        // Child categories for Fashion
        Category::create([
            'name' => 'Men\'s Apparel',
            'slug' => Str::slug('Mens Apparel'),
            'description' => 'Clothing for men.',
            'parent_id' => $fashion->id,
            'image' => 'categories/mens-apparel.jpg',
            'is_active' => true,
            'show_on_homepage' => false,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Women\'s Apparel',
            'slug' => Str::slug('Womens Apparel'),
            'description' => 'Clothing for women.',
            'parent_id' => $fashion->id,
            'image' => 'categories/womens-apparel.jpg',
            'is_active' => true,
            'show_on_homepage' => false,
            'sort_order' => 2,
        ]);

        // An inactive category
        Category::create([
            'name' => 'Vintage',
            'slug' => Str::slug('Vintage'),
            'description' => 'Classic and retro items (inactive).',
            'parent_id' => null,
            'image' => 'categories/vintage.jpg',
            'is_active' => false, // This will not show up in API calls by default
            'show_on_homepage' => false,
            'sort_order' => 4,
        ]);
    }
}
