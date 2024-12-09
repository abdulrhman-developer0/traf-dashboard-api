<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Cache;

class Client extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'rating',
        'area',
        'city',
    ];

    protected $casts = [
        'rating' => 'float'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritServices(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'favorits');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get the count of reviews for the client.
     * 
     * @return int
     */
    protected function getReviewsCountAttribute()
    {
        // Cache for 30 seconds
        return Cache::remember("client-reviews-count-{$this->id}", 30, function () {
            return $this->reviews()->count();
        });
    }

    /**
     * Get rating statistics for the client.
     * 
     * @return array
     */
    protected function getRatingStatsAttribute()
    {
        // Cache for 30 seconds
        return Cache::remember("client-rating-stats-{$this->id}", 30, function () {
            return get_rating_stats($this->reviews());
        });
    }
}
