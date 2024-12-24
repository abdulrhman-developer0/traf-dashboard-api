<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\CategoryResource;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::get();

        $data = [
            'categories'    => CategoryResource::collection($categories),
        ];

        return $data;
    }
}
