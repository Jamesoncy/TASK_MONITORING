@extends('layouts.master_layout')
@section('title_page', 'User List')
@section('content')
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><a href = "{{url('admin/create-user')}}" class="btn btn-block btn-success"><i class="fa fa-fw fa-plus"></i>Create User</a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Full Name</th>
                  <th>Username</th>
                  <th>Date Created</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($users as $person)
                    <tr>
                      <td>{{ $person->name }}</td>
                      <td>{{ $person->email }}</td>
                      <td>{{ $person->created_at }}</td>
                      <td>{{ ($person->role == 0) ? "Programmer" : "Manager" }}</td>
                      <td class = "center">
                      
                        <a href = "{{url('admin/edit-user')}}/{{$person->id}}" class=" btn-primary"><i class="fa fa-fw fa-pencil"></i></a>
                        <a href = "{{url('admin/create-user')}}" class=" btn-danger"><i class="fa fa-fw fa-trash"></i></a>

                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Date Created</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection