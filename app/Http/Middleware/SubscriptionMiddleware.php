<?php

namespace App\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;

class SubscriptionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->activeSubscription) {
            return $next($request);
        } elseif (auth()->user()->pendingSubscription) {
            return redirect()->route('client.pending.subscription');
        } else {
            Toastr::warning(__('subscribe_plan_to_access'));

            return redirect()->route('client.available.plans');
        }
    }
}
