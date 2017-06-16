<?php

namespace App\Http\Middleware;

use Closure;
use App\ProjectDetailsItem;
class CheckCompleteTimeAllocationMiddleware
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
        if(!$request->has("child_box")) return redirect()->back();


        $checkbox = $request->input("child_box");
        $unset = array();
        foreach($checkbox as $index => $cb)
        {
            $projects = ProjectDetailsItem::where("id",$cb)
                                    ->where("time_allocation_status", 100)
                                    ->count();
            if($projects == 0) array_push($unset, $index);
        }
        foreach($unset as $uns) unset($checkbox[$uns]);
        $checkbox = array_values($checkbox);
        $request->replace(array('child_box' => $checkbox));
        return $next($request);
    }
}
