<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class FcmController extends Controller
{
    use APIResponses;

    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $request->user()->update(['fcm_token' => $request->fcm_token]);

        return $this->okResponse([], 'Device token updated successfully');
    }

    public function sendFcmNotification($user_id, $title, $body)
    {
        $user = \App\Models\User::find($user_id);
        $fcm = $user->fcm_token;
        // $fcm = 'fXLzfgZsS3ayB3MXY3PvPa:APA91bFfllla1yUDMFZwot6CLTY0Cf4orNI-SDLxTpcWnMTMK8ndbWmE2xyeOG4f7I2ARRnH64A6aSR3e5TrnRzunDX2lDmFldbWTGSukAMKVCUP1lBEmJw';
        // dd($user->toArray());

        if (!$fcm) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }

        $title = $title;
        $description = $body;
        $projectId = 'traf-e54b9'; # INSERT COPIED PROJECT ID

        $credentialsFilePath = Storage::path('json/traf-e54b9-firebase-adminsdk-tfa1j-5b8b0b6030.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];
        // dd($access_token);

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        
        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => $title,
                    "body" => $description,
                ],
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
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
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            // dd(json_decode($response, true));
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
}
