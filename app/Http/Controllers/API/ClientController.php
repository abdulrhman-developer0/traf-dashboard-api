<?php

namespace App\Http\Controllers\API;

use App\Enums\ActivityActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\UserResource;
use App\Models\Client;
use App\Models\User;
use App\Notifications\TwoFactorNotification;
use App\Services\SmsService;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    use APIResponses;

    public function __construct(
        protected SmsService $sms
    ) {
        $this->middleware(['auth:sanctum'])->except(['store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Client::query()
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'))
            ->with('user');

        // Retrieve all clients with associated user data
        $clients = $query->get();

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
            'phone'                     => 'required|string|min:9|max:20|unique:users',
            'password'  => 'required|string|min:8|max:255|confirmed',
            'address'   => 'nullable|string|min:1|max:255',
        ]);

        // Create the user first (since the client depends on the user)
        $user = User::create([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'password'      => Hash::make($request->password),
            'account_type'  => 'client',
        ]);

        // Initialize wallet if not exists.
        $user->initializeWallet();

        // Create the client associated with the user
        $client = Client::create([
            'user_id'       => $user->id, // Assuming a relationship between Client and User
            'phone'         => $request->phone,
            'address'       => $request->address,
        ]);

        $data = [];

        $data['token'] = $user->createToken($user->phone)->plainTextToken;


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

        $time = now()->format('h:i a');
        activities(ActivityActions::NewClientRegistered, 'مستخدم جديد', "فام $user->name بالتسجيل في التطبيق في $time");



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

        if (config('app.env') !== 'production') {
            $data['test_code'] = $user->code;
        }


        $data['user'] = UserResource::make($user);

        // Return successful creation response
        return $this->createdResponse($data, 'Created Client Successfully');
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
        return; // disabled this action.

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
