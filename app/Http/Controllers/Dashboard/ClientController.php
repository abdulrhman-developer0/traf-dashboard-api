<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestClientCollection;
use App\Models\Client;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $clients_this_week = Client::where('created_at', '>=', now()->subWeek())->get();

        $clients_count = $clients_this_week->count();
        $new_clients   = Client::whereDay('created_at', now())->count();
        $logouts_count = 0;
        $deleted_accounts = User::onlyTrashed()
            ->whereAccountType('client')
            ->count();

        $total_clients = Client::whereYear('created_at', $year)->count();

        $stats = [
            'clients_count' => $clients_count,
            'new_clients'   => $new_clients,
            'logouts_count'   => $logouts_count,
            'deleted_accounts' => $deleted_accounts,
            'total_clients'  => $total_clients,
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
            ->select(['id', 'user_id', 'created_at'])
            ->whereYear('created_at', '=', $year)
            ->latest()
            ->with(['user'])
            ->paginate(4);


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'clients' => LatestClientCollection::make($clients_paginated),
        ];

        return Inertia::render('clients/index', [
            'data' => $data,
            'title' => 'Clients'
        ]);
    }
}
