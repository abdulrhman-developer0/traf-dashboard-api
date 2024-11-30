<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'service_id'            => $this->service_id,
            'service_name'          => optional($this->service)->name,
            'service_description'   => optional($this->service)->description,
            'service_price'         => $this->service->is_offer
                ? $this->service->price_after
                : $this->service->price_before,

            'title'                 => $this->title,
            'description'           => $this->description,
            'type'                  => $this->type,
            'media_url'             => $this->getFirstMediaUrl('media_file'),
            'created_at'            => $this->created_at?->format('m/d/Y h:i A'),
            'updated_at'            => $this->updated_at?->format('m/d/Y h:i A'),
        ];
    }
}
