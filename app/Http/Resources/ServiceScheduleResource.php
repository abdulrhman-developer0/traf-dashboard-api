<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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
            'service_id'   => $schedule?->service_id,
            'reference_id' => $schedule?->reference_id,
            'start_date'   => $schedule?->start_date?->format('m/d/Y'),
            'end_date'     => $schedule?->end_date?->format('m/d/Y'),
            'is_excluded'  =>  $schedule?->is_excluded,
            'is_custom'    =>  $schedule?->is_custom,
            'work_times'   =>  ! is_null($schedule)
                ? $this->getWorkTimes($schedule)->map(function ($wt) {
                    return [
                        'time'          => $wt->time->format('H:i'),
                        'is_available'  => (bool) $wt->bookings?->count() == 0
                    ];
                })
                : [],
            'excluded_dates'   =>  ! is_null($schedule)
                ? $schedule->excludedDates->map(function ($excludedDate) {
                    return [
                        'start_date' => $excludedDate->start_date->format('m/d/Y h:i A'),
                        'end_date'   => $excludedDate->end_date->format('m/d/Y h:i A')
                    ];
                })
                : [],
            'custom_dates'   =>  ! is_null($schedule)
                ? $schedule->customWorkDates->map(function ($customDate) {
                    return [
                        'start_date' => $customDate->start_date->format('m/d/Y h:i A'),
                        'end_date'   => $customDate->end_date->format('m/d/Y h:i A'),
                        'times' => $customDate->times->map(function ($wt) {
                            return [
                                'time'          => $wt->time->format('H:i'),
                                'is_available'  => true
                            ];
                        })
                    ];
                })
                : [],
        ];
    }

    private function getWorkTimes($schedule)
    {
        if ($schedule?->is_excluded) {
            return new Collection;
        }

        if ($schedule?->is_custom) {
            return $schedule->customWorkDates->first()->times;
        }

        return $schedule->workTimes;;
    }
}
