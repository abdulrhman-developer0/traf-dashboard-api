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
        'service_provider_id',
        'name',
        'duration',
        'description',
        'rating',
        'price_before',
        'price_after',
        'is_offer',
    ];

    protected $casts = [
        'rating' => 'float'
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

    public function serviceProvider(): BelongsTo
    {
        return $this->belongsTo(ServiceProvider::class);
    }

     
     public function clientFavorites()
     {
        return $this->belongsToMany(Client::class, 'favorits');
     }
     
     public function workers()
     {
         return $this->belongsToMany(Worker::class, 'service_workers', 'service_id', 'worker_id');
     }
     
}
