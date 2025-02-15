<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected static $baseUrl = 'https://www.dreams.sa/index.php/api/sendsms/';

    public function send(string $to, string $message)
    {
        $response = Http::post(static::$baseUrl, [
            'user'          => 'Eman_3288',
            'secret_key'    => 'c87fd3bffedf5e7af27efc16e4af12c36c7421ff015c5365b0dab91be2deda8e',
            'to'            => $to,
            'message'       => $message,
            'sender'        => 'Tarf Beauty'
        ]);

        return $response->ok();
    }
}
