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
        'name',
        'slug',
        'sku',
        'price',
        'has_size_prices',
        'base_price',
        'price_increase',
        'size_prices',
        'stock',
        'description',
        'image',
        'additional_images',
        'category_id',
        'is_featured',
        'is_active',
        'discount_percentage',
        'discount_start_date',
        'discount_end_date'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'base_price' => 'decimal:2',
        'price_increase' => 'decimal:2',
        'size_prices' => 'array',
        'additional_images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'has_size_prices' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'discount_start_date' => 'datetime',
        'discount_end_date' => 'datetime'
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
     * Get the size stocks for the product
     */
    public function sizeStocks()
    {
        return $this->hasMany(ProductSizeStock::class);
    }

    /**
     * Check if a specific size is in stock
     */
    public function isSizeInStock($sizeId)
    {
        $stock = $this->sizeStocks()->where('size_id', $sizeId)->first();

        // Jika tidak ada stok spesifik untuk ukuran ini, gunakan stok global
        if (!$stock) {
            return $this->stock > 0;
        }

        return $stock->stock > 0;
    }

    /**
     * Get stock for a specific size
     */
    public function getStockForSize($sizeId)
    {
        $stock = $this->sizeStocks()->where('size_id', $sizeId)->first();

        // Jika tidak ada stok spesifik untuk ukuran ini, gunakan stok global
        if (!$stock) {
            return $this->stock;
        }

        return $stock->stock;
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

        // Gabungkan semua template dengan pemisah yang tepat
        $fullDescription = $templates->map(function($template) {
            // Bersihkan konten dari multiple line breaks dan whitespace
            $content = preg_replace('/\s+/', ' ', $template->content);
            $content = preg_replace('/<br\s*\/?>\s*<br\s*\/?>/i', '<br>', $content);
            return trim($content);
        })->filter()->implode('<br>');

        // Tambahkan deskripsi manual jika ada
        if (!empty($this->description)) {
            $manualDescription = trim($this->description);
            if (!empty($manualDescription)) {
                $fullDescription .= '<br>' . $manualDescription;
            }
        }

        return $fullDescription;
    }

    /**
     * Check if a product currently has an active discount
     */
    public function hasActiveDiscount()
    {
        if (empty($this->discount_percentage) || $this->discount_percentage <= 0) {
            return false;
        }

        $now = now();

        // If no start date is set, assume it's already started
        $started = !$this->discount_start_date || $now >= $this->discount_start_date;

        // If no end date is set, assume it's not ended
        $notEnded = !$this->discount_end_date || $now <= $this->discount_end_date;

        return $started && $notEnded;
    }

    /**
     * Get the discounted price of the product
     */
    public function getDiscountedPriceAttribute()
    {
        if (!$this->hasActiveDiscount()) {
            return $this->price;
        }

        $discountAmount = ($this->price * $this->discount_percentage) / 100;
        return round($this->price - $discountAmount, 2);
    }

    /**
     * Get the discount amount
     */
    public function getDiscountAmountAttribute()
    {
        if (!$this->hasActiveDiscount()) {
            return 0;
        }

        return round(($this->price * $this->discount_percentage) / 100, 2);
    }

    /**
     * Get the discounted price for a specific size
     */
    public function getDiscountedSizePriceAttribute($size)
    {
        if (!$this->hasActiveDiscount() || !$this->has_size_prices) {
            return $this->getSizePriceAttribute($size);
        }

        $sizePrice = $this->getSizePriceAttribute($size);
        $discountAmount = ($sizePrice * $this->discount_percentage) / 100;
        return round($sizePrice - $discountAmount, 2);
    }

    /**
     * Helper to get price for a specific size
     */
    public function getSizePriceAttribute($size)
    {
        if (!$this->has_size_prices || !isset($this->size_prices[$size])) {
            return $this->price;
        }

        return $this->size_prices[$size];
    }
}
