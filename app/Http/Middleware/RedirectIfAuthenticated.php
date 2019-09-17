<?php

namespace App\Http\Middleware;

use Closure;
use App\Base\BaseResponse;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = new BaseResponse();

        if (Auth::guard($guard)->check()) {
            return $response->statusFail(['message' => 'You are already authenticated']);
        }

        return $next($request);
    }
}
