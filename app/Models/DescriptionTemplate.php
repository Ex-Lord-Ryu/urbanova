<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescriptionTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'content',
        'is_active'
    ];

    /**
     * Produk yang menggunakan template deskripsi ini
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'description_template_product');
    }

    /**
     * Mengambil daftar kategori yang tersedia
     */
    public static function getCategories()
    {
        return self::distinct('category')->pluck('category')->filter()->toArray();
    }
}
