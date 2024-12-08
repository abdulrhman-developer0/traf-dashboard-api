<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\ServiceResource;
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
            ->latest()
            ->withCount([
                'clientFavorites as is_favorite' => fn($q) => $q->where('client_id', Auth::user()?->client?->id)->limit(1),
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
            $query->where('service_provider_id', $request->provider_id);
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
        return $this->okResponse(ServiceResource::make($service), 'Service retrieved successfully');
    }

    public function store(Request $request)
    {
        $serviceProvider = Auth::user()?->account();
        // dd($serviceProvider);

        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'service_workers' => $serviceProvider->is_personal ? 'nullable' : 'required|array|min:1',
            'service_workers.*' => 'exists:workers,id',  // Ensure worker IDs exist in the workers table
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'price_before' => 'required|numeric',
            'price_after' => 'nullable|numeric',
            'is_home_service' => 'required|boolean',
            'address' => 'required_if:is_home_service,false|string|max:500',
            "photo"   => 'nullable|image|max:4096',
        ]);

        $is_home_service = $request->has('is_home_service') ? true : false;
        $is_offer = $request->price_after ? true : false;

        // Create the service
        $service = Service::create([
            'service_category_id' => $validated['service_category_id'],
            'service_provider_id' => $serviceProvider->id,
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'description' => $validated['description'] ?? null,
            'price_before' => $validated['price_before'],
            'price_after' => $validated['price_after'] ?? null,
            'address' => $validated['address'] ?? '',
            'is_home_service' => $is_home_service,
            'is_offer' => $is_offer,
            'photo'     => 'nullable|image|max:4096',
        ]);

        // Attach workers to the service (this will create the pivot records in service_workers)
        $service->workers()->attach($request->service_workers);

        if ($request->hasFile('photo')) {
             $service->addMedia($request->photo)
                ->toMediaCollection('photo');
        }

        return $this->createdResponse([
            'service_id' => $service->id,
        ], 'Service created successfully');
    }




    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'nullable|array|min:1',
            'service_workers.*' => 'exists:workers,id',  // Ensure worker IDs exist in the workers table
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'price_before' => 'required|numeric',
            'price_after' => 'nullable|numeric',
            'is_home_service' => 'boolean',
            'address' => 'nullable|string',
            "photo"   => 'nullable|image|max:4096',
        ]);

        $serviceProvider = Auth::user()?->account();
        $service         = $serviceProvider->services()->find($id);

        if (! $service) {
            return $this->badResponse([], "You not have a service with id $id");
        }

        $is_home_service = $request->has('is_home_service') ? true : $service->is_home_service;
        $is_offer = $request->price_after ? true : false;

        $service->update([
            'service_category_id' => $validated['service_category_id'],
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'description' => $validated['description'] ?? null,
            'price_before' => $validated['price_before'],
            'price_after' => $validated['price_after'] ?? null,
            'is_home_service'   => $is_home_service,
            'address' => $validated['address'] ?? '',
            'is_offer' => $is_offer,
            'photo'     => 'nullable|image|max:4096',
        ]);


        if ($request->has('service_workers') && !empty($request->service_workers)) {
            $service->workers()->sync($request->service_workers);
        }

        if ($request->hasFile('photo')) {
             $service->addMedia($request->photo)
                ->toMediaCollection('photo');
        }

        return $this->okResponse([], 'Service updated successfully');
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        if (! $service || $service->service_provider_id !== Auth::user()->serviceProvider?->id) {
            return $this->badResponse([], 'Service not found');
        }

        $service->delete();
        return $this->okResponse([], 'Service deleted successfully');
    }
}
