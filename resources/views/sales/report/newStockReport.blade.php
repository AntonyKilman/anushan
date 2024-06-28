@extends('layouts.navigation')
@section('stock', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>


    <!-- Main Content -->
    <div class="card">

        <div class="card-header">
            <h4 class="header">Product Stock</h4>
        </div>
        <div class="card-body">
        <form action="/sales_stock_view/report" method="get">
            @csrf

                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label>Month</label>
                        <input type="month" id="month" value="{{ $month }}" name="month" class="form-control">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Product Sub Category</label>
                        <select name="product_sub_cat_id" id="" class="form-control">
                            <option id="selectedAll" value="">All</option>
                            @foreach ($product_sub_categories as $sub)
                                <option class="product"
                                    value="{{ $sub->id }}"{{ $sub->id == $sub_cat_id ? 'selected' : '' }}>
                                    {{ $sub->product_sub_cat_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3" style="margin-top: 30px">
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>

                    <div class="form-group col-md-3 row" style="margin-top: 30px">
                        <h5>Total Amount <br>  {{ number_format($total, 2) }}</h5>
                    </div>
                </div>
        </form>

        {{-- <div class="card-body"> --}}
            <div class="table-responsive">
                <table class="table table-striped" id="inventoryTable">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Code</th>
                            <th>Product</th>
                            <th>Sub Category</th>
                            <th>Qty Type</th>
                            <th style="text-align: center">Qty</th>
                            <th style="text-align: center">Amount</th>
                            <th style="text-align: center"></th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($collection1 as $item)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $item->product_code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->product_sub_cat_name }}</td>
                                <td>{{ $item->scale_id }}</td>
                                <td style="text-align: right">{{ number_format($item->FINAL_STOCK ,2) }}</td>
                                <td style="text-align: right">{{ number_format($item->FINAL_STOCK_AMOUNT, 2) }}</td>
                                <td style="text-align: center"><button data-toggle="modal"
                                        data-product_id="{{ $item->item_id }}"
                                        data-product_name="{{ $item->name }}"
                                        data-FINAL_STOCK_AMOUNT="{{ $item->FINAL_STOCK_AMOUNT }}"
                                        data-pur_item_qty_type="{{ $item->scale_id }}" data-target="#stock_modal"
                                        title="View" class="btn btn-info btn-edit"><i class="far fa-eye"></i></button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <script>
        $(".btn-edit").click(function() {

            let product_id = $(this).attr('data-product_id');
            let pur_item_qty_type = $(this).attr('data-pur_item_qty_type');
            let FINAL_STOCK_AMOUNT = $(this).attr('data-FINAL_STOCK_AMOUNT');
            let product_name = $(this).attr('data-product_name');
            $('#total_div').empty().append(`<h6> Total Amount : ${parseFloat(FINAL_STOCK_AMOUNT).toFixed(2)}</h6>`);
            $('#formModal').empty().append(`${product_name} Stock`);

            var month = $('#month').val();
            var product_sub_cat_id = $('#product_sub_cat_id').val();

            $.ajax({
                type: 'get',
                url: '/sales_stock_view/stockByProduct',
                data: {
                    product_id,
                    pur_item_qty_type,
                    month,
                    product_sub_cat_id
                },
                success: function(data) {
                    let html = "";
                    let product_total = 0.00;

                    $.each(data, function(index, element) {
                        if (parseFloat(element.FINAL_STOCK_copy).toFixed(2) > 0) {
                            html += `
                        <tr>
                            <td>${element.name}</td>
                            <td>${element.scale_id}</td>
                            <td>${element.purchase_order_id}</td>
                            <td style="text-align: right">${parseFloat(element.FINAL_STOCK_copy).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(element.UNIT_PRICE).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(element.FINAL_STOCK_AMOUNT_copy).toFixed(2)}</td>
                        </tr>`

                        }

                    });
                    $("#modal_tbody").empty().append(html);
                },
            });
        });

        $("#reset").click(function() {
            $(".product").attr("selected", false);
            $("#selectedAll").attr("selected", true);
        });
    </script>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

       
        var month = '{{ $month }}';

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
                    messageTop: ' Stock Report - ' + month,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Stock Report - ' + month,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
@section('model')

    <div class="modal fade" id="stock_modal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div id="total_div"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="inventoryTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty Type</th>
                                        <th>GR No</th>
                                        <th style="text-align: center">Qty</th>
                                        <th style="text-align: center">Unit Price</th>
                                        <th style="text-align: center">Amount</th>
                                    </tr>
                                </thead>

                                <tbody id="modal_tbody">

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>