<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\BookingCollection;
use App\Models\Booking;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class BookingController extends Controller
{
    use APIResponses;

    public function index(Request $request)
    {
        //dd($request);
        $date = $request->has('date') ? $request->date : Carbon::now()->toDateString();

        $booking_count                   = Booking::count();
        $confirmed_booking_count         = Booking::whereStatus('confirmed')->count();
        $cash_booking_count              = Booking::whereStatus('cash')->count();
        $canceled_booking_count          = Booking::whereStatus('canceled')->count();
        $done_booking_count              = Booking::whereStatus('done')->count();

        $stats = [
            'booking_count'             => $booking_count,
            'confirmed_booking_count'   => $confirmed_booking_count,
            'cash_booking_count'        => $cash_booking_count,
            'canceled_booking_count'    => $canceled_booking_count,
            'done_booking_count'        => $done_booking_count
        ];


        $bookings_paginated = Booking::query()
            ->where(function ($q) {
                $q->whereHas('client.user', fn($q) => $q->whereNull('deleted_at'))
                    ->whereHas('service.serviceProvider.user', fn($q) => $q->whereNull('deleted_at'));
            })
            ->whereDate('date', $date)
            ->latest()
            ->paginate(4);

        $data = [
            'stats'    => $stats,
            'bookings' => BookingCollection::make($bookings_paginated),
        ];

        //dd($data);
        return $this->okResponse($data, 'Bookings retrieved successfully');

        // return Inertia::render('bookings/index', [
        //     'data' => $data,
        //     'title' => 'Bookings'
        // ]);
    }
}
