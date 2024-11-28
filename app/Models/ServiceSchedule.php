<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSchedule extends Model
{
    use HasFactory, SoftDeletes;

      protected $fillable = [
        'reference_id',
        'service_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date'    => 'datetime',
        'end_date'      => 'datetime',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(ServiceWorker::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function excludedDates(): HasMany
    {
        return $this->hasMany(ScheduleExcludedDate::class);
    }

    public function workTimes(): HasMany
    {
        return $this->hasMany(ScheduleWorkTime::class);
    }
}
