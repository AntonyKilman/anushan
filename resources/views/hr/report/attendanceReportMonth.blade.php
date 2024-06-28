@extends('layouts.navigation')
@section('attendance-report-month', 'active')
@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

    <div class="card card-success">
        <div class="card-header">
            <h4>Attendance Report - Month</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                </div>
                <div class="col-md-3">
                    <input type="month" value="{{ $month }}" class="form-control"
                        onchange="window.location.assign('/report/attendance-report-month?month='+this.value )">
                </div>
            </div>

            <div class="table-responsive">
                <table style="margin-top: 5px;" class="mb-0 table table-striped table table-responsive"
                    id="attendance_month_wise">
                    <thead style="background-color:#a6d7ff">
                        <tr>
                            <th>Employee</th>
                            @foreach ($month_date as $row2)
                                <th>
                                    {{ date('m-d', strtotime($row2->date)) }}
                                </th>
                            @endforeach

                        </tr>
                    </thead>
                    <tbody id="table_month_wise">
                        @foreach ($empDeta as $row)
                            <tr>
                                <td>{{ $row->f_name }}</td>
                                @foreach ($month_date as $row2)
                                    <?php
                                    $datex_in = 'in' . $row2->date;
                                    $datex_out = 'out' . $row2->date;
                                    ?>
                                    <td>
                                        {{ $row->$datex_in }}
                                        &#10;{{ $row->$datex_out }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        {{-- @foreach ($attendance_month->unique('emp_id') as $oneEmp)
                                    <tr>
                                        <td>{{ $oneEmp->f_name }}</td>
                                        @foreach ($month_date as $row3)
                                            @foreach ($attendance_month->where('emp_id', $oneEmp->emp_id)->where('date', $row3->date) as $oneEmpData)
                                                <td>
                                                    {{ $oneEmpData->time_in }}
                                                    &#10;{{ $oneEmpData->time_out }}
                                                </td>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                @endforeach --}}
                    </tbody>
                </table>
            </div>

        </div>
    </div>


@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {


        $('#attendance_month_wise').DataTable({
            dom: 'B',
            bSort: false,
            bPaginate: false,
            buttons: [{
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical',
                    messageTop: 'Employee Attendance Report Month Wise',
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
                    messageTop: 'Employee Attendance Report Month Wise',
                    footer: true,
                    header: true
                }   
            ],
        });

    });
</script>
