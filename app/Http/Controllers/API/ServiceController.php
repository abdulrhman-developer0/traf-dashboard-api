<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Traits\APIResponses;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    use APIResponses;

    public function index(Request $request)
    {
        $query    =  Service::query()
        ->withCount([
            'clientFavorites as is_favorite' => fn ($q) => $q->where('client_id', Auth::user()?->client?->id)->limit(1),
        ]);

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
            'service_workers' => 'required|array',
            'service_workers.*' => 'exists:workers,id',  // Ensure worker IDs exist in the workers table
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'price_before' => 'required|numeric',
            'price_after' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);
    
        $is_offer = $request->price_after ? true : false;
    
        // Create the service
        $service = Service::create([
            'service_category_id' => $validated['service_category_id'],
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'description' => $validated['description'] ?? null,
            'price_before' => $validated['price_before'],
            'price_after' => $validated['price_after'] ?? null,
            'address' => $validated['address'] ?? '',
            'is_offer' => $is_offer,
        ]);
    
        // Attach workers to the service (this will create the pivot records in service_workers)
        $service->workers()->attach($validated['service_workers']);
    
        return response()->json(['message' => 'Service created successfully']);
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
