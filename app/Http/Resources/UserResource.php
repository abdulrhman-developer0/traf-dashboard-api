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
            'is_verfied'    => (bool) $this->code_verified,
            'type'          => (bool) $this->client ? 'client' : 'service-provider',
        ];


        $model = $this->client ?? $this->serviceProvider;

        $array = $model ? $model->toArray() : [];
        $data = array_merge($data, $array);
        $data['photo'] = $model->getFirstMediaUrl('photo');

        if ( $model->is_personal ) {
            $data['maroof_document'] = $model->getFirstMediaUrl('maroof_document');
        }

        return $data;
    }
}
