@extends('layouts.master_layout')

@section('title_page', 'Edit Your Project')

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
            {{ Form::open(array('url' => 'project/save_edited_project/'. $project->id, 'method' => 'post')) }}
            	 {!! csrf_field() !!}

              <div class="box-body">
              	<div class="form-group">
                  {{ Form::label('project_name', 'Project Name') }}  
                  {{ Form::text('project_name',  $project->project , array( "disabled" => "", "class" => "form-control" , "placeholder" => "Enter Your Project Name" ) ) }} 
                </div>
                @if(Auth::user()->role == 1)
                <div class = "row">
                  <div class="form-group">
                    <div class = "col-md-12">
                        <div class = "col-md-6">
                        {{ Form::label('care_of', 'Care Of') }}  
                         {{ Form::text('name', $users[$project->programmer_id]  ,array("name" => "id", "disabled" => "", "class" => "form-control"  )) }}
                        </div>
                        <div class = "col-md-6">
                           {{ Form::label('Transfer', 'Transfer To') }}  
                           {{ Form::select('programmer_id', $users , null, array('class' => 'form-control') ) }}
                        </div>
                     </div>
                  </div>
                </div>
                @endif
                <div class="form-group">
                    <br>
                    <button type="button" id = "add-project" class="btn btn-success"><i class="fa fa-fw fa-plus"></i></button>
                </div>
                <div class="form-group">      
                  <div class="box-body">
                      <table class="table table-bordered">
                        <tr>
                          <th >#</th>
                          <th>Project Details</th>
                          <th>Start Date </th>
                          <th>End Date</th>
                          <th>Time Allocation Status</th>
                           <th>Time Allocation Days(s)</th>
                          
                           <th>Remarks</th>
                          <th>Action</th>
                        </tr>
                         
                       <tbody id = "append-row">
                          @php 
                            $counter = 1;
                            $edit_button = '<button type = "button" class="btn btn-primary  edit-program"><i class="fa fa-fw fa-pencil"></i></button>';
                            $approve_button = '<button type = "button" class="btn btn-primary approve-project" style="display: none;"><i class="fa fa-fw fa-check"></i></button>';
                            $delete = '<button type="button" class="btn btn-danger remove-project"><i class="fa fa-fw fa-trash"></i></button>';
                          @endphp
                         @foreach($project_details as $pd)
                          @php 
                          $str = wordwrap($pd->project_name,40,'<br>',TRUE); 
                           $time_allocation = 0;
                            $end_date = $pd->end_date;
                            $start_date = $pd->start_date;
                            $today = date('Y-m-d');
                             $buttons = $edit_button.$approve_button.$delete;
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
                          <td>{{ $counter }}<input type="hidden" class="token" value="{{ csrf_token() }}"></td>
                          <td style="width:50px"><label class = "project-name">{!! $str !!} </label></td>
                          <td><div class="input-group date"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input type="text" value = " @php echo $start_date @endphp " class="form-control pull-right datepicker start-date" disabled></div> </td>
                          <td><div class="input-group date"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input type="text" value = " @php echo $end_date @endphp " class="form-control pull-right datepicker end-date" disabled></div></td>
                          <td><input  type = "number" value = {{ $pd->time_allocation_status  }} class= "form-control time-allocation-status" required disabled/></td>
                          <td align = "center">{!! $time_allocation !!}</td>
                          <td><input type = "text" value = "{{ $pd->remarks }} "  class = "form-control remarks" required disabled/></td>
                          <td>{!! $buttons !!} </td>
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
              <!-- /.box-body -->

              <div class="box-footer">
                <a href="{{url('project/ongoing-projects')}}" class="btn btn-success">On Going Projects</a> <a href="{{url('projects')}}" class="btn btn-danger">Cancel</a> <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            {{ Form::close() }}
          </div>
          <!-- /.box -->
		</div>

       
