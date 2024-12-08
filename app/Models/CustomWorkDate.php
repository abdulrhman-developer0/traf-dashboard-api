<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomWorkDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_schedule_id',
        'start_date',
        'end_date',
        'times'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'times' => 'collection'
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ServiceSchedule::class);
    }
}