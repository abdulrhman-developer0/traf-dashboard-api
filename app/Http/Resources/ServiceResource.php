<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'                                => $this->id,
            'service_category_id'               => $this->service_category_id,
            'category_name'                     => $this->category?->name,
            'service_provider_id'               => $this->service_provider_id,
            'user_id'                           => $this->serviceProvider?->user?->id,
            'provider_photo'                    => $this->serviceProvider?->getFirstMediaUrl('photo'),
            'provider_name'                     => $this->serviceProvider?->user?->name,
            'name'                              => $this->name,
            'photo'                             => $this->getFirstMediaUrl('photo'),
            'duration'                          => $this->duration,
            'description'                       => $this->description,
            'price_before'                      => $this->price_before,
            'price_after'                       => $this->price_after,
            'address'                           => $this->address,
            'is_home_service'                   => $this->is_home_service,
            'is_offer'                          => $this->is_offer,
            'is_personal'                       => $this->serviceProvider->is_personal,
            'is_favorite'                       => $this->is_favorite ? true : false,
            'created_at'                        => $this->created_at?->diffForHumans(),
            'updated_at'                        => $this->updated_at?->diffForHumans(),
            'rating'                            => $this->rating,
            'reviews_count'                     => $this->reviews_count ?? 0,
            'rating_stats'                      =>  $this->rating_stats, // ['excellent' => 40, 'good' => 30, 'average' => 20, 'poor' => 10, 'terrible' => 0]
            'provider_schedule'                         => ServiceScheduleResource::make($this->schedules),
            'workers'                           => WorkerResource::collection($this->workers),
        ];
    }
}
