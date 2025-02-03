<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    protected $fillable = [
        'longitude',
        'latitude'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
