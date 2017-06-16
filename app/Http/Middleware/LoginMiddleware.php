<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
class LoginMiddleware
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
        if($request["password"] == "" || $request["email"] == "") return redirect()->intended('/');
        return $next($request);
    }
}
