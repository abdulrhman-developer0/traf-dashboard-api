<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
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
                'phone'     => 'required|string|min:9|max:20',
                'address'   => 'nullable|string|min:1|max:255',
            ],
            'service-provider'  => [
                'phone'     => 'required|string|min:9|max:20',
                'address'   => 'nullable|string|min:1|max:255',
                'job' => 'nullable|string|max:255',
            ],
            default             => []
        };

        $validated = $request->validate([
            'name'      => 'required|string|min:1|max:255',
            'photo'     => 'nullable|image|max:4096',
            'area'      => 'nullable|string|min:1|max:255',
            'city' => 'nullable|string|min:1|max:255',
            ...$dynmicRules
        ]);

        $user->fill($request->only(['name']))->save();

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

    public function destroyAccount()
    {
        try {
            $user       = Auth::user();
            $account    = $user->account();

            $accountDeleted = $account->delete();
            $userDeletedd   = $user->deleted  = $user->delete();

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

        try {
            $user = Auth::user();

            if ($user) {
                
                if ($user->isAccount('client')) {

                    $client = Client::where('user_id', $user->id)->first();
                    if ($client) {
                       
                        $bookings = $client->bookings()
                        ->where('status', 'confirmed')
                        ->with(['service.serviceProvider','payments'])
                        ->get();
            
                        return $bookings;
                    } else {
                      
                        return response()->json(['message' => 'Client not found'], 404);
                    }
                } 
                else if ($user->isAccount('service-provider')) {
                    $serviceProvider = ServiceProvider::where('user_id', $user->id)->first();
                    if ($serviceProvider) {
                        $services = Service::where('service_provider_id', $serviceProvider->id)->get();
                      
                        $allBookings = []; 
                        
                        foreach ($services as $service) {
                          
                            $bookings = Booking::where("service_id", $service->id)
                                ->where('status', 'confirmed')
                                ->with(['payments',"service","client"])
                                ->get();
                          
                            $allBookings = array_merge($allBookings, $bookings->toArray());
                        }
                    
                        return response()->json($allBookings);
                    }


                }
                else {
                    return response()->json(['message' => "you aren't allowd to access that"], 403);
                }
            } else {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
