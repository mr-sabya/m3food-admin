<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id' => 1,
                'key' => 'website_name',
                'label' => null,
                'value' => 'PSK Outfit',
                'type' => 'string',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:16:39',
                'updated_at' => '2025-12-06 23:16:39',
            ],
            [
                'id' => 2,
                'key' => 'website_tag',
                'label' => null,
                'value' => 'Buy your favorite outfit',
                'type' => 'string',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:17:18',
                'updated_at' => '2025-12-06 23:17:18',
            ],
            [
                'id' => 3,
                'key' => 'logo',
                'label' => null,
                'value' => 'settings/gAWvoTGhTFwNI9Zk8xcpmz8lso9pdvnlYDfb1sk5.png',
                'type' => 'image',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:18:12',
                'updated_at' => '2025-12-06 23:18:12',
            ],
            [
                'id' => 4,
                'key' => 'white_logo',
                'label' => null,
                'value' => 'settings/z429wDv9qESAn95EswLejhoD7hOeV5c7OS6tpYAb.png',
                'type' => 'image',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:20:18',
                'updated_at' => '2025-12-06 23:20:18',
            ],
            [
                'id' => 5,
                'key' => 'favicon',
                'label' => null,
                'value' => 'settings/WHWRcwUmTbUIoFyneJ41PiGkeZp5FPofQIGspSJD.png',
                'type' => 'image',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:20:52',
                'updated_at' => '2025-12-06 23:20:52',
            ],
            [
                'id' => 6,
                'key' => 'phone',
                'label' => null,
                'value' => '+8801929190241',
                'type' => 'string',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:21:35',
                'updated_at' => '2025-12-06 23:21:35',
            ],
            [
                'id' => 7,
                'key' => 'address',
                'label' => null,
                'value' => '37 W 24th St, New York, NY',
                'type' => 'string',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:22:10',
                'updated_at' => '2025-12-06 23:22:10',
            ],
            [
                'id' => 8,
                'key' => 'email',
                'label' => null,
                'value' => 'support@mail.com',
                'type' => 'email',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:22:42',
                'updated_at' => '2025-12-06 23:22:42',
            ],
            [
                'id' => 9,
                'key' => 'copyright',
                'label' => null,
                'value' => 'Copyright @ Zenis 2025. All right reserved.',
                'type' => 'string',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:23:22',
                'updated_at' => '2025-12-06 23:23:22',
            ],
            [
                'id' => 10,
                'key' => 'footer_about',
                'label' => null,
                'value' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, distinctio molestiae error ullam obcaecati dolorem inventore.',
                'type' => 'text',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:23:58',
                'updated_at' => '2025-12-06 23:23:58',
            ],
            [
                'id' => 11,
                'key' => 'footer_contact_text',
                'label' => null,
                'value' => 'It is a long established fact that reader distracted looking layout It is a long established fact.',
                'type' => 'text',
                'description' => '',
                'group' => 'General',
                'is_private' => 0,
                'created_at' => '2025-12-06 23:26:04',
                'updated_at' => '2025-12-06 23:26:04',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']], // Check for unique key
                $setting // Insert or Update data
            );
        }
    }
}
