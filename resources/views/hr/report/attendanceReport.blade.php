@extends('layouts.navigation')
@section('attendance-report', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Attendance Report</h4>
            <div class="card-header-action">
                <button id="run_attendance" aria-haspopup="true" aria-expanded="false" class="btn-hover-shine btn btn-warning">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fas fa-cogs"></i> </span>
                    Run Attendance Calculation
                </button>
            </div>
        </div>

        <div class="card-body">
            <form action="/report/attendance-report" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <strong>Report</strong>
                            <select class="form-control" name="report" id="report">
                                @foreach ($report_by as $repo)
                                    <option value="{{ $repo }}" {{ $repo == $report ? 'selected' : '' }}>
                                        {{ $repo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 allInput dateInput">
                        <div class="form-group">
                            <strong>Date</strong>
                            <input id="date" name="date" type="date" value="{{ $date }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3 allInput weekInput">
                        <div class="form-group">
                            <strong>Week</strong>
                            <input id="week" name="week" type="week" value="{{ $week }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3 allInput monthInput">
                        <div class="form-group">
                            <strong>Month</strong>
                            <input id="month" name="month" type="month" value="{{ $month }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3 allInput employeeInput">
                        <div class="form-group">
                            <strong>Employee</strong>
                            <input type="hidden" id="empId" name="empId" value="{{ $empId }}">
                            <input value="{{ $emp_name }}" type="text" class="form-control" id="select_emp"
                                placeholder="Select employee" data-toggle="modal" data-target="#searchEmp_" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 allInput employeeInput">
                        <div class="form-group">
                            <strong>From Date</strong>
                            <input id="fromDate" name="fromDate" type="date" value="{{ $fromDate }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 allInput employeeInput">
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

            {{-- Day Wise --}}
            @if ($report == 'Day Wise')
                <h5 style="text-align:center"> Day Wise Report - {{ $date }}</h5>
                <div class="table-responsive">
                    <table style="margin-top: 2px;" class="mb-0 table table-striped table" id="attendance_datewise">
                        <thead style="background-color:#a6d7ff">
                            <tr>
                                <th>No</th>
                                <th>Emp Code</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Roster In</th>
                                <th>In Time</th>
                                <th>Roster Out</th>
                                <th>Out Time</th>
                                <th>Late Come</th>
                                <th>Early Leave</th>
                                <th>Remark</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dayWiseAttendance as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td data-th="Emp Code">{{ $row->emp_code }}</td>
                                    <td data-th="Name">{{ $row->f_name }} <br> {{ $row->l_name }}</td>
                                    <td data-th="Date"><a target="_blank">{{ date('d-m-Y', strtotime($row->date)) }}</a>
                                    </td>
                                    <td data-th="Roster in">
                                        @if ($row->roster_in)
                                            {{ date('H:i a', strtotime($row->roster_in)) }}
                                        @else
                                            ----
                                        @endif
                                    </td>
                                    <td data-th="Time in">
                                        @if ($row->time_in)
                                            {{ date('H:i a', strtotime($row->time_in)) }}
                                        @else
                                            ----
                                        @endif
                                    </td>
                                    <td data-th="Roster out">
                                        @if ($row->roster_out)
                                            {{ date('H:i a', strtotime($row->roster_out)) }}
                                        @else
                                            ----
                                        @endif
                                    </td>
                                    <td data-th="Time out">
                                        @if ($row->time_out)
                                            {{ date('H:i a', strtotime($row->time_out)) }}
                                        @else
                                            ----
                                        @endif
                                    </td>

                                    <td data-th="Late in">
                                        @if ($row->late_in)
                                            {{ date('H:i', strtotime($row->late_in)) }}
                                        @else
                                            ----
                                        @endif
                                    </td>
                                    <td data-th="Early out">
                                        @if ($row->early_out)
                                            {{ date('H:i', strtotime($row->early_out)) }}
                                        @else
                                            ----
                                        @endif
                                    </td>
                                    <td data-th="Remark">
                                        @if ($row->remark)
                                            {{ $row->remark }}
                                        @else
                                            ----
                                        @endif
                                    </td>
                                    <td data-th="work hour">
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
            @endif

            {{-- Week Wise --}}
            @if ($report == 'Week Wise')
                <h5 style="text-align:center"> Week Wise Report - {{ $week }}</h5>
                <div class="table-responsive">
                    <table style="margin-top: 5px;" class="mb-0 table table-striped table" id="attendance_week_wise">
                        <thead style="background-color:#a6d7ff">
                            <tr>
                                <th>No</th>
                                <th>Emp Code</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Total Late Entry</th>
                                <th>Total Early Out</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($weekWiseAttendance as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->emp_code }}</td>
                                    <td>{{ $row->f_name }}</td>
                                    <td>{{ $row->l_name }}</td>
                                    <td>{{ $row->department_name }}</td>
                                    <td>{{ $row->late }}</td>
                                    <td>{{ $row->early }}</td>
                                    <td>{{ (int)$row->total_hour }} H : {{ (int)$row->total_minute }} m</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Month Wise --}}
            @if ($report == 'Month Wise')
                <h5 style="text-align:center"> Month Wise Report - {{ $monthWiseAttendanceMassage }}</h5>
                <div class="table-responsive">
                    <table style="margin-top: 5px;" class="mb-0 table table-striped table" id="attendance_month_wise">
                        <thead style="background-color:#a6d7ff">
                            <tr>
                                <th>No</th>
                                <th>Emp Code</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Total Late Entry</th>
                                <th>Total Early Out</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($montWiseAttendance as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->emp_code }}</td>
                                    <td>{{ $row->f_name }}</td>
                                    <td>{{ $row->l_name }}</td>
                                    <td>{{ $row->department_name }}</td>
                                    <td>{{ $row->late }}</td>
                                    <td>{{ $row->early }}</td>
                                    <td>{{ (int)$row->total_hour }} H : {{ (int)$row->total_minute }} m</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Employee Wise --}}
            @if ($report == 'Employee Wise')
                <h5 style="text-align:center"> Employee Wise Report - {{ $emp_name }}</h5>
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
            @endif
        </div>
    </div>
