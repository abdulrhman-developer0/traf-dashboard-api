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
            'name'          => $this->user->name,
            'email'         => $this->user->email,
            'phone'         => $this->phone,
            'address'       => $this->address,
            'created_at'    => $this->created_at->format('Y-m-d'),
        ];
    }
}
