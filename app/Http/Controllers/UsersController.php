<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Inertia\Inertia;

use App\Models\User;
use App\Models\Role;

class UsersController extends Controller
{
   
    public function index(Request $request)
    {
        $users = User::latest()->paginate(5);

        return Inertia::render('user/list/index', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|lowercase|email|max:255',
            'password' => 'required|min:3'
        ]);

        $user = User::create($data);
       
        /*if($user){
            $role = Role::find($request->role);
            $user->assignRole($role);
        }*/

        return back()->with('status', ['type' => 'success', 'action' => 'Add New User', 'text' => 'User has been added successfully.']);


    }


    public function destroy(string $id)
    {
       
    }

}
