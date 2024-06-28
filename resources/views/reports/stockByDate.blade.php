@extends('layouts.navigation')
@section('stockByDate', 'active')
@section('content')

    <?php
    use Illuminate\Support\Carbon;
    $Access = session()->get('Access');
    
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Stock By Date</h4>
        </div>

        <div class="card-body">

            <form action="/stock-by-date" method="get">
                <div class="row">
                    <div class="col-3">
                        <label>From</label>
                        <input type="date" name="from" id="from" value="{{ $from }}"
                            max="{{ Carbon::now()->format('Y-m-d') }}" class="form-control">
                    </div>

                    <div class="col-3">
                        <label>To</label>
                        <input type="date" name="to" value="{{ $to }}" id="to"
                            max="{{ Carbon::now()->format('Y-m-d') }}" class="form-control">
                    </div>

                    <div class="col-3">
                        <label>Sub Category</label>
                        <select name="product_sub_cat_id" class="form-control">
                            <option value="">All</option>
                            @foreach ($sub_categories as $row)
                                <option value=" {{ $row->id }}" {{ $row->id == $sub_cat_id ? 'selected' : '' }}>
                                    {{ $row->product_sub_cat_name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-3" style="margin-top: 28px">
                        <button class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form><br>


            <div class="table-responsive">
                <table class="table table-striped" id="inventoryTable">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th rowspan="2">Product Code</th>
                            <th rowspan="2">Product Name</th>
                            <th rowspan="2">Sub Category</th>
                            <th rowspan="2">Qty Type</th>
                            <th colspan="2" style="text-align: center">Opening</th>
                            <th colspan="2" style="text-align: center">Purchases</th>
                            <th colspan="2" style="text-align: center">Transfers</th>
                            <th colspan="2" style="text-align: center">Closing</th>
                            {{-- <th rowspan="2" style="text-align: center">Action</th>  --}}
                        </tr>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Qty</th>
                            <th>Amount</th>

                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($collection1 as $row)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $row->product_code }}</td>
                                <td>{{ $row->product_name }}</td>
                                <td>{{ $row->product_sub_cat_name }}</td>
                                <td>{{ $row->pur_item_qty_type }}</td>
                                <td style="text-align: right" class="from_stock_modal" product_id={{ $row->product_id }}
                                    qty_type={{ $row->pur_item_qty_type }}>{{ number_format($row->FINAL_STOCK, 2) }}</td>
                                <td style="text-align: right" class="from_stock_modal" product_id={{ $row->product_id }}
                                    qty_type={{ $row->pur_item_qty_type }}>
                                    {{ number_format($row->FINAL_STOCK_AMOUNT, 2) }}</td>
                                <td style="text-align: right" class="purchase_modal" product_id={{ $row->product_id }}>
                                    {{ number_format($row->PUR_QUANTITYS, 2) }}
                                </td>
                                <td style="text-align: right" class="purchase_modal" product_id={{ $row->product_id }}>
                                    {{ number_format($row->PUR_AMOUNTS, 2) }}
                                </td>
                                <td style="text-align: right" class="transfer_modal" product_id={{ $row->product_id }}>
                                    {{ number_format($row->INDOOR_QUANTITY, 2) }}</td>
                                <td style="text-align: right" class="transfer_modal" product_id={{ $row->product_id }}>
                                    {{ number_format($row->INDOOR_AMOUNT, 2) }}</td>
                                <td style="text-align: right" class="to_stock_modal" product_id={{ $row->product_id }}
                                    qty_type={{ $row->pur_item_qty_type }}>
                                    {{ number_format($row->FINAL_STOCK + $row->PUR_QUANTITYS - $row->INDOOR_QUANTITY, 2) }}
                                </td>
                                <td style="text-align: right" class="to_stock_modal" product_id={{ $row->product_id }}
                                    qty_type={{ $row->pur_item_qty_type }}>
                                    {{ number_format($row->FINAL_STOCK_AMOUNT + $row->PUR_AMOUNTS - $row->INDOOR_AMOUNT, 2) }}
                                </td>
                                {{-- <td style="text-align: center"><button class="btn btn-info btn-sm view" data-toggle="modal"
                                        data-target="#priceModal" data-product_id={{ $row->product_id }}><i
                                            class="far fa-eye"></i></button></td> --}}
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('.purchase_modal').click(function(e) {
            e.preventDefault();

            $('#purchase').modal('show');
            let product_id = $(this).attr("product_id");
            let from = $("#from").val();
            let to = $("#to").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "/stock-by-date-product",
                data: {
                    product_id,
                    from,
                    to
                },
                success: function(data) {
        
                    let purchase_html = "";

                    if (data.length > 0) {

                        $.each(data, function(indexInArray, valueOfElement) {
                            purchase_html = `
                                    <tr>
                                        <td>${valueOfElement.product_code}</td>
                                        <td>${valueOfElement.product_name}</td>
                                        <td>${valueOfElement.type}</td>
                                        <td>${valueOfElement.purchase_order_id}</td>
                                        <td>${valueOfElement.seller_name}</td>
                                        <td style="text-align: right">${parseFloat(valueOfElement.pur_item_qty).toFixed(2)}</td>
                                        <td style="text-align: right">${parseFloat(valueOfElement.pur_item_amount/valueOfElement.pur_item_qty).toFixed(2)}</td>
                                        <td style="text-align: right">${parseFloat(valueOfElement.pur_item_amount).toFixed(2)}</td>
                                        <td style="text-align: center">
                                            <a href="/purchase-order-view/${valueOfElement.purchase_order_id}" class="btn btn-info  purchases" title="view"
                                                    ><i
                                                        class="far fa-eye"></i></a>
                                        </td>
                                    </tr>
                                `;

                        });
                    } else {
                        purchase_html = `
                                <tr>
                                    <td colspan="9" style="text-align: center">No Records</td>
                                </tr>
                            `;
                    }


                    $("#purchaseTbody").empty().append(purchase_html);

                }

            });

        });

        $('.transfer_modal').click(function(e) {
            e.preventDefault();

            $('#transfer').modal('show');
            let product_id = $(this).attr("product_id");
            let from = $("#from").val();
            let to = $("#to").val();
           

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "/stock-by-date-transfer",
                data: {
                    product_id,
                    from,
                    to
                },
                success: function(data) {
                    
                    let transfer_html = "";

                    if (data.length > 0) {

                        $.each(data, function(indexInArray, valueOfElement) {
                            transfer_html = `
                                    <tr>
                                        <td>${valueOfElement.product_code}</td>
                                        <td>${valueOfElement.product_name}</td>
                                        <td>${valueOfElement.type}</td>
                                        <td>${valueOfElement.purchase_id}</td>
                                        <td>${valueOfElement.seller_name}</td>
                                        <td>${valueOfElement.dept_name}</td>
                                        <td style="text-align: right">${parseFloat(valueOfElement.transfer_quantity).toFixed(2)}</td>
                                        <td style="text-align: right">${parseFloat(valueOfElement.purchase_unit_price).toFixed(2)}</td>
                                        <td style="text-align: right">${parseFloat(valueOfElement.purchase_unit_price*valueOfElement.transfer_quantity).toFixed(2)}</td>
                                        <td style="text-align: center">
                                            <a href="/purchase-order-view/${valueOfElement.purchase_order_id}" class="btn btn-info  purchases" title="view"
                                                    ><i
                                                        class="far fa-eye"></i></a>
                                        </td>
                                    </tr>
                                `;

                        });
                    } else {
                        transfer_html = `
                                <tr>
                                    <td colspan="10" style="text-align: center">No Records</td>
                                </tr>
                            `;
                    }


                    $("#transferTbody").empty().append(transfer_html);

                }


            });

        });

        $('.to_stock_modal').click(function(e) {
            e.preventDefault();

            $('#to_stock_modal').modal('show');
            let product_id = $(this).attr("product_id");
            let pur_item_qty_type = $(this).attr("qty_type");
            let to = $("#to").val();


            $.ajax({
                type: 'get',
                url: '/get-product-stock/stockByProduct',
                data: {
                    product_id,
                    pur_item_qty_type,
                    to
                },
                success: function(data) {

                    let html = "";
                    let product_total = 0.00;

                    if (data.length > 0) {
                        $.each(data, function(index, element) {
                            if (parseFloat(element.FINAL_STOCK_copy).toFixed(2) > 0) {
                                html += `
                        <tr>
                            <td>${element.product_name}</td>
                            <td>${element.pur_item_qty_type}</td>
                            <td>${element.purchase_order_id}</td>
                            <td style="text-align: right">${parseFloat(element.FINAL_STOCK_copy).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(element.UNIT_PRICE).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(element.FINAL_STOCK_AMOUNT_copy).toFixed(2)}</td>
                            <td style="text-align: center">
                                            <a href="/purchase-order-view/${element.purchase_order_id}" class="btn btn-info  purchases" title="view"
                                                    ><i
                                                        class="far fa-eye"></i></a>
                                        </td>
                        </tr>`

                            }

                        });
                    } else {
                        html += `
                        <tr>
                            <td colspan="7" style="text-align: center">No Records</td></tr>
                        `;
                    }


                    $("#to_stock_tbody").empty().append(html);
                },
            });

        });

        $('.from_stock_modal').click(function(e) {
            e.preventDefault();

            $('#from_stock_modal').modal('show');
            let product_id = $(this).attr("product_id");
            let pur_item_qty_type = $(this).attr("qty_type");
            let to = $("#from").val();


            $.ajax({
                type: 'get',
                url: '/get-product-stock/stockByProduct',
                data: {
                    product_id,
                    pur_item_qty_type,
                    to
                },
                success: function(data) {

                    let from_html = "";
                    let product_total = 0.00;

                    if (data.length > 0) {
                        $.each(data, function(index, element) {
                            if (parseFloat(element.FINAL_STOCK_copy).toFixed(2) > 0) {
                                from_html += `
                        <tr>
                            <td>${element.product_name}</td>
                            <td>${element.pur_item_qty_type}</td>
                            <td>${element.purchase_order_id}</td>
                            <td style="text-align: right">${parseFloat(element.FINAL_STOCK_copy).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(element.UNIT_PRICE).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(element.FINAL_STOCK_AMOUNT_copy).toFixed(2)}</td>
                            <td style="text-align: center">
                                <a href="/purchase-order-view/${element.purchase_order_id}" class="btn btn-info  purchases" title="view"
                                         ><i
                                            class="far fa-eye"></i></a>
                            </td>
                        </tr>`

                            }

                        });
                    } else {
                        from_html = `
                        <tr>
                            <td colspan="7" style="text-align: center">No Records</td>
                            </tr>`;
                    }


                    $("#from_stock_tbody").empty().append(from_html);
                },
            });

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
                    messageTop: 'Stock By Date Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Stock By Date Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
@section('model')

    {{-- purchase modal start --}}
    <div class="modal fade" id="purchase" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">GR Item </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Qty Type</th>
                                        <th>GR No</th>
                                        <th>Supplier</th>
                                        <th style="text-align: center">Qty</th>
                                        <th style="text-align: center">Unit Price</th>
                                        <th style="text-align: center">Amount</th>
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="purchaseTbody"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- purchase modal end --}}


    {{-- transfer modal start --}}
    <div class="modal fade" id="transfer" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Indoor Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Qty Type</th>
                                        <th>GR No</th>
                                        <th>Supplier</th>
                                        <th>Department</th>
                                        <th style="text-align: center">Qty</th>
                                        <th style="text-align: center">Unit Price</th>
                                        <th style="text-align: center">Amount</th>
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="transferTbody"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- transfer modal end --}}

    {{-- to  stock modal start --}}
    <div class="modal fade" id="to_stock_modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal"> Closing Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div id="total_div"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty Type</th>
                                        <th>GR No</th>
                                        <th style="text-align: center">Qty</th>
                                        <th style="text-align: center">Unit Price</th>
                                        <th style="text-align: center">Amount</th>
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="to_stock_tbody">

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- to stock modal end --}}


    {{-- from  stock modal start --}}
    <div class="modal fade" id="from_stock_modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal"> Opening Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div id="total_div"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty Type</th>
                                        <th>GR No</th>
                                        <th style="text-align: center">Qty</th>
                                        <th style="text-align: center">Unit Price</th>
                                        <th style="text-align: center">Amount</th>
                                        <th style="text-align: center">Action</th>

                                    </tr>
                                </thead>

                                <tbody id="from_stock_tbody">

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- from stock modal end --}}
@endsection


<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
