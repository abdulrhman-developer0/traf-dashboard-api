<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider_id',
        'package_id',
        'start_date',
        'end_date',
        'status', // active, pending, expired
        'payment_id',
        'payment_status', // pending, paid, failed
        'transaction_reference'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
