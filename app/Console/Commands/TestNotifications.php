<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Events\SendNotification;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Notifications\DBNotification;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TestNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TestNotifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // This is a sample data to be sent in the notification, you can put any data row wherever you will use the command

        $user     = \App\Models\User::firstWhere('account_type', 'client');
        $account  = $user->account();
        $booking = Booking::where('client_id', $account->id)->first();

        $data = BookingResource::make($booking)->toArray(request());

        SendNotification::dispatch($user, $data);
        $user->notify(new DBNotification($data));
    }
}
