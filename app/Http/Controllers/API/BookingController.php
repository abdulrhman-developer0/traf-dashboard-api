<?php

namespace App\Http\Controllers\API;

use App\Enums\ActivityActions;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Events\PushNotification;
use App\Events\SendNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Service;
use App\Models\ServiceProvider;
use App\Notifications\DBNotification;
use App\Notifications\PusherNotification;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    use APIResponses;
    protected  $apiUrl;
    protected $apiToken;
    protected $apiRequestTimeout;
    public function __construct()
    {
        $this->apiUrl = config('services.tamara.api_url');
        $this->apiToken = config('services.tamara.api_token');
        $this->apiRequestTimeout = config('services.tamara.api_request_timeout');

        // protected methods
        // $this->middleware('auth:sanctum');


        // // this methods are only for client
        // $this->middleware('account:client')->only([
        //     'store',
        //     'change'
        // ]);

        // $this->middleware('account:client,service-provider')->only([
        //     'update'
        // ]);

        // // this methods are only for service provider and admin
        // $this->middleware('account:service-provider,admin')->only([
        //     'destroy',
        // ]);
    }


    public function index(Request $request)
    {

        $user    = Auth::user();
        $account = $user->account();

        $query = Booking::query()
            ->latest()
            ->whereIn('status', ['confirmed', 'done', 'cash'])
            // ->where('created_at', '>=', now()->subDays(90))
            ->with(['client', 'service'])
            ->with('reviews', function ($quary) use ($user, $account) {
                $quary->where('reviewable_type', match ($user->account_type) {
                    'client'            => ServiceProvider::class,
                    'service-provider'  => Client::class,
                    default             => null
                });
            });

        // filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }


        // filter by client_id
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // filter by service_id
        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // filter by reference_id
        if ($request->has('reference_id')) {
            $query->where('reference_id', $request->reference_id);
        }

        // filter by provider_id
        if ($request->has('provider_id')) {
            $query->whereHas('service', fn($q) => $q->where('service_provider_id', $request->provider_id));
        }

        $bookings = $query->get();
        return $this->okResponse(BookingResource::collection($bookings), 'Retrieved all bookings successfully');
    }

    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }

        return $this->okResponse(BookingResource::make($booking), 'Retrieved booking successfully');
    }
    public function tamaraCancel($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        $booking->payment_status = 'canceled';
        $booking->save();
        return $this->okResponse([], __('Tamara Payment Canceled'));
    }
    public function tamaraFailure($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        $booking->payment_status = 'failed';
        $booking->save();
        return $this->okResponse([], __('Tamara Payment Failed'));
    }
    public function tamaraNotification($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        return $this->okResponse([], __('Tamara Payment Success'));
    }
    public function tamaraCreateCheckout($booking_id)
    {

        $booking = Booking::findOrFail($booking_id);
        $booking->payment_method = 'tamara';
        $booking->payment_status = 'pending';
        $booking->reference_payment = 'TAMARA-' . uniqid();
        $booking->save();

        $client_ = new \GuzzleHttp\Client();
        $service = $booking->service;
        $client = $booking->client;
        $user = $client->user;
        $currency = 'SAR';
        $response = $client_->request('POST', $this->apiUrl . '/checkout', [
            'body' => json_encode([
                'total_amount' => ['amount' => $service->payment_amount, 'currency' => $currency],
                'shipping_amount' => ['amount' => 1, 'currency' => $currency],
                'tax_amount' => ['amount' => 1, 'currency' => $currency],
                'order_reference_id' => $booking->reference_payment,
                'order_number' => $booking->id,
                'discount' => [],
                'items' => [[
                    'name' => $service->name,
                    'type' => 'Service',
                    'reference_id' => $service->id,
                    'sku' => 'S-' . $service->id . 'B-' . $booking->id,
                    'quantity' => 1,
                    'discount_amount' => [],
                    'tax_amount' => ['amount' => 0, 'currency' => $currency],
                    'unit_price' => ['amount' => $service->price_after, 'currency' => $currency],
                    'total_amount' => ['amount' => $service->price_after, 'currency' => $currency]
                ]],
                'consumer' => [
                    'email' => $user->email,
                    'first_name' => $user->name,
                    'last_name' => $user->name,
                    'phone_number' => $user->phone
                ],
                'country_code' => 'SA',
                'description' => $service->description,
                'merchant_url' => [
                    'cancel' => asset('api/bookings/tamara/' . $booking->id . '/cancel?type=tamara'),
                    'failure' => asset('api/bookings/tamara/' . $booking->id . '/failure?type=tamara'),
                    'success' => asset('api/bookings/tamara/' . $booking->id . '/success?type=tamara'),
                    'notification' => asset('api/bookings/tamara/' . $booking->id . '/notification?type=tamara')
                ],
                'payment_type' => 'PAY_BY_INSTALMENTS',
                'instalments' => 3,
                'billing_address' => [
                    'city' => $client->city,
                    'country_code' => 'SA',
                    'first_name' => $user->name,
                    'last_name' => $user->name,
                    'line1' => $client->address,
                    'phone_number' => $client->phone,
                    'region' => 'As Sulimaniyah'
                ],
                'shipping_address' => [
                    'city' => $client->city,
                    'country_code' => 'SA',
                    'first_name' => $user->name,
                    'last_name' => $user->name,
                    'line1' => $client->address,
                    'phone_number' => $client->phone,
                    'region' => 'As Sulimaniyah'
                ],
                'is_mobile' => true,
                'locale' => 'ar_SA'
            ]),
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->apiToken,
                'content-type' => 'application/json',
            ],
        ]);
        $body = json_decode($response->getBody()->getContents());
        return $this->okResponse($body, __('Tamara checkout created successfully'));
    }
    public function tamaraGetOrderDetails($booking_id)
    {

        $booking = Booking::findOrFail($booking_id);
        if ($booking->payment_method != 'tamara') {
            return $this->badResponse('Booking payment method is not tamara');
        }
        $booking->payment_status = 'pending';
        $booking->save();

        $client_ = new \GuzzleHttp\Client();
        $response = $client_->request('POST', $this->apiUrl . '/merchants/orders/reference-id/' . $booking->reference_payment, [
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->apiToken,
                'content-type' => 'application/json',
            ],
        ]);
        $body = json_decode($response->getBody()->getContents());
        if ($body->data->status != 'new') {
            $booking->payment_status = $body->data->status;
            $booking->save();
            return $this->okResponse($body, __('Tamara Payment Completed'));
        }
        return $this->okResponse($body, __('Tamara Payment Pending'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id'   => 'required|integer|exists:services,id',
            'reference_id' => 'nullable|integer',
            'date'         => 'required|date',
            'address'      => 'nullable|string:max:255',
            'longitude'     => 'numeric',
            'latitude'      => 'numeric',
        ]);

        // get user
        $user                   = Auth::user();
        $service = Service::findOrFail($request->service_id);
        $bookingData = [
            'client_id' => $user->client->id,
            'service_id' => $request->service_id,
            'reference_id' => $request->reference_id,
            'date' => Carbon::create($request->date)->toDateTimeString(),
            'address'           => $request->address,
            'longitude'         => $request->longitude,
            'latitude'          => $request->latitude,
            'payment_amount'    => $service->price_after,
        ];

        $booking = Booking::create($bookingData);


        activities(ActivityActions::ServiceBooked, 'حجز جديد', "قام $user->name بحجز موعد {$booking->service->name}");

        return $this->createdResponse([
            'booking_id' => $booking->id,
            'date'       => $booking->date->toDatetimeString(),
            'now'        => now()->toDatetimeString(),
            'address'    => $booking->address
        ], 'Booking created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $allowedStatuses = match ($user->account_type) {
            'client'            => 'canceled,cash',
            'service-provider' => 'done,canceled',
            'admin'             => '',
        };

        $booking = Booking::find($id);

        if (! $booking) {
            return $this->badResponse('Booking not found');
        }

        $request->validate([
            'status' => "required|in:$allowedStatuses",
        ]);

        $status = $request->status;

        $totalBookingCanceledToday = Booking::query()
            ->when(
                $user->account_type == 'service-provider',
                fn($q) => $q->whereHas(
                    'service',
                    fn($q) => $q->where('service_provider_id', $user->serviceProvider->id)
                )
            )
            ->when(
                $user->account_type == 'client',
                fn($q) => $q->where('client_id', $user->client->id)
            )
            ->whereDate('canceled_at', now())
            ->count();



        // You can't cancel or change the current booking
        if ($status == 'canceled' && $totalBookingCanceledToday >= 1) {
            return $this->badResponse([
                'reason'    => 'cancel_limitation_error'
            ], "You can't cancel or change the current booking");
        }


        $booking->status = $status;
        $booking->save();

        if ($booking->status == 'canceled') {

            $booking->canceled_at = now();
            $booking->save();


            $targetUser = match ($user->account_type) {
                'client'            => $booking->service->serviceProvider->user,
                'service-provider'  => $booking->client->user,
                default             => null
            };

            activities(ActivityActions::ServiceCanceled, 'الغاء حجز', "قام $user->name بالغاء {$booking->service->name} مع $targetUser->name");

            $title   = 'تم الغاء الموعد';
            $message = __('mobile.canceled_booking', [
                'service_name' => $booking->service->name,
                'name' => $user->name,
                'time' => $booking->date->format('h:i A')
            ], 'ar');

            $data = [
                'status' => 'canceled',
                'date' => $booking->date,
                'sent_at' => now(),
                'title' => $title,
                'message' => $message,
                'user' => [
                    'id'            => (int) $user->id,
                    'account_id'    => (int) $user->account()?->id,
                    'name'          => $user->name
                ]
            ];


            //notify the user in database.
            $targetUser->notify(new DBNotification($data));

            // notify the user in FCM
            app('App\Http\Controllers\API\FcmController')->sendFcmNotification(
                $targetUser->id,
                $title,
                $message
            );

            $leftHours = now()->diffInHours($booking->date);
            // $leftHours = 2;

            $refundPenaltyPercentage = $leftHours < 2
                ? 100
                : 10;

            $payment    = $booking->payments;

            $refundedAmount = $payment->amount * (1 - $refundPenaltyPercentage / 100);

            if ($refundedAmount > 0) {
                $clientUser = $booking->client->user;

                // call refund service here.
                $clientUser->wallet->transactions()->create([
                    'transaction_type'  => TransactionType::REFUND,
                    'status'            => TransactionStatus::COMPLETED,
                    'amount'            => $refundedAmount,
                    'reference_id'      => $payment->id
                ]);

                $clientUser->wallet->increment('balance', $refundedAmount);

                activities(ActivityActions::RefundProcessed, 'عملية استرجاع اموال', "تم ارجاع مبلغ $refundedAmount ريال الي {$booking->client->user->name}");

                $payment->payment_status        = 'refund';
                $payment->refunded_amount     = $refundedAmount;
                $payment->save();
            };

            return $this->okResponse([
                'amount'                => $payment->amount,
                'refunded_amount'       => $refundedAmount,
                'penalty_percentage'    => $refundPenaltyPercentage
            ], 'Booking canceled successfuly');
        }

        return $this->okResponse(BookingResource::make($booking), 'Booking updated successfully');
    }

    public function change(Request $request)
    {
        $validated = $request->validate([
            'booking_id'   => 'required|integer|exists:bookings,id',
            'date'         => 'required|date',
            'address'      => 'nullable|string:max:255'
        ]);


        // get user
        $user                   = Auth::user();

        // get client form account.
        $client = $user->account();

        $booking = Booking::whereId($request->booking_id)
            ->whereClientId($client->id)
            ->first();

        if (! $booking) {
            return $this->badResponse([
                'reason' => 'booking_not_created_by_account',
            ], "Can'Booking with id $request->booking_id not created by this account");
        }

        if (now()->diffInHours($booking->date) < 2) {
            return $this->badResponse(
                [
                    'reason' => 'booking_too_close',
                ],
                "Cannot modify booking with ID $request->booking_id because it is within 2 hours of the scheduled time."
            );
        }

        // make array of updated data.
        $bookingData = $request->only(['date', 'address']);

        // inject changed at
        $bookingData['changed_at'] = now();

        // update the booking data
        $booking->update($bookingData);

        return $this->createdResponse([
            'booking_id' => $booking->id,
            'date'       => $booking->date->toDatetimeString(),
            'now'        => now()->toDatetimeString(),
            'address'    => $booking->address,
            'changed_at' => $booking->changed_at
        ], 'Booking changed successfully');
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }

        $booking->delete();
        return $this->okResponse([], 'Booking deleted successfully');
    }
}
