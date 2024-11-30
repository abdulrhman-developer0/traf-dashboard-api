<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleExcludedDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_schedule_id',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date'    => 'datetime',
        'end_date'      => 'datetime',
    ];}
