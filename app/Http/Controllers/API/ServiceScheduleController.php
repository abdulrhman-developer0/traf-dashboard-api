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
            'schedule_pattern' => 'required|in:daily,every_two_days,weekly,manual',
            'dates' => 'required|array',
            'dates.*.date' => 'required|date',
            'dates.*.times' => 'required|array|min:1',
            'dates.*.from_time' => 'required_if:schedule_pattern,manual|string',
            'dates.*.to_time' => 'required_if:schedule_pattern,manual|string',
            'status' => 'required|in:available,off,booked',
        ]);
    
        $schedules = [];
        $schedulePattern = $validated['schedule_pattern'];
    
        foreach ($validated['dates'] as $dateEntry) {
            $date = Carbon::parse($dateEntry['date']);
            $times = $dateEntry['times'];
            $from_time = $dateEntry['from_time'] ?? null;
            $to_time = $dateEntry['to_time'] ?? null;
    
            if ($schedulePattern === "manual") {
                $manualDates = [
                    'date' => $date->toDateString(),
                    'times' => $times,
                    'from_time' => $from_time,
                    'to_time' => $to_time,
                ];
    
                $schedule = ServiceSchedule::create([
                    'partner_service_provider_id' => $validated['partner_service_provider_id'],
                    'service_id' => $validated['service_id'],
                    'schedule_pattern' => $schedulePattern,
                    'dates' => json_encode([$manualDates]),
                    'status' => $validated['status'],
                ]);
    
                $schedules[] = $schedule;
            } else {
                $incrementDays = match ($schedulePattern) {
                    'daily' => 1,
                    'every_two_days' => 2,
                    'weekly' => 7,
                };
    
                $currentDate = $date;
                $monthlyDates = [];
                while ($currentDate->month === $date->month) {
                    foreach ($times as $time) {
                        $monthlyDates[] = [
                            'date' => $currentDate->toDateString(),
                            'time' => $time,
                        ];
                    }
                    $currentDate->addDays($incrementDays);
                }
    
                $schedule = ServiceSchedule::create([
                    'partner_service_provider_id' => $validated['partner_service_provider_id'],
                    'service_id' => $validated['service_id'],
                    'schedule_pattern' => $schedulePattern,
                    'dates' => json_encode($monthlyDates),
                    'status' => $validated['status'],
                ]);
    
                $schedules[] = $schedule;
            }
        }
    
        return response()->json([
            'message' => 'Service schedules created successfully',
            'schedules' => $schedules,
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
            'message' => 'Service schedule updated successfully',
            'initial_schedule' => $schedule,
            'additional_schedules' => $addtionalSchedules,
        ], 201);
       
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
