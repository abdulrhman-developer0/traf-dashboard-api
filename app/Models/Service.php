<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    /**
     * Get the category that the service belongs to.
     */
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ServiceProvider::class, 'partner_service_provider_id');
    }

    /**
     * Get the partner service provider that owns the service.
     */
    
     public function serviceProviders()
     {
         return $this->belongsToMany(ServiceProvider::class, 'service_provider_pivots');
     }
   
}
