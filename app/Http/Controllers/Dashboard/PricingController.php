<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestPackageCollection;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $packages_this_week = Subscription::where('created_at', '>=', now()->subWeek());

        $subscriptions_count = $packages_this_week->where('end_date', '>', now())->count();
        $new_subscriptions   = $packages_this_week->where('end_date', '>', now())->whereDay('created_at', now())->count();
        $expired_subscriptions = $packages_this_week->where('end_date', '<', now())->count();
        $total_subscriptions = Subscription::count();

        $total_packages = Package::count();

        $stats = [
            'subscriptions_count' => $subscriptions_count,
            'new_subscriptions'   => $new_subscriptions,
            'expired_subscriptions'   => $expired_subscriptions,
            'total_packages'       => $total_packages,
            'total_subscriptions'  => $total_subscriptions,
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

        $packages_paginated = Package::query()
            ->paginate(4);


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'packages' => LatestPackageCollection::make($packages_paginated),
        ];

        return $data;
    }
}
