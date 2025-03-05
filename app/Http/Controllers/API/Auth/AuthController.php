<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\TwoFactorNotification;
use App\Services\SmsService;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use APIResponses;

    public function __construct(
        protected SmsService $sms
    ) {
        // 
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'nullable|email|exists:users',
            'phone'     => 'required_without:email|string',
            'password'  => 'required|string|min:8'
        ]);

        $user = User::query()
            ->when($request->email, fn($q) => $q->where('email', $request->email))
            ->when($request->phone, fn($q) => $q->where('phone', $request->phone))
            ->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->badResponse([], 'Invalid Credentials');
        }

        // Initialize wallet if not exists.
        $user->initializeWallet();

        $data = [];

        if (! $user->code_verified) {

            $user->generateCode();

            try {
                //send mail 
                // $user->notify(new TwoFactorNotification());

                $this->sms->send(
                    $user->phone,
                    "كود التحقق الخاص بك هو $user->code"
                );
            } catch (\Throwable $throwable) {
                // 
            }

            if (config('app.env') !== 'production') {
                $data['test_code'] = $user->code;
            }
        }

        if ($user->isAccount('service-provider') && $user->account()->status != 'approved') {
            return $this->badResponse([
                'reason' => 'account_not_approved',
            ], "حسابك قيد المراجعة من قبل المسؤل ");
        }

        $username = $user->phone ??  $user->email;
        $data['token'] = $user->createToken($request->ip() . '/' .  $username)->plainTextToken;

        $data['user'] = UserResource::make($user);

        return $this->createdResponse($data, 'Token created successfuly');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->update([
            'fcm_token' => null
        ]);

        $user->currentAccessToken()->delete();

        return $this->okResponse([], 'Token deleted successfuly');
    }
}
