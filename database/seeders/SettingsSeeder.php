<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'show_featured_badge',
                'value' => '1',
                'group' => 'shop',
                'type' => 'boolean',
                'label' => 'Show Featured Badge',
                'description' => 'Show or hide the "Unggulan" badge on featured products in the shop page',
            ],
            [
                'key' => 'site_name',
                'value' => 'Urbanova',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Site Name',
                'description' => 'The name of your site',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
