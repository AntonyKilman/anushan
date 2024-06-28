@extends('layouts.navigation')
@section('company_return', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        <form action="/company-return-add-process" method="post" class="needs-validation" novalidate="">
            @csrf

            <div class="card-header">
                <h4>Company Return</h4>
            </div>

            {{-- card body start --}}
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Product</label>
                        <input type="text" class="form-control" id="product_id" placeholder="Select Product..."
                            autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Purshase Order Id</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Return Quantity</th>
                                <th>Reason</th>
                                <th class='action'>Checked</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            
                        </tbody>
                    </table>
                </div>
            </div>{{-- card body end  --}}

            <div class="card-footer text-right">
                <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                <button type="submit" id="save-button" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $("#save-button").click(function(e) {
            var input = document.getElementsByName('foodcity_produt_id[]');
            var allChecked = document.querySelectorAll(`[type='checkbox']:checked`);

            if (input.length < 1) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: 'Please Select Any Product!',
                });
            } else {
                if (allChecked.length < 1) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'info',
                        title: 'Oops...',
                        text: 'Please Select Atleast One Checked',
                    });
                }
            }
        });

        $(document).ready(function() {

            searchProduct();

            // search product in product modal
            $("#product-Search").keyup(function() {
                searchProduct();
            });

            $("#product_id").click(function() {
                $("#product").modal('show');
            });

            $('#reset').on('click', function() {
                $('#tbody').html('');
                // products.splice(0, products.length);
            });
        });


        //search product 
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

        function findProduct(product_id) {
            $.ajax({
              type: "get",
              url: "/purchase-view-by-product",
              data: {product_id},
              dataType: "json",
              success: function (response) {
                $("#product").modal('hide');
                console.log(response);
                if (response.success.length > 0) {
                    response.success.forEach(res => {
                        var body = `<tr class="id_${res.item_id}">
                                <input type="hidden" name="foodcity_produt_id[]" value="${res.id}">
                                <td><input type="text" name="pur_ord_id[]" value="${res.purchase_order_id}" class="form-control" readonly=""></td>
                                <td><input type="text" name="product_code[]" value="${res.product_code}" class="form-control" readonly=""></td>
                                <td><input type="text" name="product_name[]" value='${res.name}' class="form-control" readonly=""></td>
                                <td><input type="text" name="qty[]" value="${res.now_have_quantity}" class="form-control" readonly=""></td>
                                <td><input type="number" class="form-control" name="return_qty[]" min="0" max="${res.now_have_quantity}" id="return_qty" value="${res.now_have_quantity}"></td>
                                <td><select id="'+specific_id+'" name="reason_id[]"   class="form-control">  <option value=""  selected>Select Reason</option> @foreach ($reasons as $reason)<option value="{{$reason->reason_name}}">{{$reason->reason_name}}</option>@endforeach</select></td>
                                <td class="action"><input type="checkbox" class="form-control __status" name="check_id[]" value="${res.id}" style="width: 20px; height:20px; margin:10px auto;"></td>
                            </tr>`;

                        $('#tbody').append(body);
                    });
                    var remove = `<tr class="id_${product_id}"><td></td><td></td><td></td><td></td><td></td><td></td><td><button class="btn btn-danger" onclick="remove_fun(${product_id})" type="button"><i class="fas fa-times"></i></button></td></tr>`;
                    $('#tbody').append(remove);
                } else {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Out Of Stock!',
                    });
                }
              }
            });
        }

        function remove_fun(id) {
            $('.id_' + id).remove();
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
                                
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
