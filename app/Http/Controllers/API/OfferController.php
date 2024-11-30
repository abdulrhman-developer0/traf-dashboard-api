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

    /**
     * Retrieve all offers with optional filters.
     */
    public function index(Request $request)
    {
        $offers = Offer::with('media')
            ->when($request->has('service_id'), function ($query) use ($request) {
                $query->where('service_id', $request->query('service_id'));
            })
            ->when($request->has('provider_id'), function ($query) use ($request) {
                $query->whereHas('service', fn ($q) => $q->where('service_provider_id', $request->query('provider_id')));
            })
            ->get();

        return $this->okResponse(OfferResource::collection($offers), 'Offers retrieved successfully');
    }

    /**
     * Retrieve a specific offer by ID.
     */
    public function show($id)
    {
        $offer = Offer::find($id);

        if (!$offer) {
            return $this->badResponse([], 'Offer Not Found');
        }

        return $this->okResponse(OfferResource::make($offer), 'Offer retrieved successfully');
    }

    /**
     * Create a new offer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id'    => 'required|exists:services,id',
            'title'         => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'type'          => 'required|in:poster,short_video',
            'media_file'    => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:4500',
        ]);

        $offer = Offer::create(
            $request->only(['service_id', 'title', 'description', 'type'])
        );

        $offer->addMedia($request->file('media_file'))->toMediaCollection('media_file');

        return $this->createdResponse([], 'Offer created successfully', 201);
    }
}
