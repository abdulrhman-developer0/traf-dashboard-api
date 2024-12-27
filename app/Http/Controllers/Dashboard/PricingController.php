<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestPackageCollection;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        $start = now()->startOfYear();
        $end = now()->endOfYear();

        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('m');
            $start->addMonth();
        }

        $actualData = Subscription::query()
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

        $packages_paginated = Package::query()
            ->paginate(4);


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'packages' => LatestPackageCollection::make($packages_paginated),
        ];

        return Inertia::render('pricing/index', [
            'data' => $data,
            'title' => 'Pricing'
        ]);
    }
}
