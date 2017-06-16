<?php

namespace App\Http\Controllers;

use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\ProjectDetailsRequest;
use App\User;
use App\ProjectDetails;
use App\ProjectDetailsItem;
use Session;

class UserController extends Controller
{
    public function create(CreateUserRequest $request){
    	$request->offsetUnset('password_confirmation');
    	$request->merge( array('password' => md5($request->password) ) );
    	$result = User::create($request->input());
    	if($result->id){
	    	$message = json_encode(array(
	                "type" => "success",
	                "title" => "Info..!",
	                "message" => 'User '.$request->input('name').' successfully created!'
	        ));
        } else {
        	$message = json_encode(array(
	                "type" => "danger",
	                "title" => "Info..!",
	                "message" => 'Something Wrong Happen, Contact IT Department for this issue..!'
	        ));
        }
		Session::flash('flash_message', $message);
    	return redirect()->back();
    }

    public function edit_user(EditUserRequest $request){
        $result = User::where('id', $request->id)->update(array(
            "email" => $request->input('email'),
            "name" => $request->input('name'),
            "role" => $request->input('role')
        ));

        if($result){
            $message = json_encode(array(
                    "type" => "success",
                    "title" => "Info..!",
                    "message" => 'User '.$request->input('name').' Edited Successfully!'
            ));
        } else {
            $message = json_encode(array(
                    "type" => "danger",
                    "title" => "Info..!",
                    "message" => 'Something Wrong Happen, Contact IT Department for this issue..!'
            ));
        }

        Session::flash('flash_message', $message);
        return redirect()->back();
    }

    public function save_details(ProjectDetailsRequest $request){
        $date_diff = 1;
        $date1=date_create($request->input('start_date'));
        $date2=date_create($request->input('end_date'));
        $diff=date_diff($date1,$date2);
        $date_diff = $date_diff + $diff->format("%R%a days");
        $program_details = array();
        $program_details = $request->session()->get('program_details');
        $program_details[] = array(
            "program_details" =>  $request->input('project_name'),
            "start_date" => $request->input('start_date'),
            "end_date" => $request->input('end_date'),
            "remarks" => $request->input('remarks') 
        );
        $request->session()->put('program_details', $program_details);
        return response()->json(['date_diff' => $date_diff ]);
    }

    public function remove_project(Request $request){
        $program_details = $request->session()->get('program_details');
        unset($program_details[$request->input("index")]);
        $program_details = array_values($program_details);
        $request->session()->put('program_details', $program_details);
        return response()->json(['program_details' => $     $this->validate($request, [
            'project_name' => 'required|unique:projects,project'
        ]);

     .$request->input('project_name').' Created Successfully!'
                ));
                Session::flash('flash_message', $message);
            return redirect()->back();
        }
       
    }

   
}
   $get_project_details = $request->session()->get('program_details');
        if(empty($get_project_details)) {
            $message = json_encode(array(
                            "type" => "danger",
                            "title" => "Info..!",
                            "message" => 'No Project Items Found..!'
            ));
            Session::flash('flash_message', $message);
            return redirect()->back();
        }

        $project = array(
            "project" => $request->input("project_name"),
            "programmer_id" => Auth::user()->id
        );
        $result = ProjectDetails::create($project);
        if($result->id){
            $project_details = array();
            foreach($get_project_details as $project_items){
                $time_allocation = (isset($project_items["time_allocation"])) ? $project_items["time_allocation"] : 0;
                $overtime = (isset($project_items["overtime_final_status"])) ? $project_items["overtime_final_status"] : 0;
                $project_details[] = array(
                    "project_details_id" => $result->id,
                    "project_name" => $project_items["program_details"],
                    "time_allocation_status" =>  $time_allocation,
                    "overtime_final_status" => $overtime,
                    "start_date" =>date("Y-m-d", strtotime($project_items["start_date"])), 
                    "end_date" => date("Y-m-d", strtotime($project_items["end_date"])),
               );
            }
                ProjectDetailsItem::insert($project_details);
                Session::remove('program_details');
                $message = json_encode(array(
                            "type" => "success",
                            "title" => "Info..!",
                            "message" => 'Project '.$request->input('project_name').' Created Successfully!'
                ));
                Session::flash('flash_message', $message);
            return redirect()->back();
        }
       
    }

   
}
