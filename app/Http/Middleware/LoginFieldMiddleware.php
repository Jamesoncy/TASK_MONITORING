<?php

namespace App\Http\Middleware;

use Closure;

class LoginFieldMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        print_r($request);
        exit;
        return $next($request);
    }
}
