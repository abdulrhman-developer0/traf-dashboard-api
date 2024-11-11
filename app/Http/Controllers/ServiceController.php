<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //
    public function index()
    {
        $services = Service::all();
        return response()->json($services);
    }

    public function show($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        return response()->json($service);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'partner_service_provider_id' => 'required',
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'price_before' => 'required|numeric',
            'is_offer' => 'boolean',
        ]);

        $service = Service::create($validated);
        return response()->json(['message' => 'Service created successfully', 'service' => $service], 201);
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $validated = $request->validate([
            'service_category_id' => 'exists:service_categories,id',
            'partner_service_provider_id' => 'nullable',
            'name' => 'string|max:255',
            'duration' => 'integer',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'price_before' => 'numeric',
            'is_offer' => 'boolean',
        ]);

        $service->update($validated);
        return response()->json(['message' => 'Service updated successfully', 'service' => $service]);
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $service->delete();
        return response()->json(['message' => 'Service deleted successfully']);
    }
}
