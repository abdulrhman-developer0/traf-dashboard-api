<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\ServiceProviderPortfolio;
use Illuminate\Http\Request;

class ServiceProviderPortfolioController extends Controller
{
    //
    public function index()
    {
        $portfolios = ServiceProviderPortfolio::with('service_provider')->get();
        return response()->json($portfolios);
    }

    // Get a specific portfolio
    public function show($id)
    {
        $portfolio = ServiceProviderPortfolio::with('service_provider')->find($id);
        if (!$portfolio) {
            return response()->json(['message' => 'Portfolio not found'], 404);
        }
        return response()->json($portfolio);
    }

    // Create a new portfolio
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_provider_id' => 'required|integer|exists:service_providers,id',
            'website_url' => 'required|url',
            'description' => 'required|string',
        ]);

        $portfolio = ServiceProviderPortfolio::create($validated);
        return response()->json(['message' => 'Portfolio created successfully', 'portfolio' => $portfolio], 201);
    }

    // Update an existing portfolio
    public function update(Request $request, $id)
    {
        $portfolio = ServiceProviderPortfolio::find($id);
        if (!$portfolio) {
            return response()->json(['message' => 'Portfolio not found'], 404);
        }

        $validated = $request->validate([
            'website_url' => 'url',
            'description' => 'string',
        ]);

        $portfolio->update($validated);
        return response()->json(['message' => 'Portfolio updated successfully', 'portfolio' => $portfolio]);
    }

    // Delete a portfolio
    public function destroy($id)
    {
        $portfolio = ServiceProviderPortfolio::find($id);
        if (!$portfolio) {
            return response()->json(['message' => 'Portfolio not found'], 404);
        }

        $portfolio->delete();
        return response()->json(['message' => 'Portfolio deleted successfully']);
    }
}
