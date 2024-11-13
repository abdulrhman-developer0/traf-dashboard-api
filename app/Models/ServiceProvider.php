<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceProvider extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'years_of_experience',
        'phone',
        'address',
        'rating',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function syncPartners(array $partnerServiceProviderIds)
    {
        return $this->serviceProviderPartners()->sync([$this->id, ...$partnerServiceProviderIds]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceProviderPartners()
    {
        return $this->hasMany(ServiceProviderPartner::class);
    }

    public function serviceProviders()
    {
        return $this->belongsToMany(ServiceProvider::class, 'service_provider_partners', 'service_provider_id', 'partner_service_provider_id');
    }
}
