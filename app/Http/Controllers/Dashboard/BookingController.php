<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\BookingCollection;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings_paginated = Booking::query()
            ->when($request->has('date'), function ($q) use ($request) {
                $q->whereDate('date', $request->date);
            })
            ->latest()
            ->paginate(4);

        $data = [
            'bookings' => BookingCollection::make($bookings_paginated),
        ];

        return $data;
    }
}
