<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    use APIResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all clients with associated user data
        $clients = Client::with('user')->get();

        // Return response with clients data
        return $this->okResponse(
            ClientResource::collection($clients),
            'Retrieved Clients Data'
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

        // Create the user first (since the client depends on the user)
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        // Create the client associated with the user
        $client = Client::create([
            'user_id'       => $user->id, // Assuming a relationship between Client and User
            'phone'         => $request->phone,
            'address'       => $request->address,
        ]);

        // Return successful creation response
        return $this->createdResponse([], 'Created Client Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::find($id);

        if (! $client) {
            return $this->badResponse([], "No Client With id '{$id}'");
        }

        // Return the client details, including associated user data
        return $this->okResponse(
            ClientResource::make($client->load('user')),
            'Client Details Retrieved Successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::find($id);

        if (! $client) {
            return $this->badResponse([], "No Client With id '{$id}'");
        }

        $request->validate([
            'name'      => 'required|string|min:1|max:255',
            'phone'     => 'required|string|min:9|max:20',
            'address'   => 'required|string|min:1|max:255',
            'photo'     => 'nullable|image|max:4096',
        ]);


        $client->update($request->only(['phone', 'address']));

        if ($request->name) {
            $client->user->update([
                'name' => $request->name
            ]);
        }

        if ($request->photo) {
            $client->addMedia($request->photo)
                ->toMediaCollection('photo');
        }

        return $this->okResponse([], 'Client Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::find($id);

        if (! $client) {
            return $this->badResponse([], "No Client With id '{$id}'");
        }

        $user = $client->user;

        $client->delete();
        $user->delete();

        return $this->okResponse([], 'Client Deleted Successfully');
    }
}
