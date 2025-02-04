<?php

namespace App\Http\Controllers\API;

use App\Enums\ActivityActions;
use App\Events\PushNotification;
use App\Events\SendNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Client;
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

    public function __construct()
    {
        // protected methods
        $this->middleware('auth:sanctum');


        // this methods are only for client
        $this->middleware('account:client')->only([
            'store',
            'change'
        ]);

        $this->middleware('account:client,service-provider')->only([
            'update'
        ]);

        // this methods are only for service provider and admin
        $this->middleware('account:service-provider,admin')->only([
            'destroy',
        ]);
    }


    public function index(Request $request)
    {

        $user    = Auth::user();
        $account = $user->account();

        $query = Booking::query()
            ->latest()
            ->whereIn('status', ['confirmed', 'done'])
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

        $bookingData = [
            'client_id' => $user->client->id,
            'service_id' => $request->service_id,
            'reference_id' => $request->reference_id,
            'date' => Carbon::create($request->date)->toDateTimeString(),
            'address' => $request->address,
            'longitude'     => $request->longitude,
            'latitude'      => $request->latitude,
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
            'client'            => 'canceled',
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

                // call refund service here.
                // ??

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
