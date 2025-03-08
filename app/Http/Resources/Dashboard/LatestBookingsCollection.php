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
        $currentPage = $this->currentPage();
        $lastPage    = $this->lastPage();

        $nextPage = $currentPage + 1;

        return [
            'current_page'  => $currentPage,
            'last_page'     => $lastPage,
            'next_page'     => $nextPage > $lastPage? null : $nextPage,
            'next_page_url' => $this->nextPageUrl(),
            'per_page'  => $this->perPage(),
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
