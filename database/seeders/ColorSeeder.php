<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            [
                'name' => 'Merah',
                'slug' => 'merah',
                'hex_code' => 'FF0000',
                'is_active' => true,
            ],
            [
                'name' => 'Hijau',
                'slug' => 'hijau',
                'hex_code' => '00FF00',
                'is_active' => true,
            ],
            [
                'name' => 'Ungu',
                'slug' => 'ungu',
                'hex_code' => '800080',
                'is_active' => true,
            ],
            [
                'name' => 'Kuning',
                'slug' => 'kuning',
                'hex_code' => 'FFFF00',
                'is_active' => true,
            ],
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(['slug' => $color['slug']], $color);
        }
    }
}
