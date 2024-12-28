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

        $requestsQuery = ServiceProvider::query();

        $approved_count = $requestsQuery->where('status', 'approved')->count();
        $rejected_count = $requestsQuery->where('status', 'rejected')->count();
        $pending_count = $requestsQuery->where('status', 'pending')->count();

        $total_requests = ServiceProvider::whereYear('created_at', $year)->count();

        $stats = [
            'approved_count' => $approved_count,
            'rejected_count' => $rejected_count,
            'pending_count' => $pending_count,
            'total_requests' => $total_requests,
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

        $providers_paginated = ServiceProvider::query()
            ->select(['id', 'user_id', 'tax_registeration_number','is_personal', 'status', 'created_at'])
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

        //dd($data);

        return Inertia::render('requests/index', [
            'data' => $data,
            'title' => 'Join requests'
        ]);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'status'    => 'required',
        ]);

        $provider = ServiceProvider::find($id);
        $provider->status = $request->status;
        $provider->save();

        return back();
    }
}
