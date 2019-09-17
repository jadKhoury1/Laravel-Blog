<?php

namespace App\Http\Middleware;

use App\Base\BaseResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        $response = new BaseResponse();
        if (! $request->expectsJson()) {
            return $response->forbidden();
        }
    }
}
