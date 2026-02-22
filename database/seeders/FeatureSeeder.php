<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'icon'       => 'assets/frontend/images/feature-icon_1.svg',
                'title'      => 'Return & refund',
                'subtitle'   => 'Money back guarantee',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'icon'       => 'assets/frontend/images/feature-icon_3.svg',
                'title'      => 'Quality Support',
                'subtitle'   => 'Always online 24/7',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'icon'       => 'assets/frontend/images/feature-icon_2.svg',
                'title'      => 'Secure Payment',
                'subtitle'   => '30% off by subscribing',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'icon'       => 'assets/frontend/images/feature-icon_4.svg',
                'title'      => 'Daily Offers',
                'subtitle'   => '20% off by subscribing',
                'sort_order' => 4,
                'is_active'  => true,
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
