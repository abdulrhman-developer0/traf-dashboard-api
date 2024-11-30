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
        $mediaUrl = null;

        if ($this->type === 'poster') {
            $mediaUrl = $this->getFirstMediaUrl('posters');
        } elseif ($this->type === 'short_video') {
            $mediaUrl = $this->getFirstMediaUrl('videos');
        }

        return [
            'id' => $this->id,
            'service_id' => $this->service_id,
            'service_provider_id' => $this->service_provider_id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'media_url' => $mediaUrl,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
