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

    public function __construct()
    {

        // public methods
        $this->middleware(['auth:sanctum', 'account:service-provider', 'valid_subscribtion'])->only([
            'store',
            'update',
            'destroy',
        ]);
    }

    public function index(Request $request)
    {
        if (! $request->has('service_id')) {
            return $this->badResponse([], 'The service_id filter is required to select schedules');
        }



        if ($request->has('date')) {
            $date = Carbon::create($request->date);
        } else {
            $date = now();
        }

        $query = ServiceSchedule::query()
            ->where('service_id', $request->service_id)
            ->when($request->has('reference_id'), function ($query) use ($request) {
                $query->where('reference_id', $request->reference_id);
            })
            ->where(function ($query) use ($date) {
                $query->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })->latest();

        $schedule = $query->with(['excludedDates', 'customWorkDates.times'])
            ->first();


        if ($schedule) {
            $schedule['is_excluded'] = $schedule->excludedDates()
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->count() > 0;

            $customQuery = $schedule->customWorkDates()
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->limit(1);

            $schedule['is_custom']  = (bool) $customQuery->count() > 0;
            if ($schedule->is_custom) {
                $schedule['work_times'] = $customQuery->get();
            }
        }

        return $schedule->toArray();

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
            'exclude_limit' => 'required_if:pattern,repetition|integer|min:1', //if repetition
            'excluded_dates'    => 'nullable|array',  //ma3da 
            'excluded_dates.*'  => 'required|date',
            'times'             => 'nullable|array',
            'times.*'           => 'date_format:H:i',

            // custom dates
            'custom_dates'          => 'nullable|array',
            'custom_dates.*.date'   => 'required|date',
            'custom_dates.*.times' => 'required|array|min:1',
            'custom_dates.*.times.*' => 'date_format:H:i',
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

        $customDates = $request->has('custom_dates')
            ? collect($request->custom_dates)->map(
                fn($date) => [
                    'start_date' => Carbon::create($date['date'])->startOfDay()->format('m/d/y h:i A'),
                    'end_date'   => Carbon::create($date['date'])->endOfDay()->format('m/d/y h:i A'),
                    'times' => collect($date['times'])->map(
                        fn($time) => ['time' => $time]
                    )->toArray(),
                ]
            )->toArray()
            : [];

        $subscription = $serviceProvider->currentSubscription;
        if (! $subscription) {
            return $this->badResponse([], 'You must have a subscription to add a schedule');
        }

        $planDays = $subscription->package->duration_in_days;

        $endDate = match ($pattern) {
            "one-time"      => $startDate->copy()->endOfDay(),
            "daily"         => $startDate->copy()->addDays($planDays)->endOfDay(),
            'repetition'    => $startDate->copy()->addDays($planDays)->endOfDay(),
            'manual'        => $endDate->copy()->endOfDay()
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
                $end = $start->copy()->addDays($excludeLimit + 1);

                $excludedDates[] = [

                    // if i start from 1 for every 2 days it will start from 2 and end at 3 day 
                    'start_date' => $start->copy()->addDay()->startOfDay(),
                    'end_date'   => $end->endOfDay()
                ];
                // start again from 4 day 
                $start->addDays($excludeLimit + 1);
            }
        }

        if (! empty($excludedDates)) {
            $schedule->excludedDates()->createMany($excludedDates);
        }

        $schedule->workTimes()->createMany($times);

        if (! empty($customDates)) {
            foreach ($customDates as $customDate) {
                $schedule->customWorkDates()->create([
                    'start_date' => $customDate['start_date'],
                    'end_date'   => $customDate['end_date'],
                ])->times()->createMany($customDate['times']);
            }
        }

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
            'times'             => 'nullable|array',
            'times.*'           => 'date_format:H:i',

            // custom dates
            'custom_dates'          => 'nullable|array',
            'custom_dates.*.date'   => 'required|date',
            'custom_dates.*.times' => 'required|array',
            'custom_dates.*.times.*' => 'date_format:H:i',
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

        $customDates = $request->has('custom_dates')
            ? collect($request->custom_dates)->map(
                fn($date) => [
                    'start_date' => Carbon::create($date['date'])->startOfDay()->format('m/d/y h:i A'),
                    'end_date'   => Carbon::create($date['date'])->endOfDay()->format('m/d/y h:i A'),
                    'times' => collect($date['times'])->map(
                        fn($time) => ['time' => $time]
                    )->toArray(),
                ]
            )->toArray()
            : [];

        $subscription = $schedule->service->serviceProvider->currentSubscription;
        if (! $subscription) {
            return $this->badResponse([], 'You must have a subscription to add a schedule');
        }

        $planDays = $subscription->package->duration_in_days;

        $endDate = match ($pattern) {
            "one-time"      => $startDate->copy()->endOfDay(),
            "daily"         => $startDate->copy()->addDays($planDays)->endOfDay(),
            'repetition'    => $startDate->copy()->addDays($planDays)->endOfDay(),
            'manual'        => $endDate->copy()->endOfDay()
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

        if (! empty($customDates)) {
            $schedule->customWorkDates()->delete();
            foreach ($customDates as $customDate) {
                $schedule->customWorkDates()->create([
                    'start_date' => $customDate['start_date'],
                    'end_date'   => $customDate['end_date'],
                ])->times()->createMany($customDate['times']);
            }
        }

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
