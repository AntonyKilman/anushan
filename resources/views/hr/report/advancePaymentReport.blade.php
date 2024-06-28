@extends('layouts.navigation')
@section('advance_payment_report', 'active')
@section('content')


    <div class="card card-success">
        <div class="card-header">
            <h4>Advance Payment Report</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                </div>
                <div class="col-md-3">
                    <input class="form-control date" value="{{ $month }}" name="month" type="month" id="month"
                        onchange="window.location.assign('/report/advance-payment-report?month=' + this.value)" />
                </div>
            </div>

            <div class="table-responsive">
                <table id="employee_table" class="mb-0 table table-striped table">
                    <thead style="background-color:#a6d7ff">
                        <tr>
                            <th>No</th>
                            <th>Emp Code</th>
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align:right">
                                {{ number_format($advance_payment->sum('total_advance_payment'), 2, '.', ',') }}
                                <hr>
                            </th>
                        </tr>
                        @foreach ($advance_payment as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->emp_code }}</td>
                                <td>{{ $row->emp_name }}</td>
                                <td>{{ $row->month }}</td>
                                <td style="text-align:right">
                                    {{ number_format($row->total_advance_payment, 2, '.', ',') }}
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

        $('#employee_table').DataTable({
            dom: 'Brtip',
            bPaginate: true,
            bSort: true,
            buttons: [{
                    extend: 'print',
                    text: 'print',
                    title: 'Electrical HR',
                    messageTop: 'Advance Payment Report - Month :-' + month,
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
                    title: 'HR',
                    messageTop: 'Advance Payment Report - Month :-' + month,
                    footer: true,
                    header: true
                },   
            ],
        });

    });
</script>
