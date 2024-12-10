<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomWorkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_work_date_id',
        'start_date',
        'end_date',
        'time',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'time' => 'datetime',
    ];

    public function customWorkDate(): BelongsTo
    {
        return $this->belongsTo(CustomWorkDate::class);
    }
}
