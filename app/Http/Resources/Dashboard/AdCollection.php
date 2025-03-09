<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdCollection extends ResourceCollection
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
        $prevPage = $currentPage - 1;

        return [
            'current_page'  => $currentPage,
            'last_page'     => $lastPage,
            'next_page'     => $nextPage > $lastPage ? null : $nextPage,
            'prev_page'     => $prevPage < 1 ? null : $prevPage,
            'next_page_url' => $this->nextPageUrl(),
            'per_page'  => $this->perPage(),
            'items'     => $this->collection->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'url'                 => $item->getFirstMediaUrl('photo'),
                    'duration_in_days'      => $item->duration_in_days,
                    'total_price'           => $item->total_price,
                    'discount'              => $item->discount,
                    'status'                => $item->status,
                    'start_date'            => $item->start_date?->format('Y-m-d'),
                    'end_date'              => $item->end_date?->format('Y-m-d'),
                    'created_at'            => $item->created_at->format('Y-m-d'),
                    'provider_name'         => $item->serviceProvider->user->name,
                    'is_personal'         => $item->serviceProvider->is_personal,
                    'notes'                 => $item->notes,

                ];
            })
        ];
    }
}
