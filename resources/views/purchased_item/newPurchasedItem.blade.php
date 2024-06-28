@extends('layouts.navigation')
@section('perchased_item', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        <form action="/purchase-order-add-process" method="post" class="needs-validation" novalidate="">
            @csrf
            <div class="card-header">
                <h4>Create GR Item</h4>
            </div>

            <div class="card-body form">

                <div class="row">
                    <div class="form-group col-3">
                        <label>Good Receive Note</label>
                        <select class="form-control" name="purchase_order_id" id="purchase_order_id" onchange="goodReceive()"
                            required>
                            <option value="" disabled selected>Please Select</option>
                            @foreach ($InventoryPurchaseOrders as $InventoryPurchaseOrder)
                                <option value="{{ $InventoryPurchaseOrder->id }}">{{ $InventoryPurchaseOrder->id . "-".$InventoryPurchaseOrder->pur_ord_bill_no  }}
                                </option>
                            @endforeach
                        </select>
                        @error('purchase_order_id')
                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-3">
                        <label>Bill No</label>
                        <input class="form-control" id="bill_No" name="bill_No" readonly>
                    </div>

                    <div class="form-group col-3">
                        <label>Amount</label>
                        <input type="number" step="0.01" class="form-control" id="Amount" name="Amount" readonly>
                    </div>

                    <div class="form-group col-3">
                        <label>Supplier</label>
                        <input class="form-control" id="seller" name="seller" readonly>
                    </div>

                </div>

                <div class="table">
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity Type</th>
                                <th>Quantity</th>
                                <th>Expiry Date</th>
                                <th>Price</th>
                            </tr>

                        </thead>
                        <tbody id="good_receive">
                            <tr>
                                <td>
                                    <input type="text" id="product_id_1" class="form-control select_product"
                                        data-no="1" placeholder="Select Product..." autocomplete="off" required>
                                </td>
                                <input type="hidden" id="pro_id_1" name="product_id[]">
                                <td>
                                    <input type="text" id="qty_type_1" class="form-control "
                                        name="qty_type[]" data-no="1" placeholder="Select Qty Type..." autocomplete="off"
                                        readonly>
                                </td>
                                <td>
                                    <input type="text" id="qty" pattern="^\d+(\.\d)?\d*$" name="qty[]"
                                        class="form-control" placeholder="Enter the Quantity" required>
                                </td>
                                <td>
                                    <input type="date" name="expery_date[]" id="expery_date" class="form-control"
                                        min="{{ now()->format('Y-m-d') }}">
                                </td>
                                <td>
                                    <input type="number" id="price" name="price[]" class="form-control price"
                                        placeholder="Enter the Price" step="0.01" required>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <input type="hidden" id="no">
                    <button type="button" id="pur_product" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp Add
                        Row</button>
                </div>



                <div align='right'>
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button class="btn btn-success mr-1 submit" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script>
        function goodReceive() {

            $.ajax({
                type: "GET",
                url: "/good-receive-data/" + $("#purchase_order_id").val(),
                dataType: "json",

                success: function(response) {
                    $("#bill_No").val(response.pur_ord_bill_no);
                    $("#Amount").val(response.pur_ord_amount.toFixed(2));
                    $("#seller").val(response.seller_name);
                }
            });

        }

        $(document).ready(function() {

            $(".submit").on('click', function(e) {

                let amount = $("#Amount").val();
                let values = $("input[name='price[]']").map(function() {
                    return $(this).val();
                }).get();

                let total = 0;
                for (let i = 0; i < values.length; i++) {
                    total += Number(values[i]);
                }

                if (!(amount == total.toFixed(2))) {
                    alert("Please Consider the Amount");
                    e.preventDefault();
                }
            });

            $(document).on('click', '#remove', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>

@endsection

@section('model')

    <div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Search Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">

                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Product..." id="employee-Search">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary" onclick="searchProduct()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div><br>

                    <div class="table-responsive" style="width: 100%">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="display: none">#</th>
                                    <th style="width: 50%">Product</th>
                                    <th style="width: 25%">Code</th>
                                    <th style="width: 25%; text-align: center;">Action</th>
                                </tr>
                            </thead>

                            <tbody id="product_tbody">
                                @foreach ($products as $product)
                                    <tr>
                                        <td style="display: none">#</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->product_code }}</td>
                                        <td style="text-align: center">
                                            <button class="btn btn-success selectedProduct" data-id="{{ $product->id }}"
                                                data-product_name="{{ $product->product_name }}"
                                                data-type="{{ $product->type }}">Select</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        // display product modal
        $(".select_product").on('click', function() {
            let good_receive_note = $("#purchase_order_id").val();
            if (good_receive_note) {
                let no = $(this).attr("data-no");
                $("#no").val(no);
                $("#product").modal('show');

            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please Select Good Receive Note!',
                });
            }

        });

        // select product in product modal
        $('.selectedProduct').on('click', function() {
            console.log("click");
            let no = $("#no").val();


            let id = $(this).attr("data-id");
            let product_name = $(this).attr("data-product_name");
            let type = $(this).attr("data-type");

            $(`#product_id_${no}`).val(product_name);
            $(`#pro_id_${no}`).val(id);
            $(`#qty_type_${no}`).val(type);
            $("#product").modal('hide');
        });

        let assign_id = 1;
        $('#pur_product').on('click', function() {
            assign_id++;
            var html = '';
            html += '<tr>';
            html += '<td>';
            html +=
                `<input type="text" id="product_id_${assign_id}"  class="form-control select_product" data-no="${assign_id}" placeholder="Select Product..." autocomplete="off" required>`;
            html += '</td>';
            html += `<input type="hidden" id="pro_id_${assign_id}"  name="product_id[]" >`;
            html += '<td>';
            html +=
                `<input type ="text" name="qty_type[]" id="qty_type_${assign_id}" placeholder="Select Qty Type..." class="form-control" readonly>`;
            html += '</td>';
            html += '<td>';
            html +=
                '<input type="text" id="qty" name="qty[]" class="form-control" placeholder="Enter the Quantity" required>';
            html += '</td>';
            html += '<td>'
            html +=
                `<input type="date" name="expery_date[]" id="expery_date" class="form-control" min="{{ now()->format('Y-m-d') }}" >`;
            html += '</td>'
            html += '<td>';
            html +=
                '<input type="number" step="0.01" id="price"  name="price[]" class="form-control" placeholder="Enter the Price" required>';
            html += '</td>';
            html +=
                '<td><button type="button" id="remove" class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button></td>';

            $('#good_receive').append(html);

            // display product modal
            $(".select_product").on('click', function() {

                let good_receive_note = $("#purchase_order_id").val();
                if (good_receive_note) {
                    let no = $(this).attr("data-no");
                    $("#no").val(no);
                    $("#product").modal('show');

                } else {

                    Swal.fire({
                        icon: 'question',
                        title: 'Oops...',
                        text: 'Please Select Good Receive Note!',
                    });
                }

            });

        });

        // search product in product modal
        $("#employee-Search").keyup(function() {
            searchProduct();
        });

        //search product data
        function searchProduct() {
            let search = $("#employee-Search").val();

            $.ajax({
                type: "get",
                url: "/product-search",
                data: {
                    search
                },

                success: function(response) {

                    let product_html = "";

                    if (response.length > 0) {
                        $.each(response, function(indexInArray, valueOfElement) {

                            product_html += `<tr>
                                    <td style="display: none">#</td>
                                    <td>${valueOfElement.product_name}</td>
                                    <td>${valueOfElement.product_code}</td>
                                    <td style="text-align: center">
                                            <button class="btn btn-success selectedProduct" data-id="${valueOfElement.id}"
                                                data-product_name="${valueOfElement.product_name}"
                                                data-type="${valueOfElement.type}">Select
                                                </button>
                                    </td>
                        </tr>`;

                        });

                    } else {
                        product_html += `<tr>
                                            <td colspan="3" align="center">No Match Records</td>
                                    </tr>`;
                    }

                    $("#product_tbody").empty().append(product_html);

                    // select product in product modal
                    $('.selectedProduct').on('click', function() {
                        console.log("click");
                        let no = $("#no").val();


                        let id = $(this).attr("data-id");
                        let product_name = $(this).attr("data-product_name");
                        let type = $(this).attr("data-type");

                        $(`#product_id_${no}`).val(product_name);
                        $(`#pro_id_${no}`).val(id);
                        $(`#qty_type_${no}`).val(type);
                        $("#product").modal('hide');
                    });

                },

            });
        }

    });
</script>
