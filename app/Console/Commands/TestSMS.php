<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use Illuminate\Console\Command;

class TestSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-sms {phone} {code}';

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
        $phone = $this->argument('phone');

        $code = $this->argument('code');

        $sms = new SmsService();

        $sms->send(
            $phone,
            "كود التحقق الخاص بك هو $code"
        );
    }
}
