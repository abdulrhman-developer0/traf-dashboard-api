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
            'currency'  => 'SAR',
            'amount'    => 15 *100,
            'order_id'  => 22,
            'customer'  => [
                'first_name' => 'Abdulrhman',
                'last_name'  => 'Ahmed',
                'email'      => 'test@gmail.com',
                'phone'      => '01508373405'
            ]
        ]);

        dd($response['payment_url']);
    }
}
