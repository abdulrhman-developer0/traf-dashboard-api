<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Policy;
use App\Http\Resources\Dashboard\PolicyResource;

use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PolicyController extends Controller
{
    use APIResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies = Policy::get();

        $data = [
            'policies' => PolicyResource::collection($policies),
        ];

        // return Inertia::render('policies/index', [
        //     'data' => $data,
        //     'title' => 'Polices'
        // ]);

        return $this->okResponse($data, 'Policies retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return Inertia::render('policies/add-edit', [
        //     'title' => 'Polices'
        // ]);

        return $this->okResponse([], 'Ready to create a new policy');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $policy = Policy::create($request->only(['title', 'content']));

        // return redirect('/policies')->with('status', ['type' => 'success', 'action' => 'تم اضافة السياسة بنجاح', 'text' => '']);
        return $this->createdResponse(new PolicyResource($policy), 'Policy created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Policy $policy)
    {
        // return Inertia::render('policies/add-edit', [
        //     'policy' => $policy,
        //     'title' => 'Polices'
        // ]);

        return $this->okResponse(new PolicyResource($policy), 'Policy retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Policy $policy)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $policy->update($request->only(['title', 'content']));

        // return redirect('/policies')->with('status', ['type' => 'success', 'action' => 'تم تعديل السياسة بنجاح', 'text' => '']);

        return $this->okResponse(new PolicyResource($policy), 'Policy updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Policy $policy)
    {
        $policy->delete();

        // return back()->with('status', ['type' => 'success', 'action' => 'تم حذف السياسة بنجاح', 'text' => '']);

        return $this->okResponse([], 'Policy deleted successfully');

    }
}
