<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\DBNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SubscriptionReminderCommand extends Command
{
    protected $signature = 'app:subscription-reminder';
    protected $description = 'Send reminders about subscription expiry';

    public function handle()
    {
        $dates = [now()->addDays(2)->toDateString(), now()->addDay()->toDateString()];

        $subscriptions = Subscription::query()
            ->whereIn('end_date', $dates)
            ->with('serviceProvider.user')
            ->get();

        foreach ($subscriptions as $subscription) {
            $user = $subscription->serviceProvider->user ?? null;
            if (! $user || ! $user->fcm_token) {
                continue;
            }

            $daysRemaining = now()->diffInDays($subscription->end_date);
            $title = 'تنبيه انتهاء الاشتراك';
            $message = "متبقي على انتهاء الاشتراك ($daysRemaining) يوم";

            $data = [
                'status' => 'subscription_expiry',
                'expires_at' => $subscription->end_date,
                'sent_at' => now(),
                'title' => $title,
                'message' => $message,
                'user' => [
                    'id' => (int) $user->id,
                    'account_id' => (int) $user->account()->id,
                    'name' => $user->name
                ]
            ];

            $user->notify(new DBNotification($data));

            app('App\Http\Controllers\API\FcmController')
                ->sendFcmNotification($user->id, $title, $message);
        }

        Log::info('Subscription expiry reminders sent successfully.');
    }
}
