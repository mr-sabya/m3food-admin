<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        AttributeValue::truncate();

        // Get attributes created by AttributeSeeder
        $colorAttribute = Attribute::where('slug', 'color')->first();
        $sizeAttribute = Attribute::where('slug', 'size')->first();
        $materialAttribute = Attribute::where('slug', 'material')->first();


        if ($colorAttribute) {
            AttributeValue::create(['attribute_id' => $colorAttribute->id, 'value' => 'Red', 'slug' => Str::slug('Red'), 'metadata' => ['hex' => '#FF0000']]);
            AttributeValue::create(['attribute_id' => $colorAttribute->id, 'value' => 'Blue', 'slug' => Str::slug('Blue'), 'metadata' => ['hex' => '#0000FF']]);
            AttributeValue::create(['attribute_id' => $colorAttribute->id, 'value' => 'Green', 'slug' => Str::slug('Green'), 'metadata' => ['hex' => '#008000']]);
            AttributeValue::create(['attribute_id' => $colorAttribute->id, 'value' => 'Black', 'slug' => Str::slug('Black'), 'metadata' => ['hex' => '#000000']]);
            AttributeValue::create(['attribute_id' => $colorAttribute->id, 'value' => 'White', 'slug' => Str::slug('White'), 'metadata' => ['hex' => '#FFFFFF']]);
        }

        if ($sizeAttribute) {
            AttributeValue::create(['attribute_id' => $sizeAttribute->id, 'value' => 'Small', 'slug' => Str::slug('Small')]);
            AttributeValue::create(['attribute_id' => $sizeAttribute->id, 'value' => 'Medium', 'slug' => Str::slug('Medium')]);
            AttributeValue::create(['attribute_id' => $sizeAttribute->id, 'value' => 'Large', 'slug' => Str::slug('Large')]);
            AttributeValue::create(['attribute_id' => $sizeAttribute->id, 'value' => 'X-Large', 'slug' => Str::slug('X-Large')]);
        }

        if ($materialAttribute) {
            AttributeValue::create(['attribute_id' => $materialAttribute->id, 'value' => 'Cotton', 'slug' => Str::slug('Cotton')]);
            AttributeValue::create(['attribute_id' => $materialAttribute->id, 'value' => 'Polyester', 'slug' => Str::slug('Polyester')]);
            AttributeValue::create(['attribute_id' => $materialAttribute->id, 'value' => 'Leather', 'slug' => Str::slug('Leather')]);
            AttributeValue::create(['attribute_id' => $materialAttribute->id, 'value' => 'Wood', 'slug' => Str::slug('Wood')]);
        }

        // Add more attribute values for other attributes as needed
    }
}
