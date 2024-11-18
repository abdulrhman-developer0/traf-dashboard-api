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

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::with('user');

        if ($request->has('search')) {
            $query->whereHas(
                'user',
                fn($q) => $q->where('name', 'like', "%{$request->search}%")
            );
        }



        // Retrieve all serviceProviders with associated user data
        if ($request->has('paginate')) {
            $serviceProviders = $query->paginate(10);
        } else {
            $serviceProviders = $query->get();
        }

        // Return response with serviceProviders data
        return $this->okResponse(
            ServiceProviderResource::collection($serviceProviders),
            'Retrieved ServiceProviders Data'
        );
    }

    /**
     *  Get Partner Service Providers
     */
    public function indexForPartners(string $id)
    {
        $partnerServiceProviders =  ServiceProvider::whereHas(
            'serviceProviderPartners',
            fn($q) => $q->whereServiceProviderId($id)
        )->get();

        return $this->okResponse(
            ServiceProviderResource::collection($partnerServiceProviders),
            'Retrieve Partner Service provider'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name'                      => 'required|string|min:1|max:255',
            'email'                     => 'required|email|min:5|max:255|unique:users,email',
            'password'                  => 'required|string|min:8|max:255|confirmed',
            'phone'                     => 'required|string|min:9|max:20',
            'is_personal'               => 'required|boolean',
            'tax_registeration_number'  => 'required_without:is_personal|string|min:1|max:255'
        ]);

        // Create the user first (since the serviceProvider depends on the user)
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        // Create the serviceProvider associated with the user
        $serviceProvider = ServiceProvider::create([
            'user_id'                       => $user->id, // Assuming a relationship between ServiceProvider and User
            'phone'                         => $request->phone,
            'is_personal'                   => $request->is_personal,
            'tax_registeration_number'      => $request->tax_registeration_number,
        ]);

        if (! $request->is_personal ) {
            $serviceProvider->serviceProviderPartners()->create([
                'partner_service_provider_id' => $serviceProvider->id,
            ]);
        }

        $data = [];

        if ( $request->has('withToken') ) {
            $data['token'] = $user->createToken($user->email)->plainTextToken;
        }

        // Return successful creation response
        return $this->createdResponse($data, 'Created ServiceProvider Successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $serviceProvider = ServiceProvider::find($id);

        if (! $serviceProvider) {
            return $this->badResponse([], "No Service Provider With id '{$id}'");
        }

        // Return the serviceProvider details, including associated user data
        return $this->okResponse(
            ServiceProviderResource::make($serviceProvider->load('user')),
            'ServiceProvider Details Retrieved Successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $serviceProvider = ServiceProvider::find($id);

        if (! $serviceProvider) {
            return $this->badResponse([], "No Service Provider With id '{$id}'");
        }

        $request->validate([
            'name'      => 'required|string|min:1|max:255',
            'phone'     => 'required|string|min:9|max:20',
            'address'   => 'nullable|string|min:1|max:255',
            'years_of_experience' => 'nullable|integer|between:1,100',
            'photo'               => 'nullable|image|max:4096',
            'partnerIds'          => 'nullable|array|min:1',
            'partnerIds.*'        => 'required|integer|exists:service_providers,id',
        ]);

        $serviceProvider->update($request->only(['phone', 'address', 'years_of_experience']));

        if ($request->name) {
            $serviceProvider->user->update([
                'name' => $request->name
            ]);
        }

        if ($request->photo) {
            $serviceProvider->addMedia($request->photo)
                ->toMediaCollection('photo');
        }

        if ($request->partnerIds) {
            $serviceProvider->syncPartners($request->partnerIds);
        }

        return $this->okResponse([], 'ServiceProvider Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $serviceProvider = ServiceProvider::find($id);

        if (! $serviceProvider) {
            return $this->badResponse([], "No Service Provider With id '{$id}'");
        }

        $user = $serviceProvider->user;

        $serviceProvider->delete();
        $user->delete();

        return $this->okResponse([], 'ServiceProvider Deleted Successfully');
    }
}
