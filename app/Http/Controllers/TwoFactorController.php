<?php

namespace App\Http\Controllers;

use App\Notifications\TwoFactorNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\SmsService;
use App\Traits\APIResponses;
use Twilio\Rest\Client;

class TwoFactorController extends Controller
{
  use APIResponses;

  public function __construct(
    protected SmsService $sms
  ) {
    // 
  }

  public function store(Request $request)
  {
    $user = $request->user();


    $user->generateCode();



    try {
      //send mail 
      //  $user->notify(new TwoFactorNotification());


      $this->sms->send(
        $user->phone,
        "كود التحقق الخاص بك هو $user->code"
      );
    } catch (\Throwable $throwable) {
      // 
    }


    return response()->json([
      'message' => 'Verification code sent',
      'code' => $user->code,  // Remove this in production
    ]);
  }
  public function show(Request $request)
  {

    $request->validate([
      'code' => 'required|numeric'
    ]);
    $user = User::whereCode($request->code)->first();

    if ($user->code !== $request->code || Carbon::parse($user->expire_at)->isPast()) {
      return response()->json(['message' => 'Invalid or expired code'], 403);
    }

    $user->code_verified = true;
    $user->save();

    return response()->json(['message' => 'Two-factor authentication successful']);
  }
}
