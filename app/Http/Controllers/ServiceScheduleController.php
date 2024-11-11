<?php

namespace App\Http\Controllers;

use App\Models\ServiceSchedule;
use Illuminate\Http\Request;

class ServiceScheduleController extends Controller
{
    
    
    public function index()
    {
        $schedules = ServiceSchedule::all();
        return response()->json($schedules);
    }

    
    public function show($id)
    {
        $schedule = ServiceSchedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Service schedule not found'], 404);
        }
        return response()->json($schedule);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_service_provider_id' => 'required|integer',
            'service_id' => 'required|integer|exists:services,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'in:available,off,booked',
        ]);

        $schedule = ServiceSchedule::create($validated);
        return response()->json(['message' => 'Service schedule created successfully', 'schedule' => $schedule], 201);
    }

    
    public function update(Request $request, $id)
    {
        $schedule = ServiceSchedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Service schedule not found'], 404);
        }

        $validated = $request->validate([
            'partner_service_provider_id' => 'integer',
            'service_id' => 'integer|exists:services,id',
            'date' => 'date',
            'time' => 'nullable',
            'status' => 'in:available,off,booked',
        ]);

        $schedule->update($validated);
        return response()->json(['message' => 'Service schedule updated successfully', 'schedule' => $schedule]);
    }

    
    public function destroy($id)
    {
        $schedule = ServiceSchedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Service schedule not found'], 404);
        }

        $schedule->delete();
        return response()->json(['message' => 'Service schedule deleted successfully']);
    }
}
