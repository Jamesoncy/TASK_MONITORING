<?php

namespace App\Http\Middleware;

use Closure;
use App\ProjectDetails;
use Session;
class CheckProjectApprovalMiddleware
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
        $project = ProjectDetails::where("id", $request->id)
                                ->where('status', 1)
                                ->get();
        if($project->count() > 0) {
             $message = json_encode(array(
                    "type" => "danger",
                    "title" => "Info..!",
                    "message" => 'This Project Is Pending For Approved, Cannot be Edited!'
            ));
            \Session::flash('flash_message', $message);
            return redirect()->back();
        }

        return $next($request);
    }
}
