<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Urban Pove',
                'slug' => 'urban_pove',
                'description' => 'Urban Pove collection',
                'is_active' => true,
            ],
            [
                'name' => 'Urban Styling',
                'slug' => 'urban_styling',
                'description' => 'Urban Styling collection',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
