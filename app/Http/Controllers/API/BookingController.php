<?php

namespace App\Http\Controllers\API;

use App\Events\PushNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
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
            // ->where('created_at', '>=', now()->subDays(90))
            ->with(['client', 'service'])
            ->with('reviews', function ($quary) use ($account) {
                $quary->where('reviewable_type', $account->getMorphClass())
                    ->where('reviewable_id', $account->id)->count();
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
            'date'         => 'required|date'
        ]);

        // get user
        $user                   = Auth::user();

        $bookingData = [
            'client_id' => $user->client->id,
            'service_id' => $request->service_id,
            'reference_id' => $request->reference_id,
            'date' => Carbon::create($request->date)->toDateTimeString(),
        ];

        $booking = Booking::create($bookingData);

        return $this->createdResponse([
            'booking_id' => $booking->id,
            'date'       => $booking->date->toDatetimeString(),
            'now'        => now()->toDatetimeString(),
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

        $booking->status = $request->status;
        $booking->save();

        $user = match ($user->account_type) {
            'client'            => $booking->service->serviceProvider->user,
            'service-provider'  => $booking->client->user,
            default             => null
        };

        if ($booking->status == 'canceled') {
            $notification = new PushNotification(
                $user,
                BookingResource::make($booking)->toArray($request),
            );

            broadcast($notification)->toOthers();
        }

        return $this->okResponse(BookingResource::make($booking), 'Booking updated successfully');
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
