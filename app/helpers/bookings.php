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
        $schedules = ServiceSchedule::where('service_id', $serviceId)
            ->where('start_date', '<=', $datetime)
            ->where('end_date', '>=', $datetime)
            ->get();

        // ignored ids
        $ignoredIds = [];

        // Check if the time is available
        foreach ($schedules as $schedule) {
            $existingBooking = DB::table('bookings')
                ->where('service_id', $serviceId)
                ->where('date', $datetime)
                ->whereNotIn('id', $ignoredIds)
                ->exists();

            if (!$existingBooking) {
                return true; // Time is not available
            }

            // Add the id to the ignored ids
            $ignoredIds[] = $existingBooking->id;
        }

        return false; // Time is available
    }
}
