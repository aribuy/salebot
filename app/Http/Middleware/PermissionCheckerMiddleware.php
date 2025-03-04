<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionCheckerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            if (Auth()->user()->role_id == 1 || Auth()->user()->role_id == 2) {
                return $next($request);
            }

            $permissions = collect(auth()->user()->permissions);

            if (substr($request->route()->getName(), -6) == '.index') {
                return $next($request);
            }

            if (substr($request->route()->getName(), -6) == '.store') {
                if ($permissions->contains(str_replace('.store', '.create', $request->route()->getName()))) {
                    return $next($request);
                } else {
                    abort(403);
                }
            }
            if (substr($request->route()->getName(), -7) == '.update') {
                if ($permissions->contains(str_replace('.update', '.edit', $request->route()->getName()) || '.update')) {
                    return $next($request);
                } else {
                    abort(403);
                }
            } else {
                if ($permissions->contains($request->route()->getName())) {
                    return $next($request);
                } else {
                    abort(403);
                }
            }
        }
    }
}
