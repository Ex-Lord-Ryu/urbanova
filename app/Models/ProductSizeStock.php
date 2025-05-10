<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizeStock extends Model
{
    use HasFactory;

    protected $table = 'product_size_stock';

    protected $fillable = [
        'product_id',
        'size_id',
        'stock',
    ];

    /**
     * Get the product that owns the stock
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the size that owns the stock
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
