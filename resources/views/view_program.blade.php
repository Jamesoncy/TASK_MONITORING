@extends('layouts.master_layout')

@section('title_page', 'View Project')

@section('content')
<div id = "validation-concern">
 
</div>

 <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Project Details Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <div class="box-body">
              	<div class="form-group">
                  {{ Form::label('project_name', 'Project Name') }}  
                  {{ Form::text('project_name',  $project->project , array( "disabled" => "", "class" => "form-control" , "placeholder" => "Enter Your Project Name" ) ) }} 
                </div>
                 <div class="box-footer pull-right">
                  @php
                    echo $approve_button;
                  @endphp
             </div>
                <div class="form-group">      
                  <div class="box-body">
                      <table class="table table-bordered">
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Project Details</th>
                          <th>Start Date </th>
                          <th>End Date</th>
                          <th>Time Allocation Status</th>
                          <th>Time Allocation Days(s)</th>
                          <th>Remarks</th>
                        </tr>
                         
                       <tbody id = "append-row">
                          @php 
                            $counter = 1;
                          @endphp
                         @foreach($project_details as $pd)
                          @php 
                           $str = wordwrap($pd->project_name,40,'<br>',TRUE); 
                             $time_allocation = 0;
                            $end_date = $pd->end_date;
                            $start_date = $pd->start_date;
                            $overtime_end_date = $pd->overtime_end_date;
                            $today = date('Y-m-d');
                             $time_allocation = 1;
                                    $label = "label label-";
                                    $updated_at = date("Y-m-d", strtotime($pd->updated_at));
                                    $over_time = date('Y-m-d',date(strtotime("+1 day", strtotime($end_date))));
                                    if($pd->time_allocation_status == 100){
                                        if($updated_at <= $end_date) {
                                           $date1=date_create($start_date);
                                           $date2=date_create($end_date);
                                          $label = $label."success";
                                        }
                                        else{
                                         $date2=date_create($pd->updated_at);
                                         $date1=date_create($over_time);
                                         $label = $label."danger";
                                       }
                                      $diff=date_diff($date1,$date2);
                                      $time_allocation = $time_allocation + $diff->format("%R%a days"); 
                                      $time_allocation = '<span class="'.$label.'">'.$time_allocation.'</span>';
                                    } else {
                                          if($end_date >= $today){
                                              $date1=date_create($start_date);
                                              $date2=date_create($end_date);
                                              $label = $label."success"; 
                                           }
                                           else {
                                             $date2=date_create($today);
                                             $date1=date_create($over_time);
                                             $label = $label."danger"; 
                                             $time_allocation_status =  0;
                                           }
                                      $diff=date_diff($date1,$date2);
                                      $time_allocation = $time_allocation + $diff->format("%R%a days"); 
                                      $time_allocation = '<span class="'.$label.'">'.$time_allocation.'</span>';
                                    }


                            $end_date = date( "m/d/Y", strtotime($pd->end_date));
                            $start_date = date( "m/d/Y", strtotime($pd->start_date));

                          @endphp
                          <tr>
                          <td>{{ $counter }}</td>
                          <td><label class = "project-name">{!! $str !!}</label></td>
                          <td><div class="input-group date"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input type="text" value = " @php echo $start_date @endphp " class="form-control pull-right datepicker start-date" disabled></div> </td>
                          <td><div class="input-group date"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input type="text" value = " @php echo $end_date @endphp " class="form-control pull-right datepicker end-date" disabled></div></td>
                          <td><input  type = "number" value = {{ $pd->time_allocation_status  }} class= "form-control time-allocation-status" required disabled/></td>
                           <td align = "center">{!! $time_allocation !!}</td>
                           <td><input type = "text" value = "{{ $pd->remarks }} "  class = "form-control remarks" required disabled/></td>
                          </tr>
                          @php
                            $counter++;
                          @endphp
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                </div>         
             </div>

         </div>
          <!-- /.box -->

              <div class="box-footer">
                <a href="{{url('project/ongoing-projects')}}" class="btn btn-success">On Going Projects</a>  <a href="{{url('projects')}}" class="btn btn-danger">Cancel</a> 
              </div>
		</div>

       
</div>
@endsection

