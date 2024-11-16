<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Traits\APIResponses;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    //
    use APIResponses;
    public function index()
    {
        $bookings = Booking::with('serviceSchedule')->get();
        return $this->okResponse($bookings, 'Retrieved all bookings successfully');
    }

    public function show($id)
    {
        $booking = Booking::with('serviceSchedule')->find($id);
    if (!$booking) {
        return $this->notFoundResponse('Booking not found');
    }
    return $this->okResponse($booking, 'Retrieved booking successfully');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_schedule_id' => 'required|integer|exists:service_schedules,id',
            'status' => 'in:pending,paid,canceled,done',
        ]);
    
        $booking = Booking::create($validated);
        return $this->createdResponse(['booking' => $booking], 'Booking created successfully');
    }
    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->notFoundResponse('Booking not found');
        }
    
        $validated = $request->validate([
            'service_schedule_id' => 'integer|exists:service_schedules,id',
            'status' => 'in:pending,paid,canceled,done',
        ]);
    
        $booking->update($validated);
        return $this->okResponse(['booking' => $booking], 'Booking updated successfully');
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
