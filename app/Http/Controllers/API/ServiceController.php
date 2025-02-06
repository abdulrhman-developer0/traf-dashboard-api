<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Traits\APIResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    use APIResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query    =  Service::query()
            // Get only first schedule for each worker where service_id = service.id
            ->with('workers.schedules', function ($q) {
                $q->whereHas(
                    'worker',
                    fn($q) => $q->whereRaw('reference_id = workers.id')
                )->whereHas(
                    'service',
                    fn($q) => $q->whereRaw('service_id = services.id')
                )->latest();
            })->with([
                // 'clientFavorites as is_favorite' => fn($q) => $q->where('client_id', Auth::user()?->client?->id)->limit(1),
                'clientFavorites' => fn($q) => $q->where('client_id', Auth::user()?->client?->id)->limit(1),
            ])->latest();

        // filter by search
        if ($request->has('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%$s%")
                ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%$s%"));
        }

        // filter by category
        if ($request->has('category_id')) {
            $query->whereServiceCategoryId($request->category_id);
        }

        // filter by providers
        if ($request->has('provider_ids')) {
            $ids = explode(',', $request->provider_ids);
            $query->whereIn('service_provider_id', $ids);
        }

        //filter by pricing
        // 100-200
        if ($request->input('pricing') != null) {

            $pricingRange = collect(
                explode('-', trim($request->pricing, '- '))
            )->map(
                fn($v) => trim($v, '- ')
            );

            $query->when(
                $pricingRange->count() == 2,
                fn($q) => $q->whereBetween('price_before', $pricingRange)
                    ->orWhereBetween('price_after', $pricingRange)
            )->when(
                $pricingRange->count() == 1,
                fn($q) => $q->where('price_before', '>=', $pricingRange[0])
                    ->orWhere('price_after', '>=', $pricingRange[0])
            );
        }

        $services =  $query
            ->with(
                'schedules',
                // fn($q) => $q->whereRaw(
                //     'service_id = services.id'
                // )
            )
            ->paginate($request->page_size ?? 10);
            // ->get();

        // return $services;


        return $this->okResponse(ServiceCollection::make($services), 'Services retrieved successfully');
    }

    public function show($id)
    {
        $service = Service::whereId($id)
            // Get only first schedule for each worker where service_id = service.id
            ->with('workers.schedules', function ($q) {
                $q->whereHas(
                    'worker',
                    fn($q) => $q->whereRaw('reference_id = workers.id')
                )->whereHas(
                    'service',
                    fn($q) => $q->whereRaw('service_id = services.id')
                )->latest();
            })
            ->first();

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
            'service_workers' => $serviceProvider->is_personal ? 'nullable' : 'required|string|min:1',
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'price_before' => 'required|numeric',
            'price_after' => 'nullable|numeric',
            'is_home_service' => 'required|boolean',
            'is_on_site' => 'required|boolean',
            'address' => 'nullable|string|max:500',
            "photo"   => 'nullable|image|max:4096',
        ]);

        $serviceWorkers = $request->has('service_workers') ? collect(explode(',', $validated['service_workers']))->map(function ($workerId) {
            return [
                'worker_id' => trim($workerId, ' ,'),
            ];
        })->toArray() : [];

        $is_home_service = $request->is_home_service ?? false;
        $is_on_site = $request->is_on_site ?? false;
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
            'is_offer'   => $is_offer,
            'is_on_site' => $is_on_site
        ]);

        // Attach workers to the service (this will create the pivot records in service_workers)
        $service->workers()->attach($serviceWorkers);

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
            'service_workers' => 'nullable|string|min:1',
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'price_before' => 'required|numeric',
            'price_after' => 'nullable|numeric',
            'is_home_service' => 'boolean',
            'is_on_site'      => 'boolean',
            'address' => 'nullable|string',
            "photo"   => 'nullable|image|max:4096',
        ]);

        $serviceWorkers = $request->has('service_workers') ? collect(explode(',', $validated['service_workers']))->map(function ($workerId) {
            return [
                'worker_id' => trim($workerId, ' ,'),
            ];
        })->toArray() : [];

        $serviceProvider = Auth::user()?->account();
        $service         = $serviceProvider->services()->find($id);

        if (! $service) {
            return $this->badResponse([], "You not have a service with id $id");
        }

        $is_home_service = $request->is_home_service ?? $service->is_home_service;
        $is_on_site      = $request->is_on_site ?? $service->is_on_site;
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
            'is_on_site' => $is_on_site,
        ]);


        if ($request->has('service_workers') && !empty($request->service_workers)) {
            $service->workers()->sync($serviceWorkers);
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
