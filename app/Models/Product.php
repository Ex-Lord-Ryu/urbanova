<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'stock',
        'image',
        'additional_images',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'additional_images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the colors for the product
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'product_color');
    }

    /**
     * Get the sizes for the product
     */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    /**
     * Get the legacy colors array (for backward compatibility)
     */
    public function getColorsAttribute()
    {
        return $this->colors()->select('colors.name')->pluck('name')->toArray();
    }

    /**
     * Get the legacy sizes array (for backward compatibility)
     */
    public function getSizesAttribute()
    {
        return $this->sizes()->select('sizes.name')->pluck('name')->toArray();
    }

    /**
     * Daftar template deskripsi yang digunakan oleh produk ini
     */
    public function descriptionTemplates()
    {
        return $this->belongsToMany(DescriptionTemplate::class, 'description_template_product');
    }

    /**
     * Mendapatkan deskripsi lengkap dengan menggabungkan semua template yang dipilih
     */
    public function getFullDescriptionAttribute()
    {
        $templates = $this->descriptionTemplates;

        if ($templates->isEmpty()) {
            return $this->description ?? '';
        }

        // Gabungkan semua template dan tambahkan deskripsi manual
        $fullDescription = $templates->pluck('content')->implode("\n\n");

        // Tambahkan deskripsi manual jika ada
        if (!empty($this->description)) {
            $fullDescription .= "\n\n" . $this->description;
        }

        return $fullDescription;
    }
}
