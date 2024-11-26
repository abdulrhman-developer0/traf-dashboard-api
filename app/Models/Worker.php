<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Worker extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'phone', 'address'];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('photo')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('worker');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_workers', 'worker_id', 'service_id');
    }
}
