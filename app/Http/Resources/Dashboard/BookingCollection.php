<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
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
                    'service_name'      => $item->service->name,
                    'provider_photo'    => $item->service->serviceProvider->getFirstMediaUrl('photo'),
                    'provider_name'     => $item->service?->serviceProvider?->user?->name ?? "deleted provider",
                    'provider_rating'     => $item->service?->serviceProvider->rating,
                    'client_photo'        => $item->client->getFirstMediaUrl('photo'),
                    'client_name'         => $item->client?->user?->name ?? "deleted client",
                    'client_rating'       => $item->client->rating,
                    'from'                => $item->date->startOfHour()->format('h:i A'),
                    'to'                  => $item->date->addHour()->startOfHour()->format('h:i A'),
                    'date'                => $item->date->format('Y-m-dTH:i'),
                    'status'              => $item->status,
                    'payment_status'      => $item->payments?->status,
                    'paid_amount'         => $item->payments?->amount,
                    'refunded_amount'         => $item->payments?->refunded_amount,
                    'created_at'              => $item->created_at,
                    'client_phone'         => $item->client->phone,


                ];
            })
        ];
    }
}
