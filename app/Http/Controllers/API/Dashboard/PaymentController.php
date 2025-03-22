<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\BookingCollection;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Transaction;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Twilio\TwiML\Voice\Pay;

class PaymentController extends Controller
{
    use APIResponses;
    public function index(Request $request)
    {

        $year = $request->input('year', now()->year);

        $paymentsQuery = Payment::query();

        $total_in_payments = Payment::count();
        $in_payments_amount = Payment::sum('amount');
        $total_out_payments = Transaction::whereStatus(TransactionStatus::COMPLETED)->whereTransactionType(TransactionType::WITHDRAW)->count();
        $out_payments_amount = Transaction::whereStatus(TransactionStatus::COMPLETED)->whereTransactionType(TransactionType::WITHDRAW)->sum('amount');
        $year_in_payments_amount = Payment::whereYear('created_at', $year)->sum('amount');

        $stats = [
            'total_in_payments' => $total_in_payments,
            'in_payments_amount' => $in_payments_amount,
            'total_out_payments' => $total_out_payments,
            'out_payments_amount' => $out_payments_amount,
            'year_in_payments_amount' => $year_in_payments_amount,
        ];


        $start = now()->startOfYear();
        $end = now()->endOfYear();

        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('m');
            $start->addMonth();
        }

        $actualData = Payment::query()
            ->select()
            ->selectRaw('
                DATE_FORMAT(created_at, "%m") as month
            ')
            ->whereYear('created_at', '=', $year)
            ->get()
            ->groupBy('month');


        $chart = collect($months)->map(function ($month) use ($actualData) {
            $actual = $actualData[$month] ?? collect();
            return $actual->sum('amount');
        })->toArray();

        $bookings = Booking::query()
            ->whereHas(
                'payments',
                fn($q) => $q->when($request->status, fn($q) => $q->where('payment_status', $request->status))
            )
            ->select(['id', 'client_id', 'service_id', 'date', 'status'])
            ->latest()
            ->with('client.user', 'service.serviceProvider.user')
            ->paginate($request->input('page_size', 4));


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'bookings' => BookingCollection::make($bookings),
        ];

        //dd($data);
        // return Inertia::render('payments/index', [
        //     'data' => $data,
        //     'year' => $year,
        //     'title' => 'Payments'
        // ]);
        return $this->okResponse($data, 'Payments data retrieved successfully');
    }
}
