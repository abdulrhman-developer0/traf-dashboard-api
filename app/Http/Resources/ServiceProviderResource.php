<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user->id,
            'photo'         => $this->getFirstMediaUrl('photo'),
            'name'          => $this->user->name,
            'address'       => $this->address,
            'rating'        => $this->rating,

        ];
    }
}