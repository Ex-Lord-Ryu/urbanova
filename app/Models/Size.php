<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Set the size's name to uppercase.
     */
    protected function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    /**
     * Get the products that use this size.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_size');
    }

    /**
     * Get the product variants that use this size.
     */
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Create a slug from the name.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($size) {
            if (empty($size->slug)) {
                $size->slug = strtolower(str_replace(' ', '_', $size->name));
            } else {
                $size->slug = strtolower(str_replace(' ', '_', $size->slug));
            }
        });

        static::updating(function ($size) {
            if ($size->isDirty('slug')) {
                $size->slug = strtolower(str_replace(' ', '_', $size->slug));
            }
            if ($size->isDirty('name') && empty($size->slug)) {
                $size->slug = strtolower(str_replace(' ', '_', $size->name));
            }
        });
    }

    /**
     * Set the size's slug.
     */
    protected function setSlugAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['slug'] = strtolower(str_replace(' ', '_', $value));
        }
    }
}
