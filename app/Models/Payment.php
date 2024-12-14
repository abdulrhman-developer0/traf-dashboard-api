<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'status',
        'payment_status',
        'payment_id',
        'transaction_reference',
        'start_date',
        'end_date',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
