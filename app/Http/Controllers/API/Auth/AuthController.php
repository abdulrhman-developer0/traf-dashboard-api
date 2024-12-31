<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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

        $data = [];

        if (! $user->code_verified) {
            
            $user->generateCode();

            //send mail 
            $user->notify(new TwoFactorNotification());

            if (config('app.env') !== 'production') {
                $data['test_code'] = $user->code;
            }
        }




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

        if ( $user->account_type = 'client' || ($user->account_type == 'service-provider' && $user->serviceProvider->status == 'approved') ) {
        $data['token'] = $user->createToken('api-user-login')->plainTextToken;
        }

        $data['user'] = UserResource::make($user);

        return $this->createdResponse($data, 'Token created successfuly');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return $this->okResponse([], 'Token deleted successfuly');
    }
}
