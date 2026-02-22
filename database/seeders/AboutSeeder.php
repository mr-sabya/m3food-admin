<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\About;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        About::truncate();

        About::create([
            'sub_title'       => 'About Company',
            'title'           => 'Well-coordinated Teamwork Speaks About Us',
            'description'     => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cupiditate aspernatur molestiae minima pariatur consequatur voluptate sapiente deleniti soluta.',
            'image'           => 'assets/frontend/images/about_img.jpg',
            'experience_year' => '12',
            'experience_text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate officiis architecto reiciendis.',
            'author_name'     => 'jhon deo',
            'features'        => [
                [
                    'title' => 'trusted partner',
                    'description' => 'Lorem Ipsum Dolor Sit Amet Consectetur, Adipisicing Elit. Minus, Officiis Placeat Iusto Quasi Adipisci Impedit Delectus Beatae Ab Maxime.'
                ],
                [
                    'title' => 'quality products',
                    'description' => 'Lorem Ipsum Dolor Sit Amet Consectetur, Adipisicing Elit. Minus, Officiis Placeat Iusto Quasi Adipisci Impedit Delectus Beatae Ab Maxime.'
                ],
                [
                    'title' => 'first Delivery',
                    'description' => 'Lorem Ipsum Dolor Sit Amet Consectetur, Adipisicing Elit. Minus, Officiis Placeat Iusto Quasi Adipisci Impedit Delectus Beatae Ab Maxime.'
                ],
                [
                    'title' => 'secure payment',
                    'description' => 'Lorem Ipsum Dolor Sit Amet Consectetur, Adipisicing Elit. Minus, Officiis Placeat Iusto Quasi Adipisci Impedit Delectus Beatae Ab Maxime.'
                ],
            ]
        ]);
    }
}
