<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class CheckLandingPageStatus
{
    public function handle(Request $request, Closure $next)
    {
        $landingPageDisable = (setting('disable_landing_page')=='1') ? true:false;
        if ($landingPageDisable) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
