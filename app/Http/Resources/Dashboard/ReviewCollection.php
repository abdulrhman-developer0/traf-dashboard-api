<?php

namespace App\Http\Resources\Dashboard;

use App\Models\Client;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
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
                    'name'              => $item->reviewable->user->name,
                    'rating'            => $item->rating,
                    'comment'           => $item->comment,
                    'created_at'        => $item->created_at->format('Y-m-dTH:i') ,
                ];
            })
        ];
    }
}
