<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'reference_id',
        'date',
        'status'
    ];

    protected $casts = [
        'date'  => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function payments()
    {
        return $this->hasOne(Payment::class);
    }
}
