<?php

namespace App\Http\Middleware;

use Closure;
use App\ProjectDetails;
use Session;
class CheckProjectApprovedMiddleware
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
        if($project["status"] == 0)  return $next($request);
         $message = json_encode(array(
                    "type" => "danger",
                    "title" => "Info..!",
                    "message" => 'This Project Is Approved, Cannot be Edited!'
            ));
        \Session::flash('flash_message', $message);
        return redirect()->back();
    }
}
