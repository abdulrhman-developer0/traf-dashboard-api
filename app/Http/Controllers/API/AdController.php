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
        $this->middleware('auth')->except(['index']);

        $this->middleware('account:service-provider')->except(['index']);
    }

    public function index()
    {
        $ads = Ad::query()
            ->whereStatus('approved')
            ->latest()
            ->limit(10)
            ->get();


        return $this->okResponse([
            'items' => adResource::collection($ads)
        ], "Ads retrieved successfuly");
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo'               => 'required|image|max:4096',
            'duration_in_days'    => 'required|integer|min:1|max:30',
        ]);

        $user            = Auth::user();

        $serviceProvider = $user->serviceProvider;

        $currentSubscription = $serviceProvider->currentSubscription;

        $adPrice = AdPrice::last();

        $adDiscount = $currentSubscription->package->as_siscount;
        $adDiscount = $adDiscount > 0 ? $adDiscount : 1;

        $totalPrice = ($adP->price * (int)$request->duration_in_days) * (1 - $adDiscount / 100);

        $ad = Ad::create([
            'service_provider_id'   => $serviceProvider->id,
            'ad_price_id'           => $adPrice->id,
            'total_price'           => $totalPrice,
        ]);


        $ad->addMedia($request->photo)
            ->toMediaCollection('photo');

        return $this->createdResponse([
            'ad' => $ad,
        ], 'Ad created successfuly');
    }
}
