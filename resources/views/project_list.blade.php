@extends('layouts.master_layout')
@section('title_page', 'Project List')
@section('content')
          <div class="box">
            @if(Auth::user()->role == 0)
            <div class="box-header">
              <h3 class="box-title"><a href = "{{url('programmer/create-project')}}" class="btn btn-block btn-success"><i class="fa fa-fw fa-plus"></i>Add Project</a></h3>
            </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Project Name</th>
                  <th>Care By</th>
                  <th>Date Created</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($projects as $project)
                    <tr>
                      <td>{{ $project->project }}</td>
                      <td>{{ $project->name }}</td>
                      <td>{{  $project->created_at->format('m-d-Y') }}</td>
                      <td>
                          @if($project->status == 0) <span class="label label-warning">On Going</span>
                          @elseif ($project->status == 1 && $project->manage_id == null)  <span class="label label-primary">For Approval</span>
                          @elseif ($project->status == 1 && $project->manage_id != null)  <span class="label label-success">Complete</span>
                          @endif
                      </td>
                      <td>
                          @if($project->status == 0)
                          <a href = "{{ URL::action('Dashboard@edit_project_form', $project->id) }}" class="btn-primary"><i class="fa fa-fw fa-pencil"></i></a></h3>
                           @endif
                            
                          <a href = "{{ URL::action('Dashboard@view_project', $project->id) }}" class="btn-default"><i class="fa fa-fw fa-search"></i></a></h3>

                          @if(Auth::user()->role == 1 && $project->manage_id == null)
                          <div class = "pull-right">
                          <a href = "{{ URL::action('Project@delete_project', $project->id) }}" class="btn-danger"><i class="fa fa-fw fa-trash"></i></a></h3>
                          </div>
                           @endif
                         
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>Project Name</th>
                  <th>Care By</th>
                  <th>Date Created</th>
                  <th>Status</th>
                  <th>Status</th>
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