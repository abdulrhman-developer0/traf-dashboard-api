<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'user' => fn () => $request->user()
                ? $request->user()->only('id', 'name', 'email')
                : null,
            'user.roles' => $request->user() ? $request->user()->roles->pluck('name') : [],
            'user.permissions' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
            'user.notifications' => $request->user() ? $request->user()->notifications()->orderBy('id','desc')->limit(10)->get() : [],
            'user.main_role' => $request->user() ? $request->user()->roles->first() : [],
            'user.photo' => $request->user() ? ($request->user()->student ? $request->user()->student->photo : null) : null,

            'flash' => [
                'status'=> session('status')
            ]
    
        ]);
    }
}
