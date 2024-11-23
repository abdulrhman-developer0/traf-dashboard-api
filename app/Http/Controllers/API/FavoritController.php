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

        $services = $client->favoritServices ?? [];

        return $this->okResponse(
            ServiceResource::collection($services),
            'Retrieve favorit services'
        );
    }

    public function taggle($serviceId)
    {
        $client = Auth::user()?->client;

        if (! $client) {
            return $this->badResponse([], 'Plese login by client account to access favorits');
        }

        $idsOfServices = $client->favoritServices()->pluck('service_id')->toArray();

        if ( in_array($serviceId, $idsOfServices) ) {
            $client->favoritServices()->detach($serviceId);
            return $this->okResponse([], 'Service removed from favorits successfuly');
        }

        $client->favoritServices()->attach($serviceId);

        return $this->okResponse([], 'Service added to favorits successfuly');
    }
}
