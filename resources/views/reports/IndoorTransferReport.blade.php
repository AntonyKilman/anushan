@extends('layouts.navigation')
@section('indoor_transfer_report', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        <form action="/indoor-transfer-report" method="POST">
            @csrf

            <div class="card-body form">
                <h6>Indoor Transfer Report</h6>

                <div class="form-row">

                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" id="from" name="from" value="{{ $from }}" class="form-control"
                            required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" max="{{ now()->format('Y-m-d') }}" id="to" name="to"
                            value="{{ $to }}" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Department</label>
                        <select id="department" name="department" class="form-control" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->department_id }}"
                                    {{ $department_id == $department->department_id ? 'selected' : '' }}>
                                    {{ $department->dept_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3" style="margin-top: 28px">
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>
                </div>
                

            </div>

        </form>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Quantity Type</th>
                            <th>Date</th>
                            <th>Department Name</th>
                            <th style="text-align: center">Quantity</th>
                            <th style="text-align: center">Unit Price</th>
                            <th style="text-align: center">Total</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($indoor_transfer as $indoor_transfer)
                            <tr>
                                <td>{{ $indoor_transfer->product_code }}</td>
                                <td>{{ $indoor_transfer->product_name }}</td>
                                <td>{{ $indoor_transfer->pur_item_qty_type }}</td>
                                <td>{{ substr($indoor_transfer->created_at, 0, 10) }}</td>
                                <td>{{ $indoor_transfer->dept_name }}</td>
                                <td style="text-align: right">{{ number_format($indoor_transfer->qty,2) }}</td>
                                <td style="text-align: right">
                                    {{ number_format($indoor_transfer->pur_item_amount / $indoor_transfer->pur_item_qty, 2) }}
                                </td>
                                <td style="text-align: right">
                                    {{ number_format(($indoor_transfer->pur_item_amount / $indoor_transfer->pur_item_qty) * $indoor_transfer->qty, 2) }}
                                </td>

                                <td style="text-align: center">
                                    <button data-toggle="modal" data-target="#view" type="button"
                                        class="btn btn-info btn-edit btn-view"
                                        data-dept_id="{{ $indoor_transfer->dept_id }}"
                                        data-product_id="{{ $indoor_transfer->product_id }}"
                                        data-status="{{ $indoor_transfer->status }}"><i class="far fa-eye"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



@section('model')
    <div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Transfer History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="padding: 10px 0">
                        <button id="modal-print" class="btn btn-primary">Print</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="print-html">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Department Name</th>
                                    <th>Product Name</th>
                                    <th>Quantity Type</th>
                                    <th>GR No</th>
                                    <th style="text-align: center">Quantity</th>
                                    <th style="text-align: center">Unit Price</th>
                                    <th style="text-align: center">Total</th>
                                </tr>
                            </thead>
                            <tbody id="modal-view">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#inventoryTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#modal-print').click(function(e) {
            e.preventDefault();
            var printHtml = $('#print-html').html();
            var mywindow = window.open('', 'PRINT');
            mywindow.document.write(
                '<!DOCTYPE html><html lang="en"><head><style>table, td, th {border: 1px solid #ddd;text-align: left;}table {border-collapse: collapse;width: 100%;}th, td {padding: 15px;}</style></head><body><table border="1">' +
                printHtml + '</table></body></html>');
            mywindow.focus();
            mywindow.print();
            mywindow.close();
        });
        $('#reset').click(function(e) {
            e.preventDefault();
            $('#from').val('');
            $('#to').val('');
            $('#department').val('');
            $('#status').val('');
        });

        $('.btn-view').on('click', function() {
            $('#modal-view').html('');
            let from = $('#from').val();
            let to = $('#to').val();
            let filter_by = $('#filter_by').val();
            var dept_id = $(this).attr('data-dept_id');
            var pro_id = $(this).attr('data-product_id');
            var status = $(this).attr('data-status');
            var html = '';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/get-specific-details-for-report",
                data: {
                    from: from,
                    to: to,
                    filter_by: filter_by,
                    dept_id: dept_id,
                    pro_id: pro_id,
                    status: status
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    for (const key in response) {
                        var date = response[key]['created_at'].split("T")[0];
                        var dept_id = response[key]['dept_id'];
                        var created_at = date;
                        var dept_name = response[key]['dept_name'];
                        var pro_id = response[key]['product_id'];
                        var pro_name = response[key]['product_name'];
                        var pro_code = response[key]['product_code'];
                        var pur_amount = response[key]['pur_item_amount'];
                        var pur_qty = response[key]['pur_item_qty'];
                        var purchase_unit_price = pur_amount / pur_qty
                        var purchase_order_id = response[key]['purchase_id'];
                        var qty = response[key]['qty'];
                        var Qty_type = response[key]['pur_item_qty_type'];
                        var total = qty * purchase_unit_price;

                        var status_string;
                        if (response[key]['status'] == 0) {
                            status_string = "Pending";
                        } else if (response[key]['status'] == 1) {
                            status_string = "Accepted";
                        } else if (response[key]['status'] == 2) {
                            status_string = "Rejected";
                        } else {

                        }

                        html += '<tr>';
                        html += '<td>' + created_at + '</td>';
                        html += '<td>' + dept_name + '</td>';
                        html += '<td>' + pro_name + '</td>';
                        html += '<td>' + Qty_type + '</td>';
                        html += '<td>' + purchase_order_id + '</td>';
                        html += '<td style="text-align: right">' + parseFloat(qty).toFixed(
                            2) + '</td>';
                        html += '<td style="text-align: right">' + parseFloat(
                            purchase_unit_price).toFixed(2) + '</td>';
                        html += '<td style="text-align: right">' + parseFloat(total)
                            .toFixed(2) + '</td>';
                        html += '<tr>';
                    }
                    $('#modal-view').append(html);
                }

            });
        });
    });

    setMinDate();

    $('#from').change(function(e) {
        e.preventDefault();
        setMinDate();
    });

    function setMinDate() {
        var from = $('#from').val();
        if (from) {
            $('#to').attr('min', from);
        }
    }
</script>
