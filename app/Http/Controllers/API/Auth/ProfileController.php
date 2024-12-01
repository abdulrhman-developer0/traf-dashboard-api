<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use APIResponses;

    public function data(Request $request)
    {
        $user = $request->user();


        return $this->okResponse([
            'user' => UserResource::make($user)
        ], 'Retrieved Profile Successfuly');
    }

    public function update(Request $request)
    {
        $request->validate(
            
        );

        $user    = Auth::user();
        $account = $user->account();
        dd($account->fillable);
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8'
        ]);

        $user = $request->user();

        if (!$user || !Hash::check($request->old_password, $user->password)) {
            return $this->badResponse([], 'Invalid Password');
        }

        $user->password = $request->new_password;
        $user->save();

        return $this->okResponse([], 'Password Change Successfuly');
    }
}
