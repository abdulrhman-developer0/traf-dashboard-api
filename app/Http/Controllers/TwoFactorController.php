<?php

namespace App\Http\Controllers;

use App\Notifications\TwoFactorNotification;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Twilio\Rest\Client;

class TwoFactorController extends Controller
{
    //

    public function store(Request $request)
    {
       $user=Auth::user();
       \Log::info('Authenticated User:', ['user' => $user]);
       $user->generateCode();



       //send mail 
       $user->notify(new TwoFactorNotification());



       // send mobile
    //    $message="رمز التحقق هو ".$user->code;
    //    $account_sid=getenv("TWILIO_SID");
    //    $auth_token=getenv("TWILIO_TOKEN");
    //    $twilio_number=getenv("TWILIO_FROM");
    //    $client=new Client($account_sid,$auth_token);
    //    $client->messages->create('+2001027629534',[
    //     'from'=> $twilio_number,
    //     'body' => $message
    //    ]);
       return response()->json([
        'message' => 'Verification code sent',
        'code' => $user->code,  // Remove this in production
    ]);
    }
    public function show(Request $request)
    {
     
       $request->validate([
        'code'=>'required|numeric'
       ]);
       $user=User::whereCode($request->code)->first();

       if ($user->code !== $request->code || Carbon::parse($user->expire_at)->isPast()) {
        return response()->json(['message' => 'Invalid or expired code'], 403);
    }
      $user->code_verified = true;
      $user->save();

      return response()->json(['message' => 'Two-factor authentication successful']);


    }
 
}
