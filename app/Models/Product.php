<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'image',
        'additional_images',
        'category_id',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'additional_images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all colors for the product regardless of stock
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_variants', 'product_id', 'color_id')
            ->distinct();
    }

    /**
     * Get only colors that have stock available
     */
    public function availableColors()
    {
        return $this->belongsToMany(Color::class, 'product_variants', 'product_id', 'color_id')
            ->whereHas('productVariants', function($query) {
                $query->where('product_id', $this->id)
                      ->where('stock', '>', 0);
            })
            ->distinct();
    }

    /**
     * Get all sizes for the product regardless of stock
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_variants', 'product_id', 'size_id')
            ->distinct();
    }

    /**
     * Get only sizes that have stock available
     */
    public function availableSizes()
    {
        return $this->belongsToMany(Size::class, 'product_variants', 'product_id', 'size_id')
            ->whereHas('productVariants', function($query) {
                $query->where('product_id', $this->id)
                      ->where('stock', '>', 0);
            })
            ->distinct();
    }

    /**
     * Get colors that have stock available for a specific size
     */
    public function availableColorsForSize($sizeId)
    {
        return $this->belongsToMany(Color::class, 'product_variants', 'product_id', 'color_id')
            ->where('product_variants.size_id', $sizeId)
            ->where('product_variants.stock', '>', 0)
            ->distinct();
    }

    /**
     * Get sizes that have stock available for a specific color
     */
    public function availableSizesForColor($colorId)
    {
        return $this->belongsToMany(Size::class, 'product_variants', 'product_id', 'size_id')
            ->where('product_variants.color_id', $colorId)
            ->where('product_variants.stock', '>', 0)
            ->distinct();
    }

    /**
     * Get the variants for the product
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get only variants that have stock available
     */
    public function availableVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('stock', '>', 0);
    }

    /**
     * Get the discounts for the product
     */
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    /**
     * Get the active discount for the product
     */
    public function activeDiscount()
    {
        $now = now();
        return $this->discounts()
            ->where('percentage', '>', 0)
            ->where(function ($query) use ($now) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            })
            ->orderBy('percentage', 'desc')
            ->first();
    }

    /**
     * Check if product has an active discount
     *
     * @return bool Returns true if the product has an active discount, false otherwise
     */
    public function hasActiveDiscount()
    {
        return $this->activeDiscount() !== null;
    }

    /**
     * Get variants with specific size
     */
    public function getVariantsBySize($sizeId)
    {
        return $this->variants()->where('size_id', $sizeId)->get();
    }

    /**
     * Get variants with specific color
     */
    public function getVariantsByColor($colorId)
    {
        return $this->variants()->where('color_id', $colorId)->get();
    }

    /**
     * Get variants with specific size and color
     */
    public function getVariantsBySizeAndColor($sizeId, $colorId)
    {
        return $this->variants()
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->get();
    }

    /**
     * Check if a specific size is in stock for this product
     *
     * @param int $sizeId The size ID to check
     * @return bool Returns true if the size is in stock, false otherwise
     */
    public function isSizeInStock($sizeId)
    {
        $variants = $this->variants()->where('size_id', $sizeId)->get();

        foreach ($variants as $variant) {
            if ($variant->stock > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a specific color is in stock for this product
     *
     * @param int $colorId The color ID to check
     * @return bool Returns true if the color is in stock, false otherwise
     */
    public function isColorInStock($colorId)
    {
        $variants = $this->variants()->where('color_id', $colorId)->get();

        foreach ($variants as $variant) {
            if ($variant->stock > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a specific size and color combination is in stock
     *
     * @param int $sizeId The size ID to check
     * @param int $colorId The color ID to check
     * @return bool Returns true if the combination is in stock, false otherwise
     */
    public function isSizeAndColorInStock($sizeId, $colorId)
    {
        return $this->variants()
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->where('stock', '>', 0)
            ->exists();
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

        // Gabungkan semua template dan bersihkan HTML yang berlebihan
        $fullDescription = $templates->map(function($template) {
            // Bersihkan konten dari multiple BR tags
            $content = trim($template->content);

            // Hapus double BR tags dan white spaces di antara tag
            $content = preg_replace('/<br\s*\/?>\s*<br\s*\/?>/i', '<br>', $content);

            // Hapus spasi berlebihan
            $content = preg_replace('/\s+/', ' ', $content);

            // Perbaiki spacing untuk list items
            $content = str_replace('<li>', '<li> ', $content);

            return $content;
        })->filter()->implode('');

        // Tambahkan deskripsi manual jika ada
        if (!empty($this->description)) {
            $manualDescription = trim($this->description);
            if (!empty($manualDescription)) {
                $fullDescription .= $manualDescription;
            }
        }

        return $fullDescription;
    }

    /**
     * Get the discount percentage for this product
     */
    public function getDiscountPercentageAttribute()
    {
        // Get active discount if available
        $activeDiscount = $this->activeDiscount();

        if ($activeDiscount) {
            return $activeDiscount->percentage;
        }

        // If no active discount, check for future discounts
        $futureDiscount = $this->discounts()
            ->where('percentage', '>', 0)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->first();

        return $futureDiscount ? $futureDiscount->percentage : 0;
    }

    /**
     * Get the minimum price across all variants after applying discount
     */
    public function getDiscountedPriceAttribute()
    {
        $minVariant = $this->variants()->orderBy('price', 'asc')->first();

        if (!$minVariant) {
            return 0;
        }

        // Get the discount percentage which now handles both active and future discounts
        $discountPercentage = $this->discount_percentage;

        if ($discountPercentage > 0) {
            return $minVariant->price * (1 - ($discountPercentage / 100));
        }

        return $minVariant->price;
    }

    /**
     * Get base price (minimum price across all variants)
     */
    public function getPriceAttribute()
    {
        $minVariant = $this->variants()->orderBy('price', 'asc')->first();
        return $minVariant ? $minVariant->price : 0;
    }

    /**
     * Get total stock available for a specific size
     *
     * @param int $sizeId The size ID to check
     * @return int Returns the total stock available for this size
     */
    public function getStockForSize($sizeId)
    {
        $variants = $this->variants()->where('size_id', $sizeId)->get();
        return $variants->sum('stock');
    }

    /**
     * Get total stock available for a specific color
     *
     * @param int $colorId The color ID to check
     * @return int Returns the total stock available for this color
     */
    public function getStockForColor($colorId)
    {
        $variants = $this->variants()->where('color_id', $colorId)->get();
        return $variants->sum('stock');
    }

    /**
     * Check if this product has different prices for different sizes
     *
     * @return bool Whether the product has size-specific prices
     */
    public function getHasSizePricesAttribute()
    {
        // Check if there are different prices for different sizes
        $variantPrices = $this->variants()
            ->select('size_id', 'price')
            ->get()
            ->groupBy('size_id');

        // If there's only one size, obviously can't have different prices per size
        if ($variantPrices->count() <= 1) {
            return false;
        }

        // Check if prices are different across sizes
        $prices = [];
        foreach ($variantPrices as $sizeId => $variants) {
            $minPrice = $variants->min('price');
            $prices[$sizeId] = $minPrice;
        }

        // If prices are all the same, we don't consider it to have size-specific prices
        $uniquePrices = array_unique($prices);
        return count($uniquePrices) > 1;
    }

    /**
     * Get the price for each size
     *
     * @return array An associative array with size name as key and price as value
     */
    public function getSizePricesAttribute()
    {
        $result = [];
        $sizes = $this->sizes()->get();

        foreach ($sizes as $size) {
            $variants = $this->variants()->where('size_id', $size->id)->get();

            if ($variants->count() > 0) {
                $minPrice = $variants->min('price');
                $result[$size->name] = $minPrice;
            }
        }

        return $result;
    }

    /**
     * Get variant price for a specific size
     *
     * @param int $sizeId The size ID to check
     * @return float Returns the price for this size
     */
    public function getVariantPrice($sizeId)
    {
        $variants = $this->variants()->where('size_id', $sizeId)->get();

        if ($variants->isEmpty()) {
            return $this->price; // Fall back to base price
        }

        return $variants->min('price');
    }

    /**
     * Get variant price for a specific size and color combination
     *
     * @param int $sizeId The size ID to check
     * @param int $colorId The color ID to check
     * @return float Returns the price for this size and color combination
     */
    public function getVariantPriceWithColor($sizeId, $colorId)
    {
        $variant = $this->variants()
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->where('stock', '>', 0)
            ->first();

        if (!$variant) {
            // Fall back to price for this size only
            return $this->getVariantPrice($sizeId);
        }

        return $variant->price;
    }
}
