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
            'partner_service_provider_id'       => $this->partner_service_provider_id,
            'name'                              => $this->name,
            'duration'                          => $this->duration,
            'description'                       => $this->description,
            'rating'                            => $this->rating,
            'price_before'                      => $this->price_before,
            'is_offer'                          => (bool) $this->is_offer,
            'created_at'                        => $this->created_at->diffForHumans(),
            'updated_at'                        => $this->updated_at->diffForHumans(),
        ];
    }
}
