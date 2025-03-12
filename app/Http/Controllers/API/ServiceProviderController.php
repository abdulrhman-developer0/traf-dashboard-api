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
use App\Services\SmsService;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class ServiceProviderController extends Controller
{
    use APIResponses;

    public function __construct(
        protected SmsService $sms
    ) {
        $this->middleware(['auth:sanctum'])->except(['index', 'indexWithLocations', 'store']);

        // $this->middleware('account:admin')->except(['update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::query()
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'));

        // filter by category
        if ($request->input('category_id') != null) {
            $query->whereHas('services.category', fn($q) => $q->whereId($request->category_id));
        }

        // filter by search
        if ($request->input('search') != null) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        // ->orWhere('name', 'REGEXP', "[$search]")
                        ->orderByRaw("
                            CASE
                                WHEN users.name LIKE ? THEN 1
                                WHEN users.name LIKE ? THEN 2
                                ELSE 3
                            END
                    ", ["$search%", "%$search%"]);
                });
            });
        }

        if ($request->input('longitude') && $request->input('latitude')) {

            // distance in km.
            $km = $request->input('km', 10);

            // km to geo degrees.
            $degrees =  $km / 111;

            // longitude range.
            $longitudeRange = [
                ($request->input('longitude', 0.1) - $degrees),
                ($request->input('longitude', 0.1) + $degrees)
            ];

            // latitude range.
            $latitudeRange = [
                ($request->input('latitude', 0.1) - $degrees),
                ($request->input('latitude', 0.1) + $degrees)
            ];

            $query->whereHas('user.location', function ($q) use ($longitudeRange, $latitudeRange) {
                $q->whereBetween('longitude', $longitudeRange)
                    ->whereBetween('latitude', $latitudeRange);
            });
        }

        // filter by city
        if ($request->input('city') != null) {
            $query->where('city', 'like', "$request->city%")
                ->orWhere('city', 'regexp', "[$request->city]");
        }

        // filter by area
        if ($request->input('area') != null) {
            $query->where('area', 'like', "$request->area%")
                ->orWhere('area', 'regexp', "[$request->area]");
        }

        //filter by pricing
        // 100-200
        if ($request->input('pricing') != null) {

            $pricingRange = collect(
                explode('-', trim($request->pricing, '- '))
            )->map(
                fn($v) => trim($v, '- ')
            );

            $query->when(
                $pricingRange->count() == 2,
                fn($q) => $q->whereHas(
                    'services',
                    fn($q) => $q->whereBetween('price_before', $pricingRange)
                        ->orWhereBetween('price_after', $pricingRange)
                )
            )->when(
                $pricingRange->count() == 1,
                fn($q) => $q->whereHas(
                    'services',
                    fn($q) => $q->where('price_before', '>=', $pricingRange[0])
                        ->orWhere('price_after', '>=', $pricingRange[0])
                )
            );
        }

        //filter by rating
        if ($request->input('rating') != null) {
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

    public function indexWithLocations(Request $request)
    {
        $query = ServiceProvider::query()
            ->select(['id', 'user_id', 'rating'])
            ->with('user');

        if ($request->input('longitude') && $request->input('latitude')) {

            // distance in km.
            $km = $request->input('km', 10);

            // km to geo degrees.
            $degrees =  $km / 111;

            // longitude range.
            $longitudeRange = [
                ($request->input('longitude', 0.1) - $degrees),
                ($request->input('longitude', 0.1) + $degrees)
            ];

            // latitude range.
            $latitudeRange = [
                ($request->input('latitude', 0.1) - $degrees),
                ($request->input('latitude', 0.1) + $degrees)
            ];

            $query->whereHas('user.location', function ($q) use ($longitudeRange, $latitudeRange) {
                $q->whereBetween('longitude', $longitudeRange)
                    ->whereBetween('latitude', $latitudeRange);
            });
        }

        // filter by search
        if ($request->input('search') != null) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        // ->orWhere('name', 'REGEXP', "[$search]")
                        ->orderByRaw("
                            CASE
                                WHEN users.name LIKE ? THEN 1
                                WHEN users.name LIKE ? THEN 2
                                ELSE 3
                            END
                    ", ["$search%", "%$search%"]);
                });
            });
        }


        $serviceProviders = $query->get();

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
            'phone'                     => 'required|string|min:9|max:20|unique:users',
            'password'                  => 'required|string|min:8|max:255|confirmed',
            'is_personal'               => 'required|boolean',
            'maroof_document'           => 'required_if:is_personal,true|file|mimes:jpg,png,pdf|max:4096',
            'tax_registeration_number'  => 'required_if:is_personal,false|string|min:1|max:255',
            'accept_policy'             => 'required|boolean',

            'longitude'                 => 'required|numeric',
            'latitude'                      => 'required|numeric'
        ]);

        if (! $request->accept_policy) {
            return $this->badResponse([], 'Plese accept policy to create an account');
        }

        // Create the user first (since the serviceProvider depends on the user)
        $user = User::create([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'password'      => Hash::make($request->password),
            'account_type'  => 'service-provider',
        ]);

        // Initialize wallet if not exists.
        $user->initializeWallet();

        $user->location()->updateOrCreate(
            $request->only([
                'longitude',
                'latitude'
            ])
        );

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



        try {
            //send mail 
            // $user->notify(new TwoFactorNotification());

            $this->sms->send(
                $user->phone,
                "كود التحقق الخاص بك هو $user->code"
            );
        } catch (\Throwable $throwable) {
            // 
        }

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

        $data['token'] = $user->createToken($user->phone)->plainTextToken;

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
