<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToArray;

class ProfileController extends Controller
{
    use APIResponses;

    public function data(Request $request)
    {
        $user = $request->user();


        return $this->okResponse(UserResource::make($user), 'Retrieved Profile Successfuly');
    }

    public function update(Request $request)
    {
        $user    = Auth::user();

        $dynmicRules = match ($user->account_type) {
            'client'            => [
                'phone'     => 'required|string|min:9|max:20',
                'address'   => 'nullable|string|min:1|max:255',
            ],
            'service-provider'  => [
                'phone'     => 'required|string|min:9|max:20',
                'address'   => 'nullable|string|min:1|max:255',
                'years_of_experience' => 'nullable|integer|between:1,100',
            ],
            default             => []
        };

        $validated = $request->validate([
            'name'      => 'required|string|min:1|max:255',
            'city_id'   => 'required|integer|exists:cities,id',
            ...$dynmicRules
        ]);

        $user->fill($request->only(['name']));

        $account = $user->account();
        $accountData = collect($validated)
            ->except(['name'])
            ->only($account?->getFillable() ?? [])
            ->toArray();


        $account?->fill($accountData)->save();

        return $this->okResponse(UserResource::make($user), 'Updated profile data successfuly');
    }

    public function changePhoto(Request $request)
    {
        $request->validate([
            'photo'     => 'required|image|max:4096',
        ]);

        $account = Auth::user()?->account();

        $account?->addMedia($request->file('photo'))
            ->toMediaCollection('photo');

        return $this->okResponse([], 'Profile photo changed successfuly');
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
