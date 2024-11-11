<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Spatie\Permission\Models\Role;
use App\Models\Permission;
use App\Models\PermissionGroup;

use Inertia\Inertia;
use Auth;

class RolesController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('roles-and-permissions-roles-view')) {
            abort(403,'Access Forbidden');
        }

        $roles = Role::with('permissions')->with('users','users.employee','users.student')->get();

        $data = $roles->map(function ($role) {
            $role->permissions_names = $role->permissions->pluck('name')->toArray();
            return $role;
        })->toArray();

        $permissions_groups = PermissionGroup::orderBy('id', 'asc')->with('permissions')->get();

        $permissions_groups_grouped = $permissions_groups->mapToGroups(function ($item, $key) {
            return [$item['module'] => $item];
        });
        
        //dd($roles[2]->users);

        return Inertia::render('roles/index', [
            'data' => $data,
            'all_permissions' => $permissions_groups_grouped
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('roles-and-permissions-roles-add')) {
            abort(403,'Access Forbidden');
        }

        $data = $request->validate([
            'name' => 'required|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $permissions_names = $request->permissions_names;

        $permissions_ids = Permission::whereIn('name',$permissions_names)->pluck('id');

        $role->syncPermissions($permissions_ids);

        return back()->with('status', ['type' => 'success', 'action' => 'Add New Record', 'text' => 'Record has been added successfully.']);

    }

    public function update(Request $request, Role $role)
    {
        if (!Auth::user()->can('roles-and-permissions-roles-edit')) {
            abort(403,'Access Forbidden');
        }

        $data = $request->validate([
            'name' => 'required|max:255',
        ]);

        $role->update([
            'name' => $request->name
        ]);

        $permissions_names = $request->permissions_names;

        $permissions_ids = Permission::whereIn('name',$permissions_names)->pluck('id');

        $role->syncPermissions($permissions_ids);

        return back()->with('status', ['type' => 'success', 'action' => 'Updating Record', 'text' => 'Record has been updated successfully.']);

    }

    public function destroy(Role $role)
    {
        if (!Auth::user()->can('roles-and-permissions-roles-delete')) {
            abort(403,'Access Forbidden');
        }

        $role->delete();

        return back()->with('status', ['type' => 'success', 'action' => 'Deleting Record', 'text' => 'Record has been deleted successfully.']);

    }
}

