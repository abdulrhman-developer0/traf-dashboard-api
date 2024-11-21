<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page'      => $this->CurrentPage(),
            'path'              => $this->path(),
            'key'               => 'page',
            'last_page'         => $this->lastPage(),
            'next_page_url'     => $this->nextPageUrl(),
            'per_page'          => $this->perPage(),
            'items'             => ServiceResource::collection($this->collection),
            'filters'           => $this->generateSearchFilters(),
        ];
    }

    private function generateSearchFilters()
    {
        return [];
    }
}
