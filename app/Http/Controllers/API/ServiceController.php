<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Traits\APIResponses;

class ServiceController extends Controller
{
    use APIResponses;

    public function index(Request $request)
    {
        $query    =  Service::query();

        if ($request->has('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%$s%")
                ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%$s%"));
        }

        if ($request->has('category_id')) {
            $query->whereServiceCategoryId($request->category_id);
        }

        if ($request->has('provider_id')) {
            $query->where('partner_service_provider_id', $request->provider_id);
        }

        $services =  $query->paginate($request->page_size ?? 10);


        return $this->okResponse(ServiceCollection::make($services), 'Services retrieved successfully');
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
            'partner_service_provider_id' => 'required|array',
            'partner_service_provider_id.*' => 'exists:service_provider_partners,id',
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'price_before' => 'required|numeric',
            'is_offer' => 'boolean',
        ]);

        $serviceData = $validated;
        unset($serviceData['partner_service_provider_id']);

        $service = Service::create($serviceData);


        $service->serviceProviders()->attach($validated['partner_service_provider_id']);

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
