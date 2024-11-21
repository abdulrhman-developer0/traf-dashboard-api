<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Traits\APIResponses;

class ServiceCategoryController extends Controller
{
    //
    use APIResponses;
    public function index()
    {
        $categories = ServiceCategory::where('is_active', true)->get(['id', 'name','image_path']);
        return $this->okResponse(ServiceCategoryResource::collection($categories), 'Service categories retrieved successfully');
    }

    public function show($id)
    {
        $category = ServiceCategory::find($id);
        if (!$category) {
            return $this->badResponse([], 'Service category not found');
        }
        return $this->okResponse($category, 'Service category retrieved successfully');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $category = ServiceCategory::create($validated);
        return $this->createdResponse($category, 'Service category created successfully');
    }

    public function update(Request $request, $id)
    {
        $category = ServiceCategory::find($id);
        if (!$category) {
            return $this->badResponse([], 'Service category not found');
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'image_path' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);
        return $this->okResponse($category, 'Service category updated successfully');
    }

    public function destroy($id)
    {
        $category = ServiceCategory::find($id);
        if (!$category) {
            return $this->badResponse([], 'Service category not found');
        }

        $category->delete();
        return $this->okResponse([], 'Service category deleted successfully');
    }
}
