<?php

namespace App\Http\Middleware;

use App\Traits\APIResponses;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountType
{
    use APIResponses;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type, string ...$types): Response
    {
        foreach ([$type, ...$types] as $value) {
            if ( Auth::user()?->isAccount($value) ) return $next($request);
        }

        $available_accounts = implode('|', [$type, ...$types]);

        return $this->unauthorizedResponse([], "Plese login by $available_accounts account to access this resource");
    }
}
