<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Traits\APIResponses;

class ServiceController extends Controller
{
    //
    use APIResponses;
    public function index(Request $request)
    {
        $query    =  Service::query();

        if ($request->has('categoryId')) {
            $query->whereCategoryId($request->categoryId);
        }

        if ($request->has('providerId')) {
            $query->where('partner_service_provider_id', $request->providerId);
        }

        $services =  $query->pagnate(10);


        return $this->okResponse($services, 'Services retrieved successfully');
    }

    public function show($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return $this->badResponse([], 'Service not found');
        }
        return $this->okResponse($service, 'Service retrieved successfully');
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
        return $this->createdResponse($service, 'Service created successfully');
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return $this->badResponse([], 'Service not found');
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
        return $this->okResponse($service, 'Service updated successfully');
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return $this->badResponse([], 'Service not found');
        }

        $service->delete();
        return $this->okResponse([], 'Service deleted successfully');
    }
}
