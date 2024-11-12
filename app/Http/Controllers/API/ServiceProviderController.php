<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceProviderResource;
use App\Models\ServiceProvider;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ServiceProviderController extends Controller
{
    use APIResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all serviceProviders with associated user data
        $serviceProviders = ServiceProvider::with('user')->get();

        // Return response with serviceProviders data
        return $this->okResponse(
            ServiceProviderResource::collection($serviceProviders),
            'Retrieved ServiceProviders Data'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name'      => 'required|string|min:1|max:255',
            'email'     => 'required|email|min:5|max:255',
            'password'  => 'required|string|min:8|max:255|confirmed',
            'phone'     => 'required|string|min:9|max:20',
            'address'   => 'nullable|string|min:1|max:255',
        ]);

        // Create the user first (since the serviceProvider depends on the user)
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        // Create the serviceProvider associated with the user
        $serviceProvider = ServiceProvider::create([
            'user_id'       => $user->id, // Assuming a relationship between ServiceProvider and User
            'phone'         => $request->phone,
            'address'       => $request->address,
        ]);

        $serviceProvider->serviceProviderPartners()->create([
            'partner_service_provider_id' => $serviceProvider->id,
        ]);

        // Return successful creation response
        return $this->createdResponse([], 'Created ServiceProvider Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceProvider $serviceProvider)
    {
        // Return the serviceProvider details, including associated user data
        return $this->okResponse(
            ServiceProviderResource::make($serviceProvider->load('user')),
            'ServiceProvider Details Retrieved Successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceProvider $serviceProvider)
    {

        $request->validate([
            'name'      => 'required|string|min:1|max:255',
            'phone'     => 'required|string|min:9|max:20',
            'address'   => 'nullable|string|min:1|max:255',
        ]);

        $serviceProvider->update($request->only(['phone', 'address']));

        return $this->okResponse([], 'ServiceProvider Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceProvider $serviceProvider)
    {

        $user = $serviceProvider->user;

        $serviceProvider->delete();
        $user->delete();

        return $this->okResponse([], 'ServiceProvider Deleted Successfully');
    }
}
