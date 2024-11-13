<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use APIResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->okResponse(
            City::get(['id', 'name']),
            'Retrieved Cities successfuly'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
