<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LatestPackageCollection extends ResourceCollection
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
                    'id'                => $item->id,
                    'name'              => $item->name,
                    'price'              => $item->price,
                    'price_after'        => $item->price_after,
                    'duration_in_days'              => $item->duration_in_days,
                    'ads_discount'              => $item->ads_discount,
                ];
            })
        ];
    }
}
