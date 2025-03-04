<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\Client;
use App\Traits\ApiReturnFormatTrait;
use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckApiKeyMiddleware
{
    use ApiReturnFormatTrait;
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('apikey')) {
            $api_check = Client::where('api_key', $request->header('apikey'))->first();
            if ($api_check) {
                // API key is valid
                return $next($request);
            } else {
                // API key is invalid
                return $this->responseWithError(__('API key invalid'), [], 403);
            }
        } else {
            // API key is missing
            return $this->responseWithError(__('api_key_missing'), [], 401);
        }
    }

}
