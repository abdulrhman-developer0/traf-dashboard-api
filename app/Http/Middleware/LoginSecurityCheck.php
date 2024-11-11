<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jenssegers\Agent\Agent;
use ElFactory\IpApi\IpApi;

use App\Models\User;
use App\Models\LoginAttempt;

class LoginSecurityCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // Check the banned status and time
        $banned_check = LoginAttempt::where(function ($query) use ($request)
                                        {
                                            $query->where('email',$request->email)
                                                    ->orWhere('ip',$request->ip());
                                        })
                                        ->where('is_success',false)
                                        ->where('banned_until', '>', now()->toDateTimeString())
                                        ->first();

        if($banned_check){
            $remain_time = round(now()->diffInMinutes($banned_check->banned_until,false), 0);
            return back()->withErrors([
                'tooManyLogins' => "Login disabled for $remain_time minutes."
            ])->onlyInput('email');
        }

        // Check if email exists
        $user = User::where('email', $request->email)->first();

        // Get request IP details -- The added IP is just for testing outside Localhost
        $ip_details = IpApi::default('197.37.134.140')->fields(['city', 'country'])->lookup();

        // Load user agent analyzer
        $user_agent_details = new Agent();
        $browser = $user_agent_details->browser();
        $platform = $user_agent_details->platform();

        // Store Login Attempt Details
        $attemptLog = LoginAttempt::create([
            'user_id' => $user ? $user->id : NULL,
            'email' => $request->email,

            'ip' => $request->ip(),
            'country' => $ip_details['country'],
            'city' => $ip_details['city'],

            'platform' => $platform,
            'platform_version' => $user_agent_details->version($platform),

            'browser' => $browser,
            'browser_version' => $user_agent_details->version($browser),

            'is_desktop' => $user_agent_details->isDesktop(),
            'is_phone' => $user_agent_details->isPhone(),
            'is_robot' => $user_agent_details->is_robot(),

            'device_name' => $user_agent_details->device(),

            'user_agent' => request()->header('User-Agent'),
        ]);

        $request->merge(['attemptLog' => $attemptLog]);

        return $next($request);
    }
}
