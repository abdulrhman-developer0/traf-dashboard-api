<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdPrice extends Model
{
    protected $fillable = [
        'price'
    ];

    protected $casts = [
        'price' => 'float'
    ];

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }
}
