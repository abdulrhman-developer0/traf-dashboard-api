<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\BookingCollection;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        //dd($request);
        $date = $request->has('date') ? $request->date : Carbon::now()->toDateString();

        
        $bookings_paginated = Booking::query()
            ->whereDate('date', $date)
            ->latest()
            ->paginate(4);

        $data = [
            'bookings' => BookingCollection::make($bookings_paginated),
        ];

        //dd($data);

        return Inertia::render('bookings/index', [
            'data' => $data,
            'title' => 'Bookings'
        ]);
    }
}
