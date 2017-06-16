<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ProjectDetails;
use App\ProjectDetailsItem;
use Url;
use Session;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{

    public function index(){
    	return view('dashboard');
    }

    public function create_user(Request $request){
		return view('create_user')->with('user');
    }

    public function edit_user(Request $request){
    	$user = User::whereId($request->id)->first();
        $role = Auth::user()->role;
    	return view('edit_user')->with(['user' => $user, "role" => $role]);
    }
    

     public function user_list(Request $request){
     	$user = User::orderBy('id', 'desc')->get();
     	return view('user_list')->with('users', $user);
    }

    public function create_program(Request $request){
        Session::remove('program_details');
     	return view('create_program');
    }

    public function project_listing(){
        $id = (Auth::user()->role == 0) ? Auth::user()->id : null;
        $project_list = ProjectDetails::ProjectsList($id);
        return view('project_list')->with('projects', $project_list);
    }

    public function edit_project_form(Request $request){
        Session::remove('program_details');
        $project_array_details = array();
        $project = ProjectDetails::where("id",$request->id)->first();
        $project_details = ProjectDetailsItem::GetProjectItems($request->id)
                                            ->where('status',0);
        $users = User::select('id', 'name')->where("role",0)->pluck('name', 'id')->prepend('Select a Programmer', '')->toArray();                                    
        $project_details_edit = array();
        foreach($project_details as $pd)
            $project_details_edit[] = array(
                    "project_name" => $pd["project_name"],
                    "time_allocation_status" =>  $pd["time_allocation_status"],
                    "start_date" => $pd["start_date"], 
                    "end_date" => $pd["end_date"],
                    "remarks" => $pd["remarks"],
                    "updated_at" => $pd["updated_at"]
             );
        if($project->status == 0) Session::put('program_details', $project_details_edit);
        return view('edit_program')->with([ 'project'=>$project,'project_details'=>$project_details, 'users' => $users]);
    }

    public function view_project(Request $request){
        $project_array_details = array();
        $project = ProjectDetails::where("id",$request->id)->first();
        $project_details = ProjectDetailsItem::GetProjectItems($request->id);
        $time_allocation_status = 0;
        $project_details_edit = array();
        $complete = 0;
        $approve_button = "";
        foreach($project_details as $pd){
            $project_details_edit[] = array(
                    "project_name" => $pd["project_name"],
                    "time_allocation_status" =>  $pd["time_allocation_status"],
                    "start_date" => $pd["start_date"], 
                    "end_date" => $pd["end_date"],
                    "remarks" => $pd["remarks"],
                    
            );
           if($pd["time_allocation_status"]== 100) $complete++;
         }
         $approve_url = url('project/approve-request/'.$request->id);
         $cancel_approval = url('project/cancel-request/'.$request->id);
         if($project->status == 0 && $complete == $project_details->count()) $approve_button = ' <a href = "'.$approve_url.'" class="btn btn-success"><i class="fa fa-fw  fa-thumbs-up"></i></a>';
         else if($project->status == 1 && $complete == $project_details->count()) $approve_button = ' <a href = "'.$cancel_approval.'" class="btn btn-warning"><i class="fa fa-fw  fa-thumbs-down"></i></a>';
         if(Auth::user()->role == 1 && $project->status == 1 && $project->manage_id == null) {
            $approve_button = ' <a href = "'.$approve_url.'" class="btn btn-success"><i class="fa fa-fw  fa-thumbs-up"></i></a>';
            $approve_button = $approve_button.' <a href = "'.$cancel_approval.'" class="btn btn-warning"><i class="fa fa-fw  fa-thumbs-down"></i></a>';
         }
         if($project->status == 1 && $project->manage_id != null ) $approve_button = "";
        return view('view_program')->with([ 'project'=>$project,'project_details'=>$project_details, "approve_button" => $approve_button]);   
    }

    public function project_for_approval(){
        $projects_for_approve = ProjectDetails::where("status", 1)
                                            ->where("manage_id", null)
                                            ->get();
        return view('project_list')->with(['projects' =>  $projects_for_approve, "approval" => true ]);
    }

    public function ongoing_projects(){
        if(Auth::user()->id == 106) $programmers = User::where("id", 9)->get();
        else $programmers = User::where("role", 0)->get();
        $main_details = array();
        foreach($programmers as $programmer){
                $project = ProjectDetails::where('manage_id', null)
                                    ->where('programmer_id', $programmer->id);
                if(Auth::user()->role == 0) $project = $project->where("programmer_id", Auth::user()->id);
                $project = $project->orderBy("id", "desc")
                                ->get();

                if($project->count()>0) {
                    $details = array();
                    foreach($project as $header){
                        $approve_button = "";
                        $complete = 0;
                        $project_details = ProjectDetailsItem::where("project_details_id", $header["id"])
                                                            ->where('status', 0)
                                                            ->get();
                        foreach($project_details as $pd) if($pd["time_allocation_status"]== 100) $complete++;
                        $approve_url = url('project/approve-request/'.$header->id);
                        $cancel_approval = url('project/cancel-request/'.$header->id);
                        $edi_url = url('project/edit-project/'.$header->id);
                        if($header->status == 0 && $complete == $project_details->count()) $approve_button = ' <a href = "'.$approve_url.'" class=" btn-success"><i class="fa fa-fw  fa-thumbs-up"></i></a>';
                        else if($header->status == 1 && $complete == $project_details->count()) $approve_button = ' <a href = "'.$cancel_approval.'" class=" btn-warning"><i class="fa fa-fw  fa-thumbs-down"></i></a>';
                        if(Auth::user()->role == 1 && $header->status == 1 && $header->manage_id == null) {
                            $approve_button = ' <a href = "'.$approve_url.'" class=" btn-success"><i class="fa fa-fw  fa-thumbs-up"></i></a>';
                            $approve_button = $approve_button.' <a href = "'.$cancel_approval.'" class="btn-warning"><i class="fa fa-fw  fa-thumbs-down"></i></a>';
                        }
                        $edit_button = ' <a href = "'.$edi_url.'" class=" btn-primary"><i class="fa fa-fw  fa-pencil"></i></a>';
                        if($header->status == 0) $approve_button = $edit_button.' '.$approve_button;
                        $details[] = array( "data" => $project_details, "approve_button" => $approve_button, "header" => $header);
                    }

                     $main_details[] = array(
                            "details" => $details,
                            "programmer_name" => $programmer->name
                     );
                 }
             
        }
        Session::put('pdf_excel', $main_details);
        return view('ongoing_projects')->with('projects', $main_details);

    }

    public function create_excel(Request $request){
       \Excel::create('TimeLine_Project'. date("Y-m-d_h:i:s"), function($excel) {
        
            $excel->setTitle('no title');
            $excel->setCreator('no no creator')->setCompany('no company');
            $excel->setDescription('report file');
            $excel->sheet('sheet1', function($sheet) {
                $projects = Session::get('pdf_excel');
                $data_record = array();
                $row_number = 0;
                foreach($projects as $row){
                    $programmer_name = $row["programmer_name"];
                    $sheet->appendRow(array($programmer_name));
                    $row_number++;
                    $sheet->cells('A'.$row_number, function($cells) {
                        $cells->setFontWeight('bold');
                    });
                    $counter = 0;
                    foreach($row["details"] as $details)
                    {
                        $data_record = array("#", $details["header"]["project"], "Start Date", "End Date", "Time Allocation Status", "Time Allocation Days", "Remarks");
                        $sheet->appendRow($data_record);
                        $row_number++;
                        $sheet->cells('A'.$row_number.':G'.$row_number, function($cells) {
                            $cells->setFontWeight('bold');
                        });
                        foreach($details["data"] as $pd){
                              $counter++;
                                    $time_allocation = 0;
                                    $str = wordwrap($pd->project_name,50,'<br>',TRUE); 
                                    $end_date = $pd->end_date;
                                    $start_date = $pd->start_date;
                                    $overtime_end_date = $pd->overtime_end_date;
                                    $today = date('Y-m-d');
                                    $time_allocation = 1;
                                    $label = "label label-";
                                     $type = "success";
                                    $date1=date_create($start_date);
                                    $date2=date_create($end_date);
                                    $updated_at = date("Y-m-d", strtotime($pd->updated_at));
                                    $over_time = date('Y-m-d',date(strtotime("+1 day", strtotime($end_date))));
                                    if($pd->time_allocation_status == 100){
                                        if($updated_at <= $end_date) {
                                           $date1=date_create($start_date);
                                           $date2=date_create($end_date);
                                           $type = "success";
                                        }
                                        else{
                                          $date2=date_create($pd->updated_at);
                                         $date1=date_create($over_time);
                                         $type = "danger";
                                       }
                                      $diff=date_diff($date1,$date2);
                                      $time_allocation = $time_allocation + $diff->format("%R%a days"); 
                                      $time_allocation = $time_allocation;
                                    } else {
                                          
                                           if($end_date >= $today){
                                              $date1=date_create($start_date);
                                              $date2=date_create($end_date);
                                              $type = "success"; 
                                           }
                                           else {
                                             $date2=date_create($today);
                                             $date1=date_create($over_time);
                                             $type = "danger"; 
                                             $time_allocation_status =  0;
                                           }
                                          
                                      $diff=date_diff($date1,$date2);
                                      $time_allocation = $time_allocation + $diff->format("%R%a days"); 
                                      $time_allocation = $time_allocation;
                                    }
                                    $end_date = date( "m/d/Y", strtotime($pd->end_date));
                                    $start_date = date( "m/d/Y", strtotime($pd->start_date));
                                    $data_record = array($counter, $str, $start_date, $end_date, $pd->time_allocation_status, $time_allocation, $pd->remarks);
                                    $sheet->appendRow($data_record);
                                    $row_number++;
                                    $sheet->cells('F'.$row_number, function($cells) use($type) {
                                        if($type == "success") $cells->setBackground('#008000');
                                        else  $cells->setBackground('#FF0000');
                                    });
                        }
                    }
                }

            });
        })->download('xlsx');



    }
    

}
