<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\BookingCollection;
use App\Http\Resources\Dashboard\LatestClientCollection;
use App\Http\Resources\Dashboard\ReviewCollection;
use App\Http\Resources\ServiceCollection;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    use APIResponses;

    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $clientsQuery = Client::query()
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'));

        $clients_count = $clientsQuery->count();
        $new_clients = Client::whereHas('user', fn($q) => $q->whereNull('deleted_at'))->whereDay('created_at', now())->count();
        $logouts_count = 0;
        $deleted_accounts = User::onlyTrashed()
            ->whereAccountType('client')
            ->count();

        $year_total_clients = Client::whereNull('deleted_at'))->whereYear('created_at', $year)->count();

        $stats = [
            'clients_count' => $clients_count,
            'new_clients' => $new_clients,
            'logouts_count' => $logouts_count,
            'deleted_accounts' => $deleted_accounts,
            'year_total_clients' => $year_total_clients,
        ];

        $start = now()->startOfYear();
        $end = now()->endOfYear();

        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('m');
            $start->addMonth();
        }

        $actualData = Payment::query()
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

        $clients_paginated = Client::query()
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'))
            ->select(['id', 'user_id', 'phone', 'created_at'])
            ->latest()
            ->with(['user'])
            ->paginate(4);


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'clients' => LatestClientCollection::make($clients_paginated),
        ];

        // return Inertia::render('clients/index', [
        //     'data' => $data,
        //     'year' => $year,

        //     'title' => 'Clients'
        // ]);
        return $this->okResponse($data, "Ads retrieved successfuly");
    }

    public function show(Request $request, string $id)
    {
        $client = Client::query()
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
            ->whereHas('booking', fn($q) => $q->whereClientId($client->id));

        $reviews_stats = array_merge([
            'total' => $reviewQuery->count()
        ], get_rating_stats($reviewQuery));

        $reviews = $reviewQuery->paginate(4);


        // query the bookings and booking stats
        $bookingQuery = Booking::query()
            ->whereClientId($client->id)
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
            'id' => $client->id,
            'photo' => $client->getFirstMediaUrl('photo'),
            'user_id' => $client->user_id,
            'name' => $client->user->name,
            'phone' => $client->phone,
            'rating' => $client->rating,
            'reviews_cstats' => $reviews_stats,
            'reviews' => ReviewCollection::make($reviews),
            'bookings_stats' => $bookings_stats,
            'bookings' => BookingCollection::make(
                $bookings
            ),

            // 'services'       => ServiceCollection::make(
            //     $serviceProvider->services()->latest()->paginate(4)
            // ),
        ];

        // return $data;

        return $this->okResponse($data, "Client details retrieved successfully");
    }

    public function destroy($id)
    {
        $client = Client::find($id);

        $user = $client->user;

        $user->phone = $user->phone . '-' . $user->created_at;
        $user->save();

        $user->delete();

        // back();
        return $this->okResponse([], "Client deleted successfully");
    }
}
