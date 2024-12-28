<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\BookingCollection;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;


class PaymentController extends Controller
{
    public function index(Request $request)
    {

        $year = $request->input('year', now()->year);

        $total_in_payments = 100;
        $in_payments_amount = 1309;
        $total_out_payments = 10;
        $out_payments_amount = 459;
        $year_in_payments_amount = 5080;

        $stats = [
            'total_in_payments' => $total_in_payments,
            'in_payments_amount' => $in_payments_amount,
            'total_out_payments' => $total_out_payments,
            'out_payments_amount' => $out_payments_amount,
            'year_in_payments_amount' => $year_in_payments_amount,
        ];


        $chart = [];


        $data = [
            'stats' => $stats,
            'chart' => $chart,
        ];


        return Inertia::render('payments/index', [
            'data' => $data,
            'title' => 'Payments'
        ]);
    }
}
