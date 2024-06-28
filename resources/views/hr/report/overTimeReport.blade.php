@extends('layouts.navigation')
@section('over_time_report', 'active')
@section('content')


    <div class="card card-success">
        <div class="card-header">
            <h4>Over Time Report</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                </div>
                <div class="col-md-3">
                    <input class="form-control date" value="{{ $month }}" name="month" type="month" id="month"
                        onchange="window.location.assign('/report/over-time-report?month=' + this.value)" />
                </div>
            </div>

            <div class="table-responsive">
                <table id="overTimeReport" class="mb-0 table table-striped table">
                    <thead style="background-color:#a6d7ff">
                        <tr>
                            <th>No</th>
                            <th>Emp Code</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Work Preriod</th>
                            <th>Over Time Work</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($over_time as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->emp_code }}</td>
                                <td>{{ $row->emp_name }}</td>
                                <td>{{ $row->date }}</td>
                                <td>{{ $row->work_period }}</td>
                                <td>{{ $row->over_time_work }}</td>
                                <td>
                                    @if ($row->remark)
                                        {{ $row->remark }}
                                    @else
                                        -----
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

        var month = '{{ $month }}';

        $('#overTimeReport').DataTable({
            dom: 'Brtip',
            bPaginate: true,
            bSort: true,
            buttons: [{
                    extend: 'print',
                    text: 'print',
                    title: 'Reecha HR',
                    messageTop: 'Over Work Time Report - Month :-' + month,
                    pageOrientation: 'landscape',
                    footer: true,
                    header: true,
                    customize: function(doc) {
                        $(doc.document.body).find('td').css('font-size', '11pt');
                        $(doc.document.body).find('th').css('font-size', '11pt');
                    }
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Reecha HR',
                    messageTop: 'Over Work Time Report - Month :-' + month,
                    footer: true,
                    header: true
                },
                // { extend: 'pdf',
                //   text: 'pdf',
                //   title: 'Reecha HR',
                //   messageTop: 'Over Work Time Report - Month :-' + month,
                //   footer: true,
                //   header: true 
                // }     
            ],
        });

    });
</script>
