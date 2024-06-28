@extends('layouts.navigation')
@section('permanent_assets_report', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>
    <!-- Main Content -->


    <div class="card">
        <div class="card-body">
            <h6>Permanent Assets Report</h6>

            <form action="permanantAssetsReportShow" method="POST">
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
                </div>

                <div class="row">
                    <div class="form-group col-md-3"></div>
                    <div class="form-group col-md-3">
                        <div align='right'>
                            <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                            <button class="btn btn-success mr-1" id="submit"
                                onclick="window.location.assign('/permanantAssetsReportShow?from='+$('#from').val()+'&to='+$('#to').val()+'&product_id='+$('#product_id').val())">Submit</button>
                        </div> <br>
                    </div>
                </div>
            </form>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Quantity Type</th>
                            <th>Amount</th>
                            <th>View</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->product_code }}</td>
                                <td>{{ $data->product_name }}</td>
                                <td>{{ $data->qty }}</td>
                                <td>{{ $data->pur_item_qty_type }}</td>
                                <td>{{ $data->amount }}</td>
                                <td>
                                    <button data-toggle="modal" data-qty_type={{ $data->pur_item_qty_type }}
                                        data-pro_id="{{ $data->pro_id }}" data-target="#reportView" title="edit"
                                        class="btn btn-info btn-edit btn-view"><i class="far fa-eye"></i></button>
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

            $("#from").val('');
            $("#to").val('');

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
            var qty_type = $(this).attr('data-qty_type');
            var from = $('#from').val();
            var to = $('#to').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let html = "";

            $.ajax({
                type: "POST",
                url: "permanantAssetsReportModal",
                data: {
                    from: from,
                    to: to,
                    pro_id: pro_id,
                    qty_type: qty_type
                },
                dataType: "json",
                success: function(response) {


                    for (const key in response) {
                        let serial = response[key]['serial_number'];
                        let warranty = response[key]['warranty'];

                        if (serial == null, warranty == null) {
                            serial = "";
                            warranty = "";
                        }

                        html += "<tr>";
                        html += "<td>" + response[key]['purchase_order_id'] + "</td>";
                        html += "<td>" + response[key]['product_name'] + "</td>";
                        html += "<td>" + response[key]['pur_item_qty'] + "</td>";
                        html += "<td>" + response[key]['pur_item_qty_type'] + "</td>";
                        html += "<td>" + response[key]['pur_item_amount'] + "</td>";
                        html += "<td>" + serial + "</td>";
                        html += "<td>" + warranty + "</td>";
                        html += "</tr>";
                    }
                    $("#model-view").html(html);
                },
                error: function(errors) {

                }
            });
        });

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
                    messageTop: 'Permanent Assets  Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Permanent Assets Report From - ' + date_from + ' to - ' + date_to,
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
                <h5 class="modal-title" id="formModal">Permanent Assets Histroy </h5>
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
                                <th>GR Number</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Quantity Type</th>
                                <th>Amount</th>
                                <th>Serial No</th>
                                <th>Warranty</th>
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
