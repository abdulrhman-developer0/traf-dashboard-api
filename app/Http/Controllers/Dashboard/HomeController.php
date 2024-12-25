<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestBookingsCollection;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $users_count = User::whereYear('created_at', '=', $year)->count();
        $visitors_count = 2100;
        $providers_count = ServiceProvider::whereYear('created_at', '=', $year)->count();
        $bookings_count = Booking::whereYear('created_at', '=', $year)->count();
        $services_count = Service::count();

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

        $bookings_paginated = Booking::query()
            ->select(['id', 'client_id', 'service_id', 'date', 'status'])
            ->whereYear('created_at', '=', $year)
            ->latest()
            ->with('client.user', 'service.serviceProvider.user')
            ->paginate(3);

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

        $data = [
            'stats' => [
                'users_count' => $users_count,
                'visitors_count' => $visitors_count,
                'providers_count' => $providers_count,
                'bookings_count' => $bookings_count,
                'services_count' => $services_count,
            ],
            'category_stats' => $category_stats,
            'chart' => $chart,
            'bookings' => LatestBookingsCollection::make($bookings_paginated),
        ];


        return Inertia::render('index', [
            'data' => $data,
            'title' => 'Dashboard'
        ]);
    }
}
