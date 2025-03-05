<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\CategoryResource;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::get();

        $data = [
            'categories' => CategoryResource::collection($categories),
        ];

        // return Inertia::render('services-categories/index', [
        //     'data' => $data,
        //     'title' => 'Services types'
        // ]);
        return $this->okResponse($data, 'Categories retrieved successfully');
    }
}
