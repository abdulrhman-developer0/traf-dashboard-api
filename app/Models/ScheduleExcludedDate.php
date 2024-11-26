<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleExcludedDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_schedule_id',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];
}
