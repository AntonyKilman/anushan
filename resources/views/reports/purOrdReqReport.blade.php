@extends('layouts.navigation')
@section('purchase_order_request_report', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->

    <div class="card">
        <div class="card-header">
            <h4 class="header">Purchase Order Request </h4>
        </div>
        <div class="card-body">
           
            <form action="/purchaseOrderRequestRepShow" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" value="{{ $from }}" id="from" name="from" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" value="{{ $to }}" id="to" name="to"
                            class="form-control" required>
                    </div>


                    <div class="form-group col-md-3">
                        <label>Department</label>
                        <select id="departmentId" class="form-control" name="departmentId" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($Departments as $Department)
                                <option value="{{ $Department->id }}"
                                    {{ $Department->id == $departmentId ? 'selected' : '' }}>
                                    {{ $Department->dept_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Status</label>
                        <select id="status" class="form-control" name="status" required>
                            <option value="" disabled {{ $status == '' ? 'selected' : '' }}>Select Status</option>
                            <option value="0" {{ $status == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ $status == '1' ? 'selected' : '' }}>Accepted</option>
                            <option value="2" {{ $status == '2' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>
                <div align='right'>
                    <button type="reset" class="btn btn-danger" id="reset">Reset</button>
                    <button class="btn btn-success mr-1" id="submit">Submit</button>
                </div> <br>
        </div>
        </form>


        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-striped " id="InventryTable">

                    <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Quantity Type</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->product_code }}</td>
                                <td>{{ $data->product_name }}</td>
                                <td>{{ $data->qty }}</td>
                                <td>{{ $data->pur_req_qty_type }}</td>
                                <td>{{ $data->dept_name }}</td>
                                <td>
                                    <?php if ($data->status == 0) {
                                        echo 'Pending';
                                    } elseif ($data->status == 1) {
                                        echo 'Accepted';
                                    } elseif ($data->status == 2) {
                                        echo 'Rejected';
                                    } else {
                                    } ?>
                                </td>
                                <td>
                                    <button data-toggle="modal" data-qty-type="{{ $data->pur_req_qty_type }}"
                                        data-status="{{ $status }}" data-dept_id="{{ $data->dept_id }}"
                                        data-pro_id="{{ $data->pro_id }}" data-qty="{{ $data->pur_req_qty }}"
                                        data-type="{{ $data->pur_req_qty_type }}" data-des="{{ $data->pur_req_des }}"
                                        data-status="{{ $data->pur_req_status }}" data-pro="{{ $data->product_id }}"
                                        data-department_id="{{ $data->department_id }}" data-target="#reportView"
                                        title="edit" class="btn btn-info btn-edit btn-view"><i
                                            class="far fa-eye"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $("#reset").on("click", function(e) {
            e.preventDefault();
            console.log("reser");
            $("#from").val('');
            $("#to").val('');
            $("#departmentId").val('');
            $("#status").val('');
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

        $(".btn-edit").on("click", function() {

            var pro_id = $(this).attr('data-pro_id');
            var dept_id = $(this).attr('data-dept_id');
            var qty_type = $(this).attr('data-qty-type');
            var status = $(this).attr('data-status');
            let from = $('#from').val();
            let to = $('#to').val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let html = "";

            $.ajax({
                type: "POST",
                url: "purchaseOrderRequestRepModal",
                data: {
                    from: from,
                    to: to,
                    dept_id: dept_id,
                    pro_id: pro_id,
                    qty_type: qty_type,
                    status: status
                },
                dataType: "json",
                success: function(response) {
                    for (const key in response) {
                        let reason = response[key]['pur_req_reason'];
                        if (reason == null) {
                            reason = "";
                        }
                        var status_string;
                        if (response[key]['pur_req_status'] == 0) {
                            status_string = "Pending";
                        } else if (response[key]['pur_req_status'] == 1) {
                            status_string = "Accepted";
                        } else if (response[key]['pur_req_status'] == 2) {
                            status_string = "Rejected";
                        } else {

                        }
                        html += "<tr>";
                        html += "<td>" + response[key]['product_name'] + "</td>";
                        html += "<td>" + response[key]['pur_req_qty'] + "</td>";
                        html += "<td>" + response[key]['pur_req_qty_type'] + "</td>";
                        html += "<td>" + response[key]['dept_name'] + "</td>";
                        html += "<td>" + reason + "</td>";
                        html += "<td>" + status_string + "</td>";
                        html += "</tr>";

                    }
                    $("#model-view").html(html);
                }
            });
        })

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
    </script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#InventryTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Purchase Order Request Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Purchase Order Request Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>

<div class="modal fade" id="reportView" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Purchased Order Request History </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="append-table">
                <div class="table-responsive">
                    <div style="padding: 10px 0">
                        <button id="modal-print" class="btn btn-primary">Print</button>
                    </div>
                    <table class="table table-striped" id="print-html">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Quantity Type</th>
                                <th>Department</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="model-view">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
