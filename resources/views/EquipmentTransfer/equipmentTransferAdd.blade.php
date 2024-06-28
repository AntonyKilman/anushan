@extends('layouts.navigation')
@section('equipment_transfer', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    use Carbon\Carbon;
    ?>


    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4>Equipment Transfer</h4>
        </div>

        <form action="/equipmentTransferAdd" method="post" class="needs-validation" novalidate="">
            @csrf


            <div class="card-body form">

                <div class="row">

                    <div class="form-group col-md-4">
                        <label>Select Product</label>
                        <input type="text" class="form-control" id="product_id" placeholder="Select Product...">
                        {{-- <select class="form-control equipmentTransfer" id="product_id" required>
                            <option value="" disabled selected>Select Product</option>
                            @foreach ($products as $product)
                                <option name="product_id" class="product_id" id="product_id"
                                    value="{{ $product->product_id }}">
                                    {{ $product->product_name }}</option>
                            @endforeach
                            
                        </select> --}}
                    </div>


                    <div class="form-group col-md-4">
                        <label>Select Department</label>
                        <select class="form-control department_id" name="department_id" id="department_id" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($departments as $department)
                                <option name="department_id" class="department_id" id="department_id"
                                    value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                            <span class="text-danger">
                                @error('product_id')
                                    {{ $message }}
                                @enderror
                            </span>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Select Employee</label>
                        <select class="form-control " name="employee_id" id="employee_id" required>
                            <option value="" disabled selected>Select Employee</option>
                            @foreach ($employees as $employee)
                                <option name="employee_id" class="employee_id  emp_{{ $employee->department_id }}"
                                    value="{{ $employee->id }}">{{ $employee->f_name }}</option>
                            @endforeach
                            <span class="text-danger">
                                @error('employee_id')
                                    {{ $message }}
                                @enderror
                            </span>
                        </select>
                    </div>



                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Reason</label>
                        <input type="text" name="reason" class="form-control" required>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>GR Number</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Transfer</th>
                                <th>Purchase Date</th>
                                <th>No of Days</th>
                                <th>Selected</th>
                            </tr>
                        </thead>

                        <tbody class="reset"> </tbody>

                    </table>
                </div>



                <div align='right'>
                    <button type="reset" class="btn btn-danger" id="reset">Reset</button>
                    <button class="btn btn-success mr-1" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".department_id").on("change", function() {

            $(".employee_id").hide();
            let dept_id = $("#department_id").val();

            $('.emp_' + dept_id).show();

        });

        var selectedProduct = [];

        $(document).on('click', '#reset', function() {

            $('.reset').html('');
            selectedProduct.splice(0, selectedProduct.length);
        });



        $(document).on('change', '.equipmentTransfer', function() {

            var product = document.getElementById('product_id').value;

            if (selectedProduct.includes(product)) {
                alert("Already Selected");
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

                        for (const key in respon 


                            if (response[key]['pur_item_qty'] > 0) {
                                checkData++;
                                var quantity = response[key]['pur_item_qty'];
                                var quantityType = response[key]['pur_item_qty_type'];
                                var exDate = response[key]['pur_item_expery_date'];
                                var amount = response[key]['pur_item_amount'];
                                var purchaseId = response[key]['purchase_order_id'];
                                var productName = response[key]['product_name'];
                                var proSubCatId = response[key]['product_sub_cat_id'];
                                var productCode = response[key]['product_code'];
                                var purchaseAmount = response[key]['pur_item_amount'];
                                var qtyType = response[key]['pur_item_qty_type'];
                                var productId = response[key]['product_id']; //inventory products.id
                                var specific_id = response[key]['purchase_order_id'] + '-' + response[
                                    key]['product_id'];



                                html += '<tr class="pro_' + product + '">';
                                html +=
                                    '<td style="display: none"><input type="hidden" class="form-control" value="' +
                                    proSubCatId + '"  name="proSubCatId[]"></td>';
                                html +=
                                    '<td style="display: none"><input type="hidden" class="form-control" value="' +
                                    productId + '"  name="pro_id[]"></td>';
                                html +=
                                    '<td style="display: none"><input type="hidden" class="form-control" value="' +
                                    purchaseAmount + '"  name="purchase_amount[]"></td>';
                                html +=
                                    '<td style="display: none"><input type="hidden" class="form-control" value="' +
                                    qtyType + '"  name="qtyType[]"></td>';
                                html += '<td><input type="text" id="purchaseId" value="' + purchaseId +
                                    '" class="form-control" name="purchase_id[]" readonly="readonly"></td>';
                                html += '<td><input type="text" value="' + productName +
                                    '" class="form-control" name="product_name[]" readonly="readonly"></td>';
                                html +=
                                    '<td style="display: none"><input type="hidden" class="form-control" value="' +
                                    productCode + '" name="product_code[]" readonly="readonly"></td>';
                                html += '<td><input type="text" value="' + quantity +
                                    '" class="form-control" name="qty[]" readonly="readonly"></td>';
                                html += '<td><input type="number" value="' + quantity +
                                    '" class="form-control" name="transfer_qty[]"  min="0" max="' +
                                    quantity + '"></td>';
                                html += '<td><input type="date"  class="form-control ' + specific_id +
                                    '" id="" name="purDate[]" ></td>';
                                html +=
                                    '<td><input type="number"  min="0" required class="form-control ' +
                                    specific_id +
                                    '" name="no_of_days[]" ></td>'
                                html +=
                                    '<td style="text-align: center;"><input type="checkbox" required data-id="' +
                                    specific_id + '"  class="messageCheckbox __status" name="' +
                                    purchaseId + '-' + productId + '"  value="' + purchaseId + '-' +
                                    productId +
                                    '"  style="width: 20px; height:20px; margin:10px auto;" ></td>';
                                html += '</tr>';
                            }
                        }

                        if (checkData > 0) {
                            html += '<tr class="pro_' + product + '">';
                            html +=
                                '<td></td><td></td><td><td></td></td><td></td><td></td><td><button class="btn btn-danger" onclick="remove_fun(' +
                                product + ')" type="button"><i class="fas fa-times"></i></button></td>';
                            html += '</td>';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Out Of Stock!',
                            });
                        }

                        $('tbody').append(html);

                        $('.__status').on('change', function() {
                            spec_id = $(this).attr('data-id');

                            if ($(this).prop('checked')) {

                                $('.' + spec_id).attr('required', true)
                            } else {

                                $('.' + spec_id).attr('required', false)
                            }
                        });
                    }

                });

            }

        });



        function remove_fun(id) {

            console.log('remove id : ' + id);
            $('.pro_' + id).hide();

            let index = selectedProduct.findIndex(checkIndex);
            selectedProduct.splice(index, 1);

            function checkIndex(product) {
                return product == id;
            }

            $('#product_id').val('');
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
