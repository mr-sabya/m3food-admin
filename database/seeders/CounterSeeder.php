<?php

namespace Database\Seeders;

use App\Models\Counter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    Counter::create([
        'items' => [
            ['icon' => 'assets/images/counter_icon_1.png', 'number' => '950', 'suffix' => '+', 'title' => 'Happy customers'],
            ['icon' => 'assets/images/counter_icon_2.png', 'number' => '350', 'suffix' => '+', 'title' => 'Expert Team'],
            ['icon' => 'assets/images/counter_icon_3.png', 'number' => '35', 'suffix' => '+', 'title' => 'Award Wining'],
            ['icon' => 'assets/images/counter_icon_4.png', 'number' => '4.9', 'suffix' => '', 'title' => 'Avarage Rating'],
        ]
    ]);
}
}
