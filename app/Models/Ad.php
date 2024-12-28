<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ad extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'ad_price_id',
        'duration_in_days',
        'total_price',
        'status',
        'start_date',
        'end_date',
    ];

    /**
     * Attribute casting for automatic type conversion.
     *
     * @var array
     */
    protected $casts = [
        'duration_in_days' => 'integer',
        'total_price' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $attributes = [
        'status'  => 'under-review'
    ];

    /**
     * Allowed statuses for the ad.
     *
     * @var array
     */
    public const STATUSES = [
        'under-review',
        'rejected',
        'pending-payment',
        'approved',
        'waiting',
    ];

    public function adPrice(): BelongsTo
    {
        return $this->belongsTo(AdPrice::class);
    }
}
