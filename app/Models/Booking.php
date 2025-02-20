<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Booking extends Model
{
    use HasFactory;

    public const SATUSES = [
        'pending',
        'canceled',
        'confirmed',
        'done'
    ];
    public const PAYMENT_SATUSES = [
        'pending',
        'fully_captured',
        'fully_refunded',
        'canceled',
        'failed'
    ];

    protected $fillable = [
        'client_id',
        'service_id',
        'reference_id',
        'date',
        'address',
        'status',
        'canceled_at',
        'changed_at',
        'longitude',
        'latitude',
        'payment_method',
        'payment_status',
        'reference_payment',
        'payment_amount',
    ];

    protected $casts = [
        'date'          => 'datetime',
        'longitude'     => 'float',
        'latitude'      => 'float',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'reference_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function payments()
    {
        return $this->hasOne(Payment::class);
    }
}
