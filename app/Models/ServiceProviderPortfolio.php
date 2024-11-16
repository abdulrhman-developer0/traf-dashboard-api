<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderPortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider_id',  // Uncomment if you're using service_provider_id
        'website_url',
        'description',
    ];

    /**
     * Get the service provider associated with this portfolio.
     */
    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id');
    }
}
