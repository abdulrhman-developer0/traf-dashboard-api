<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        /*Gate::before(function ($user, $ability) {
            if ($user->hasRole(env('APP_SUPER_ADMIN', 'super admin'))) {
                return true;
            }
        });*/
    }
}
