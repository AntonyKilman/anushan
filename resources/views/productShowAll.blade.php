@extends('layouts.navigation')
@section('product', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $productAdd = false;
    $productEdit = false;
    if (in_array('inventory.productAdd', $Access)) {
        $productAdd = true;
    }
    if (in_array('inventory.productUpdate', $Access)) {
        $productEdit = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Product</h4>

            @if ($productAdd)
                <button data-toggle="modal" data-target="#product_add" title="edit" class="btn btn-success add">Add</button>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th style="text-align: center">Code</th>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Product Type</th>
                            <th style="text-align: center">Product Brand</th>
                            <th style="text-align: center">Product Sub Category</th>
                            <th style="text-align: center">Qty Type</th>
                            <th style="text-align: center">Minimum Quantity</th>
                            <th style="text-align: center">Danger Quantity</th>
                            <th style="text-align: center">Maximum Quantity</th>
                            @if ($productEdit)
                                <th class='action'>Action</th>
                            @endif
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $data->product_code }}</td>
                                <td>{{ $data->product_name }}</td>
                                <td>{{ $data->product_type_name }}</td>
                                <td>{{ $data->brand_name }}</td>
                                <td>{{ $data->product_sub_cat_name }}</td>
                                <td>{{ $data->type }}</td>
                                <td>{{ $data->min_qty }}</td>
                                <td>{{ $data->danger_qty }}</td>
                                <td>{{ $data->max_qty }}</td>
                                @if ($productEdit)
                                    <td class='action'>
                                        <button data-toggle="modal" data-id="{{ $data->id }}"
                                            data-name="{{ $data->product_name }}" data-code="{{ $data->product_code }}"
                                            data-des="{{ $data->product_des }}" data-type="{{ $data->product_type_id }}"
                                            data-brand="{{ $data->brand_id }}" data-qtytype="{{ $data->type }}"
                                            data-subcat="{{ $data->product_sub_cat_id }}"
                                            data-catid="{{ $data->product_cat_id }}" data-min="{{ $data->min_qty }}"
                                            data-danger="{{ $data->danger_qty }}" data-max="{{ $data->max_qty }}"
                                            data-target="#product_add" title="edit" class="btn btn-primary btn-edit"><i
                                                class="far fa-edit"></i></button>



                                        <button title="histroy" data-toggle="modal" data-target="#purchaseModal"
                                            data-productId="{{ $data->id }}" class="btn btn-dark purchases btn-edit"><i
                                                class="far fa-file"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.add', function() {
            $("#AddReset").show();
            $("#EditReset").hide();
            $('.sub_cat').hide();
            $('.brands').hide();
            $('#id').val('');
            $('#product_name').val('');
            $('#product_code').val('');
            $('#product_des').val('');
            $('#product_type_id').val('');
            $('#productCatId').val('');
            $('#proSubCatId').val('');
            $('#brand_set').val('');
            $('#min_qty').val('');
            $('#danger_qty').val('');
            $('#max_qty').val('');
            $("#formModal").html("Create Product");


            $(".proCatId").on("change", function() {

                $(".pro_sub_id").hide();

                let pro_cat_id = $("#productCatId").val();
                $('.subCat_' + pro_cat_id).show();
            });


        });

        $(document).on('click', '.btn-edit', function() {
            $("#AddReset").show();
            $("#EditReset").hide();
            $('#id').val('');
            $('#product_name').val('');
            $('#product_code').val('');
            $('#product_des').val('');
            $('#product_type_id').val('');
            $('#productCatId').val('');
            $('#proSubCatId').val('');
            $('#brand_set').val('');
            $('#min_qty').val('');
            $('#danger_qty').val('');
            $('#max_qty').val('');
            $('#type').val('');

            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var code = $(this).attr('data-code');
            var des = $(this).attr('data-des');
            var protype = $(this).attr('data-type');
            var brand = $(this).attr('data-brand');
            var subcat = $(this).attr('data-subcat');
            var catid = $(this).attr('data-catid');
            var min = $(this).attr('data-min');
            var danger = $(this).attr('data-danger');
            var max = $(this).attr('data-max');
            var qtytype = $(this).attr('data-qtytype');


            $('#id').val(id);
            $('#product_name').val(name);
            $('#product_code').val(code);
            $('#product_des').val(des);
            $('#product_type_id').val(protype);
            $('#productCatId').val(catid);
            $('#proSubCatId').val(subcat);
            $('#brand_set').val(brand);
            $('#min_qty').val(min);
            $('#danger_qty').val(danger);
            $('#max_qty').val(max);
            $('#type').val(qtytype);


            $("#EditReset").on('click', function(e) {
                e.preventDefault();

                $('#id').val(id);
                $('#product_name').val(name);
                $('#product_code').val(code);
                $('#product_des').val(des);
                $('#product_type_id').val(protype);
                $('#productCatId').val(catid);
                $('#proSubCatId').val(subcat);
                $('#brand_set').val(brand);
                $('#min_qty').val(min);
                $('#danger_qty').val(danger);
                $('#max_qty').val(max);

            });

        });



        $(document).on('click', '.btn-edit', function() {

            $("#formModal").html("Update Product");

            $(document).on('click', '.editCatId', function() {

                $('.editSubCatid').hide();
                $('.editBrand').hide();
                var editsubCatid = document.getElementById('catid').value;
                $('.editSubCatid_' + editsubCatid).show();

                $(document).on('click', '.editSubCat', function() {
                    $('.editBrand').hide();
                    var editbrandid = document.getElementById('subcat').value;

                    $.ajax({
                        type: "GET",
                        url: "/getBrandId/" + editbrandid,
                        dataType: "json",

                        success: function(response) {

                            for (const key in response) {
                                var brand_id = response[key]['brand_id'];
                                var brand_name = response[key]['brand_name'];
                                $('.editBrand_' + brand_id).show();

                            }
                        }
                    });
                });
            });

        });

        $(document).ready(function() {
            if (!@json($errors->isEmpty())) {
                $('#product_add').modal();
            }
        });


        function getCode() {
            let subCode = $("#proSubCatId").val();

            $.ajax({
                type: "GET",
                dataType: "json",
                url: `/product-code/${subCode}`,

                success: function(response) {
                    $("#product_code").val(response);

                },
            });

        }

        $(".purchases").on('click', function(e) {
            e.preventDefault();
            console.log("clickj");
            let product_id = $(this).attr("data-productId");
            $.ajax({
                type: 'GET',
                url: `/product-purchase/${product_id}`,

                success: function(data) {
                console.log(data);
                    let html = "";
                    $.each(data, function(indexInArray, valueOfElement) {
                        let credit = valueOfElement.pur_ord_credit;
                        if (credit === null) {
                            credit = 0.00;
                        }

                        let cash = valueOfElement.pur_ord_cash;
                        if (cash === null) {
                            cash = 0.00;
                        }
                        html += `
                            <tr>
                                        <td style="text-align: center">${valueOfElement.purchase_order_id}</td>
                                        <td style="text-align: center">${valueOfElement.pur_ord_bill_no}</td>
                                        <td style="text-align: center">${valueOfElement.seller_name}</td>
                                        <td style="text-align: center">${valueOfElement.date}</td>
                                        <td style="text-align: right">${parseFloat(valueOfElement.pur_item_amount).toFixed(2)}</td>
                                        <td style="text-align: center">${valueOfElement.pur_item_qty}</td>
                                       
                                        <td style="text-align: center">
                                            <a href="/purchase-order-view/${valueOfElement.purchase_order_id}/${valueOfElement.product_code}" class="btn btn-info btn-sm purchases" title="view"
                                         ><i
                                            class="far fa-eye"></i></a>
                                            </td>
                                    </tr>
                            `;
                    });




                    $("#purchaseTbody").empty().append(html);


                },

                error: function(data) {
                    console.log(data);
                },


            });

        });
    </script>

