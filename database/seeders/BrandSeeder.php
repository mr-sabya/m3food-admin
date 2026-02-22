<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::truncate(); // Clear existing brands

        Brand::create(['name' => 'Fashionista', 'slug' => Str::slug('Fashionista')]);
        Brand::create(['name' => 'EcoClean', 'slug' => Str::slug('EcoClean')]);
        Brand::create(['name' => 'Luminara', 'slug' => Str::slug('Luminara')]);
        Brand::create(['name' => 'TechMaster', 'slug' => Str::slug('TechMaster')]);
        Brand::create(['name' => 'Apple', 'slug' => Str::slug('Apple')]);
        Brand::create(['name' => 'Sony', 'slug' => Str::slug('Sony')]);
    }
}
