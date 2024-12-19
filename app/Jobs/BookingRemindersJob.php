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
                foreach ([$booking->client->user, $booking->service->serviceProvider->user] as $targetUser) {
                    $title = 'تذكير';
                    $message = 'لديك موعد قريب';

                    $user = match ($targetUser->account_type) {
                        'client'            => $booking->service->serviceProvider->user,
                        'service-provider'  => $booking->client->user,
                        default             => null
                    };

                    $data = [
                        'status' => 'confirmed',
                        'date' => $booking->date,
                        'sent_at' => now(),
                        'title' => $title,
                        'message' => $message,
                        'user' => [
                            'id' => $user->id,
                            'account_id' => $user->account()->id,
                            'name' => $user->name
                        ]
                    ];

                    // Notify the target user in the database.
                    $targetUser->notify(new DBNotification($data));

                    // Notify the target user via FCM
                    app('App\Http\Controllers\API\FcmController')
                        ->sendFcmNotification(
                            $targetUser->id,
                            $title,
                            $message
                        );
                }
            }
        }
    }
}
