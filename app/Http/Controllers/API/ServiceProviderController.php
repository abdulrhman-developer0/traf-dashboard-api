<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceProviderResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkerResource;
use App\Models\ServiceProvider;
use App\Models\User;
use App\Models\Worker;
use App\Notifications\JoinRequestNotification;
use App\Notifications\TwoFactorNotification;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class ServiceProviderController extends Controller
{
    use APIResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['store']);

        // $this->middleware('account:admin')->except(['update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::query();

        // filter by category
        if ($request->has('category_id')) {
            $query->whereHas('services.category', fn($q) => $q->whereId($request->category_id));
        }

        // filter by search
        if ($request->has('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhere('name', 'REGEXP', "[$search]")
                        ->orderByRaw("
                            CASE
                                WHEN users.name LIKE ? THEN 1
                                WHEN users.name LIKE ? THEN 2
                                WHEN users.name REGEXP ? THEN 3
                                ELSE 4
                            END
                    ", ["$search%", "%$search%", "[$search]"]);
                })->orWhereHas('services', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhereHas('category', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "$search%")
                                ->orWhere('name', 'LIKE', "%$search%");
                        });
                });
            });
        }

        // filter by city
        if ($request->has('city')) {
            $query->where('city', 'like', "$request->city%")
                ->orWhere('city', 'regexp', "[$request->city]");
        }

        // filter by area
        if ($request->has('area')) {
            $query->where('area', 'like', "$request->area%")
                ->orWhere('area', 'regexp', "[$request->area]");
        }

        //filter by pricing
        // 100-200
        if ($request->has('pricing')) {

            $pricingRange = collect(
                explode('-', trim($request->pricing, '- '))
            )->map(
                fn($v) => trim($v, '- ')
            )->toArray();

            $query->whereHas('services', function ($q) use ($pricingRange) {
                $q->whereBetween('price_before', $pricingRange)
                    ->orWhereBetween('price_after', $pricingRange);
            });
        }

        //filter by rating
        if ($request->has('rating')) {
            $query->where('rating', 'like', "{$request->rating}%")
                ->orderBy('rating', 'desc');
        }




        $serviceProviders = $query->with('user')
            ->get(['id', 'user_id', 'rating']);

        return $this->okResponse([
            'provider_ids'  => $serviceProviders->pluck('id')->join(','),
            'items'         => ServiceProviderResource::collection($serviceProviders),
        ], 'Retrieved ServiceProviders Data');
    }

    /**
     *  Get Partner Service Providers
     */
    public function indexForWorkers(string $id)
    {
        $Workers =  Worker::whereServiceProviderId($id)->get();

        return $this->okResponse(
            WorkerResource::collection($Workers),
            'Retrieve Partner Service provider'
        );
    }

    public function indexForAddresses(string $id)
    {
        $addresses =  ServiceProvider::whereHas('workers', fn($q) => $q->whereServiceProviderId($id))
            ->whereNotNull('address')
            ->pluck('address');

        return $this->okResponse(
            $addresses,
            'Retrieve service provider addresses successfuly'
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
            'maroof_document'           => 'required_if:is_personal,true|file|mimes:jpg,png,pdf|max:4096',
            'tax_registeration_number'  => 'required_if:is_personal,false|string|min:1|max:255',
            'accept_policy'             => 'required|boolean',
        ]);

        if (! $request->accept_policy) {
            return $this->badResponse([], 'Plese accept policy to create an account');
        }

        // Create the user first (since the serviceProvider depends on the user)
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'account_type'  => 'service-provider',
        ]);

        // Create the serviceProvider associated with the user
        $serviceProvider = ServiceProvider::create([
            'user_id'                       => $user->id, // Assuming a relationship between ServiceProvider and User
            'phone'                         => $request->phone,
            'is_personal'                   => $request->is_personal,
            'tax_registeration_number'      => $request->tax_registeration_number,
        ]);

        if ($request->is_personal && $request->hasFile('maroof_document')) {
            $serviceProvider->addMedia($request->maroof_document)
                ->toMediaCollection('maroof_document');
        }

        $user->generateCode();



        //send mail 
        $user->notify(new TwoFactorNotification());

        Notification::send(
            User::whereAccountType('admin')->get(),
            new JoinRequestNotification($user)
        );



        // send mobile
        //    $message="رمز التحقق هو ".$user->code;
        //    $account_sid=getenv("TWILIO_SID");
        //    $auth_token=getenv("TWILIO_TOKEN");
        //    $twilio_number=getenv("TWILIO_FROM");
        //    $client=new Client($account_sid,$auth_token);
        //    $client->messages->create('+2001027629534',[
        //     'from'=> $twilio_number,
        //     'body' => $message
        //    ]);

        $data = [];

        $data['token'] = $user->createToken($user->email)->plainTextToken;

        if (config('app.env') !== 'production') {
            $data['test_code'] = $user->code;
        }

        $data['user'] = UserResource::make($user);


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
        return; // disabled this action.

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
