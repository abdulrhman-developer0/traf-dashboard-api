<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Models\User;
use Auth;

class UserSettingsController extends Controller
{
   
    public function index(Request $request)
    {

        return Inertia::render('user/settings', [
            'title' => 'My Settings'

        ]);
    }

    public function changePassword(Request $request)
    {

        $fields = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->numbers()->letters()
            ]
        ]);

        $request->user()->update([
            'password' => Hash::make($fields['password'])
        ]);


        return back()->with('status', ['type' => 'success', 'action' => 'Change password', 'text' => 'Password has been changed successfully.']);

    }

}
