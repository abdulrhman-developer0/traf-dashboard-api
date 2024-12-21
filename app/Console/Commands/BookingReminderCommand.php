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
            ->whereBetween('date', [now(),  now()->addMinute(30)])
            ->orWhereBetween('date', [now(),  now()->addMinute(60)])
            ->get();

        foreach ($bookings as $booking) {

            // notify the (client + provider)
            foreach ([$booking->client->user, $booking->service->serviceProvider->user] as $targetUser) {
                if (! $targetUser->fcm_token ) continue;

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

                $user = match ($targetUser->account_type) {
                    'client'            => $booking->service->serviceProvider->user,
                    'service-provider'  => $booking->client->user,
                    default             => null
                };

                $data = [
                    'status' => 'reminding',
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

        Log::info('Test reminder notifications');
    }
}
