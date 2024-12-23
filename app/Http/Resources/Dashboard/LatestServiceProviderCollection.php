<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LatestServiceProviderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page'  => $this->currentPage(),
            'last_page'     => $this->lastPage(),
            'next_page_url' => $this->nextPageUrl(),
            'items'     => $this->collection->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'user_id'           => $item->user->id,
                    'provider_name'     => $item->user->name,
                    'is_personal'       => $item->is_personal,
                    'tax_number'        => $item->tax_registeration_number,
                    'maroof_document'   => $item->getFirstMediaUrl('maroof_document'),
                    'created_at'        => $item->created_at->format('Y-m-dTH:i')
                ];
            })
        ];
    }
}
