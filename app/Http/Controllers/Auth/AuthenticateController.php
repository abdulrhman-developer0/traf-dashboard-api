<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Models\LoginAttempt;


class AuthenticateController extends Controller
{

    public function index()
    {
        return Inertia::render('auth/login', [
            'status' => session('status')
        ]);
    }

    public function store(Request $request)
    {
        
        // Login attempt record coming from the LoginSecurityCheck Middleware.
        $attemptLog = $request->attemptLog;

        if (Auth::attempt(['email'=>$request->email, 'password'=>$request->password], $request->boolean('remember'))) {
            
            $request->session()->regenerate();

            $attemptLog->update([
                'is_success' => true,
                'is_active' => true
            ]);

            return redirect()->intended('/dashboard');

        } 

        // Ban email/IP from login attempts for 1 hour 
        $this->banOnFailedAttemptExceeded($request,$attemptLog);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function banOnFailedAttemptExceeded($request,$attemptLog){
        
        $last_faild_attempts = LoginAttempt::where(function ($query) use ($request)
                                        {
                                            $query->where('email',$request->email)
                                                    ->orWhere('ip',$request->ip());
                                        })
                                        ->where('is_success',false)
                                        ->where('created_at', '>', now()->subHour()->toDateTimeString())
                                        ->count();

        if($last_faild_attempts == 4){
            $attemptLog->update([
                'banned_until' => now()->addHour()->toDateTimeString(),
            ]);
        }

    }
}
