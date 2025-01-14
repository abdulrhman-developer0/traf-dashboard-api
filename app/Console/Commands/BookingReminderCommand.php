<?php

namespace App\Console\Commands;

use App\Jobs\BookingRemindersJob;
use App\Models\Booking;
use App\Notifications\DBNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\default_ca_bundle;

class BookingReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:booking-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookings =  Booking::query()
            ->whereStatus('confirmed')
            // ->whereBetween('date', [now()->startOfDay(),  now()->endOfDay()])
            // ->where('date', '>=', now())
            ->orderByDesc('date')
            ->get();

        foreach ($bookings as $booking) {

            $minutes = max(
                0,
                round(
                    now()->diffInMinutes($booking->date)
                )
            );

            if (! in_array($minutes, [15, 30, 60, 120])) {
                continue;
            }

            dd($minutes);

            // notify the (client + provider)
            foreach ([$booking->client->user, $booking->service->serviceProvider->user] as $targetUser) {
                if (! $targetUser->fcm_token) continue;

                $user = match ($targetUser->account_type) {
                    'client'            => $booking->client->user,
                    'service-provider'  => $booking->service->serviceProvider->user,
                    default             => null
                };

                $title = 'تذكير';
                $message = $message = __('mobile.confirmed_booking', [
                    'service_name' => $booking->service->name,
                    'name' => $user->name,
                    'time' => $booking->date->format('h:i A')
                ], 'ar');;

                $data = [
                    'status' => 'reminding',
                    'date' => $booking->date,
                    'sent_at' => now(),
                    'title' => $title,
                    'message' => $message,
                    'user' => [
                        'id' => (int) $user->id,
                        'account_id' => (int) $user->account()->id,
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

        Log::info('Test reminder notifications');
    }
}
