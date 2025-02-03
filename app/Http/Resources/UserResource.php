<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function GuzzleHttp\default_ca_bundle;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $account = $this->account();

        return [
            'is_verfied'        => (bool) $this->code_verified,
            'is_personal'       => (bool) $account?->is_personal,
            'user_id'           => $this->id,
            'fcm_token'         => $this->fcm_token,
            'account_id'        => $account?->id,
            'type'              => $this->account_type,
            'status'            => $account->status ?? 'approved',
            'photo'             => $account->getFirstMediaUrl('photo'),
            'name'              => $this->name,
            'job'               => $account->job,
            'email'             => $this->email,
            'phone'             => $account->phone,
            'address'           => $account->address,
            'area'              => $account->area,
            'city'              => $account->city,
            'tax_registeration_number'  => $account->tax_registeration_number,
            'maroof_document'   => $account->getFirstMediaUrl('maroof_document'),
            'rating'            => $account->rating,
            'reviews_count'     => $account->reviews_count ?? 0,
            'rating_stats'      => $account->rating_stats,
            'booking_stats'     => $account->booking_stats,
            'subscription'      => $account->currentSubscription ? SubscriptionResource::make($account->currentSubscription) : null,
            'longitude'         => $this->location?->longitude,
            'latitude'         => $this->location?->latitude,
        ];
    }
}
