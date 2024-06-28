@extends('layouts.navigation')
@section('indoor_transfer', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4>Create Indoor Transfer</h4>
        </div>


        <form action="/indoorTransferAdd" method="post" class="needs-validation" novalidate="">
            @csrf

            <div class="card-body form">

                <div class="row">

                    <div class="form-group col-md-4">
                        <label>Select Product</label>
                        <input type="text" class="form-control" id="product_id" placeholder="Select Product..."
                            autocomplete="off">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Department</label>
                        <input type="text" name="dept_name" class="form-control" id="dept_name" value="{{$dept_name}}"readonly>
                        <input type="hidden" name="dept_id" class="form-control" id="dept_id" value="{{$dept_id}}"readonly>
                    </div>
                </div>


                <div class="table">
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <th>GR Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Quantity Type</th>
                                <th>Transfer Quantity</th>
                                <th>Expiry Date</th>
                                <th>Selected</th>
                            </tr>
                        </thead>

                        <tbody class="reset" id="transfer_tbody"> </tbody>

                    </table>
                </div>


                <div align="right">
                    <button type="reset" class="btn btn-danger" id="reset">Reset</button>
                    <button class="btn btn-success mr-1" id="save_button" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        // for validation
        $("#save_button").click(function(e) {

            let product = document.getElementsByName('pro_id[]');
            var allChecked = document.querySelectorAll(`[type='checkbox']:checked`);
            
            if (product.length < 1) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: 'Please Select Any Product!',
                });
                return;
            }

            if (allChecked.length < 1) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: 'Please Select Atleast One Checked',
                });
            }
        });

        // $("#dept_id").change(function(){
        //     $('.employee').hide();
        //     let dept = $("#dept_id").val();
        //     $(`.emp_${dept}`).show();
        // });




        var selectedProduct = [];

        $(document).on('click', '#reset', function() {

            $('.reset').html('');
            selectedProduct.splice(0, selectedProduct.length);
        });


        function remove_fun(id) {
            $('.pro_' + id).hide();
            $('#product_id').val('');

            let index = selectedProduct.findIndex(checkIndex);
            selectedProduct.splice(index, 1);

            function checkIndex(product) {
                return product == id;
            }

        }


        $("#submit").click(function(e) {
            // e.preventDefault();

            var input = document.getElementsByName('proSubCatId[]');
            console.log(input.length);

            if (input.length == 0) {
                e.preventDefault();
                Swal.fire({
                    title: '<strong>HTML <u>example</u></strong>',
                    icon: 'info',
                    html: 'You can use <b>bold text</b>, ' +
                        '<a href="//sweetalert2.github.io">links</a> ' +
                        'and other HTML tags',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    cancelButtonText: '<i class="fa fa-thumbs-down"></i>',
                    cancelButtonAriaLabel: 'Thumbs down'
                })
            }

        });


        $(document).ready(function() {

            $("#product_id").click(function() {
                $("#product").modal('show');
            });

            // search product in product modal
            $("#product-Search").keyup(function() {
                searchProduct();
            });

            //search product data
            function searchProduct() {
                let search = $("#product-Search").val();
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
                                        <td style="text-align: center"><button class="btn btn-success"
                                                onclick="findProduct(${valueOfElement.id})">Select</button>
                                        </td>
                            </tr>`;

                            });

                        } else {
                            product_html += `<tr>
                                                <td colspan="3" align="center">No Match Records</td>
                                        </tr>`;
                        }


                        $("#product_tbody").empty().append(product_html);
                    },

                });
            }

        });

        function findProduct(product) {

            if (selectedProduct.includes(product)) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'This Product Already Selected!',
                });
            } else {
                selectedProduct.push(product);


                $.ajax({
                    type: "GET",
                    url: "/GetPurchaseId/" + product,
                    dataType: "json",

                    success: function(response) {
                        console.log(response);

                        var html = '';
                        var checkData = 0;

                        for (const key in response) {

                            if (response[key]['pur_item_qty'] > 0) {
                                checkData++;
                                var quantityType = response[key]['pur_item_qty_type'];
                                var exDate = response[key]['pur_item_expery_date'];
                                var amount = response[key]['pur_item_amount'];
                                var purchaseId = response[key]['purchase_order_id'];
                                var productName = response[key]['product_name'];
                                var proSubCatId = response[key]['product_sub_cat_id'];
                                var productCode = response[key]['product_code'];
                                var pur_item_qty = response[key]['pur_item_qty'];
                                var purchaseAmount = response[key]['pur_item_amount'];
                                var qtyType = response[key]['pur_item_qty_type'];
                                var productId = response[key][
                                    'product_id'
                                ]; //inventory products.id



                                html += '<tr class="pro_' + product + '">';
                                html +=
                                    '<td style="display:none"><input type="hidden" value="' +
                                    proSubCatId +
                                    '" class="form-control"  name="proSubCatId[]"></td>';
                                html +=
                                    '<td style="display:none"><input type="hidden" value="' +
                                    productId +
                                    '"  class="form-control" name="pro_id[]"></td>';


                                html += '<td><input type="text" value="' + purchaseId +
                                    '"  name="purchase_id[]" class="form-control" readonly></td>';
                                html += '<td><input type="text" value="' + productName +
                                    '"  name="product_name[]" class="form-control" readonly></td>';
                                html +=
                                    '<td style="display:none"><input type="text" value="' +
                                    productCode +
                                    '" class="form-control" name="product_code[]" readonly></td>';
                                html += '<td><input type="text" value="' + pur_item_qty.toFixed(2) +
                                    '" name="qty[]" class="form-control" readonly="readonly"></td>';
                                html += '<td><input type="text" value="' +
                                    purchaseAmount.toFixed(2) +
                                    '"  class="form-control" name="purchase_amount[]" readonly></td>';
                                html += '<td><input type="text" value="' +
                                    qtyType +
                                    '" class="form-control"  name="qtyType[]" readonly></td>';
                                html += '<td><input type="number" step="0.01" value="' +
                                    pur_item_qty.toFixed(2) +
                                    '" name="transfer_qty[]" class="form-control"  min="0" max="' +
                                    pur_item_qty.toFixed(2) + '" required></td>';
                                html += '<td><input type="date"  value="' + exDate +
                                    '" name="exDate[]" class="form-control" readonly ></td>';
                                html +=
                                    '<td style="text-align: center;"><input type="checkbox" class="messageCheckbox"  class="form-control" name="' +
                                    purchaseId + '-' + productId + '"  value="' +
                                    purchaseId + '-' +
                                    productId +
                                    '" style="width: 20px; height:20px; margin:10px auto;"></td>';
                                html += '</tr>';
                            }

                        }

                        if (checkData > 0) {


                            html += '<tr class="pro_' + product + '">';
                            html +=
                                '<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><button class="btn btn-danger" onclick="remove_fun(' +
                                product +
                                ')" type="button"><i class="fas fa-times"></i></button></td>';
                            html += '</td>';
                            $("#product").modal('hide');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Out Of Stock!',
                            });
                        }

                        $('#transfer_tbody').append(html);

                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            }
        }
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
                        <input type="text" class="form-control" placeholder="Search Product..." id="product-Search">
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
                                        <td style="text-align: center"><button class="btn btn-success"
                                                onclick="findProduct({{ $product->id }})">Select</button>
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
