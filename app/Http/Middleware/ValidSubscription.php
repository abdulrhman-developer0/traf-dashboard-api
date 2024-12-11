<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $account = Auth::user()->account();

        // if (!$account->current_subscriptions) {
        //     return response()->json(['message' => 'Access denied. No valid subscription found.'], 403);
        // }

        return $next($request);
    }
}