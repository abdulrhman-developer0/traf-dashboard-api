<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorNotification;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use APIResponses;

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string|min:8'
        ]);

        $user = User::firstWhere('email', $request->email);
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->badResponse([], 'Invalid email or password');
        }

        $user->generateCode();
        $token = $user->createToken('api-user-login');
      
 
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
    //     return response()->json([
    //      'message' => 'Verification code sent',
    //      'code' => $user->code,  // Remove this in production
    //  ]);
        return $this->okResponse([
            'token'  => $token->plainTextToken,
            'code' => $user->code,
        ], 'Token created successfuly');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return $this->okResponse([], 'Token deleted successfuly');
    }
}
