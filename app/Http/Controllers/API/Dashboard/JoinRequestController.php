<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestBookingsCollection;
use App\Http\Resources\Dashboard\LatestServiceProviderCollection;
use App\Models\JoinRequest;
use App\Models\ServiceProvider;
use App\Models\Subscription;
use App\Notifications\ServiceProviderApproved;
use App\Notifications\ServiceProviderRejected;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JoinRequestController extends Controller
{
    use APIResponses;

    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        //$requestsQuery = ServiceProvider::query();

        $total_requests = ServiceProvider::whereHas('user', fn($q) => $q->whereNull('deleted_at'))->count();
        $approved_count = ServiceProvider::whereHas('user', fn($q) => $q->whereNull('deleted_at'))->whereStatus('approved')->count();
        $rejected_count = ServiceProvider::whereHas('user', fn($q) => $q->whereNull('deleted_at'))->whereStatus('rejected')->count();
        $pending_count = ServiceProvider::whereHas('user', fn($q) => $q->whereNull('deleted_at'))->whereStatus('pending')->count();

        ServiceProvider::whereHas('user', fn($q) => $q->whereNotNull('deleted_at'))->get()->map(function ($i) {
            $i->phone = $i->phone . '-' . $i->created_at;
            $i->save();
        });


        $year_total_requests = ServiceProvider::whereYear('created_at', $year)->count();

        $stats = [
            'total_requests' => $total_requests,
            'approved_count' => $approved_count,
            'rejected_count' => $rejected_count,
            'pending_count' => $pending_count,

            'year_total_requests' => $year_total_requests,

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
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'))
            ->select(['id', 'user_id', 'tax_registeration_number', 'is_personal', 'status', 'created_at'])
            ->whereStatus('pending')
            ->latest()
            ->with(['user'])
            ->paginate($request->input('page_size', 4));


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'providers' => LatestServiceProviderCollection::make($providers_paginated),
        ];

        //dd($data);

        // return Inertia::render('requests/index', [
        //     'data' => $data,
        //     'year' => $year,

        //     'title' => 'Join requests'
        // ]);

        return $this->okResponse($data, 'Join requests fetched successfully');
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason'  => 'string'
        ]);

        $provider = ServiceProvider::find($id);
        if (!$provider) {
            return $this->notFoundResponse([], 'Service Provider not found');
        }

        $provider->update(
            $request->only(['status', 'rejection_reason'])
        );

        if (in_array($request->status, ['approved', 'rejected'])) {
            $notification = match ($request->status) {
                'approved' => new ServiceProviderApproved($provider),
                'rejected' => new ServiceProviderRejected($provider)
            };

            $provider->user->notify($notification);
        }

        // return back();
        return $this->okResponse($provider, 'Status updated successfully');
    }
}
