@extends('layouts.navigation')
@section('basic salary report' ,'active')
@section('content')

    
<div class="card card-success" >
  <div class="card-header">
      <h4>Basic Salary Report</h4>
  </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-9">
            </div>
            <div class="col-md-3">
                    <label>Status</label>
                    <select  class="form-control" name="status" id="status" onchange="window.location.assign('/report/basic-salary-report?status=' + $('#status').val())">
                        <option value="All" {{ ($status == 'All') ? 'selected' : '' }}>All</option>
                        <option value="Active" {{ ($status == 'Active') ? 'selected' : '' }}>Active</option>
                        <option value="Deactive" {{ ($status == 'Deactive') ? 'selected' : '' }}>Deactive</option>
                    </select>
            </div>
        </div>

      <div class="table-responsive">
          <table id="basic_salary" class="mb-0 table table-striped table">
            <thead style="background-color:#a6d7ff">
                <tr>
                    <th>No</th>
                    <th>Emp Code</th>
                    <th>Employee</th>
                    <th style="text-align:right">Basic Salary</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align:right">{{ number_format($basic_salary->sum('basic_salary'),2) }} <hr></th>
                </tr>
                @foreach ($basic_salary as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->emp_code }}</td>
                        <td>{{ $row->emp_name }}</td>
                        <td style="text-align:right">{{ number_format($row->basic_salary,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
          </table>
      </div>
      
    </div>
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {


        $('#basic_salary').DataTable( {
            dom: 'Brtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                { extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical',
                    messageTop: 'Basic Salary Report',
                    pageOrientation: 'landscape',
                    footer: true,
                    header: true,
                    customize: function ( doc ) {
                        $(doc.document.body).find('td').css('font-size', '11pt');
                        $(doc.document.body).find('th').css('font-size', '11pt');
                    }
                } ,  
                { extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical',
                    messageTop: 'Basic Salary Report',
                    footer: true,
                    header: true 
                },   
            ],
        });

    });
</script>