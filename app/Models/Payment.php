<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_status',
        'payment_id',
        'transaction_reference',
       
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
