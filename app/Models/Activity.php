<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'action',
        'title',
        'description',
    ];

    // Casts
    protected $casts = [
        'action' => \App\Enums\ActivityActions::class,
    ];
}
