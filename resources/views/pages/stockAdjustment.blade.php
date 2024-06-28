@extends('layouts.navigation')
@section('newProductStock_adjustment', 'active')
@section('content')
    <?php
    use Carbon\Carbon;
    $Access = session()->get('Access');
    ?>


    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Product Stock Adjustment </h4>
        </div>
        <div class="card-body">
        <form action="/get-product-stock/adjustment" method="get">
            @csrf

            {{-- <div class="card-body form"> --}}

                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" id="to" value="{{ $to }}" name="to" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Product Sub Category</label>
                        <select name="product_sub_cat_id" id="" class="form-control">
                            {{-- <option id="selectedAll" value="">All</option> --}}
                            @foreach ($product_sub_categories as $sub)
                                <option class="product"
                                    value="{{ $sub->id }}"{{ $sub->id == $sub_cat_id ? 'selected' : '' }}>
                                    {{ $sub->product_sub_cat_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2" style="margin-top: 27px">
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>
                </div>
            {{-- </div> --}}
        </form>

        {{-- <div class="card-body"> --}}
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Code</th>
                            <th>Product</th>
                            <th>Qty Type</th>
                            <th style="text-align: center">Stock</th>
                            <th style="text-align: center">Amount</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($collection1 as $item)
                            <tr>
                                <td style="display: none">#</th>
                                <td>{{ $item->product_code }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->pur_item_qty_type }}</td>
                                <td style="text-align: right">{{ $item->FINAL_STOCK }}</td>
                                <td style="text-align: right">{{ number_format($item->FINAL_STOCK_AMOUNT, 2) }}</td>
                                <td style="text-align: center">
                                    <button data-toggle="modal" data-product_id="{{ $item->product_id }}"
                                        data-product_name="{{ $item->product_name }}"
                                        data-FINAL_STOCK_AMOUNT="{{ $item->FINAL_STOCK_AMOUNT }}"
                                        data-pur_item_qty_type="{{ $item->pur_item_qty_type }}" data-target="#stock_modal"
                                        title="View" class="btn btn-info btn-product"><i class="far fa-eye"></i></button>
                                    <button data-toggle="modal" data-product_id="{{ $item->product_id }}" data-type="edit"
                                        data-product_name="{{ $item->product_name }}"
                                        data-FINAL_STOCK_AMOUNT="{{ $item->FINAL_STOCK_AMOUNT }}"
                                        data-pur_item_qty_type="{{ $item->pur_item_qty_type }}" data-target="#stock_modal"
                                        title="View" class="btn btn-primary btn-product"><i
                                            class="far fa-edit"></i></button>
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
    {{-- show the product quantity modal --}}
    <div class="modal fade" id="stock_modal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="oroductModal"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form action="/stock-adjustment-store" method="post" class="needs-validation" novalidate="">
                            <div class="row">
                                <div class="col-4">
                                    <div id="total_div"></div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col- d-flex">
                                    <label style="margin-top: 8px" class="hidden_item">Date</label>&nbsp;
                                    <input type="date" class="form-control hidden_item"
                                        value="{{ Carbon::now()->format('Y-m-d') }}" name="date" required>
                                </div>
                            </div><br>

                            <div class="table-responsive">
                                @csrf
                                <table class="table table-striped" id="table-1">
                                    <thead id="modal_thead">

                                    </thead>

                                    <tbody id="modal_tbody"></tbody>

                                </table>
                                <div align="right" class="hidden_item">
                                    <button class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js"
    integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {


        $('#table-1').on('click', '.btn-product', function() {
            let type = $(this).attr('data-type');
            let product_id = $(this).attr('data-product_id');
            let pur_item_qty_type = $(this).attr('data-pur_item_qty_type');
            let FINAL_STOCK_AMOUNT = $(this).attr('data-FINAL_STOCK_AMOUNT');
            let product_name = $(this).attr('data-product_name');
            $('#total_div').empty().append(
                `<h6> Total Amount : ${parseFloat(FINAL_STOCK_AMOUNT).toFixed(2)}</h6>`);
            $('#oroductModal').empty().append(`${product_name} Stock`);

            $.ajax({
                type: 'get',
                url: '/get-product-stock/stockByProduct',
                data: {
                    product_id,
                    pur_item_qty_type
                },
                success: function(data) {
                    let modal_thead = "";
                    let modal_tbody = "";
                    let product_total = 0.00;

                    var myClasses = document.getElementsByClassName('hidden_item'),
                        i = 0,
                        l = myClasses.length;


                    if (type) {

                        for (i; i < l; i++) {
                            myClasses[i].style.display = 'block';
                        }

                        modal_thead +=
                            `<tr>
                        <th>Product</th>
                        <th>Qty Type</th>
                        <th>Purchase Order Id</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                        <th>Change Qty </th>
                        <th>select</th>
                    </tr>`;

                    } else {

                        for (i; i < l; i++) {
                            myClasses[i].style.display = 'none';
                        }

                        modal_thead +=
                            `<tr>
                        <th>Product</th>
                        <th>Qty Type</th>
                        <th>Purchase Order Id</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                    </tr>`;

                    }


                    $.each(data, function(index, element) {

                        // for edit action
                        if (type) {

                            modal_tbody +=
                                `<tr>
                                <td><input type="text" class="form-control" readonly value="${element.product_name}"> </td>
                                <td style="display:none"><input type="text"  value="${element.product_id}" name="product_id[]"></td>
                                <td><input type="text" class="form-control" readonly value="${element.pur_item_qty_type}" name="qty_type[]"></td>
                                <td><input type="number" class="form-control" readonly value="${element.purchase_order_id}" name="purchase_id[]"></td>
                                <td><input type="number" step="0.01" class="form-control" readonly value="${parseFloat(element.FINAL_STOCK_copy).toFixed(2)}" name="final_stock[]"></td>
                                <td><input type="number" step="0.01" class="form-control" readonly value="${(parseFloat(element.FINAL_STOCK_AMOUNT_copy)/parseFloat(element.FINAL_STOCK_copy)).toFixed(2)}"></td>
                                <td><input type="number" step="0.01" class="form-control" readonly value="${parseFloat(element.FINAL_STOCK_AMOUNT_copy).toFixed(2)}" ></td>
                                <td><input type="number"  step="0.01" class="form-control"  value="${parseFloat(element.FINAL_STOCK_copy).toFixed(2)}" name="change_qty[]"></td>
                                <td><input type="checkbox" class="form-control w-50" name="${element.product_id}-${element.purchase_order_id}"></td>
                            </tr>`;

                        } //view function
                        else {

                            modal_tbody +=
                                `<tr>
                                <td><input type="text" class="form-control" readonly value="${element.product_name}"> </td>
                                <td><input type="text" class="form-control" readonly value="${element.pur_item_qty_type}" name="product_id[]"></td>
                                <td><input type="number" class="form-control" readonly value="${element.purchase_order_id}" name="purchase_id[]"></td>
                                <td><input type="number" step="0.01" class="form-control" readonly value="${parseFloat(element.FINAL_STOCK_copy).toFixed(2)}"></td>
                                <td><input type="number" step="0.01" class="form-control" readonly value="${parseFloat(element.UNIT_PRICE).toFixed(2)}"></td>
                                <td><input type="number" step="0.01" class="form-control" readonly value="${parseFloat(element.FINAL_STOCK_AMOUNT_copy).toFixed(2)}"></td>
                            </tr>`;
                        }

                    });
                    $("#modal_thead").empty().append(modal_thead);
                    $("#modal_tbody").empty().append(modal_tbody);
                },
            });
        });



        $('#table-1').on('click', '.btn-edit', function() {

            let product_id = $(this).attr("data-product_id");
            let product_name = $(this).attr("data-product_name");
            let qty_type = $(this).attr("data-qty_type");
            let stock = $(this).attr("data-stock");

            $("#product").val(product_name);
            $("#qty_type").val(qty_type);
            $("#current_stock").val(stock);
            $("#change_stock").val(stock);
            $("#product_id").val(product_id);

        });

        $('#reset').on('click', function() {
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear() + "-" + (month) + "-" + (day);
            $('#to').val(today);
        });

    });
</script>
