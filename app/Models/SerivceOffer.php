<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerivceOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'discount_percentage',
        'description',
        'price_after',
        'start_at',
        'end_at',
    ];

    /**
     * Get the service associated with this offer.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
