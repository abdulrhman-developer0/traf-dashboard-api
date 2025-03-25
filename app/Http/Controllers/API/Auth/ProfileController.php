<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Resources\UserResource;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Service;
use App\Models\ServiceProvider;
use App\Models\User;
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

    public function dataFor(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return $this->badResponse([], "No User With id '{$id}'");
        }


        return $this->okResponse(UserResource::make($user), 'Retrieved Profile Successfuly');
    }

    public function update(Request $request)
    {
        $user    = Auth::user();

        $dynmicRules = match ($user->account_type) {
            'client'            => [
                // 'phone'     => 'required|string|min:9|max:20',
                'address'   => 'nullable|string|min:1|max:255',
            ],
            'service-provider'  => [
                // 'phone'     => 'required|string|min:9|max:20',
                'address'   => 'nullable|string|min:1|max:255',
                'job' => 'nullable|string|max:255',
                'longitude'   => 'nullable|numeric',
                'latitude'   => 'required_with:longitude|numeric',
                'bank_account_number' => 'nullable|string|min:1|max:255',
            ],
            'admin'             => [
                'email'     => "nullable|email|unique:users,email,$user->id",
            ],
            default             => []
        };

        $validated = $request->validate([
            'name'      => 'nullable|string|min:1|max:255',
            'photo'     => 'nullable|image|max:4096',
            'area'      => 'nullable|string|min:1|max:255',
            'city' => 'nullable|string|min:1|max:255',
            ...$dynmicRules
        ]);

        $user->fill($request->only(['name']))->save();

        $user->location?->fill(
            $user->location?->getFillable() ?? []
        )->save();

        $account = $user->account();
        $accountData = collect($validated)
            ->except(['name'])
            ->only($account?->getFillable() ?? [])
            ->toArray();

        $account?->fill($accountData)->save();

        if ($request->hasFile('photo')) {
            $account?->addMedia($request->file('photo'))
                ->toMediaCollection('photo');
        }

        return $this->okResponse(UserResource::make($user), 'Updated profile data successfuly');
    }

    public function changePhoto(Request $request)
    {
        $request->validate([
            'photo'     => 'required|image',
        ]);

        $account = (Auth::user()?->account()) ?? Auth::user();

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

    public function destroyAccount()
    {
        try {
            $user       = Auth::user();
            $account    = $user->account();

            $userDeletedd   = $user->delete();
            $accountDeleted = $account->delete();

            return $this->okResponse([
                'user_deleted'       => $userDeletedd,
                'account_deleted'    => $accountDeleted
            ], 'Account deleted successfuly.');
        } catch (\Exception $e) {
            return $this->badResponse($e->getMessage());
        }
    }
    public function reports()
    {
        $user       = Auth::user();
        $account    = $user->account();

        $bookings = Booking::whereHas('payments')
            ->with(['client.user', 'service.serviceProvider.user', 'payments'])
            ->when($user->isAccount('client'), function ($q) use ($account) {
                $q->where('client_id', $account->id);
            })
            ->when($user->isAccount('service-provider'), function ($q) use ($account) {
                $q->whereHas('service.serviceProvider', fn($q) => $q->where('id', $account->id));
            })
            ->latest()
            ->limit(30)
            ->get();

        return $this->okResponse(
            BookingResource::collection($bookings),
            'Profile reports retrieved successfuly.'
        );
    }
}
