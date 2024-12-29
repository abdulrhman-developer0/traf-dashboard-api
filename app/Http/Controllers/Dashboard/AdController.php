<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\AdCollection;
use App\Models\Ad;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $total_in_ads           = Ad::count();

        $in_ads_amount           = Ad::sum('total_price');

        $in_ads_today           = Ad::whereDay('created_at', now())->count();

        $in_ads_today_amount    = Ad::whereDay('created_at', now())
            ->whereIn('status', ['approved', 'waiting'])
            ->sum('total_price');

        $stats = [
            'total_in_ads'          => $total_in_ads,
            'in_ads_amount'          => $in_ads_amount,
            'in_ads_today'          => $in_ads_today,
            'in_ads_today_amount'   => $in_ads_today_amount,
        ];

        $ads = [];

        // Grab all ad statuses.
        foreach (Ad::STATUSES as $status) {
            $paginator = Ad::query()
                ->whereStatus($status)
                ->latest()
                ->paginate(6);

            $ads[$stats] = AdCollection::make($paginator);
        }

        $data = [
            'stats' => $stats,
            'ads'   => $ads
        ];

        return Inertia::render('ads/index', [
            'data' => $data,
            'title' => 'Ads'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
