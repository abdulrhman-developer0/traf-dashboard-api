<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LatestServiceCollection extends ResourceCollection
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
                    'photo'             => $item->getFirstMediaUrl('photo'),
                    'name'              => $item->name,
                    'category_name'     => $item->category->name,
                    'rating'            => $item->rating,
                    'price'             => $item->is_offer ? $item->price_after : $item->price_before,
                ];
            })
        ];
    }
}
