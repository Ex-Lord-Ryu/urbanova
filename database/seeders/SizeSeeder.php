<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            [
                'name' => 'XS',
                'slug' => 'xs',
                'is_active' => true,
            ],
            [
                'name' => 'S',
                'slug' => 's',
                'is_active' => true,
            ],
            [
                'name' => 'M',
                'slug' => 'm',
                'is_active' => true,
            ],
            [
                'name' => 'L',
                'slug' => 'l',
                'is_active' => true,
            ],
            [
                'name' => 'XL',
                'slug' => 'xl',
                'is_active' => true,
            ],
            [
                'name' => 'XXL',
                'slug' => 'xxl',
                'is_active' => true,
            ],
        ];

        foreach ($sizes as $size) {
            Size::updateOrCreate(['slug' => $size['slug']], $size);
        }
    }
}
