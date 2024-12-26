<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'payment_stats' => $this->payment_status,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'left_days'     => round(now()->diffInDays($this->end_date)),
        ];
    }
}
