<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User; // Assuming User model for vendor
use App\Enums\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate(); // Clear existing products

        // Get a default vendor and brand (ensure they exist or create them)
        $vendor = User::first() ?? User::factory()->create(); // Using factory if UserSeeder isn't run first
        $defaultBrand = Brand::first() ?? Brand::create(['name' => 'Generic Brand', 'slug' => Str::slug('Generic Brand')]);

        // Get some categories (ensure CategorySeeder is run first or create them)
        $electronicsCategory = Category::firstOrCreate(['slug' => 'electronics'], ['name' => 'Electronics', 'description' => 'Electronic gadgets']);
        $clothingCategory = Category::firstOrCreate(['slug' => 'clothing'], ['name' => 'Clothing', 'description' => 'Apparel and accessories']);
        $homeCategory = Category::firstOrCreate(['slug' => 'home-decor'], ['name' => 'Home Decor', 'description' => 'Items for home decoration']);
        $plantsCategory = Category::firstOrCreate(['slug' => 'plants'], ['name' => 'Plants', 'description' => 'Indoor and outdoor plants']);
        $mobileCategory = Category::firstOrCreate(['slug' => 'mobile-phones'], ['name' => 'Mobile Phones', 'description' => 'Smartphones']);

        // --- Normal Products ---
        $product1 = Product::create([
            'vendor_id' => $vendor->id,
            'brand_id' => $defaultBrand->id,
            'name' => 'Decorative Plant For Home',
            'slug' => Str::slug('Decorative Plant For Home'),
            'short_description' => 'A beautiful, low-maintenance plant to brighten up your living space.',
            'long_description' => '<p>This decorative plant is perfect for adding a touch of nature to any room. It requires minimal watering and thrives in indirect sunlight, making it ideal for busy individuals or beginners.</p>',
            'thumbnail_image_path' => 'products/collection_5.jpg',
            'type' => ProductType::Normal,
            'sku' => 'PLANT-001',
            'price' => 35.00,
            'compare_at_price' => 45.00,
            'cost_price' => 15.00,
            'quantity' => 50,
            'weight' => 1.5,
            'is_active' => true,
            'is_featured' => true,
            'is_new' => true,
            'is_manage_stock' => true,
            'min_order_quantity' => 1,
            'max_order_quantity' => 10,
            'seo_title' => 'Decorative Home Plant',
            'seo_description' => 'Buy indoor decorative plants.',
        ]);
        $product1->categories()->attach([$homeCategory->id, $plantsCategory->id]);

        $product2 = Product::create([
            'vendor_id' => $vendor->id,
            'brand_id' => $defaultBrand->id,
            'name' => 'Oil Soap Wood Home Cleaner',
            'slug' => Str::slug('Oil Soap Wood Home Cleaner'),
            'short_description' => 'Natural and effective wood cleaner with a fresh scent.',
            'long_description' => '<p>Keep your wooden furniture and floors pristine with this natural oil soap cleaner. It gently removes dirt and grime while nourishing the wood, leaving a beautiful shine and a fresh, clean aroma.</p>',
            'thumbnail_image_path' => 'products/collection_6.png',
            'type' => ProductType::Normal,
            'sku' => 'CLEAN-001',
            'price' => 15.22,
            'compare_at_price' => 20.00,
            'cost_price' => 8.00,
            'quantity' => 120,
            'weight' => 0.7,
            'is_active' => true,
            'is_featured' => false,
            'is_new' => false,
            'is_manage_stock' => true,
            'min_order_quantity' => 1,
            'max_order_quantity' => 5,
            'seo_title' => 'Wood Home Cleaner',
            'seo_description' => 'Best wood cleaning solution.',
        ]);
        $product2->categories()->attach([$homeCategory->id]);

        $product3 = Product::create([
            'vendor_id' => $vendor->id,
            'brand_id' => $defaultBrand->id,
            'name' => 'Large Pendant Light Ceiling',
            'slug' => Str::slug('Large Pendant Light Ceiling'),
            'short_description' => 'Modern and elegant pendant light for your living room or dining area.',
            'long_description' => '<p>This large pendant light adds a contemporary touch to any space. Featuring a sleek design and adjustable height, it provides ample illumination and becomes a focal point in your decor.</p>',
            'thumbnail_image_path' => 'products/collection_7.png',
            'type' => ProductType::Normal,
            'sku' => 'LIGHT-001',
            'price' => 11.70,
            'compare_at_price' => 18.00,
            'cost_price' => 6.00,
            'quantity' => 30,
            'weight' => 2.2,
            'is_active' => true,
            'is_featured' => false,
            'is_new' => true,
            'is_manage_stock' => true,
            'min_order_quantity' => 1,
            'max_order_quantity' => 3,
            'seo_title' => 'Pendant Ceiling Light',
            'seo_description' => 'Modern hanging lights.',
        ]);
        $product3->categories()->attach([$homeCategory->id, $electronicsCategory->id]);

        // --- Variable Product (e.g., for clothing) ---
        $product4 = Product::create([
            'vendor_id' => $vendor->id,
            'brand_id' => $defaultBrand->id,
            'name' => 'Ladies Short Sleeve Dress',
            'slug' => Str::slug('Ladies Short Sleeve Dress'),
            'short_description' => 'A stylish and comfortable short sleeve dress for all occasions.',
            'long_description' => '<p>Crafted from soft, breathable fabric, this dress offers both comfort and style. Available in multiple colors and sizes, it\'s a versatile addition to any wardrobe.</p>',
            'thumbnail_image_path' => 'products/collection_5.png', // Using the same image for the base product
            'type' => ProductType::Variable,
            'sku' => 'DRESS-001',
            'price' => 30.00, // Base price, variants will have their own
            'quantity' => 0, // Quantity for variable product is sum of variants
            'weight' => 0.4,
            'is_active' => true,
            'is_featured' => true,
            'is_new' => true,
            'is_manage_stock' => true,
            'min_order_quantity' => 1,
            'max_order_quantity' => 5,
            'seo_title' => 'Ladies Dress',
            'seo_description' => 'Buy women\'s short sleeve dresses.',
        ]);
        $product4->categories()->attach([$clothingCategory->id]);

        // --- Digital Product ---
        $product5 = Product::create([
            'vendor_id' => $vendor->id,
            'brand_id' => $defaultBrand->id,
            'name' => 'Ebook: Laravel API Development',
            'slug' => Str::slug('Ebook Laravel API Development'),
            'short_description' => 'A comprehensive guide to building RESTful APIs with Laravel.',
            'long_description' => '<p>Master Laravel API development with this in-depth ebook. Learn about routing, authentication, resource controllers, and more.</p>',
            'thumbnail_image_path' => 'products/ebook_laravel.jpg',
            'type' => ProductType::Digital,
            'sku' => 'EBOOK-001',
            'price' => 19.99,
            'cost_price' => 5.00, // Cost of licensing or creation
            'quantity' => 9999, // Essentially unlimited for digital
            'weight' => 0.0,
            'is_active' => true,
            'is_featured' => false,
            'is_new' => true,
            'is_manage_stock' => false,
            'min_order_quantity' => 1,
            'max_order_quantity' => 1,
            'seo_title' => 'Laravel API Ebook',
            'seo_description' => 'Learn Laravel API development.',
            'digital_file' => 'digital/ebook-laravel-api.pdf', // Path in storage
            'download_limit' => 3,
            'download_expiry_days' => 365,
        ]);
        $product5->categories()->attach([$electronicsCategory->id]); // Could be a 'Books' category too

        // --- Affiliate Product ---
        $product6 = Product::create([
            'vendor_id' => $vendor->id,
            'brand_id' => $defaultBrand->id,
            'name' => 'Sony WH-1000XM5 Noise Cancelling Headphones',
            'slug' => Str::slug('Sony WH-1000XM5 Noise Cancelling Headphones'),
            'short_description' => 'Industry-leading noise cancellation and premium sound quality.',
            'long_description' => '<p>Experience audio like never before with the Sony WH-1000XM5. These headphones offer unparalleled noise cancellation, exceptional sound, and all-day comfort.</p>',
            'thumbnail_image_path' => 'products/sony_headphones.jpg',
            'type' => ProductType::Affiliate,
            'sku' => 'SONY-HP-001',
            'price' => 399.99, // Display price, actual purchase happens on affiliate site
            'quantity' => 0, // Not managed by us
            'weight' => 0.25,
            'is_active' => true,
            'is_featured' => true,
            'is_new' => false,
            'is_manage_stock' => false,
            'min_order_quantity' => 1,
            'max_order_quantity' => 1,
            'seo_title' => 'Sony XM5 Headphones',
            'seo_description' => 'Buy Sony noise cancelling headphones.',
            'affiliate_url' => 'https://www.amazon.com/sony-wh1000xm5-headphones-link', // Example affiliate link
        ]);
        $product6->categories()->attach([$electronicsCategory->id]);

        // --- Another Normal Product (e.g., iPhone) ---
        $product7 = Product::create([
            'vendor_id' => $vendor->id,
            'brand_id' => $defaultBrand->id,
            'name' => 'iPhone New Model',
            'slug' => Str::slug('iPhone New Model'),
            'short_description' => 'The latest iPhone with a powerful chip and stunning display.',
            'long_description' => '<p>Experience the next generation of mobile technology with the new iPhone. Featuring an A17 Bionic chip, an advanced camera system, and all-day battery life.</p>',
            'thumbnail_image_path' => 'products/collection_8.png',
            'type' => ProductType::Normal,
            'sku' => 'IPHONE-NEW',
            'price' => 999.00,
            'compare_at_price' => 1099.00,
            'cost_price' => 700.00,
            'quantity' => 20,
            'weight' => 0.2,
            'is_active' => true,
            'is_featured' => true,
            'is_new' => true,
            'is_manage_stock' => true,
            'min_order_quantity' => 1,
            'max_order_quantity' => 2,
            'seo_title' => 'New iPhone Model',
            'seo_description' => 'Buy the latest Apple iPhone.',
        ]);
        $product7->categories()->attach([$electronicsCategory->id, $mobileCategory->id]);
    }
}
