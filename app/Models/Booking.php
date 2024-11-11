<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_schedule_id',
        'status',
    ];

    /**
     * Get the service schedule associated with this booking.
     */
    public function serviceSchedule()
    {
        return $this->belongsTo(ServiceSchedule::class, 'service_schedule_id');
    }

    /**
     * Get the client associated with this booking.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
