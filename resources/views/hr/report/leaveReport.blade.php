@extends('layouts.navigation')
@section('Leave_report', 'active')
@section('content')


    <div class="card card-success">
        <div class="card-header">
            <h4>Leave Report</h4>
        </div>
        <div class="card-body">

            <form class="col-md-12 mx-auto" method="get" action="{{ route('hr.report.leaveReport') }}">
                {{ csrf_field() }}
                <div style="margin-top: -10px;" class="row date_filter">
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="date_from" value="{{ $date_from }}" />
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="date_to" value="{{ $date_to }}" />
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </div>
            </form>
            <br />

            <div class="table-responsive">
                <table id="leave_table" class="mb-0 table table-striped table" >
                    <thead style="background-color:#a6d7ff">
                        <tr>
                            <th rowspan='2'>Code</th>
                            <th rowspan='2'>Name</th>
                            <th rowspan='2'>Type</th>
                            <th rowspan='2'>Leave Date</th>
                            <th rowspan='2'>No of days</th>
                            <th rowspan='2'>Reason</th>
                            <th rowspan='2'>Status</th>
                            <th style="text-align: center" colspan="2">Action By</th>
                        </tr>
                        <tr>
                            <th>Enter</th>
                            <th>Approve</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leave_report as $row)
                            <tr>
                                <td>{{ $row->emp_code }}</td>
                                <td>{{ $row->emp_name }}</td>
                                <td>{{ $row->leave_type_name }}</td>
                                <td>{{ $row->request_date }}</td>
                                <td>{{ $row->no_of_days }}</td>
                                <td>{{ $row->reason }}</td>
                                <td>
                                    @if ($row->status_id == 2)
                                        <p class="text-success">Approved</p>
                                    @endif
                                    @if ($row->status_id == 3)
                                        <p class="text-danger">Rejected
                                    @endif
                                    
                                </td>
                                <td>{{ $row->enterd_by_name }}</td>
                                <td>{{ $row->approved_by_name }}</td>
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

        var fdate = '{{$date_from}}';
        var tdate = '{{$date_to}}';

        $('#leave_table').DataTable( {
            dom: 'B',
            bSort:false, 
            bPaginate: true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                // 'excel','print','pdf'
                { extend: 'print',
                      text: 'print',
                      title: 'Reecha HR',
                      messageTop: 'Leave Report<br>Date : '+fdate+' to '+tdate,
                      footer: true,
                      customize: function ( doc ) {
                            $(doc.document.body).find('td').css('font-size', '10pt');
                        },
                      header: true 
                    } ,  
                    { extend: 'excel',
                      text: 'excel',
                      title: 'Reecha HR',
                      messageTop: 'Leave Report Date : '+fdate+' to '+tdate,
                      footer: true,
                      header: true 
                    }    
            ],
        });

    });
</script>
