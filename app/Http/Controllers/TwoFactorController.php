<?php

namespace App\Http\Controllers;

use Carbon\Carbon; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TwoFactorController extends Controller
{
    //

    public function store(Request $request)
    {
       $user=Auth::user();
       \Log::info('Authenticated User:', ['user' => $user]);
       $user->generateCode();
       return response()->json([
        'message' => 'Verification code sent',
        'code' => $user->code,  // Remove this in production
    ]);
    }
    public function show(Request $request)
    {
       $user=Auth::user();
       $request->validate([
        'code'=>'required|numeric'
       ]);
       if ($user->code !== $request->code || Carbon::parse($user->expire_at)->isPast()) {
        return response()->json(['message' => 'Invalid or expired code'], 403);
    }
    $user->code_verified = true;
    $user->save();

    return response()->json(['message' => 'Two-factor authentication successful']);
    }
 
}
