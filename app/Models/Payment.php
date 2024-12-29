<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public const PAYMENT_STATUSES = [
        'pending',
        'paid',
        'failed',
        'refund'
    ];

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_status',
        'payment_id',
        'transaction_reference',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
