<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Events\SendNotification;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Notifications\DBNotification;
use Illuminate\Support\Facades\Storage;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TestNotifications {user_id}';

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

        /*$user     = \App\Models\User::firstWhere('account_type', 'client');
        $account  = $user->account();
        $booking = Booking::where('client_id', $account->id)->first();

        $data = BookingResource::make($booking)->toArray(request());

        SendNotification::dispatch($user, $data);
        $user->notify(new DBNotification($data));*/

        $user = \App\Models\User::firstWhere('id', $this->argument('user_id'));
        $firebaseToken = $user->fcm_token;
        $firebaseToken = 'fXLzfgZsS3ayB3MXY3PvPa:APA91bFfllla1yUDMFZwot6CLTY0Cf4orNI-SDLxTpcWnMTMK8ndbWmE2xyeOG4f7I2ARRnH64A6aSR3e5TrnRzunDX2lDmFldbWTGSukAMKVCUP1lBEmJw';

        // $this->send($firebaseToken, 'Test tile', 'Is here', 'Message', 1);

        app('App\Http\Controllers\API\FcmController')
            ->sendFcmNotification(
                $user->id,
                "Test",
                "Body"
            );
    }

    public static function send($fcm, $title, $body, $type, $id)
    {

        $credentialsFilePath = Storage::path('json/traf-e54b9-firebase-adminsdk-tfa1j-5b8b0b6030.json');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        
        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
                "data" => [
                    'type' => $type,
                    'id' => strval($id)
                ]
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/traf-e54b9/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            dd($err);
            /*return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);*/
        } else {
            dd(json_decode($response, true));
        }
    }
}
