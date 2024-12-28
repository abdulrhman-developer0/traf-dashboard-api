<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Policy;
use App\Http\Resources\Dashboard\PolicyResource;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies = Policy::get();

        $data = [
            'policies'    => PolicyResource::collection($policies),
        ];
        
        return Inertia::render('policies/index', [
            'data' => $data,
            'title' => 'Polices'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('policies/add-edit', [
            'title' => 'Polices'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'content'    => 'required',
        ]);

        Policy::create($request->only(['title','content']));

        return redirect('/policies')->with('status', ['type' => 'success', 'action' => 'تم اضافة السياسة بنجاح', 'text' => '']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Policy $policy)
    {
        return Inertia::render('policies/add-edit', [
            'policy' => $policy,
            'title' => 'Polices'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Policy $policy)
    {
        $request->validate([
            'title'    => 'required',
            'content'    => 'required',
        ]);

        $policy->update($request->only(['title','content']));

        return redirect('/policies')->with('status', ['type' => 'success', 'action' => 'تم تعديل السياسة بنجاح', 'text' => '']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Policy $policy)
    {
        $policy->delete();

        return back()->with('status', ['type' => 'success', 'action' => 'تم حذف السياسة بنجاح', 'text' => '']);

    }
}
