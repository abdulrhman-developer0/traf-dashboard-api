<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestServiceCollection;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $total_services = Service::count();

        $stats = [
            'total_services'  => $total_services,
        ];

        $start = now()->subMonths(11)->startOfMonth()->year($year);
        $end = now()->startOfMonth()->year($year);
        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }

        $actualData = Payment::query()
            ->join('bookings', 'bookings.id', '=', 'payments.booking_id')
            ->selectRaw('DATE_FORMAT(bookings.date, "%Y-%m") as month, SUM(payments.amount) as total_amount')
            ->whereYear('bookings.date', '=', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_amount', 'month')
            ->toArray();

        $chart = collect($months)->map(function ($month) use ($actualData) {
            return [
                'month' => $month,
                'total_amount' => $actualData[$month] ?? 0,
            ];
        })->toArray();

        $services_paginated = Service::query()
            ->whereYear('created_at', '=', $year)
            ->latest()
            ->with(['category'])
            ->paginate(4);


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'services' => LatestServiceCollection::make($services_paginated),
        ];

        return $data;
    }
}
