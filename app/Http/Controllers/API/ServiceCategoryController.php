<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    //
    public function index()
    {
        $categories = ServiceCategory::all();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = ServiceCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Service category not found'], 404);
        }
        return response()->json($category);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $category = ServiceCategory::create($validated);
        return response()->json(['message' => 'Service category created successfully', 'category' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $category = ServiceCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Service category not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);
        return response()->json(['message' => 'Service category updated successfully', 'category' => $category]);
    }

    public function destroy($id)
    {
        $category = ServiceCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Service category not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Service category deleted successfully']);
    }
}
