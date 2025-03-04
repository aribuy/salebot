<?php
namespace App\Providers;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        $this->app->singleton('settings', function () {
            return Cache::rememberForever('settings', function () {
                return Schema::hasTable('settings') ? Setting::all() : collect();
            });
        });
        $this->app->singleton('languages', function () {
            return Cache::rememberForever('languages', function () {
                return Schema::hasTable('languages') ? Language::where('status', 1)->get() : collect();
            });
        });
        $this->app->singleton('currencies', function () {
            return Cache::rememberForever('currencies', function () {
                return Schema::hasTable('currencies') ? Currency::all() : collect();
            });
        });
        $this->app->singleton('permission_list', function () {
            return Auth()->user()->permissions;
        });

        //load migration for subdirectory
        $mainPath       = database_path('migrations');
        $directories    = glob($mainPath . '/*' , GLOB_ONLYDIR);
        $paths          = array_merge([$mainPath], $directories);
        $this->loadMigrationsFrom($paths);
    }
}
