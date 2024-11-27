<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'service_provider_id'    => $this->service_provider_id,
            'service_provider_name'  => $this->serviceProvider->user->name,
            'name'                   => $this->name,
            'phone'                  => $this->phone,
            'address'                => $this->address,
            'photo'                  => $this->getFirstMediaUrl('photo'),
            'created_at'             => $this->created_at?->diffForHumans(),
            'updated_at'             => $this->updated_at?->diffForHumans(),
        ];
    }
}
