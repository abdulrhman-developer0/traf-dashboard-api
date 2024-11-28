<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleWorkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_schedule_id',
        'time'
    ];

    protected $casts = [
        'time' => 'datetime'
    ];
}
