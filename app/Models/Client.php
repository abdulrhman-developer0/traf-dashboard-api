<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        return $this->belongsTo(User::class, 'user_id', 'id');
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

    /**
     * Get booking statistics for the client.
     * 
     * @return array
     */
    protected function getBookingStatsAttribute()
    {
        $threeMonthsAgo = now()->subMonths(3);

        $statuses = DB::table('bookings')
            ->where('client_id', $this->id)
            ->where('date', '>=', $threeMonthsAgo)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->mapWithKeys(function ($count, $status) {
                return [strtolower($status) => (int) $count];
            })
            ->toArray();

        // Ensure only existing statuses in the enum are included
        $enumStatuses = ['pending', 'canceled', 'confirmed', 'done'];
        $statuses = array_intersect_key($statuses, array_flip($enumStatuses));

        // Add missing statuses with count 0
        foreach ($enumStatuses as $status) {
            if (!array_key_exists($status, $statuses)) {
                $statuses[$status] = 0;
            }
        }

        // Add total count
        $statuses['total'] = array_sum($statuses);

        return $statuses;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
