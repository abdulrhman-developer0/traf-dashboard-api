<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Cache;

class ServiceProvider extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'is_personal',
        'tax_registeration_number',
        'job',
        'phone',
        'address',
        'rating',
        'area',
        'city',
    ];

    protected $casts = [
        'is_personal'  => 'boolean',
        'rating' => 'float'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();

        $this->addMediaCollection('maroof_document')->singleFile();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get the count of reviews for the service provider.
     * 
     * @return int
     */
    protected function getReviewsCountAttribute()
    {
        // Cache for 30 seconds
        return Cache::remember("service-provider-reviews-count-{$this->id}", 30, function () {
            return $this->reviews()->count();
        });
    }

    /**
     * Get rating statistics for the service provider.
     * 
     * @return array
     */
    protected function getRatingStatsAttribute()
    {
        // Cache for 30 seconds
        return Cache::remember("service-provider-rating-stats-{$this->id}", 30, function () {
            return get_rating_stats($this->reviews());
        });
    }
}
