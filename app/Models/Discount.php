<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'percentage',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the product that owns the discount
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Advance start date by 1 day if it's set to today - now returns original date
     */
    public static function advanceStartDateIfToday($startDate)
    {
        // Return the original start date without modifying it
        // Previous implementation would advance it by 1 day if it was today
        return $startDate;
    }

    /**
     * Set end date to one day after the provided start date
     */
    public static function setEndDateToNextDay($startDate)
    {
        if (empty($startDate)) {
            return null;
        }

        $startDateCarbon = Carbon::parse($startDate);
        return $startDateCarbon->addDay()->format('Y-m-d');
    }
}
