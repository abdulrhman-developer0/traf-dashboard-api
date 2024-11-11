<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    //
    public function index()
    {
        $bookings = Booking::with('serviceSchedule')->get(); // Include related service schedule
        return response()->json($bookings);
    }

    public function show($id)
    {
        $booking = Booking::with('serviceSchedule')->find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }
        return response()->json($booking);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_schedule_id' => 'required|integer|exists:service_schedules,id',
            'status' => 'in:pending,paid,canceled,done',
        ]);

        $booking = Booking::create($validated);
        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    }
    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $validated = $request->validate([
            'service_schedule_id' => 'integer|exists:service_schedules,id',
            'status' => 'in:pending,paid,canceled,done',
        ]);

        $booking->update($validated);
        return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking]);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
