<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $role = $user->roles->first();

        if($role->name == 'Admin'){
            return $next($request);
        } else {
            //dd($role);
            abort(403, 'Access Forbidden');
            //return new Response(view('notauthorized')->with('role', 'admin'));

        }
    }
}
