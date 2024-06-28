@extends('layouts.navigation')
@section('attendance-view-employee', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Attendance View - Login User</h4>
        </div>
        <div class="card-body">
            <form action="/view/attendance" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <strong>From Date</strong>
                            <input id="fromDate" name="fromDate" type="date" value="{{ $fromDate }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <strong>To Date</strong>
                            <input id="toDate" name="toDate" type="date" value="{{ $toDate }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top:24px">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <br>

            <h5 style="text-align:center"> Employee Attendance Report - {{ $emp_name }}</h5>
            <div class="table-responsive">
                <table style="margin-top: 5px;" class="mb-0 table table-striped table" id="attendance_employee_wise">
                    <thead style="background-color:#a6d7ff">
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Roster In</th>
                            <th>In Time</th>
                            <th>Roster Out</th>
                            <th>Out Time</th>
                            <th>Late Come</th>
                            <th>Early Leave</th>
                            <th>Remark</th>
                            <th>Work Period</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empWiseAttendance as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->date }}</td>
                                <td>
                                    @if ($row->roster_in)
                                        {{ $row->roster_in }}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td>
                                    @if ($row->time_in)
                                        {{ $row->time_in }}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td>
                                    @if ($row->roster_out)
                                        {{ $row->roster_out }}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td>
                                    @if ($row->time_out)
                                        {{ $row->time_out }}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td>
                                    @if ($row->late_in)
                                        {{ date('H:i', strtotime($row->late_in)) }}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td>
                                    @if ($row->early_out)
                                        {{ date('H:i', strtotime($row->early_out)) }}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td>
                                    @if ($row->remark)
                                        {{ $row->remark }}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td>
                                    @if ($row->work_period)
                                        {{ $row->work_period }}
                                    @else
                                        ----
                                    @endif
                                </td>
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
        var emp_name = '{{ $emp_name }}';
        var fromDate = '{{ $fromDate }}';
        var toDate = '{{ $toDate }}';
        $('#attendance_employee_wise').DataTable({
            dom: 'Bfrtip',
            bSort: true,
            bPaginate: true,
            buttons: [{
                    extend: 'print',
                    text: 'print',
                    title: 'Reecha HR',
                    messageTop: 'Employee Attendance Report For - ' + emp_name + ' -(' + fromDate +
                        ' - ' + toDate + ')',
                    footer: true,
                    header: true,
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '7pt')
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Reecha HR',
                    messageTop: 'Employee Attendance Report For - ' + emp_name + ' -(' + fromDate +
                        ' - ' + toDate + ')',
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
