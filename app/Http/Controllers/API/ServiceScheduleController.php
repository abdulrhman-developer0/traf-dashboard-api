<?php

namespace App\Http\Controllers\API;

use App\Filters\Schedules\ProviderFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceScheduleResource;
use App\Models\ScheduleWorkTime;
use App\Models\ServiceSchedule;
use App\Traits\APIResponses;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceScheduleController extends Controller
{
    use APIResponses;

    public function index(Request $request)
    {
        if (! $request->has('service_id')) {
            return $this->badResponse([], 'The service_id filter is required to select schedules');
        }

        if (! $request->has('reference_id')) {
            return $this->badResponse([], 'The reference_id filter is required to select schedules');
        }



        if ($request->has('date')) {
            $date = Carbon::create($request->date);
        } else {
            $date = now();
        }

        $query = ServiceSchedule::query()
            ->where('service_id', $request->service_id)
            ->where('reference_id', $request->reference_id)
            ->where(function ($query) use ($date) {
                $query->where('start_Date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })->whereDoesntHave('excludedDates', function ($query) use ($date) {
                $query->where('start_Date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })->latest();

        $schedule = $query->with(['excludedDates', 'workTimes'])
            ->first();

        return $this->okResponse(ServiceScheduleResource::make($schedule), 'Retrieve times successfuly');
    }


    public function show($id)
    {
        $schedule = ServiceSchedule::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Service schedule not found'], 404);
        }

        return $this->okResponse(ServiceScheduleResource::make($schedule), 'Schedule retrieved successfuly');
    }


    public function store(Request $request)
    {
    
        $serviceProvider = FacadesAuth::user()->serviceProvider;

        $validated = $request->validate([
            'reference_id'      => $serviceProvider->is_personal ? 'nullable|integer' : 'required|integer',
            'service_id'        => 'required|integer|exists:services,id',
            'pattern'           => 'required|in:one-time,daily,repetition,manual',
            'start_date'        => 'required|date',
            'end_date'          => 'required_if:pattern,manual|date|after_or_equal:start_date',
            'exclude_limt' => 'required_if:pattern,repetition|integer|min:1', //if repetition
            'excluded_dates'    => 'nullable|array',  //ma3da 
            'excluded_dates.*'  => 'required|date',
            'times'             => 'required|array|min:1',
            'times.*'           => 'date_format:H:i',
        ]);

        $pattern   = $request->pattern;

        $startDate = Carbon::create($request->start_date);
        $endDate   = $request->end_date
            ? Carbon::create($request->end_date)
            : null;

        $excludeLimit   = $request->exclude_limit ?? 1;
        $excludedDates = $request->excluded_dates
            ? collect($request->excluded_dates)->map(function ($date) {
                $d = Carbon::create($date);
                return [
                    'start_date' => $d->startOfDay()->format('m/d/y h:i A'),
                    'end_date'   => $d->endOfDay()->format('m/d/y h:i A')
                ];
            }) 
            : [];

        $times  = $times = collect($request->times)->map(
            fn($time) => ['time' => $time]
        )->toArray(); // convert time 

        $planDays = 90;
        $endDate = match ($pattern) {
            "one-time"      => $startDate->copy()->addDay(),
            "daily"         => $startDate->copy()->addDays($planDays),
            'repetition'    => $startDate->copy()->addDays($planDays),
            'manual'        => $endDate
        };

        $schedule = ServiceSchedule::create([
            'reference_id'       => $serviceProvider->is_personal ? $serviceProvider->id : $request->reference_id,
            'service_id'         => $request->service_id,
            'start_date'         => $startDate,
            'end_date'           => $endDate
        ]);



        if ($pattern == 'repetition') {
            $start = $startDate->copy();
            // Exclude Limit ==> every day,2days,week 
            while ($start < $endDate) {
                // end of chunk 
                $end = $start->copy()->addDays($excludeLimt + 1);

                $excludedDates[] = [

                    // if i start from 1 for every 2 days it will start from 2 and end at 3 day 
                    'start_date' => $start->copy()->addDay()->startOfDay(),
                    'end_date'   => $end->endOfDay()
                ];
                // start again from 4 day 
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
            'pattern'           => 'required|in:one-time,daily,repetition,manual',
            'start_date'        => 'required|date',
            'end_date'          => 'required_if:pattern,manual|date|after_or_equal:start_date',
            'exclude_limit' => 'required_if:pattern,repetition|integer|min:1',
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

        $excludeLimit   = $request->exclude_limit ?? 1;
        $excludedDates = $request->excluded_dates
            ? collect($request->excluded_dates)->map(function ($date) {
                $d = Carbon::create($date);
                return [
                    'start_date' => $d->startOfDay()->format('m/d/y h:i A'),
                    'end_date'   => $d->endOfDay()->format('m/d/y h:i A')
                ];
            })
            : [];

        $times  = $times = collect($request->times)->map(
            fn($time) => ['time' => $time]
        )->toArray();

        $planDays = 90;
        $endDate = match ($pattern) {
            "one-time"      => $startDate->copy()->addDay(),
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
                $end = $start->copy()->addDays($excludeLimit + 1);

                $excludedDates[] = [
                    'start_date' => $start->copy()->addDay()->startOfDay()->format('m/d/y h:i A'),
                    'end_date'   => $end->endOfDay()->format('m/d/y h:i A')
                ];

                $start->addDays($excludeLimit + 1);
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
