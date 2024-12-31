<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'service_id'        => $this->service_id,
            'reference_id'      => $this->reference_id,
            'service_name'      => $this->service?->name,
            'host_name'         => $this->getHostName(),
            'host_photo'        => $this->getHostPhoto(),
            'host_phone'        => $this->service->serviceProvider->phone,
            'rating'            => $this->service->serviceProvider->rating,
            'is_personal'       => $this->Service->serviceProvider->is_personal,
            'status'            => $this->status,
            'payment_status'    =>  $this->payments?->payment_status ?? 'pending',
            'paid_amount'       => $this->payments?->amount,
            'refunded_amont'    => $this->payments?->amount,
            'date'              => $this->date->toDatetimeString(),
            'left_time'         => $this->getLeftTime(),
            // $this->status === 'confirmed' &&
            'is_now'            => $this->getLeftTime() <= 0 && $this->status !== 'done',
            'is_reviewed'       =>  $this->reviews->count() > 0,
            'address'           => $this->getAddress(),
            'created_at'        => $this->created_at?->diffForHumans(),
            'client_name'       => $this->client->user->name,
            'client_phone'      => $this->client->phone,
            'client_photo'      => $this->client->getFirstMediaUrl('photo'),
            'client_address'    => $this->address ?? $this->client->address,
            'client_rating'     => $this->client->rating
        ];
    }

    protected function getHostName()
    {
        if ($this->service->serviceProvider->is_personal) {
            return $this->service->serviceProvider->user->name;
        }

        return $this->worker?->name;
    }

    protected function getHostPhoto()
    {
        if ($this->service->serviceProvider->is_personal) {
            return $this->service->serviceProvider->getFirstMediaUrl('photo');
        }

        return $this->worker?->getFirstMediaUrl('photo');
    }

    public function getLeftTime()
    {
        return now()->diffInMinutes(Carbon::parse($this->date));
    }


    public function getAddress(): ?string
    {
        if ($this->service->is_home_service) {
            return $this->address;
        }

        return $this->service->address ?? $this->service->serviceProvider->address;
    }
}
