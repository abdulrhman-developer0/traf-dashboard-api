<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestServiceCollection;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    use APIResponses;

    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $year_total_services = Service::whereYear('created_at', $year)->count();

        $stats = [
            'year_total_services' => $year_total_services,
        ];

        $category_stats = ServiceCategory::query()
            ->join('services', 'services.service_category_id', '=', 'service_categories.id')
            ->select([
                'service_categories.id',
                'service_categories.name'
            ])
            ->selectRaw("
                (SELECT COUNT(*) FROM bookings WHERE bookings.service_id = services.id) as bookings_count,
                ((SELECT COUNT(*) FROM bookings WHERE bookings.service_id = services.id) / (SELECT COUNT(*) FROM bookings) * 100) as percentage
            ")
            ->take(6)
            ->get()
            ->map(function ($category) {
                $category['percentage'] = (float) $category['percentage'];
                return $category;
            })->sortByDesc('percentage')
            ->toArray();

        $start = now()->startOfYear();
        $end = now()->endOfYear();

        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('m');
            $start->addMonth();
        }

        $actualData = Payment::query()
            ->selectRaw('
                    DATE_FORMAT(created_at, "%m") as month
                ')
            ->whereYear('created_at', '=', $year)
            ->get()
            ->groupBy('month');

        $chart = collect($months)->map(function ($month) use ($actualData) {
            $actual = $actualData[$month] ?? collect();
            return $actual->count();
        })->toArray();


        $services_paginated = Service::query()
            ->latest()
            ->with(['category'])
            ->paginate(8);


        $data = [
            'stats' => $stats,
            'category_stats' => $category_stats,
            'chart' => $chart,
            'services' => LatestServiceCollection::make($services_paginated),
        ];

        // return Inertia::render('services/index', [
        //     'data' => $data,
        //     'year' => $year,

        //     'title' => 'Services'
        // ]);

        return $this->okResponse($data, 'Services data retrieved successfully');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'deletion_reason' => "required|string:max:500"
        ]);


        $service = Service::find($id);

        if (!$service) {
            return $this->notFoundResponse();
        }

        $service->update([
            'deletion_reason' => $request->input('deletion_reason', "NO Reason"),
            'deleted_at'      => now()
        ]);

        return $this->okResponse([], __('Service Deleted Successfuly'));
    }
}
