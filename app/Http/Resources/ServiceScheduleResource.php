<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $schedule = $this->resource ?? null;

        return [
            'schedule_id'  => $schedule?->id,
            'start_date'   => $schedule?->start_date?->format('m/d/Y'),
            'end_date'     => $schedule?->end_date?->format('m/d/Y'),
            'work_times'   =>  ! is_null($schedule)
                ? $schedule->workTimes->map(function ($wt) {
                    return [
                        'time'          => $wt->time->format('H:i'),
                        'is_available'  => (bool) $wt->bookings?->count() == 0
                    ];
                })
                : []
        ];
    }
}
