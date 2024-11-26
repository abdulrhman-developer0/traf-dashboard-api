<?php

namespace App\Http\Controllers\API;

use App\Filters\Schedules\ProviderFilter;
use App\Http\Controllers\Controller;

use App\Models\ServiceSchedule;
use App\Traits\APIResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceScheduleController extends Controller
{
    use APIResponses;

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
            'service_worker_id' => 'required|integer',
            'service_id'        => 'required|integer|exists:services,id',
            'pattern'           => 'required|in:daily,repetition,manual',
            'start_date'        => 'required|date',
            'end_date'          => 'required_if:pattern,manual|date|after_or_equal:start_date',
            'exclude_limt' => 'required_if:pattern,repetition|integer|min:1',
            'excluded_dates'    => 'nullable|array',
            'excluded_dates.*'  => 'required|date',
            'times'             => 'required|array|min:1',
            'times.*'           => 'date_format:H:i',
        ]);

        $serviceWorkerId  = $request->serive_worker_id;
        $serviceId        = $request->service_id;
        $pattern   = $request->pattern;

        $startDate = Carbon::create($request->start_date);
        $endDate   = $request->end_date
            ? Carbon::create($request->end_date)
            : null;

        $excludeLimt   = $request->exclude_limt ?? 1;
        $excludedDates = $request->excluded_dates
            ? collect($request->excluded_dates)->map(function ($date) {
                $d = Carbon::create($date);
                return [
                    'start_date' => $d->startOfDay(),
                    'end_date'   => $d->endOfDay()
                ];
            })
            : [];

        $times  = $times = collect($request->times)->map(
            fn($time) => ['time' => $time]
        )->toArray();

        $planDays = 90;
        $endDate = match ($pattern) {
            "daily"         => $startDate->copy()->addDays($planDays),
            'repetition'    => $startDate->copy()->addDays($planDays),
            'manual'        => $endDate
        };


        $schedule = ServiceSchedule::create([
            'service_worker_id'  => $serviceWorkerId,
            'service_id'         => $serviceId,
            'start_date'         => $startDate,
            'end_date'           => $endDate
        ]);



        if ($pattern == 'repetition') {
            $start = $startDate->copy();

            while ($start < $endDate) {
                $end = $start->copy()->addDays($excludeLimt + 1);

                $excludedDates[] = [
                    'start_date' => $start->copy()->addDay()->startOfDay(),
                    'end_date'   => $end->endOfDay()
                ];

                $start->addDays($excludeLimt + 1);
            }
        }

        if (! empty($excludedDates)) {
            $schedule->excludedDates()->createMany($excludedDates);
        }

        $schedule->workTimes()->createMany($times);

        return $this->createdResponse([], 'Schedule created successfuly');
    }


    public function update(Request $request, $id)
    {
        $schedule = ServiceSchedule::find($id);

        if (! $schedule) {
            return $this->badResponse([], "No schedule with id $id");
        }

        $validated = $request->validate([
            'pattern'           => 'required|in:daily,repetition,manual',
            'start_date'        => 'required|date',
            'end_date'          => 'required_if:pattern,manual|date|after_or_equal:start_date',
            'exclude_limt' => 'required_if:pattern,repetition|integer|min:1',
            'excluded_dates'    => 'nullable|array',
            'excluded_dates.*'  => 'required|date',
            'times'             => 'required|array|min:1',
            'times.*'           => 'date_format:H:i',
        ]);

        $pattern   = $request->pattern;

        $startDate = Carbon::create($request->start_date);
        $endDate   = $request->end_date
            ? Carbon::create($request->end_date)
            : null;

        $excludeLimt   = $request->exclude_limt ?? 1;
        $excludedDates = $request->excluded_dates
            ? collect($request->excluded_dates)->map(function ($date) {
                $d = Carbon::create($date);
                return [
                    'start_date' => $d->startOfDay(),
                    'end_date'   => $d->endOfDay()
                ];
            })
            : [];

        $times  = $times = collect($request->times)->map(
            fn($time) => ['time' => $time]
        )->toArray();

        $planDays = 90;
        $endDate = match ($pattern) {
            "daily"         => $startDate->copy()->addDays($planDays),
            'repetition'    => $startDate->copy()->addDays($planDays),
            'manual'        => $endDate
        };


        $schedule->update([
            'start_date'         => $startDate,
            'end_date'           => $endDate
        ]);



        if ($pattern == 'repetition') {
            $start = $startDate->copy();

            while ($start < $endDate) {
                $end = $start->copy()->addDays($excludeLimt + 1);

                $excludedDates[] = [
                    'start_date' => $start->copy()->addDay()->startOfDay(),
                    'end_date'   => $end->endOfDay()
                ];

                $start->addDays($excludeLimt + 1);
            }
        }

        if (! empty($excludedDates)) {
            $schedule->excludedDates()->delete();
            $schedule->excludedDates()->createMany($excludedDates);
        }

        $schedule->workTimes()->delete();
        $schedule->workTimes()->createMany($times);

        return $this->okResponse([], 'Schedule updated successfuly');
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
