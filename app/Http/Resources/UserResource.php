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

        $data = [
            'name'          => $this->name,
            'email'          => $this->email,
            'is_verfied'    => (bool) $this->code_verified,
            'type'          => $this->account_type,
        ];


        $account = $this->account();
        dd($account, $this->resource->toArray());

        $array = $account ? $account->toArray() : [];
        $data = array_merge($data, $array);
        $data['photo'] = $account->getFirstMediaUrl('photo');

        if ( $account->is_personal ) {
            $data['maroof_document'] = $account->getFirstMediaUrl('maroof_document');
        }

        return $data;
    }
}
