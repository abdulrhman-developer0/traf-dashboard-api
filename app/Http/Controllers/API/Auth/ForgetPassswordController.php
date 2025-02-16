<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimePassword;
use App\Models\ResetToken;
use App\Models\User;
use App\Notifications\OTPNotification;
use App\Services\SmsService;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgetPassswordController extends Controller
{
    use APIResponses;

    protected $expiredAfterMinutes = 10;

    public function __construct(
        protected SmsService $sms
    ) {
        // 
    }

    public function send(Request $request)
    {
        $request->validate([
            'phone'  => 'required|string|exists:users'
        ]);

        $user = User::firstWhere('phone', $request->phone);


        $otp = OneTimePassword::create([
            'email'         => $user->phone,
            'code'          => random_int(10000, 99999),
            'expired_at'     => now()->addMinutes($this->expiredAfterMinutes)
        ]);

        try {
            // $user->notify(new OTPNotification($otp->code));

            $this->sms->send(
                $user->phone,
                "كود التحقق الخاص بك هو $user->code"
            );
        } catch (\Throwable $throwable) {
            // 
        }

        $data = ['to' => $user->phone];

        if (config('app.env') !== 'production') {
            $data['test_code'] = $otp->code;
        }


        return $this->createdResponse($data, 'Code sent successfuly');
    }


    public function check(Request $request)
    {
        $request->validate([
            'phone'  => 'required|string|exists:users',
            'code'   => 'required|string|digits:5'
        ]);

        $otp = OneTimePassword::wherePhone($request->phone)
            ->whereCode($request->code)
            ->where('expired_at', '>', now())
            ->first();

        if (! $otp) {
            return $this->badResponse([], 'Invalid Code');
        }

        $user = User::firstWhere('phone', $otp->phone);

        $plainTextToken = "$otp->id|" . Str::random(24);

        $resetToken = ResetToken::create([
            'user_id'       => $user->id,
            'token'         => $plainTextToken,
            'expired_at'    => now()->addMinutes($this->expiredAfterMinutes)
        ]);

        $otp->expired_at = now();
        $otp->save();

        return $this->createdResponse([
            'reset_token' => $plainTextToken
        ], 'Reset token created successfuly');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'reset_token'      => 'required|string|min:26|max:255',
            'password'         => 'required|string|min:8|max:16|confirmed',
        ]);

        $resetToken = ResetToken::whereToken($request->reset_token)
            ->where('expired_at', '>', now())
            ->first();

        if (! $resetToken) {
            return $this->badResponse([], 'Invalid reset token');
        }

        $user = $resetToken->user;
        $user->password = Hash::make($request->password);
        $user->save();

        $resetToken->expired_at = now();
        $resetToken->save();

        $data = ['token' => $user->createToken($user->phone)->plainTextToken];

        return $this->createdResponse($data, 'Reset password successfuly');
    }
}
