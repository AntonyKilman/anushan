@extends('layouts.navigation')
@section('epf_etf_report', 'active')
@section('content')


    <div class="card card-success">
        <div class="card-header">
            <h4>EPF&ETF Report</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('hr.report.epfEtfReport') }}" method="GET">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <input class="form-control date" value="{{ $date_from }}" name="date_from" type="month"
                            id="datepicker_from" /></td>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control date" value="{{ $date_to }}" name="date_to" type="month"
                            id="datepicker_to" /></td>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-success" value="Submit" />
                    </div>
                </div>
            </form>
            <br>

            <table style="width: 100%;" id="epfTable" class="mb-0 table table-striped table">
                <thead style="background-color:#a6d7ff">
                    <tr>
                        <th>No</th>
                        <th>Salary Month</th>
                        <th>Emp code</th>
                        <th>Emp Name</th>
                        <th style="text-align:right">Company EPF</th>
                        <th style="text-align:right">Company ETF</th>
                        <th style="text-align:right">Employee EPF</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align:right">{{ number_format($epf_etf->sum('company_epf'),2) }} <hr></th>
                        <th style="text-align:right">{{ number_format($epf_etf->sum('company_etf'),2) }} <hr></th>
                        <th style="text-align:right">{{ number_format($epf_etf->sum('epf'),2) }} <hr></th>
                    </tr>
                    @foreach ($epf_etf as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->salary_month }}</td>
                            <td>{{ $row->emp_code }}</td>
                            <td>{{ $row->f_name }}</td>
                            <td style="text-align:right">{{ number_format($row->company_epf,2) }}</td>
                            <td style="text-align:right">{{ number_format($row->company_etf,2) }}</td>
                            <td style="text-align:right">{{ number_format($row->epf,2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $date_from }}';
        var date_to = '{{ $date_to }}';

        $('#epfTable').DataTable({
            dom: 'Brtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Reecha HR',
                    messageTop: 'Employee EPF,ETF Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Reecha HR',
                    messageTop: 'Employee EPF,ETF Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
