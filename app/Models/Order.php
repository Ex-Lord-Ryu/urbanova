<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'full_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'payment_proof',
        'tracking_number',
        'courier_name',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the formatted order status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->order_status) {
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'processing' => '<span class="badge badge-info">Processing</span>',
            'shipped' => '<span class="badge badge-primary">Shipped</span>',
            'delivered' => '<span class="badge badge-success">Delivered</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }

    /**
     * Get the formatted payment status.
     */
    public function getPaymentBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'paid' => '<span class="badge badge-success">Paid</span>',
            'failed' => '<span class="badge badge-danger">Failed</span>',
            'refunded' => '<span class="badge badge-info">Refunded</span>',
            'verification' => '<span class="badge badge-primary">Verifikasi</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }
}
