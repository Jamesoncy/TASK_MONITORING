<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckEditProject;
use App\ProjectDetails;
use App\ProjectDetailsItem;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;
class Project extends Controller
{
    public function edit(CheckEditProject $request){
    	$date = date("Y-m-d");
    	$index = $request->input("index");
    	$get_project_details = $request->session()->get('program_details');
    	$request->session()->forget('program_details');
		if(isset($get_project_details[$index])){
			$project_details["remarks"] ="";
			if($request->has("remarks")) $get_project_details[$index]["remarks"] = $request->input("remarks");
	    	if($request->has("project_name"))$get_project_details[$index]["project_name"] = $request->input("project_name");
	    	if($request->has("time_allocation_status")) {
	    		if($request->input("time_allocation_status") != $get_project_details[$index]["time_allocation_status"]){
	    			$get_project_details[$index]["time_allocation_status"] = $request->input("time_allocation_status");
	    			$get_project_details[$index]["updated_at"] = $date;
	    		}
	    	}
	    	if($request->has("start_date")) $get_project_details[$index]["start_date"] = date( "Y-m-d",strtotime($request->input("start_date")) );
	    	if($request->has("end_date")) $get_project_details[$index]["end_date"] = date( "Y-m-d", strtotime($request->input("end_date")) );
	    	$date_diff = 1;
	    	$date1=date_create($get_project_details[$index]["start_date"]);
	        $date2=date_create($get_project_details[$index]["end_date"]);
	        $diff=date_diff($date1,$date2);
	        $date_diff = $date_diff + $diff->format("%R%a days");

			$request->session()->put('program_details', $get_project_details);
			return  response()->json(['result' => $get_project_details, 'date_diff' => $date_diff ]);
		} else {
			$date_diff = 1;
	        $date1=date_create($request->input('start_date'));
	        $date2=date_create($request->input('end_date'));
	        $diff=date_diff($date1,$date2);
	        $date_diff = $date_diff + $diff->format("%R%a days");
			$project_details = array();
			$project_details["remarks"] ="";
			if($request->has("project_name")) $project_details["project_name"] = $request->input("project_name");
			if($request->has("remarks")) $project_details["remarks"] = $request->input("remarks");
			if($request->has("start_date")) $project_details["start_date"] = $request->input("start_date");
			if($request->has("end_date")) $project_details["end_date"] = $request->input("end_date");
			$get_project_details[] = $project_details;
			$request->session()->put('program_details', $get_project_details);
			return response()->json(['date_diff' => $date_diff ]);
		}
	}

	public function save_edited_project(Request $request){
		$get_project_details = $request->session()->get('program_details');
		$insert_details = array();

		if($request->has("programmer_id")){
			$this->validate($request, [
		        'programmer_id' => 'exists:users,id'
		    ]);
		  	ProjectDetails::where('id', $request->id)->update(['programmer_id' => $request->input("programmer_id")]);
		}

		ProjectDetailsItem::where('project_details_id', $request->id)
							->where('status', 0)
							->delete();
		foreach($get_project_details as $gpd){
			$gpd["project_details_id"] = $request->id;
			$gpd["start_date"] = date("Y-m-d" , strtotime($gpd["start_date"]));
			$gpd["end_date"] = date("Y-m-d" , strtotime($gpd["end_date"]));
			if(isset($gpd["updated_at"])) $gpd["updated_at"] = date("Y-m-d" , strtotime($gpd["updated_at"]));
			ProjectDetailsItem::insert($gpd);
		} 
		
		$message = json_encode(array(
	                "type" => "success",
	                "title" => "Info..!",
	                "message" => 'Project Has been Successfully edited'
	    ));
		Session::flash('flash_message', $message);
		return redirect()->back();
	}

	public function project_approval(Request $request){
		if(Auth::user()->role == 0){
			ProjectDetails::where('id', $request->id)
         	->update(['status' => 1]);
        } else {
        	ProjectDetails::where('id', $request->id)
         	->update(['status' => 1, 'manage_id' => Auth::user()->id]);
        }
         $message = json_encode(array(
	                "type" => "success",
	                "title" => "Info..!",
	                "message" => 'Project Has been Approved Successfully'
	     ));
         Session::flash('flash_message', $message);
         return redirect()->back();
	}

	public function project_cancel(Request $request){
		ProjectDetails::where('id', $request->id)
         ->update(['status' => 0]);
         $message = json_encode(array(
	                "type" => "success",
	                "title" => "Info..!",
	                "message" => 'Project Has been Cancelled Successfully'
	     ));
         Session::flash('flash_message', $message);
         return redirect()->back();
	}

	public function save_complete_details(Request $request){
		ProjectDetailsItem::whereIn('id', $request->input("child_box"))->update(['status' => 1]);
		$message = json_encode(array(
	                "type" => "success",
	                "title" => "Info..!",
	                "message" => 'Project Details Approved Successfully'
	     ));
         Session::flash('flash_message', $message);
         return redirect()->back();
	}

	public function delete_project(Request $request){
		ProjectDetails::where('id', $request->id)->delete();
		ProjectDetailsItem::where('project_details_id', $request->id)->delete();
		 $message = json_encode(array(
	                "type" => "success",
	                "title" => "Info..!",
	                "message" => 'Project Details Deleted Successfully'
	     ));
         Session::flash('flash_message', $message);
         return redirect()->back();
	}

	public function create_excel(){
		
	}

}
