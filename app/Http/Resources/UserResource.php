<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            // 'user_id'           => $this->id,
            'account_id'        => $account->id,
            'type'              => $this->account_type,
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
        ];
    }
}