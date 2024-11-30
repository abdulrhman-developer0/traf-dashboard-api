<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    use APIResponses;
    // get all offer
    public function index(Request $request) {
        $serviceId=$request->query('service_id');
        $providerId=$request->query('serviceProviderId');
        $offers=Offer::with('media')
        ->when($serviceId,function($query,$serviceId) {
            return $query->where('service_id',$serviceId);
        })
        ->when($providerId,function($query,$providerId) {
             return $query->where('service_provider_id',$providerId);
        })
        ->get();
        return OfferResource::collection($offers);
    }
    // get one 
    public function show($Id) {
   $offer= Offer::find($Id);
   if(!$offer) {
    return $this->badResponse([], 'Offer Not Found');
   }
   return new OfferResource($offer);

    }

    // create offer
    public function store(Request $request) {
        $request->validate([
        'service_id'=> 'required|exists:services,id',
        'service_provider_id' => 'required|exists:service_providers,id',
        'title' => 'nullable|string|max:255',
        'description'=>'nullable|string',
        'type' => 'required|in:poster,short_video',
        'media' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:4500'
        ]);
        $offer=Offer::create($request->only(['service_id','service_provider_id','title','description','type']));
        if($request->type === 'poster'){
          $offer->addMedia($request->file('media'))->toMediaCollection('posters');
        }
        else if($request->type ==='short_video'){
            $offer->addMedia($request->file('media'))->toMediaCollection('videos');
          }
        return new OfferResource($offer);
    }
}
