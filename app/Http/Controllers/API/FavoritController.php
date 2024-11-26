<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    use APIResponses;

    public function index(Request $request)
    {
        $client = Auth::user()?->client;

        if (! $client) {
            return $this->badResponse([], 'Plese login by client account to access favorits');
        }

        $services = $client->favoritServices()->get();
        $services->each(function($service) {
            $service['is_favorite'] = 1;
        });

        return $this->okResponse(
            ServiceResource::collection($services),
            'Retrieve favorit services'
        );
    }

    public function taggle(Request $request)
    {
        $request->validate([
            'service_id'  => 'required|integer'
        ]);

        $client = Auth::user()?->client;

        if (! $client) {
            return $this->badResponse([], 'Plese login by client account to access favorits');
        }

        $idsOfServices = $client->favoritServices()->pluck('service_id')->toArray();

        if ( in_array($request->service_id, $idsOfServices) ) {
            $client->favoritServices()->detach($request->service_id);
            return $this->okResponse([], 'Service removed from favorits successfuly');
        }

        $client->favoritServices()->attach($request->service_id);

        return $this->okResponse([], 'Service added to favorits successfuly');
    }
}