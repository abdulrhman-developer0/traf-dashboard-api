<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon as SupportCarbon;

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
                ? $this->getWorkTimes($schedule)->map(function ($wt) use ($request, $schedule) {
                    return [
                        'time'          => $wt->time->format('H:i'),
                        'is_available'  => $request->has('date')
                            ? isTimeAvailable($schedule->service_id, SupportCarbon::create($request->date . '' . $wt->time->format('H:i')))
                            : false,
                    ];
                })
                : [],
            'excluded_dates'   =>  ! is_null($schedule)
                ? $schedule->excludedDates->map(function ($excludedDate) {
                    return $excludedDate->start_date->format('m/d/Y');
                })
                : [],
            'custom_dates'   =>  ! is_null($schedule)
                ? $schedule->customWorkDates->map(function ($customDate) {
                    return [
                        'date' => $customDate->start_date->format('m/d/Y'),
                        'times' => $customDate->times->map(function ($wt) {
                            return $wt->time->format('H:i');
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

        // if ($s`chedule?->is_custom) {
        //     return dd($schedule->customWorkDates->toArray());
        // }`

        return $schedule->workTimes;
    }
}