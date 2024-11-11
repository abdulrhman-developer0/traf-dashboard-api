<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

use App\Models\User;


class SystemTrashController extends Controller
{
   
    public function index(Request $request)
    {
        $model = 'User';
        $targetModel = "App\Models\\".$model;

        $hasMediaModels = ['User'];
        $data = $targetModel::onlyTrashed();

        if(in_array($model,$hasMediaModels)){
            $data = $data->with('media');
        }
        $data = $data->paginate(5);

        return Inertia::render('system-trash/index', [
            'data' => $data,
        ]);
    }

   
}
