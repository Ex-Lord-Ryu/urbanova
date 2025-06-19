<?php

namespace Database\Seeders;

use App\Models\DescriptionTemplate;
use Illuminate\Database\Seeder;

class DescriptionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'T-Shirt Template',
                'category' => 'Clothing',
                'content' => "Deskripsi Produk –  [products name]

🌿 Bahan: Katun combed 100%, adem dan menyerap keringat

👕 Desain: Lengan pendek dengan potongan regular fit, cocok untuk pria dan wanita

🎨 Pilihan Warna: Tersedia dalam hitam, putih, navy, abu, dan marun

🧵 Detail: Jahitan rapi, leher model crew neck, nyaman untuk penggunaan harian

📏 Ukuran: S, M, L, XL (tersedia panduan ukuran)

🧺 Perawatan: Mudah dicuci, tidak mudah melar atau luntur

🧑‍🤝‍🧑 Cocok Untuk: Kegiatan santai, kerja casual, kuliah, atau jalan-jalan",
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            DescriptionTemplate::updateOrCreate(['name' => $template['name']], $template);
        }
    }
}
