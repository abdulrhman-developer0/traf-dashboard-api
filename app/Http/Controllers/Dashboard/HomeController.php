<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestBookingsCollection;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        $year = $request->input('year', now()->year);

        $users_count = User::where('account_type', '!=', 'admin')->count();
        $clients_count = Client::count();
        $providers_count = ServiceProvider::count();
        $bookings_count = Booking::count();

        $year_bookings_count = Booking::whereYear('created_at', $year)->count();

        $stats = [
            'users_count' => $users_count,
            'clients_count' => $clients_count,
            'providers_count' => $providers_count,
            'bookings_count' => $bookings_count,
            'year_bookings_count' => $year_bookings_count,
        ];

        $start = now()->startOfYear();
        $end = now()->endOfYear();

        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('m');
            $start->addMonth();
        }

        $actualData = Booking::query()
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

        $bookings_paginated = Booking::query()
            ->select(['id', 'client_id', 'service_id', 'date', 'status'])
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
            ->take(6)
            ->get()
            ->map(function ($category) {
                $category['percentage'] = (float) $category['percentage'];
                return $category;
            })->sortByDesc('percentage');

        $data = [
            'stats' => $stats,
            'category_stats' => $category_stats,
            'chart' => $chart,
            'bookings' => LatestBookingsCollection::make($bookings_paginated),
        ];

        //dd($data['bookings']);

        return Inertia::render('index', [
            'data' => $data,
            'year' => $year,
            'title' => 'Dashboard'
        ]);
    }
}
