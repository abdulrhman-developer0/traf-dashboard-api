<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Offer extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'service_id',
        'title',
        'description',
        'type'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('media_file')->singleFile();
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
