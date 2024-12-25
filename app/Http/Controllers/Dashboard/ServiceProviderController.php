<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestServiceProviderCollection;
use App\Models\ServiceProvider;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $providerss_this_week = ServiceProvider::where('created_at', '>=', now()->subWeek())->get();

        $providers_count = $providerss_this_week->count();
        $new_providers   = ServiceProvider::whereDay('created_at', now())->count();
        $logouts_count = 0;
        $deleted_accounts = User::onlyTrashed()
            ->whereAccountType('service-provider')
            ->count();

        $total_providers = ServiceProvider::count();

        $stats = [
            'providers_count' => $providers_count,
            'new_providers'   => $new_providers,
            'logouts_count'   => $logouts_count,
            'deleted_accounts' => $deleted_accounts,
            'total_providers'  => $total_providers,
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

        return $data;
    }
}
