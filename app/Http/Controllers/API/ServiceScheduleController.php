<?php

namespace App\Http\Controllers\API;

use App\Filters\Schedules\ProviderFilter;
use App\Http\Controllers\Controller;

use App\Models\ServiceSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceScheduleController extends Controller
{


    public function index(Request $request)
    {
        $query = ServiceSchedule::query();

        if ($request->has('serviceId')) {
            $query->whereServiceId($request->serviceId);
        }

        if ($request->has('providerId')) {
            $query->where('partner_service_provider_id', $request->providerId);
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('time')) {
            $query->whereDate('time', $request->time);
        }


        $schedules = $query->get();

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
            // 'status' => 'in:available,off,booked',
        ]);

        $schedule = ServiceSchedule::create($validated);
        $addtionalSchedules=[];
        $date=Carbon::parse($validated['date']);
        for($i=1;$i<=4;$i++){
            $newDate=$date->copy()->addDays(7*$i);
            if ($newDate->month != $date->month) {
                break; 
            }
            $addtionalSchedules[] = ServiceSchedule::create([
                'partner_service_provider_id' => $validated['partner_service_provider_id'],
                'service_id' => $validated['service_id'],
                'date' => $newDate,
                'time' => $validated['time'],
                'status' => 'available', 
            ]);

        }
        return response()->json([
            'message' => 'Service schedule created successfully',
            'initial_schedule' => $schedule,
            'additional_schedules' => $addtionalSchedules,
        ], 201);
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
            // 'status' => 'in:available,off,booked',
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
