@extends('layouts.navigation')
@section('indoor_return','active')
@section('content')
<?php
  $Access=session()->get('Access');
?>

    <!-- Main Content -->
    <div class="card">
    <form action="/indoor-return-add-process" method="post" class="needs-validation" novalidate="" enctype="multipart/form-data">
        @csrf

        <div class="card-header">
            <h4>Indoor Return</h4>
        </div>

        {{-- card body start --}}
        <div class="card-body">

            {{-- first row --}}
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Select Department</label>
                    <select id="department_id" name="department_id" class="form-control" required>
                        <option value="" disabled selected>Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }},{{ $department->dept_name }},{{ $department->acc_dept_id }}">{{ $department->dept_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Select Product</label>
                    <input type="text" class="form-control" id="product_id" placeholder="Select Product..." autocomplete="off">
                    {{-- <select id="product_id" name="product_id" class="form-control" required>
                        <option value="" id="disabled" disabled selected>Select Product</option>
                        @foreach ($products as $product)
                            <option class="product" value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select> --}}
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

            {{-- <button type="button" id="pur_product" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp Add Row</button> --}}
        </div>{{-- card body end --}}

        <div class="card-footer text-right">
            <button type="reset" id="reset" class="btn btn-danger">Reset</button>
            <button type="submit" id="save-button" class="btn btn-success">Submit</button>
        </div>
    </form>
    </div>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        $("#save-button").click(function(e){
        var input = document.getElementsByName('produt_id[]');
        var allChecked = document.querySelectorAll(`[type='checkbox']:checked`);
        var department_id = $('#department_id').val();

        if(!department_id){
            Swal.fire({
                icon: 'info',
                title: 'Oops...',
                text: 'Please Select Department!',
            });
        }
        else{

            if(input.length<1){
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: 'Please Select Any Product!',
                });
            }
            else{
                if(allChecked.length<1){
                    e.preventDefault();
                    Swal.fire({
                        icon: 'info',
                        title: 'Oops...',
                        text: 'Please Select Atleast One Checked',
                    });
                }
            }
        }

      });

        const products = [];

        function remove_fun(id) {
            $('.id_' + id).html("");
            index_ele = products.findIndex(checkIndex);
            products.splice(index_ele, 1);

            function checkIndex(products) {
                return products == id;
            }
        }

        $(document).ready(function() {

            $("#product_id").click(function() {
                var department_id = $('#department_id').val();

                if(!department_id){
                    Swal.fire({
                        icon: 'info',
                        title: 'Oops...',
                        text: 'Please Select Department!',
                    });

                }
                else{
                    $("#product").modal('show');
                }

            });
            $('.product').hide();
            $('#reset').on('click', function() {
                $('#tbody').html('');
                products.splice(0, products.length);
            });

            $('#department_id').on('change', function() {
                $('.product').show();
                $('#product_id').val('');
                $('#tbody').html('');
                products.splice(0, products.length);
            });


        });

        // $('#product_id').on('change', function() {
            function findProduct(product_id) {

                // var product_id = $(this).val();
                var department_id = $('#department_id').val();

                if (products.includes(product_id)) {
                    Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'This Product Already Selected!',
                });

                } else {


                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: "/department-total-qty",
                        data: {id: product_id, department_id:department_id},
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            var pro_id;
                            var count_qty = 0;
                            var html = '';

                            for (const key in response.batches) {
                                purchase_unit_price = response.batches[key]['purchase_unit_price'];
                                pro_id = response.batches[key]['product_id'];
                                pro_name=response.batches[key]['product_name'];
                                pro_code=response.batches[key]['product_code'];
                                qty=response.batches[key]['qty'];
                                Qty_type=response.batches[key]['Qty_type'];
                                purchase_id=response.batches[key]['purchase_id'];
                                specific_id=response.batches[key]['purchase_id']+'-'+response.batches[key]['product_id'];
                                if (response.batches[key]['qty'] > 0) {

                                    count_qty++;
                                    html+='<tr class="id_'+pro_id+'">';
                                    html+='<input type="hidden" name="purchase_unit_price[]" value="'+purchase_unit_price+'">';
                                    html+='<input type="hidden" name="produt_id[]" value="'+pro_id+'">';
                                    html+='<input type="hidden" name="Qty_type[]" value="'+Qty_type+'">';
                                    html+='<td ><input type="text" name="pur_ord_id[]" id="" value="'+purchase_id+'" class="form-control" readonly></td>';
                                    html+='<td ><input type="text" name="product_code[]" id="" value="'+pro_code+'" class="form-control" readonly></td>';
                                    html+='<td ><input type="text" name="product_name[]" id="" value="'+pro_name+'" class="form-control" readonly></td>';
                                    html+='<td ><input type="text" name="qty[]" id="" value="'+qty+'" class="form-control" readonly></td>';
                                    html+='<td><input type="number" class="form-control" name="return_qty[]" min="1" max="'+qty+'" id="return_qty" value="'+qty+'" ></td>';
                                    html+='<td>';
                                    html+='<select id="'+specific_id+'" name="reason_id[]" class="form-control"><option value="" disabled selected>Select Reason</option>@foreach ($reasons as $reason)<option value="{{ $reason->id }}">{{ $reason->reason_name }}</option>@endforeach</select>';
                                    html+='</td>';
                                    html+='<td class="action">';
                                    html+='<input type="checkbox" class="form-control __status" name="'+specific_id+'" data-id="'+specific_id+'" style="width:20px; height:20px; margin:10px auto;">';
                                    html+='</td>';
                                    html+='</tr>';
                                }
                            }

                            if (count_qty > 0) {
                                products.push(product_id);
                                $("#product").modal('hide');
                                html +='<tr class="id_'+pro_id+'"><td></td><td></td><td></td><td></td><td></td><td></td><td><button class="btn btn-danger" type="button" onclick="remove_fun('+pro_id+')"><i class="fas fa-times"></i></button></td></tr>';
                            }
                            else {
                                Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Out Of Stock!',
                                });
                        }

                            $('#tbody').append(html);


                            $('.__status').on('change',function(){
                                spec_id=$(this).attr('data-id');
                                if ($(this).prop('checked')) {
                                    $('#'+spec_id).attr('required',true)
                                }else{
                                    $('#'+spec_id).attr('required',false)
                                }
                            });
                        }
                    });
                }
            }
    </script>

    {{-- <script>
          function remove_fun(id) {
              console.log(id);
              $('.id_'+id).html("");
            }

          $(document).ready(function(){
            $('#reset').on('click',function(){
              $('#tbody').html('');
            });
          });

          $(document).ready(function() {
            $('#product_id').on('change',function(){
              var product_id = $(this).val();
              // console.log('product_id : '+product_id);
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

              var html='';

              $.ajax({
                type: "POST",
                url: "/purchase-view-by-product-id",
                data: {id:product_id},
                dataType: "json",   //expect html to be returned
                success: function(response){
                  var pro_id;
            var count_qty=0;
            var html='';
            for (const key in response.batches) {

              if (response.batches[key]['qty']>0) {
                count_qty++;
                html+='<tr class="id_'+response.batches[key]['product_id']+'">';
                html+='<input type="hidden" name="produt_id[]" value="'+response.batches[key]['product_id']+'">';
                html+='<td ><input type="text" name="pur_ord_id[]" id="" value="'+response.batches[key]['purchase_order_id']+'" class="form-control" readonly></td>';
                html+='<td ><input type="text" name="product_code[]" id="" value="'+response.batches[key]['product_code']+'" class="form-control" readonly></td>';
                html+='<td ><input type="text" name="product_name[]" id="" value="'+response.batches[key]['product_name']+'" class="form-control" readonly></td>';
                html+='<td ><input type="text" name="qty[]" id="" value="'+response.batches[key]['qty']+'" class="form-control" readonly></td>';
                html+='<td><input type="number" class="form-control" name="return_qty[]" min="0" max="'+response.batches[key]['qty']+'" id="return_qty" value="'+response.batches[key]['qty']+'" ></td>';
                html+='<td>';
                html+='<select id="reason_id" name="reason_id[]" class="form-control" required><option value="" disabled selected>Select Reason</option>@foreach ($reasons as $reason)<option value="{{$reason->id}}">{{$reason->reason_name}}</option>@endforeach</select>';
                html+='</td>';
                html+='<td class="action">';
                html+='<input type="checkbox" class="form-control" name="'+response.batches[key]['purchase_order_id']+'-'+response.batches[key]['product_id']+'" id="status" style="width: 20px; height:20px; margin:10px auto;">';
                html+='</td>';
                html+='</tr>';
              }
              pro_id=response.batches[key]['product_id'];
            }
            if (count_qty>0) {
              html+='<tr class="id_'+pro_id+'"><td></td><td></td><td></td><td></td><td></td><td></td><td><button class="btn btn-danger" onclick="remove_fun('+pro_id+')" type="button"><i class="fas fa-times"></i></button></td></tr>';
            }
                  $('#tbody').append(html);
                }
              });
            });
          });
      </script> --}}
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
                                @foreach ($all_product as $product)
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


