<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\User;


class UserProfileController extends Controller
{
    use APIResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('user/profile', [
            'user' => $user,
            'title' => 'My Profile'

        ]);
    }

    public function store(Request $request)
    {
        //dd($request);

        $user = Auth::user();

        $user->update([
            'email' => $request->email,
            'name' => $request->name
        ]);

        if ($request->newAvatar) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('newAvatar')->toMediaCollection('avatar');
        }


        return back()->with('status', ['type' => 'success', 'action' => 'Update Profile', 'text' => 'Profile has been updated successfully.']);

    }


}
