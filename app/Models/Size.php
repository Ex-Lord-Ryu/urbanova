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
     * Create a slug from the name.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($size) {
            if (empty($size->slug)) {
                $size->slug = Str::slug($size->name);
            }
        });
    }
}
