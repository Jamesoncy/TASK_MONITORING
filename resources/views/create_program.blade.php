@extends('layouts.master_layout')

@section('title_page', 'Create Your Project')

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
            {{ Form::open(array('url' => 'programmer/save-project', 'method' => 'post')) }}
            	 {!! csrf_field() !!}
              <div class="box-body">
              	<div class="form-group">
                  {{ Form::label('project_name', 'Project Name') }}  
                  {{ Form::text('project_name',  Input::old('project_name') , array( "class" => "form-control" , "placeholder" => "Enter Your Project Name" ) ) }} 
                </div>
                
                <div class="form-group">
                    <button type="button" id = "add-project" class="btn btn-success"><i class="fa fa-fw fa-plus"></i></button>
                </div>
                <div class="form-group">      
                  <div class="box-body">
                      <table class="table table-bordered">
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Project Details</th>
                          <th>Time Allocation</th>
                          <th>Start Date </th>
                          <th>End Date</th>
                          <th>Remarks</th>
                          <th>Action</th>
                        </tr>
                        <tbody id = "append-row">
                        </tbody>
                      </table>
                    </div>
                </div>         
             </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <a href="{{url('projects')}}" class="btn btn-danger">Cancel</a> <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            {{ Form::close() }}
          </div>
          <!-- /.box -->
		</div>

       
</div>
@endsection
@section('customize_js')
        var counter = 0,
            validation_concern = $("#validation-concern"),
            time_allocation = '<label class = "time-allocation"></label>',
            token = '<input type="hidden" class="token" value="{{ csrf_token() }}">',
            project_name = '<input type = "text" name = "project_name[]" class = "form-control project-name" required/>',
            start_date = '<div class="input-group date"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input type="text" value = " @php echo date("m/d/Y")  @endphp " class="form-control pull-right datepicker start-date"></div>',
            end_date = '<div class="input-group date"> <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div> <input type="text" value = " @php echo date("m/d/Y")  @endphp " class="form-control pull-right datepicker end-date"></div>',
            remarks = '<input type = "text" name = "remarks[]" class = "form-control remarks" required/>',
            close_button = ' <button type = "button" class="btn btn-danger remove-project"><i class="fa fa-fw fa-trash"></i></button>',
            approve_button = ' <button type = "button" class="btn btn-primary approve-project"><i class="fa fa-fw fa-check"></i></button>';;
            
       $("#add-project").click(function(){
          counter++;
          $("#append-row").append("<tr> <td> "+token +" <label class = 'numbering'> " +counter+ " </label> </td> <td>" +project_name+ "</td> <td>" +time_allocation+ "</td>  <td>" +start_date+ "</td> <td>" +end_date+ "</td> <td>" +remarks+ "</td> <td>" +close_button+ approve_button + "</td></tr>");
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
         index =$('.remove-project').index ($(this));;
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
                       
                    }
        }); 
  });

   $( document ).on( 'click', '.approve-project', function() {
       remove_validation_concern();
       var tr =  $(this).closest("tr"),
           start_date = tr.find('.start-date').val(),
           end_date = tr.find(".end-date").val(),
           project_name = tr.find(".project-name").val(),
           remarks = tr.find(".remarks").val(),
           token = tr.find(".token").val();
           if(start_date == ""){
                add_validation( "Required Field..!", "Start Date Must Have Value"); 
                return true;
            }
            if(end_date == ""){
              add_validation( "Required Field..!", "Start Date Must Have Value"); 
                return true;
          }
         if(project_name.length == 0) add_validation( "Required Field..!", "Project Name must have Details"); 
       else if (new Date(end_date).getTime()  < new Date(start_date).getTime())  add_validation( "Date Range Problem..!", "End Date must be greater or equal than Start Date"); 
       else {
              $(this).remove();
              $.ajax({
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  type: 'post',
                  url: ' {{url('programmer/save-project-details')}} ',
                  data: { '_token': token, 'start_date': start_date, 'end_date' : end_date, 'project_name': project_name, 'remarks': remarks  },
                  dataType: 'json',
                  success: function(data){
                       tr.find('.start-date').attr("disabled", true);
                       tr.find(".end-date").attr("disabled", true);
                       tr.find(".project-name").attr("disabled", true);
                       tr.find(".remarks").attr("disabled", true);
                       tr.find(".time-allocation").text(data.date_diff + " Day(s)");
                       $('#add-project').attr("disabled", false);
                      
             
                  },
                  error: function(data){
                    alert(JSON.stringify(data));
                  }
                });
         
       }
  });


  function remove_validation_concern(){
      validation_concern.html('');
  }

  function add_validation(title, message, type = "danger"){
    validation_concern.html('<div class="alert alert-'+type+' alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <h4><i class="icon fa fa-ban"></i> '+title+'</h4> '+message+' </div>');
  }
  
@endsection
