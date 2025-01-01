<?php

namespace App\Console\Commands;

use App\Services\PayMobService;
use Illuminate\Console\Command;

class TestPaymob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-paymob';

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
        $paymob = new PayMobService;

        $response = $paymob->createPaymentOrder([
            'amount'    => 15 *100,
            'order_id'  => 22,
            'customer'  => [
                'first_name' => 'Abdulrhman',
                'last_name'  => 'Ahmed',
                'phone'      => 'EGP'
            ]
        ]);

        dd($response);
    }
}
