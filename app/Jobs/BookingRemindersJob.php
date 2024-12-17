<?php

namespace App\Jobs;

use App\Events\PushNotification;
use App\Http\Resources\BookingResource;
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
        // 
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bookings =  Booking::query()
            ->latest()
            // ->where('date', '>=', now())
            // ->where('date', '<=', now()->endOfDay())
            ->get();

        // dd($bookings);

        foreach ($bookings as $booking) {
            $hours = now()->diffInHours(Carbon::parse($this->date));

            if ($hours > 0 && $hours < 3) {
                $user = $booking->client->user;

                // send the notification for user client
                // $user->notify(new PushNotification(
                //     $user,
                //     BookingResource::make($booking)
                // ));
            }
        }
    }
}
