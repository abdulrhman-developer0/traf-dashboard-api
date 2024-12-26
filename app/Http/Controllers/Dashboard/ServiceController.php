<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestServiceCollection;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $total_services = Service::whereYear('created_at', $year)->count();

        $stats = [
            'total_services'  => $total_services,
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
            ->get()
            ->map(function ($category) {
                $category['percentage'] = (float) $category['percentage'];
                return $category;
            });

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
            ->whereYear('created_at', '=', $year)
            ->latest()
            ->with(['category'])
            ->paginate(4);


        $data = [
            'stats' => $stats,
            'category_stats' => $category_stats,
            'chart' => $chart,
            'services' => LatestServiceCollection::make($services_paginated),
        ];

        return $data;
    }
}
