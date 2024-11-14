<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\SerivceOffer;
use Illuminate\Http\Request;
use App\Traits\APIResponses;

class ServiceOfferController extends Controller
{
    //
    use APIResponses;
    public function index()
    {
        $offers = SerivceOffer::with('service')->get();
        return $this->okResponse($offers, 'Retrieved all offers successfully');
    }

    // Get a specific service offer
    public function show($id)
    {
        $offer = SerivceOffer::with('service')->find($id);
        if (!$offer) {
            return $this->notFoundResponse([], 'Offer not found');
        }
        return $this->okResponse($offer, 'Retrieved offer successfully');
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
        return $this->createdResponse($offer, 'Offer created successfully');
    }

    // Update an existing service offer
    public function update(Request $request, $id)
    {
        $offer = SerivceOffer::find($id);
        if (!$offer) {
            return $this->notFoundResponse([], 'Offer not found');
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
        return $this->okResponse($offer, 'Offer updated successfully');
    }

    // Delete a service offer
    public function destroy($id)
    {
        $offer = SerivceOffer::find($id);
    if (!$offer) {
        return $this->notFoundResponse([], 'Offer not found');
    }

    $offer->delete();
    return $this->okResponse([], 'Offer deleted successfully');
    }
}
