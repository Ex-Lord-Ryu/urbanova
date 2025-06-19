<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Get the product that owns the variant
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the size that belongs to the variant
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * Get the color that belongs to the variant
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Check if this variant is in stock
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Scope a query to only include variants that are in stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope a query to only include variants for a specific product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope a query to only include variants with a specific size
     */
    public function scopeWithSize($query, $sizeId)
    {
        return $query->where('size_id', $sizeId);
    }

    /**
     * Scope a query to only include variants with a specific color
     */
    public function scopeWithColor($query, $colorId)
    {
        return $query->where('color_id', $colorId);
    }

    /**
     * Get variants for a specific product and size
     */
    public static function getVariantsBySize($productId, $sizeId)
    {
        return self::where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->get();
    }

    /**
     * Get in-stock variants for a specific product and size
     */
    public static function getInStockVariantsBySize($productId, $sizeId)
    {
        return self::where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->where('stock', '>', 0)
            ->get();
    }

    /**
     * Get variants for a specific product and color
     */
    public static function getVariantsByColor($productId, $colorId)
    {
        return self::where('product_id', $productId)
            ->where('color_id', $colorId)
            ->get();
    }

    /**
     * Get in-stock variants for a specific product and color
     */
    public static function getInStockVariantsByColor($productId, $colorId)
    {
        return self::where('product_id', $productId)
            ->where('color_id', $colorId)
            ->where('stock', '>', 0)
            ->get();
    }

    /**
     * Get variants for a specific product, size and color
     */
    public static function getVariantsBySizeAndColor($productId, $sizeId, $colorId)
    {
        return self::where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->get();
    }

    /**
     * Get in-stock variants for a specific product, size and color
     */
    public static function getInStockVariantsBySizeAndColor($productId, $sizeId, $colorId)
    {
        return self::where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->where('stock', '>', 0)
            ->get();
    }

    /**
     * Get variants for a specific product grouped by size
     */
    public static function getVariantsGroupedBySize($productId)
    {
        return self::where('product_id', $productId)
            ->get()
            ->groupBy('size_id');
    }

    /**
     * Get in-stock variants for a specific product grouped by size
     */
    public static function getInStockVariantsGroupedBySize($productId)
    {
        return self::where('product_id', $productId)
            ->where('stock', '>', 0)
            ->get()
            ->groupBy('size_id');
    }

    /**
     * Get variants for a specific product grouped by color
     */
    public static function getVariantsGroupedByColor($productId)
    {
        return self::where('product_id', $productId)
            ->get()
            ->groupBy('color_id');
    }

    /**
     * Get in-stock variants for a specific product grouped by color
     */
    public static function getInStockVariantsGroupedByColor($productId)
    {
        return self::where('product_id', $productId)
            ->where('stock', '>', 0)
            ->get()
            ->groupBy('color_id');
    }

    /**
     * Get discounted price based on the product's active discount
     */
    public function getDiscountedPriceAttribute()
    {
        $product = $this->product;
        $discountPercentage = $product->discount_percentage;

        if ($discountPercentage > 0) {
            return $this->price * (1 - ($discountPercentage / 100));
        }

        return $this->price;
    }
}
