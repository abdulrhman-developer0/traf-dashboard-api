<?php

namespace App\Jobs;

use App\Events\PushNotification;
use App\Events\SendNotification;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Notifications\DBNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;

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
            ->where('date', '>=', now())
            // ->where('date', '<=', now()->endOfDay())
            ->get();

        foreach ($bookings as $booking) {


            $hours = now()->diffInHours($booking->date);
            

            if ($hours > 0 && $hours <= 3) {
                $data = BookingResource::make($booking)->toArray(request());
                $user = $booking->client->user;

                // Notify the user (database + broadcast)
                $user->notify(new DBNotification($data));
                SendNotification::dispatch($user, $data);
            }
        }
    }
}
