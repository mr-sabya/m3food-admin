<?php

namespace Database\Seeders;

use App\Enums\AttributeDisplayType;
use App\Models\Attribute;
use App\Models\AttributeSet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Attribute::truncate();
        AttributeSet::truncate();

        // 1. Create Attributes
        $colorAttribute = Attribute::create([
            'name' => 'Color',
            'slug' => Str::slug('Color'),
            'display_type' => AttributeDisplayType::Color,
            'is_filterable' => true,
            'is_active' => true,
        ]);

        $sizeAttribute = Attribute::create([
            'name' => 'Size',
            'slug' => Str::slug('Size'),
            'display_type' => AttributeDisplayType::Select,
            'is_filterable' => true,
            'is_active' => true,
        ]);

        $materialAttribute = Attribute::create([
            'name' => 'Material',
            'slug' => Str::slug('Material'),
            'display_type' => AttributeDisplayType::Text,
            'is_filterable' => true,
            'is_active' => true,
        ]);

        // 2. Create an Attribute Set (e.g., for Apparel products)
        $apparelAttributeSet = AttributeSet::create([
            'name' => 'Apparel Attributes',
            'description' => 'Common attributes for clothing products (Color, Size)',
        ]);

        $electronicsAttributeSet = AttributeSet::create([
            'name' => 'Electronics Specifications',
            'description' => 'Common attributes for electronic products (RAM, Storage)',
        ]);


        // 3. Attach Attributes to Attribute Sets
        $apparelAttributeSet->attributes()->attach([$colorAttribute->id, $sizeAttribute->id]);
        // We'll leave the electronics attribute set without attached attributes for now,
        // as those attributes would be created in a similar fashion.
    }
}
