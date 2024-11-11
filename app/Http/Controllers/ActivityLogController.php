<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Spatie\Activitylog\Models\Activity;
use Inertia\Inertia;


class ActivityLogController extends Controller
{
    
    public function index(Request $request)
    {
        $data = Activity::with('causer')->latest()->paginate(10);

        return Inertia::render('activity-log/index', [
            'data' => $data,
        ]);
    }


}
