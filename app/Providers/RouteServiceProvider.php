<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME   = '/dashboard';

    public const ADMIN  = '/admin/dashboard';

    public const CLIENT = '/client/dashboard';

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            Route::middleware('web')
                ->group(base_path('routes/install.php'));

            Route::middleware(['web', 'isInstalled'])
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'isInstalled'])
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'auth', 'verified'])
                ->name('client.')
                ->group(base_path('routes/client.php'));
            Route::middleware(['api'])
                ->group(base_path('routes/api.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
