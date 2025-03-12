<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\BookingCollection;
use App\Http\Resources\Dashboard\LatestServiceProviderCollection;
use App\Http\Resources\Dashboard\ReviewCollection;
use App\Http\Resources\ServiceCollection;
use App\Models\Booking;
use App\Models\Review;
use App\Models\ServiceProvider;
use App\Models\Subscription;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceProviderController extends Controller
{
    use APIResponses;
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $providerssQuery = ServiceProvider::query()
            ->whereHas('user');

        $providers_count = $providerssQuery->count();
        $new_providers = ServiceProvider::whereDay('created_at', now())->count();
        $logouts_count = 0;
        $deleted_accounts = User::onlyTrashed()
            ->whereAccountType('service-provider')
            ->count();

        $year_total_providers = ServiceProvider::whereYear('created_at', $year)->count();

        $stats = [
            'providers_count' => $providers_count,
            'new_providers' => $new_providers,
            'logouts_count' => $logouts_count,
            'deleted_accounts' => $deleted_accounts,
            'year_total_providers' => $year_total_providers,
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
            ->select(['id', 'user_id', 'is_personal', 'status', 'created_at'])
            ->whereStatus('pending')
            ->latest()
            ->with(['user'])
            ->paginate(4);


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'providers' => LatestServiceProviderCollection::make($providers_paginated),
        ];


        // return Inertia::render('providers/index', [
        //     'data' => $data,
        //     'year' => $year,
        //     'title' => 'Providers'
        // ]);

        return $this->okResponse($data);
    }

    public function show(Request $request, string $id)
    {
        $serviceProvider = ServiceProvider::query()
            ->whereId($id)
            ->select([
                '.id',
                'user_id',
                'phone',
                'rating'
            ])
            ->with([
                'user' => fn($q) => $q->select(['id', 'name']),
            ])
            ->firstOrFail();

        // query the reviews and reviews stats
        $reviewQuery = Review::query()
            ->whereHas('booking.service.serviceProvider', fn($q) => $q->whereId($serviceProvider->id));

        $reviews_stats = array_merge([
            'total' => $reviewQuery->count()
        ], get_rating_stats($reviewQuery));

        $reviews = $reviewQuery->paginate(4);


        // query the bookings and booking stats
        $bookingQuery = Booking::whereHas('service.serviceProvider', fn($q) => $q->whereId($serviceProvider->id))
            ->when(
                $request->has('date'),
                fn($q) => $q->whereDate('date', $request->input('date'))
            )
            ->latest();

        $bookings = $bookingQuery->paginate(4);

        $default_bookings_stats = [
            'total' => $bookingQuery->count(),
            'canceled' => 0,
            'confirmed' => 0,
            'done' => 0,
        ];

        $actualBookingStats = $bookingQuery->get()
            ->groupBy('status')
            ->map(fn($group) => $group->count())
            ->toArray();

        $bookings_stats = collect($default_bookings_stats)->map(function ($value, $key) use ($actualBookingStats) {
            return $actualBookingStats[$key] ?? $value;
        })->toArray();


        $data = [
            'id' => $serviceProvider->id,
            'photo' => $serviceProvider->getFirstMediaUrl('photo'),
            'user_id' => $serviceProvider->user_id,
            'name' => $serviceProvider->user->name,
            'phone' => $serviceProvider->phone,
            'rating' => $serviceProvider->rating,
            'reviews_cstats' => $reviews_stats,
            'reviews' => ReviewCollection::make($reviews),
            'bookings_stats' => $bookings_stats,
            'bookings' => BookingCollection::make(
                $bookings
            ),
            'services' => ServiceCollection::make(
                $serviceProvider->services()->latest()->paginate(4)
            ),
        ];

        // return Inertia::render('providers/show', [
        //     'data' => $data,
        //     'title' => 'Providers'
        // ]);
        return $this->okResponse($data);
    }

    public function destroy($id)
    {
        $provider = ServiceProvider::find($id);

        if (!$provider) {
            return $this->notFoundResponse([], 'Service Provider not found');
        }

        $provider->user->delete();

        return $this->okResponse([], 'Service provider deleted successfully');
        // back();
    }
}
