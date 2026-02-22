<?php

namespace Database\Seeders;

use App\Models\WhyChoose;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhyChooseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WhyChoose::create([
            'title' => 'Why we are the <span>best</span>',
            'image' => 'assets/frontend/images/why_choose_img.jpg',
            'items' => [
                ['icon' => 'fal fa-tshirt', 'title' => 'quality products', 'description' => 'Objectively pontificate quality models before intuitive information.'],
                ['icon' => 'fal fa-truck', 'title' => 'Fast Delivery', 'description' => 'Objectively pontificate quality models before intuitive information.'],
                ['icon' => 'far fa-undo-alt', 'title' => 'return policy', 'description' => 'Objectively pontificate quality models before intuitive information.'],
                ['icon' => 'fas fa-headset', 'title' => '24/7 Service', 'description' => 'Objectively pontificate quality models before intuitive information.'],
            ]
        ]);
    }
}
