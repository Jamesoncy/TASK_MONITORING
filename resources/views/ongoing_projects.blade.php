@extends('layouts.master_layout')
@section('title_page', 'Ongoing Projects')
@section('content')
 {{ Form::open(array('url' => 'project/save-complete-project-details', 'method' => 'post')) }}

 <div class = "pull-right"> @if(Auth::user()->role == 1)<button type="submit" class="btn btn-success">Submit</button> @endif</div>
 <div class = "pull-left"> @if(Auth::user()->role == 1)<a  href="{{  URL::asset('project/create-csv') }}" id = "excel" class="btn btn-primary">Excel</a> @endif</div>
  {!! csrf_field() !!}
@foreach($projects as $project)
    
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                     <span class="text"><b><h4>Programmer Name</b>: {{ $project["programmer_name"] }}</h4></span>
                </div>
                 <div class="box-footer pull-right">
             </div>
                     <div class="form-group">      
                  <div class="box-body">
                      @php
                        $project_details = $project["details"];
                      @endphp

                      <table class="table table-bordered">
                        @foreach($project_details as  $i => $details)
                        <tr>
                          @if( Auth::user()->role == 1 )<th style="width: 5%"></th> @endif
                          <th style="width: 5%">#</th>
                          <th ><a href="#" class="name"> {{ $details["header"]["project"] }} </a></th>
                           @if($i == 0)
                          <th>Start Date</th>
                          <th>End Date</th>
                          <th>Time Allocation Status</th>
                          <th>Time Allocation Days</th>
                          <th>Remarks <div class = "pull-right"> {!! $details["approve_button"] !!}</div></th>
                          @else
                           <th  style="width: 10%"></th><th  style="width: 10%"></th><th  style="width: 10%"></th><th  style="width: 10%"></th><th  style="width: 10%"> <div class = "pull-right"> {!! $details["approve_button"] !!} </div></th>
                          @endif
                         
                          
                        </tr>
                         
                       <tbody id = "append-row">
                        @php 
                            $counter = 0;
                        @endphp
                            @foreach($details["data"] as $pd)
                                 @php 
                                    $counter++;
                                    $time_allocation = 0;
                                    $str = wordwrap($pd->project_name,50,'<br>',TRUE); 
                                    $end_date = $pd->end_date;
                                    $start_date = $pd->start_date;
                                    $overtime_end_date = $pd->overtime_end_date;
                                    $today = date('Y-m-d');
                                    $time_allocation = 1;
                                    $label = "label label-";
                                    $date1=date_create($start_date);
                                    $date2=date_create($end_date);
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
                                    @if( Auth::user()->role == 1 )<th style="width: 5%">  @if( $pd->time_allocation_status == 100 )   <input type = "checkbox" class = "child-checkbox" value = "{{ $pd->id }}" name = "child_box[]"/> @endif</th>@endif
                                    <td>{{ $counter }}</td>
                                    <td style = " white-space: nowrap;
    width: 1%;font-weight: bold;">{!! $str !!}</td>
                                    <td> {{ $start_date }} </td>
                                    <td>{{ $end_date }}</td>
                                    <td>{{$pd->time_allocation_status}}</td>
                                    <td align = "center">{!! $time_allocation !!}</td>
                                    <td>{{ $pd->remarks }}</td>
                                  </tr>

                            @endforeach
                        @endforeach
                      </table>
                    </div>
                </div>   
             </div>

         </div>
  </div>
</div>
@endforeach
{{ Form::close() }}
@endsection
@section('customize_js')

    $(".master-checkbox").click(function(){
            var parent_table = $(this).closest("tbody"),
              childbox = parent_table.find(".child-checkbox");
            if($(this).is(":checked")) childbox.prop('checked', true);
            else  childbox.prop('checked', false);
    });

@endsection