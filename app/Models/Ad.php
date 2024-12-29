<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ad extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * Mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'service_provider_id',
        'ad_price_id',
        'package_id',
        'duration_in_days',
        'total_price',
        'discount',
        'status',
        'notes',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile();
    }

    public function serviceProvider(): BelongsTo
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function adPrice(): BelongsTo
    {
        return $this->belongsTo(AdPrice::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
