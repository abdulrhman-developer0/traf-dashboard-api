<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


class DashboardController extends Controller
{
 
    public function index()
    {
        //dd(auth()->user());
        return Inertia::render('index', [
            'title' => 'Dashboard'
        ]);
    }

}