</div>
@endsection
@section('customize_js')

        var counter = $("#append-row > tr").length,
            validation_concern = $("#validation-concern"),
            time_allocation = '<span class = "label label-success time-allocation"></span>',
            token = '<input type="hidden" class="token" value="{{ csrf_token() }}">',
            project_name = '<input type = "text" name = "project_name[]" class = "form-control project-name" required/>',
            start_date = '<div class="input-group date"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input type="text" value = " @php echo date("m/d/Y")  @endphp " class="form-control pull-right datepicker start-date"></div>',
            end_date = '<div class="input-group date"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input type="text" value = " @php echo date("m/d/Y")  @endphp " class="form-control pull-right datepicker end-date"></div>',
            remarks = '<input type = "text" name = "remarks[]" class = "form-control remarks" required/>',
            close_button = ' <button type = "button" class="btn btn-danger remove-project"><i class="fa fa-fw fa-trash"></i></button>',
            approve_button = ' <button type = "button" class="btn btn-primary approve-project"><i class="fa fa-fw fa-check"></i></button>';
         $('.datepicker').datepicker({
            autoclose: true
          });   

       $("#add-project").click(function(){
          counter++;
          $("#append-row").append("<tr> <td> "+token +" <label class = 'numbering'> " +counter+ " </label> </td> <td > " +project_name+ "</td>  <td>" +start_date+ "</td> <td>" +end_date+ "</td> <td> </td> <td align = 'center'>" +time_allocation+ "</td>   <td>" +remarks+ "</td> <td>"+ approve_button +close_button+  "</td></tr>");
          $(this).attr("disabled", true);
          $('.datepicker').datepicker({
            autoclose: true
          });
      });

  
  $( document ).on( 'click', '.remove-project', function() {

     remove_validation_concern();
     counter--;
     var tempo_counter = 1;
     var tr = $(this).closest('tr'),
         token = tr.find(".token").val(),
         index =$(this).closest("tr").index();
     tr.remove();
     
        $( ".numbering" ).each(function( index ) {
            $(this).text(tempo_counter);
            tempo_counter++;
        });

        $.ajax({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: ' {{url('programmer/delete-project-details')}} ',
                    data: { '_token': token, 'index': index },
                    dataType: 'json',
                    success: function(data){
                       $('#add-project').attr("disabled", false);
                        
                    },
                    error: function(data){
                       alert(JSON.stringify(data));
                    }
        }); 
  });

   $( document ).on( 'click', '.approve-project', function() {
       hide_alert_message();
       var approve_button = $(this),
           tr = $(this).closest("tr"),
           edit =  tr.find(".edit-program"),
           index = $(this).closest("tr").index(),
           form = $(this).closest('form'),
           token = form.find("input[name='_token']").val(),
           remarks = tr.find(".remarks").val(),
           time_allocation_status = tr.find(".time-allocation-status").val(),
           start_date = tr.find(".start-date").val(),
           end_date = tr.find(".end-date").val(),
           project_name = "",
           project_text_field = tr.find(".project-name");
           if(project_text_field.is('input')) project_name = project_text_field.val();
           else project_name = project_text_field.text();
           $.ajax({
                      headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      type: 'post',
                      url: ' {{url('programmer/save-edit-project')}} ',
                      data: { 
                        "_token": token,  
                        "index": index, 
                        "remarks": remarks, 
                        "time_allocation_status": time_allocation_status, 
                        "start_date": start_date,
                        "end_date": end_date ,
                        "project_name": project_name
                      },
                      dataType: 'json',
                      success: function(data){
                         $("#add-project").attr("disabled", false);
                         approve_button.hide();
                         tr.find(".remarks").attr("disabled", true);
                         tr.find(".time-allocation-status").attr("disabled", true);
                         tr.find(".project-name").attr("disabled", true);
                         tr.find(".start-date").attr("disabled", true);
                         tr.find(".end-date").attr("disabled", true);
                               edit.show(); 
                        
                         tr.find(".time-allocation").text(data.date_diff);
                        
                         approve_button.hide();
                     },
                      error: function(data){
                        alert_message("Field Validation", data.responseJSON);
                      }
          }); 

  });


   $( document ).on( 'click', '.edit-program', function() {
      var tr = $(this).closest("tr");
      tr.find(".remarks").attr("disabled", false);
      tr.find(".end-date").attr("disabled", false);
      tr.find(".time-allocation-status").attr("disabled", false);
       tr.find(".start-date").attr("disabled", false);
      tr.find(".over-time-allocation-status").attr("disabled", false);
      tr.find(".over-time-end-date").attr("disabled", false);
      $(this).hide();
      tr.find(".approve-project").show();
  });


  function remove_validation_concern(){
      validation_concern.html('');
  }

  function add_validation(title, message, type = "danger"){
    validation_concern.html('<div class="alert alert-'+type+' alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <h4><i class="icon fa fa-ban"></i> '+title+'</h4> '+message+' </div>');
  }
  
@endsection
