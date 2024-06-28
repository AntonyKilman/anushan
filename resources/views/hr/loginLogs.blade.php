@extends('layouts.navigation')
@section('login_log' ,'active')
@section('content')

<div class="card card-success" >
  <div class="card-header">
      <h4>Login Logs</h4>
  </div>
    <div class="card-body">
      <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th width="50">No</th>
                <th width="100" >Employee Name</th>
                <th width="100" >Property Name</th>
                <th width="100">Status</th>
                <th width="100">Date & Time</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1 ?>
              @foreach($login_log as $row)
                <tr>
                  <td data-th="No">{{ $i++}}</td>
                  <td data-th="Employee Name">{{ $row->emp_name}}</td>
                  <td data-th="Property Name">{{ $row->properities}}</td>
                  <td data-th="Status">{{ $row->status}}</td>
                  <td data-th="Date & Time">{{ $row->date_time}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      <div class="float-right">
          {{ $login_log->appends(request()->query()) }}
      </div>
      <div class="float-left">
          <b> {{ $login_log->firstItem() }} - {{ $login_log->lastItem() }} of
              {{ $login_log->total() }} </b>
      </div>
    </div>
</div>

@endsection