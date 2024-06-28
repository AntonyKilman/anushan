@extends('layouts.navigation')
@section('indoor_transfer','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

    <!-- Main Content -->
    <div class="card">
    <div class="card-header">
        <h4>Use Products</h4>
    </div>


    <form action="#" method="post" class="needs-validation" novalidate="">
        @csrf

        <div class="card-body form">

            <div class="row">

                <div class="form-group col-md-4">
                    <label>Select Product</label>
                    <select class="form-control" id="product_id" required>
                        <option value="" disabled selected>Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                        <span class="text-danger">@error('product_id') {{ $message }}@enderror</span>
                        </select>
                    </div>

                    {{-- <div class="form-group col-md-4">
                        <label>Select Department</label>
                        <select class="form-control" name="dept_id" id="dept_id" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($departments as $department)
                                <option name="dept_id" class="dept_id" id="dept_id" value="{{ $department->id }}">
                                    {{ $department->dept_name }}</option>
                            @endforeach
                            <span class="text-danger">@error('dept_id') {{ $message }}@enderror</span>
                            </select>
                    </div> --}}
                </div>





                    <div class="table table-responsive">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Purchase Order Id</th>
                                    <th>Product Name</th>
                                    {{-- <th>Product Code</th> --}}
                                    <th>Quantity</th>
                                    <th>Used Quantity</th>
                                    <th>Expiry Date</th>
                                    <th>Reason</th>
                                    <th>Selected</th>

                                </tr>
                            </thead>

                            <tbody class="body"> 
                            </tbody>

                        </table>
                    </div>


                    <div align="right">
                        <button type="reset" class="btn btn-danger" id="reset">Reset</button>
                        <button class="btn btn-success mr-1" type="submit" id="submit">Submit</button>
                    </div>
                </div>
            </form>
    </div>

            <script>
                var selectedProduct = [];

                $(document).on('click', '#reset', function() {
                    
                    $('.reset').html('');
                    selectedProduct.splice(0, selectedProduct.length);
                });


                $(document).on('change', '#product_id', function() {
                   
                    var product = $(this).val();;
                  
                   
                    if (selectedProduct.includes(product)) {
                        alert("Already Selected");
                        
                    } else {
                        selectedProduct.push(product);
                      

                        $.ajax({
                            type: "GET",
                            url: "/electric-use-add-getdata/" + product,
                            dataType: "json",
                            success: function(response) {
                                var html = '';
                                var checkData = 0;
                              
                                for (const key in response) {
                                    checkData++;
                                    if (response[key]['qty'] > 0) {
                                        // var quantityType = response[key]['pur_item_qty_type'];
                                        var exDate = response[key]['exDate'];
                                        // var amount = response[key]['pur_item_amount'];
                                        var purchaseId = response[key]['purchase_id'];
                                        // var product_id = response[key]['product_id'];
                                        var productName = response[key]['product_name'];
                                        // var proSubCatId = response[key]['product_sub_cat_id'];
                                        var productCode = response[key]['product_code'];
                                        var transfer_quantity = response[key]['qty'];
                                        // var purchaseAmount = response[key]['pur_item_amount'];
                                        // var qtyType = response[key]['pur_item_qty_type'];
                                        var productId = response[key]['product_id']; //inventory products.id

                                        html += '<tr class="pro_' + product + '">';
                                        // html += '<td style="display: none"><input type="hidden" value="' +proSubCatId + '" class="form-control"  name="proSubCatId[]"></td>';
                                        // html += '<td style="display: none"><input type="hidden" value="' +productId + '"  class="form-control" name="pro_id[]"></td>';
                                        // html += '<td style="display: none"><input type="hidden" value="' +purchaseAmount +'"  class="form-control" name="purchase_amount[]"></td>';
                                        // html += '<td style="display: none"><input type="hidden" value="' +qtyType + '" class="form-control"  name="qtyType[]"></td>';
                                        html += '<td><input type="text" value="' + purchaseId +'"  name="purchase_id[]" class="form-control" readonly></td>';
                                        html += '<td><input type="text" value="' + productName +'"  name="product_name[]" class="form-control" readonly></td>';
                                        // html += '<td><input type="text" value="' +productCode +'" class="form-control" name="product_code[]" readonly></td>';
                                        html += '<td><input type="number" value="' + transfer_quantity +'" name="qty[]" class="form-control" step="0.01" readonly="readonly"></td>';
                                        html += '<td><input type="number" value="' + transfer_quantity +'" name="transfer_qty[]" class="form-control" step="0.01"  min="0" max="' +transfer_quantity + '" required></td>';
                                        html += '<td><input type="date"  value="' + exDate +'" name="exDate[]" class="form-control" readonly="readonly"></td>';
                                        html+='<td><textarea name="reason" id="reason" cols="1" rows="1" class="form-control"></textarea></td>';
                                        html +='<td style="text-align: center;"><input type="checkbox" class="messageCheckbox" class="form-control" name="' +purchaseId + '-' + productId + '"  value="' + purchaseId + '-' +productId +'" style="width: 20px; height:20px; margin:10px auto;"></td>';
                                        html += '</tr>';
                                    }
                                }

                                // if (checkData > 0) {
                                //     html += '<tr class="pro_' + product + '">';
                                //     html +=
                                //         '<td></td><td></td><td></td><td></td><td></td><td><button class="btn btn-danger" onclick="remove_fun(' +
                                //         product + ')" type="button"><i class="fas fa-times"></i></button></td>';
                                //     html += '</td>';
                                // }

                                $('tbody').append(html);
                            }
                        });
                    }
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
            </script>

        @endsection
