<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\ServiceProviderPortfolio;
use Illuminate\Http\Request;
use App\Traits\APIResponses;

class ServiceProviderPortfolioController extends Controller
{
    //
    use APIResponses;
    public function index()
    {
        $portfolios = ServiceProviderPortfolio::with('service_provider')->get();
    return $this->okResponse($portfolios, 'Retrieved all portfolios successfully');
    }

    // Get a specific portfolio
    public function show($id)
    {
        $portfolio = ServiceProviderPortfolio::with('service_provider')->find($id);
        if (!$portfolio) {
            return $this->notFoundResponse('Portfolio not found');
        }
        return $this->okResponse($portfolio, 'Retrieved portfolio successfully');
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
        return $this->createdResponse(['portfolio' => $portfolio], 'Portfolio created successfully');
    }

    // Update an existing portfolio
    public function update(Request $request, $id)
    {
        $portfolio = ServiceProviderPortfolio::find($id);
        if (!$portfolio) {
            return $this->notFoundResponse('Portfolio not found');
        }
    
        $validated = $request->validate([
            'website_url' => 'url',
            'description' => 'string',
        ]);
    
        $portfolio->update($validated);
        return $this->okResponse(['portfolio' => $portfolio], 'Portfolio updated successfully');
    }

    // Delete a portfolio
    public function destroy($id)
    {
        $portfolio = ServiceProviderPortfolio::find($id);
        if (!$portfolio) {
            return $this->notFoundResponse('Portfolio not found');
        }
    
        $portfolio->delete();
        return $this->okResponse([], 'Portfolio deleted successfully');
    }
}
