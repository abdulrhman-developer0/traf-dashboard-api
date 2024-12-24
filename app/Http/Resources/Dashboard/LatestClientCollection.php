<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LatestClientCollection extends ResourceCollection
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
                    'client_name'     => $item->user->name,
                    'created_at'        => $item->created_at->format('Y-m-dTH:i')
                ];
            })
        ];
    }
}
