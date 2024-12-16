<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BookingRemindersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $bookings =  Booking::query()
            ->latest()
            ->where('date', '>=', now())
            ->where('date', '<=', now()->endOfDay())
            ->get();

            // dd($bookings->toArray());
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
