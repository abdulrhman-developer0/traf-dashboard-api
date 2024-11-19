<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
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

        return $this->okResponse([
            'token'  => $token->plainTextToken
        ], 'Token created successfuly');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return $this->okResponse([], 'Token deleted successfuly');
    }
}
