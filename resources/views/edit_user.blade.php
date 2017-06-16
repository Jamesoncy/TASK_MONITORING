@extends('layouts.master_layout')

@section('title_page', 'Edit User')

@section('content')



 <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit User - Details Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @php $url = 'admin/edit-user/'. $user->id @endphp

            {{ Form::open(array('url' => $url , 'method' => 'post')) }}
            	 {!! csrf_field() !!}
              <div class="box-body">
              	<div class="form-group">
                  {{ Form::label('full_name', 'Full Name') }}  
                  {{ Form::text('name',  $user->name , array( "class" => "form-control" , "placeholder" => "Enter Full Name" ) ) }} 
                </div>
                <div class="form-group">
                   {{ Form::label('email', 'Username') }}  
                   {{ Form::text('email',  $user->email, array( "class" => "form-control" , "placeholder" => "Enter Your Username" ) ) }} 
                </div>
                 <div class="form-group">
                  @if($role == 1)
                   {{ Form::label('role', 'Select Role') }}  
                   {{ Form::select('role', array('0' => 'Programmer', '1' => 'Manager') , $user->role, array('class' => 'form-control') ) }}
                  @else 
                    @php
                      $roles = array("Programmer", "Manager");
                    @endphp
                    <div class="form-group">
                      <span class="text"><b>Role</b>: {{ $roles[$user->role] }}</span>
                    </div>
                  @endif

                 </div>
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <a href="{{url('admin/user-list')}}" class="btn btn-danger">Cancel</a> <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            {{ Form::close() }}
          </div>
          <!-- /.box -->
		</div>

       
</div>
@endsection