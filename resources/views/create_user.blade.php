@extends('layouts.master_layout')

@section('title_page', 'Create User')

@section('content')



 <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">User - Details Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{ Form::open(array('url' => 'admin/create_user', 'method' => 'post')) }}
            	 {!! csrf_field() !!}
              <div class="box-body">
              	<div class="form-group">
                  {{ Form::label('full_name', 'Full Name') }}  
                  {{ Form::text('name',  Input::old('name') , array( "class" => "form-control" , "placeholder" => "Enter Full Name" ) ) }} 
                </div>
                <div class="form-group">
                   {{ Form::label('email', 'Username') }}  
                   {{ Form::text('email',  Input::old('email') , array( "class" => "form-control" , "placeholder" => "Enter Your Username" ) ) }} 
                </div>
                <div class="form-group">
                   {{ Form::label('password', 'Password') }}  
                   {{ Form::password('password', array( "class" => "form-control" , "placeholder" => "Enter Your Password" ), Input::old('password') ) }} 
                </div>
                  <div class="form-group">
                   {{ Form::label('confirm', 'Confirm Password') }}  
                   {{ Form::password('password_confirmation', array( "class" => "form-control" , "placeholder" => "Confirm Password" ), Input::old('password') ) }} 
                </div>
                <div class="form-group">
                	 {{ Form::label('role', 'Select Role') }}  
                   {{ Form::select('role', array('0' => 'Programmer', '1' => 'Manager') , Input::old('role'), array('class' => 'form-control') ) }}
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