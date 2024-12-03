<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user =  optional($this->reviewable)->user;

        return [
            'id'            => $this->id,
            'name'          => $user?->name,
            'rating'        => $this->rating,
            'comment'       => $this->comment,
            'created_at'    => $this->created_at->format('Y-m-d'),
            'updated_at'    => $this->updated_at->format('Y-m-d'),
        ];
    }
}
