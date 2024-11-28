<?php

namespace App\Traits;

use App\Models\Client;
use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserTypes
{
    /**
     * Used to define available account types with models
     * 
     * @static
     * @return array the acount types with models ['admin' => Admin::clas, ...]
     */
    public static function acountTypes(): array
    {
        return [];
    }
    
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function serviceProvider(): HasOne
    {
        return $this->hasOne(ServiceProvider::class);
    }
}