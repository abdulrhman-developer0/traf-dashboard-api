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
            'is_verfied'          => (bool) $this->code_verified,
            'is_personal'         => (bool) $account?->is_personal,
            'user_id'             => $this->id,
            'fcm_token'           => $this->fcm_token,
            'account_id'          => $account?->id,
            'type'                => $this->account_type,
            'status'              => $account?->status ?? 'approved',
            'deleted_at'          => $this->deleted_at,
            'photo'               => $account?->getFirstMediaUrl('photo') ?? $this->getFirstMediaUrl('photo'),
            'name'                => $this->name,
            'email'               => $this->email,
            'job'                 => $account?->job,
            'phone'               => $this->phone,
            // 'phone'               => $account?->phone,
            'address'             => $account?->address,
            'bank_account_number' => $account?->bank_account_number,
            'area'                => $account?->area,
            'city'                => $account?->city,
            'tax_registeration_number'  => $account?->tax_registeration_number,
            'maroof_document'           => $account?->getFirstMediaUrl('maroof_document'),
            'rating'                    => $account?->rating,
            'reviews_count'     => $account?->reviews_count ?? 0,
            'rating_stats'      => $account?->rating_stats,
            'booking_stats'     => $account?->booking_stats,
            'subscription'      => $account?->currentSubscription ? SubscriptionResource::make($account?->currentSubscription) : null,
            'longitude'         => $this->location?->longitude,
            'latitude'         => $this->location?->latitude,
            'created_at'       => $this->created_at,
            'wallet'           => $this->wallet->only(['balance']),
        ];
    }
}
