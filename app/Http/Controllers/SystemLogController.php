<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

use App\Models\SystemLog;


class SystemLogController extends Controller
{

    public function index(Request $request)
    {
        $data = SystemLog::latest()->paginate(10);

        return Inertia::render('system-log/index', [
            'data' => $data,
        ]);
    }
}
