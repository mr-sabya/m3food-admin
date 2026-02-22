<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Disable foreign key checks for all truncates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        $this->call(AdminSeeder::class); // Assuming you have a UserSeeder
        $this->call(UserSeeder::class); // Assuming you have a UserSeeder
        $this->call(BrandSeeder::class); // Assuming you have a BrandSeeder
        // $this->call(CategorySeeder::class); // Assuming you have a CategorySeeder

        // 2. Attributes and their values (Product Variants depend on these)
        $this->call(AttributeSeeder::class);
        $this->call(AttributeValueSeeder::class);

        // 3. Products (must be after Categories, Users, Brands)
        $this->call(ProductSeeder::class);

        // 4. Product Variants (depends on Products and AttributeValues)
        $this->call(ProductVariantSeeder::class);

        // 5. Product Images (depends on Products and ProductVariants)
        $this->call(ProductImageSeeder::class);

        // 6. Deals (depends on Products)
        $this->call(DealSeeder::class);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
