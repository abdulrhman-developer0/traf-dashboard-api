<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    use APIResponses;


    public function index(Request $request)
    {
        $query = Worker::query();

        if ($request->has('provider_id')) {
            $query->where('service_provider_id', $request->provider_id);
        }

        if ($request->has('service_id')) {
            $query->whereHas('services', fn($q) => $q->where('service_id', $request->service_id));
        }


        $workers = $query->get();

        return $this->okResponse(WorkerResource::collection($workers), 'Workers retrieved successfuly');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|regex:/^\+?[0-9]{7,15}$/',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|photo|mimes:jpeg,jpg,png|max:2048',
        ]);

        $serviceProvider = Auth::user()?->serviceProvider;

        $creationData                        = $request->only('name', 'phone', 'address');
        $creationData['service_provider_id'] = $serviceProvider?->id;

        $worker = $serviceProvider->workers()->create($creationData);

        if ($request->hasFile('photo')) {
            $worker->addMedia($request->photo)
                ->toMediaCollection('photo');
        }

        return $this->createdResponse([], 'Worker created successfully');
    }

    public function update(Request $request, $workerId)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|regex:/^\+?[0-9]{7,15}$/',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|photo|mimes:jpeg,jpg,png|max:2048',
        ]);

        $serviceProvider = Auth::user()?->serviceProvider;
        $worker          = $serviceProvider->workers()->find($workerId);

        if (! $worker) {
            return $this->badResponse([], "You not have a worker with id $workerId");
        }

        $worker->update($request->only('name', 'phone', 'address'));

        if ($request->hasFile('photo')) {
            $worker->addMedia($request->photo)
                ->toMediaCollection('photo');
        }

        return $this->okResponse([], 'Worker updated successfully');
    }

    public function destroy($workerId)
{
    
    $worker = Worker::findOrFail($workerId);
    $worker->delete();

    return response()->json(['message' => 'Worker deleted successfully'], 200);
}
public function restore($workerId)
{
    $worker = Worker::withTrashed()->findOrFail($workerId);
    $worker->restore();

    return response()->json(['message' => 'Worker restored successfully'], 200);
}


}
