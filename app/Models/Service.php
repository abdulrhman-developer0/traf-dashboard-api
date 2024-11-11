<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
      // Define the fillable fields for mass assignment
      protected $fillable = [
        'service_category_id',
        'partner_service_provider_id',
        'name',
        'duration',
        'description',
        'rating',
        'price_before',
        'is_offer',
    ];

    /**
     * Get the category that the service belongs to.
     */
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Get the partner service provider that owns the service.
     */
    public function partnerServiceProvider()
    {
        return $this->belongsTo(ServiceProviderPartner::class);
    }
   
}