@endsection

@section('modal')
    {{-- search employee --}}
    <div class="modal fade" id="searchEmp_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Search Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Custom Search..." id="employee-Search">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="20%">Code</th>
                                    <th width="20%">Employee Name</th>
                                    <th width="20%">Department Name</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="new_employee_">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // emp list get
        empSearch();
        // input hide
        inputShowHide();

        $('#report').change(function (e) { 
            inputShowHide();
        });

        function inputShowHide() {
            var report = $('#report').val();

            $('.allInput').hide();

            switch (report) {
                case 'Day Wise':
                    $('.dateInput').show();
                    break;
                case 'Week Wise':
                    $('.weekInput').show();
                    break;
                case 'Month Wise':
                    $('.monthInput').show();
                    break;
                case 'Employee Wise':
                    $('.employeeInput').show();
                    break;
                default:
                    break;
            }
        }

        // search employee
        $(document).on('keyup', '#employee-Search', function() {
            // emp list get
            empSearch();
        });

        function empSearch() {
            var query = $('#employee-Search').val();

            $.ajax({
                url: "{{ route('common.search-employee') }}",
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {

                    $('#new_employee_').html('');

                    if (res != '') {
                        $.each(res, function(index, row) {

                            employee_row = ` <tr>
                                                <td >` + row.emp_code + `</td>
                                                <td >` + row.f_name + `</td>
                                                <td >` + row.department_name + `</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-small btn-success empSelect" href="#" style="padding:0px 20px" 
                                                    data-emp_id="` + row.id + `" data-emp_name ="` + row.f_name + `" >Select</a>
                                                </td>
                                            </tr>`;

                            $('#new_employee_').append(employee_row);

                        });

                    } else {

                        employee_row =
                            '<tr><td align="center" colspan="4">No Data Found</td></tr>';

                        $('#new_employee_').append(employee_row);
                    }

                    // select employee
                    $('.empSelect').on('click', function() {
                        var eid = $(this).attr('data-emp_id');
                        var name = $(this).attr('data-emp_name');

                        $("#empId").val(eid);
                        $("#select_emp").val(name);
                        $('#searchEmp_').modal('hide'); // model hide
                    });
                }
            })
        }

        // run attendance
        $('#run_attendance').on('click', function(event) {
            event.preventDefault();

            swal({
                    title: 'Run Attendance Scheduler ?',
                    text: 'Run Scheduler Now !',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // loader show
                        $(".loader-loader").show();

                        $.ajax({
                            url: "{{ route('process-attendance') }}",
                            method: 'get',
                            data: {},
                            success: function(res) {

                                // loader show
                                $(".loader-loader").hide();

                                swal('Poof! Run Attendance Calculation !', {
                                    icon: 'success',
                                    timer: 1000,
                                });

                                location.reload();
                            }
                        });
                    }
                });
        });

        var date = '{{ $date }}';
        $('#attendance_datewise').DataTable({
            dom: 'Bfrtip',
            bSort: true,
            bPaginate: true,
            buttons: [{
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report Date Wise - ' + date,
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
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report Date Wise - ' + date,
                    footer: true,
                    header: true
                }
            ],
        });

        var week = '{{ $week }}';
        $('#attendance_week_wise').DataTable({
            dom: 'Bfrtip',
            bSort: true,
            bPaginate: true,
            buttons: [{
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report Week Wise - ' + week,
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
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report Week Wise - ' + week,
                    footer: true,
                    header: true
                },
            ],
        });

        var month = '{{ $month }}';
        var monthWiseAttendanceMassage = '{{ $monthWiseAttendanceMassage }}';
        $('#attendance_month_wise').DataTable({
            dom: 'Bfrtip',
            bSort: true,
            bPaginate: true,
            buttons: [{
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report Month Wise - ' + month +
                        monthWiseAttendanceMassage,
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
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report Month Wise - ' + month +
                        monthWiseAttendanceMassage,
                    footer: true,
                    header: true
                },
            ],
        });

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
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report For - ' + emp_name + ' -(' + fromDate + ' - ' + toDate + ')',
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
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report For - ' + emp_name + ' -(' + fromDate + ' - ' + toDate + ')',
                    footer: true,
                    header: true
                }
            ],
        });

    });
</script>
