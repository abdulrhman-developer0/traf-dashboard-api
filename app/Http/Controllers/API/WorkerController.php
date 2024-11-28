<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    //
    public function index()
{
    $workers = Worker::all()->map(function ($worker) {
        return [
            'id' => $worker->id,
            'name' => $worker->name,
            'phone' => $worker->phone,
            'address' => $worker->address,
            'photo' => $worker->getFirstMediaUrl('photo'), // Directly get the image URL
        ];
    });
    return response()->json([
        'message' => 'Workers retrieved successfully',
        'data' => $workers,
    ], 200);


}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);
    
        $worker = Worker::create($request->only('name', 'phone', 'address'));
    
        if ($request->hasFile('image')) {
            try {
                $worker->addMedia($request->file('image'))->toMediaCollection('worker');
            } catch (\Exception $e) {
                \Log::error('Media Upload Error', ['Error' => $e->getMessage()]);
                return response()->json([
                    'message' => 'Image upload failed.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
        $worker->load('media');
        return response()->json([
            'message' => 'Worker created successfully',
            'worker' => [
                'id' => $worker->id,
                'name' => $worker->name,
                'phone' => $worker->phone,
                'address' => $worker->address,
                'image' => $worker->getFirstMediaUrl('worker'), 
            ],
        ]);
    }
    
}
