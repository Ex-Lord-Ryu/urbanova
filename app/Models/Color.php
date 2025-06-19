<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'hex_code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the products that use this color.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_color');
    }

    /**
     * Create a slug from the name.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($color) {
            if (empty($color->slug)) {
                $color->slug = strtolower(str_replace(' ', '_', $color->name));
            } else {
                $color->slug = strtolower(str_replace(' ', '_', $color->slug));
            }
        });

        static::updating(function ($color) {
            if ($color->isDirty('slug')) {
                $color->slug = strtolower(str_replace(' ', '_', $color->slug));
            }
            if ($color->isDirty('name') && empty($color->slug)) {
                $color->slug = strtolower(str_replace(' ', '_', $color->name));
            }
        });
    }

    /**
     * Set the color's name to uppercase.
     */
    protected function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    /**
     * Set the color's slug.
     */
    protected function setSlugAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['slug'] = strtolower(str_replace(' ', '_', $value));
        }
    }

        /**
     * Get the product variants that use this size.
     */
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

}
