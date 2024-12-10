<?php

namespace App\Http\Resources;

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
            'service_name'      => $this->service?->name,
            'host_name'         => $this->getHostName(),
            'host_photo'        => $this->getHostPhoto(),
            'is_personal'       => $this->Service->serviceProvider->is_personal,
            'status'            => $this->status,
            'date'              => $this->date,
            'left_time'         => $this->getLeftTime(),
            'created_at'        => $this->created_at?->diffForHumans(),
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
        return now()->addDay()->diffInMinutes($this->date);
    }
}
