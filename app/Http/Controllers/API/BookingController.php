<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // this methods are only for service provider and admin
        $this->middleware('account:service-provider,admin')->only([
            'update',
            'destroy',
        ]);
    }


    public function index(Request $request)
    {

        $user    = Auth::user();
        $account = $user->account();

        $query = Booking::query()
            ->latest()
            ->where('created_at', '>=', now()->subDays(90))
            ->when(
                $user->isAccount('client'),
                fn($q) => $q->where('client_id', $account->id)
            )
            ->when(
                $user->isAccount('service-provider'),
                fn($q) => $q->whereHas(
                    'service',
                    fn($q) => $q->where('service_provider_id', $account->id)
                )
            )
            ->with(['client', 'service']);

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
        $bookingData = $validated = $request->validate([
            'service_id'   => 'required|integer|exists:services,id',
            'reference_id' => 'required|integer',
            'date'         => 'required|date'
        ]);

        // inject client id.
        $user                   = Auth::user();
        $validated['client_id'] = $user->client?->id;



        $booking = Booking::create($validated);


        return $this->createdResponse([
            'booking_id' => $booking->id,
        ], 'Booking created successfully');
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (! $booking) {
            return $this->badResponse('Booking not found');
        }

        $request->validate([
            'status' => 'in:canceled,done',
        ]);

        $booking->status = $request->status;
        $booking->save();

        return $this->okResponse([], 'Booking updated successfully');
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