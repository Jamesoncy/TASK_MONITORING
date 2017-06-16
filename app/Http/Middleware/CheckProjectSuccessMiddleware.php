<?php

namespace App\Http\Middleware;

use Closure;
use App\ProjectDetails;
class CheckProjectSuccessMiddleware
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
        if($project["status"] == 1 && $project["manage_id"] != null){
              $message = json_encode(array(
                    "type" => "danger",
                    "title" => "Info..!",
                    "message" => 'This Project Is Complete, Cannot be Approved , Cancelled Again, or Delete!'
            ));
            \Session::flash('flash_message', $message);
            return redirect()->back();
        }
        return $next($request);
    }
}
