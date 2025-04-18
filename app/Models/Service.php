<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

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
        'address',
        'is_home_service',
        'is_offer',
        'is_on_site',
        'deletion_reason',
        'deleted_at',
    ];

    protected $casts = [
        'rating'            => 'float',
        'price_before'      => 'float',
        'price_after'       => 'float',
        'is_home_service'   => 'boolean',
        'is_offer'          => 'boolean',
        'is_on_site'        => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }


    /**
     * Get the count of reviews for the service.
     * 
     * @return int
     */
    protected function getReviewsCountAttribute()
    {
        // for 30 seconds
        return Cache::remember("service-reviews-count-{$this->id}", 30, function () {
            return Review::whereHas('booking.service', fn($q) => $q->where('id', $this->id))->count();
        });
    }

    protected function getRatingStatsAttribute()
    {
        // for 30 seconds
        return Cache::remember("service-rating-stats-{$this->id}", 30, function () {
            $query = Review::whereHas('booking.service', fn($q) => $q->where('id', $this->id));

            return get_rating_stats($query);
        });
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

    public function schedules()
    {
        return $this->hasOne(ServiceSchedule::class);
    }
}
