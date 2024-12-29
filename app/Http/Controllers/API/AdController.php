<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\adResource;
use App\Models\Ad;
use App\Models\AdPrice;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    use APIResponses;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index']);

        $this->middleware('account:service-provider')->except(['index']);
        $this->middleware('valid_subscribtion')->except(['index']);
    }

    public function index()
    {
        $ads = Ad::query()
            // ->whereStatus('approved')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function (AD $ad) {
                return [
                    'id'            => $ad->id,
                    'provider_id'   => $ad->service_provider_id,
                    'url'           => $ad->getFirstMediaUrl('photo')
                ];
            });


        return $this->okResponse($ads, "Ads retrieved successfuly");
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo'               => 'required|image|max:4096',
            'duration_in_days'    => 'required|integer|min:1|max:3',
        ]);

        $user            = Auth::user();

        $serviceProvider = $user->serviceProvider;

        $currentSubscription = $serviceProvider->currentSubscription;

        $currentPackage = $currentSubscription->package;

        $adPrice = AdPrice::latest()->first();

        $adsDiscount = $currentPackage->ads_discount;

        $totalPrice = ($adPrice->price * (int)$request->duration_in_days) * (1 - $adsDiscount / 100);

        $ad = Ad::create([
            'service_provider_id'   => $serviceProvider->id,
            'ad_price_id'           => $adPrice->id,
            'package_id'            => $currentPackage->id,
            'duration_in_days'      => $request->duration_in_days,
            'total_price'           => $totalPrice,
            'discount'              => $currentPackage->ads_discount,
        ]);


        $ad->addMedia($request->photo)
            ->toMediaCollection('photo');

        return $this->createdResponse([
            'ad_id'     => $ad->id,
            'status'    => $ad->status
        ], 'Ad created successfuly');
    }

    public function myAds(Request $request)
    {
        $user = Auth::user();

        $serviceProvider = $user->serviceProvider;

        $ads = Ad::whereHas('serviceProvider', fn($q) => $q->where('id', $serviceProvider->id))
            ->when(
                $request->has('status'),
                fn($q) => $q->whereStatus($request->query('status'))
            )
            ->latest()
            ->limit(10)
            ->get();

        return $this->okResponse([
            'count' => $ads->count(),
            'items' => adResource::collection($ads)
        ], 'My Ads retrived successfuly');
    }
}
