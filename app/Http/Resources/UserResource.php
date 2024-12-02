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
        $data    = $account->toArray() ?? [];


        return [
            'name'              => $this->name,
            'email'             => $this->email,
            'is_verfied'        => (bool) $this->code_verified,
            'type'              => $this->account_type,
            'photo'             => $account->getFirstMediaUrl('photo'),
            ...$data,
            'maroof_document'   => $account->getFirstMediaUrl('maroof_document'),
        ];
    }
}
