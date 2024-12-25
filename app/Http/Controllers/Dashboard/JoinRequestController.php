<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestBookingsCollection;
use App\Http\Resources\Dashboard\LatestServiceProviderCollection;
use App\Models\JoinRequest;
use App\Models\ServiceProvider;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JoinRequestController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $requests_this_week = ServiceProvider::where('created_at', '>=', now()->subWeek())->get();

        $approved_count = $requests_this_week->where('status', 'approved')->count();
        $rejected_count = $requests_this_week->where('status', 'rejected')->count();
        $pending_count = $requests_this_week->where('status', 'pending')->count();

        $total_requests = ServiceProvider::count();

        $stats = [
            'approved_count' => $approved_count,
            'rejected_count' => $rejected_count,
            'pending_count' => $pending_count,
            'total_requests' => $total_requests,
        ];

        $start = now()->subMonths(11)->startOfMonth()->year($year);
        $end = now()->startOfMonth()->year($year);
        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }

        $actualData = Subscription::query()
            ->selectRaw('DATE_FORMAT(subscriptions.start_date, "%Y-%m") as month, SUM(subscriptions.amount) as total_amount')
            ->whereYear('subscriptions.start_date', '=', $year)
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

        $providers_paginated = ServiceProvider::query()
            ->select(['id', 'user_id', 'is_personal', 'status', 'created_at'])
            ->whereStatus('pending')
            ->whereYear('created_at', '=', $year)
            ->latest()
            ->with(['user'])
            ->paginate(4);
        

        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'providers' => LatestServiceProviderCollection::make($providers_paginated),
        ];

        //return $data;

        return Inertia::render('requests/index', [
            'data' => $data,
            'title' => 'Join requests'
        ]);
    }
}