@endsection

@section('model')

    {{-- purchase modal start --}}
    <div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Good Receive</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="ModalTable">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">GR No</th>
                                        <th style="text-align: center">Bill</th>
                                        <th style="text-align: center">Supplier</th>
                                        <th style="text-align: center">Date</th>
                                        <th style="text-align: center">Amount</th>
                                        <th style="text-align: center">Quantity</th>
                                        
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseTbody">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- purchase modal end --}}

    {{-- add modal start --}}
    <div class="modal fade" id="product_add" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/productAdd" class="needs-validation" novalidate="" method="post">
                        @csrf
                        <input type="hidden" class="form-control" name="id" id="id" required>

                        {{-- first row --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{ old('product_name') }}"
                                    name="product_name" id="product_name" required>
                                <span class="text-danger">
                                    @error('product_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Select Product Type</label>
                                <select class="form-control" name="product_type_id" id="product_type_id" required>
                                    <option value="" disabled selected>Select Product Type</option>
                                    @foreach ($proTypes as $proType)
                                        <option name="product_type_id" class="pro-type" value="{{ $proType->id }}"
                                            {{ $proType->id == old('product_type_id') ? 'selected' : '' }}>
                                            {{ $proType->product_type_name }}</option>
                                    @endforeach
                                    <span class="text-danger">
                                        @error('product_type_id')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Select Product Brand</label>
                                <select class="form-control" name="brand_id" id="brand_set" required>
                                    <option value="" disabled selected>Select Product Brand</option>
                                    @foreach ($brands as $brand)
                                        <option class="" value="{{ $brand->id }}"
                                            {{ $brand->id == old('brand_id') ? 'selected' : '' }}>
                                            {{ $brand->brand_name }}</option>
                                    @endforeach
                                    <span class="text-danger">
                                        @error('brand_id')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </select>
                            </div>
                        </div>

                        {{-- second row --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Select Category</label>
                                <select class="form-control proCatId" name="product_cat_id" id="productCatId" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option class="proCatId pro_cat_id" name="product_cat_id"
                                            value="{{ $category->id }}"
                                            {{ $category->id == old('product_cat_id') ? 'selected' : '' }}>
                                            {{ $category->product_cat_name }}</option>
                                    @endforeach
                                    <span class="text-danger">
                                        @error('product_cat_id')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Product Sub Category</label>
                                <select class="form-control proSubCatId" onchange="getCode()" id="proSubCatId"
                                    name="product_sub_cat_id" required>
                                    <option id="emptySelect" value="" disabled selected>Select Sub Category</option>
                                    @foreach ($subCats as $subCat)
                                        <option name="pro_sub_id" class="pro_sub_id subCat_{{ $subCat->product_cat_id }}"
                                            value="{{ $subCat->id }}"
                                            {{ $subCat->id == old('product_sub_cat_id') ? 'selected' : '' }}>
                                            {{ $subCat->product_sub_cat_name }}</option>
                                    @endforeach
                                    <span class="text-danger">
                                        @error('product_sub_cat_id')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Qty Type</label>
                                <select class="form-control" name="type" id="type" required>
                                    <option value="" disabled selected>Please Select Qty Type</option>
                                    @foreach ($qty_types as $row)
                                        <option value="{{ $row->name }}"
                                            {{ $row->name == old('type') ? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                    <span class="text-danger">
                                        @error('type')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </select>

                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Danger Quantity</label>
                                <input type="number" value="{{ old('danger_qty') }}" class="form-control"
                                    name="danger_qty" id="danger_qty"min="1" step="0.01" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Min Quantity</label>
                                <input type="number" class="form-control" value="{{ old('min_qty') }}" name="min_qty"
                                    id="min_qty" step="0.01" min="1" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Max Quantity</label>
                                <input type="number" class="form-control" value="{{ old('max_qty') }}" name="max_qty"
                                    id="max_qty" min="0" step="0.01" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Code</label>
                                <input type="text" class="form-control" value="{{ old('product_code') }}"
                                    name="product_code" id="product_code" required readonly>
                                <span class="text-danger">
                                    @error('product_code')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Description</label>
                                <textarea class="form-control" name="product_des" id="product_des">{{ old('product_des') }}</textarea>
                            </div>
                        </div>


                        <div align="right">
                            <button type="reset" class="btn btn-danger" id="AddReset">Reset</button>
                            {{-- <button class="btn btn-danger" id="EditReset">Reset</button> --}}
                            <button class="btn btn-success mr-1" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- add modal end --}}
@endsection

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
