<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\ProjectDetails;
use Session;
class CheckProjectExistMiddleware
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
        $message = json_encode(array(
                    "type" => "danger",
                    "title" => "Info..!",
                    "message" => 'Project ID Does not Exist'
            ));
       if(Auth::user()){
            if(ProjectDetails::where("id", $request->id)->first()) return $next($request);
            else {
                \Session::flash('flash_message', $message);
                return redirect()->back();
            }
        }
        \Session::flash('flash_message', $message);
        return redirect()->back();
    }
}
