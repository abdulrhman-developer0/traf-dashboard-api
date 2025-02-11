<?php

namespace App\Http\Middleware;
use App\Models\Activity;

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
            'user' => fn () => auth()->user()
                ? auth()->user()->only('id', 'name', 'email')
                : null,
            'user.photo' => $request->user() ? ($request->user()->student ? $request->user()->student->photo : null) : null,
            'activities' => Activity::latest()->take(5)->get(),
            'notifications' => $request->user() ? $request->user()->notifications()->latest()->limit(5)->get() : [],

            'flash' => [
                'status'=> session('status')
            ]
    
        ]);
    }
}
