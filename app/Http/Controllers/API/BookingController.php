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

    public function index(Request $request)
    {
        $query = Booking::query()
            ->with(['client', 'service']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

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


        return $this->createdResponse([], 'Booking created successfully');
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (! $booking ) {
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
