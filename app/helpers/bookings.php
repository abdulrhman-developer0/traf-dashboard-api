<?php

use App\Models\Service;
use App\Models\ServiceSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('isTimeAvailable')) {
    /**
     * Check if a given datetime is available for scheduling.
     *
     * @param int $serviceId
     * @param \Illuminate\Support\Carbon $datetime
     * @return bool
     */
    function isTimeAvailable(int $serviceId, Carbon $datetime): bool
    {
        // Retrieve all schedules for the service
        $schedules = ServiceSchedule::latest()
            ->where('service_id', $serviceId)
            ->where('start_date', '<=', $datetime)
            ->where('end_date', '>=', $datetime)
            ->get();

            dd($schedules, request()->query() );

        // ignored ids
        $ignoredIds = [];
        // dd($schedules->toArray());

        // Check if the time is available
        foreach ($schedules as $schedule) {
            $existingBooking = DB::table('bookings')
                ->where('service_id', $serviceId)
                ->whereStatus('confirmed')
                ->where('date', $datetime)
                ->whereNotIn('id', $ignoredIds)
                ->when(request()->has('reference_id'), fn($q) => $q->where('reference_id', request()->query('reference_id') ))
                ->first();

            // dd($existingBooking);

            if (!$existingBooking) {
                return true; // Time is not available
            }

            // Add the id to the ignored ids
            $ignoredIds[] = $existingBooking->id;
        }

        return false; // Time is available
    }
}
