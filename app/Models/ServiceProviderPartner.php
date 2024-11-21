<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider_id',
        'partner_service_provider_id',
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id');
    }

    public function partnerServiceProvider()
    {
        return $this->belongsTo(ServiceProvider::class, 'partner_service_provider_id');
    }
    public function services()
{
    return $this->belongsToMany(Service::class, 'partner_service_pivots', 'partner_service_provider_id', 'service_id');
}
}
