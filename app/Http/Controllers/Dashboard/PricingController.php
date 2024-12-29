<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LatestPackageCollection;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $packagesQuery = Subscription::query();

        $subscriptions_count = $packagesQuery->where('end_date', '>', now())->count();
        $new_subscriptions   = $packagesQuery->where('end_date', '>', now())->whereDay('created_at', now())->count();
        $expired_subscriptions = $packagesQuery->where('end_date', '<', now())->count();

        $year_total_amount_subscriptions = Subscription::whereYear('created_at', $year)->sum('amount');

        $total_packages = Package::count();

        $stats = [
            'subscriptions_count' => $subscriptions_count,
            'new_subscriptions'   => $new_subscriptions,
            'expired_subscriptions'   => $expired_subscriptions,
            'total_packages'       => $total_packages,
            'year_total_amount_subscriptions'  => $year_total_amount_subscriptions,
        ];

        $start = now()->startOfYear();
        $end = now()->endOfYear();

        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('m');
            $start->addMonth();
        }

        $actualData = Subscription::query()
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


        $packages_paginated = Package::query()
            ->paginate(4);


        $data = [
            'stats' => $stats,
            'chart' => $chart,
            'packages' => LatestPackageCollection::make($packages_paginated),
        ];

        return Inertia::render('pricing/index', [
            'data' => $data,
            'year' => $year,

            'title' => 'Pricing'
        ]);
    }

    public function store(Request $request)
    {
        //dd($request);

        $request->validate([
            'name'    => 'required',
            'price'    => 'required',
            'duration_in_days'    => 'required',
            'ads_discount'    => 'required',

        ]);

        Package::create($request->only(['name','price','duration_in_days','ads_discount']));

        return back()->with('status', ['type' => 'success', 'action' => 'تم اضافة الباقة بنجاح', 'text' => '']);
    }

    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name'    => 'required',
            'price'    => 'required',
            'duration_in_days'    => 'required',
            'ads_discount'    => 'required',

        ]);

        $package = Package::find($id);

        $package->update($request->only(['name','price','duration_in_days','ads_discount']));

        return back()->with('status', ['type' => 'success', 'action' => 'تم تعديل الباقة بنجاح', 'text' => '']);

    }


    public function destroy($id)
    {
        $package = Package::find($id);
        
        $package->delete();

        return back()->with('status', ['type' => 'success', 'action' => 'تم حذف الباقة بنجاح', 'text' => '']);

    }
}
