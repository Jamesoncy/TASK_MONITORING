<?php

namespace App\Http\Middleware;

use Closure;
use App\ProjectDetailsItem;
use Session;
class CheckApprovalMiddleware
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
        $approvals = ProjectDetailsItem::where("project_details_id", $request->id)->get();
        $complete = 0;
        foreach($approvals as $approval){
            if ($approval["time_allocation_status"] == 100) $complete++;
        }
        if($approvals->count() == $complete) return $next($request);
         $message = json_encode(array(
                    "type" => "danger",
                    "title" => "Info..!",
                    "message" => 'This Project Is Not Yet Completed..!'
            ));
        \Session::flash('flash_message', $message);
        return redirect()->back();
    }
}
