<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class adResource extends JsonResource
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
            'url'                 => $this->getFirstMediaUrl('photo'),
            'duration_in_days'      => $this->duration_in_days,
            'total_price'           => $this->total_price,
            'discount'              => $this->discount,
            'status'                => $this->status,
            'notes'                 => $this->notes,
            'start_date'            => $this->start_date?->format('Y-m-d'),
            'end_date'            => $this->end_date?->format('Y-m-d'),
            'created_at'            => $this->created_at->format('Y-m-d')
        ];
    }
}
