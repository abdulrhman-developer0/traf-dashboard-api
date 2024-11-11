<?php

namespace App\Http\Controllers;

use App\Models\SerivceOffer;
use Illuminate\Http\Request;

class ServiceOfferController extends Controller
{
    //
    public function index()
    {
        $offers = SerivceOffer::with('service')->get();
        return response()->json($offers);
    }

    // Get a specific service offer
    public function show($id)
    {
        $offer = SerivceOffer::with('service')->find($id);
        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }
        return response()->json($offer);
    }

    // Create a new service offer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'description' => 'required|string',
            'price_after' => 'required|numeric|min:0',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        $offer = SerivceOffer::create($validated);
        return response()->json(['message' => 'Offer created successfully', 'offer' => $offer], 201);
    }

    // Update an existing service offer
    public function update(Request $request, $id)
    {
        $offer = SerivceOffer::find($id);
        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }

        $validated = $request->validate([
            'service_id' => 'integer|exists:services,id',
            'discount_percentage' => 'integer|min:0|max:100',
            'description' => 'string',
            'price_after' => 'numeric|min:0',
            'start_at' => 'date',
            'end_at' => 'date|after:start_at',
        ]);

        $offer->update($validated);
        return response()->json(['message' => 'Offer updated successfully', 'offer' => $offer]);
    }

    // Delete a service offer
    public function destroy($id)
    {
        $offer = SerivceOffer::find($id);
        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }

        $offer->delete();
        return response()->json(['message' => 'Offer deleted successfully']);
    }
}
