<?php

namespace App\Http\Middleware;
use App\ProjectDetails;
use Closure;
use Session;
use App\Users;
use Illuminate\Support\Facades\Auth;
class CheckProjectMiddleware
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

        $project = ProjectDetails::where("id", $request->id)->first();
        if( $project ) 
            {   
                if(Auth::user()->role == 1) return $next($request);
                else{
                    if($project["programmer_id"] == Auth::user()->id) return $next($request);
                    else{
                        $message = json_encode(array(
                        "type" => "danger",
                        "title" => "Info..!",
                        "message" => 'You Have No Access to this Project'
                        ));
                        \Session::flash('flash_message', $message);
                        return redirect()->back();
                    }
                }
            } 
        else{
            $message = json_encode(array(
                    "type" => "danger",
                    "title" => "Info..!",
                    "message" => 'Project ID Does not Exist'
            ));
            \Session::flash('flash_message', $message);
            return redirect()->back();
        }
    }
}
