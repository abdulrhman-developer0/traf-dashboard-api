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


        $bookings_paginated = Booking::query()
            ->whereHas('client.user', fn($q) => $q->whereNull('deleted_at'))
            ->whereDate('date', $date)
            ->latest()
            ->paginate(4);

        $data = [
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
