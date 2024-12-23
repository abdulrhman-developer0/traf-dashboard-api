<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LatestBookingsCollection extends ResourceCollection
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
            'items'     => $this->collection->map(function($item) {
                return [
                    'id'                => $item->id,
                    'provider_name'     => $item->service?->serviceProvider?->user?->name,
                    'client_name'       => $item->client?->user?->name,
                    'date'              => $item->date->format('Y-m-dTH:i'),
                    'status'            => $item->status,
                ];
            })
        ];
    }
}
