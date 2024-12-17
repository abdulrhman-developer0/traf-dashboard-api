<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Events\SendNotification;

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
        $data = \App\Models\User::find(1);

        SendNotification::dispatch($data);


    }
}
