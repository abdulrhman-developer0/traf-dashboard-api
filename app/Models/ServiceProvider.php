<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceProvider extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'is_personal',
        'tax_registeration_number',
        'city_id',
        'years_of_experience',
        'phone',
        'address',
        'rating',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile()
            ->useFallbackUrl(
                asset('/logos/person-244.svg')
            );

        $this->addMediaCollection('maroof_document')->singleFile();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function Servicess()
    {
        return $this->belongsToMany(Service::class, 'service_provider_pivots');
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }
}
