<?php

namespace App\Http\Resources;

use App\Models\AdPrice;
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
            'id'            => $this->id,
            'payment_stats' => $this->payment_status,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'left_days'     => round(now()->diffInDays($this->end_date)),
            'package'       => [
                'id'                => $this->package->id,
                'name'              => $this->package->name,
                'duration_in_days'  => $this->package->duration_in_days,
                'ads_discount'       => $this->package->ads_discount,
                'ad_price'          => (AdPrice::latest()->first()?->price) ?? 0,
            ]
        ];
    }
}
