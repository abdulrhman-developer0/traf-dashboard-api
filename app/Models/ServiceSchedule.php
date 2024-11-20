<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSchedule extends Model
{
    use HasFactory;

      // Define the fillable fields for mass assignment
      protected $fillable = [
        'partner_service_provider_id',
        'service_id',
        'schedule_pattern',
        'dates',
        'status',
    ];
    

    /**
     * Get the service associated with this schedule.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Get the partner service provider offering this schedule.
     */
    public function partnerServiceProvider()
    {
        return $this->belongsTo(ServiceProvider::class, 'partner_service_provider_id');
    }
}
