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
        return [
            'current_page'  => $this->currentPage(),
            'last_page'     => $this->lastPage(),
            'next_page_url' => $this->nextPageUrl(),
            'items'     => $this->collection->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'name'              => $item->name,
                    'price'              => $item->price,
                    'price_after'        => $item->price_after,
                    'duration_in_days'              => $item->duration_in_days,
                    'ads_discount'                  => $item->ads_discount,
                ];
            })
        ];
    }
}
