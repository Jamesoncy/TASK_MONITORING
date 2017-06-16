<?php

namespace App\Http\Middleware;

use Closure;

class CreateUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public $message = array();
    public function handle($request, Closure $next)
    {
        /*if(isset($request["full_name"]) && $request["password"] && $request["role"] && $request["email"]) return $next($request);
        else {
            $missing_fields = array();

                if(!$request->is('full_name')) array_push( $missing_fields,"Full Name");
                if(!$request->is('password')) array_push($missing_fields,"Password");
                if(!$request->is('role')) array_push($missing_fields,"Role");
                if(!$request->is('email')) array_push($missing_fields,"Email");
          
            $message = array(
                "type" => "warning",
                "title" => "Missing Fields Required..!",
                "message" => implode(", ", $missing_fields) . " Fields Are Required, Kindly Supplied Them...",
                "request" => $request
            );
           // \Session::forget('message');
              \Session::flash('message',json_encode($message));
             return redirect('admin/create_user');*/
              
        //}
        
        return $next($request);
    }

}
