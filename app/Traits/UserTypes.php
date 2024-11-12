<?php

namespace App\Traits;

use App\Models\Client;
use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserTypes
{
    
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function serviceProvider(): HasOne
    {
        return $this->hasOne(ServiceProvider::class);
    }
}