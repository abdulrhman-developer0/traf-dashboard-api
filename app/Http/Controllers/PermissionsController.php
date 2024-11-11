<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\PermissionGroup;
use Auth;

class PermissionsController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('roles-and-permissions-permissions-view')) {
            abort(403,'Access Forbidden');
        }

        $groups = PermissionGroup::orderBy('id', 'asc')->with('permissions')->get();

        $data = $groups->mapToGroups(function ($item, $key) {
            return [$item['module'] => $item];
        });
        

        //dd($data);

        return Inertia::render('permissions/index', [
            'data' => $data,
        ]);
    }

}

