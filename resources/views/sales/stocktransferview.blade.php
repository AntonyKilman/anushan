@extends('layouts.navigation')
@section('Stock_Transfer', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Sales Stock Transfer</h4>
            <div class="card-header-action">
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#commdetails_">
                        <span class="btn-icon-wrapper pr-2"> </span>
                        Add
                    </a>
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product Name</th>
                            <th>Quantity Type</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            {{-- <th class="col-action">Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocktransfer as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="">{{ $row->product_name }} {{$row->food_city_product_name}}</td>
                                <td data-th="">{{ $row->quantity_type }}</td>
                                <td data-th="">{{ $row->quantity }}</td>
                                <td data-th=""><button class="btn {{$row->status == 'in' ? 'btn-success' : 'btn-danger'}}">{{ $row->status }}</button></td>
                                {{-- <td class="col-action">
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#commdetails_" data-id="{{ $row->id }}" data-product_code="{{ $row->product_code }}"
                                            data-product_name="{{ $row->product_name }}" data-productquantity="{{ $row->quantity }}"
                                            data-name="{{ $row->name }}">
                                            <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-edit"></i>
                                        </a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection


@section('modal')

    {{-- create & update model --}}
    <div class="modal fade" id="commdetails_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">StockTransfer Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('sales.stocktransferAdd') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="{{ old('id') }}">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for=" name">Select sales Product</label>
                                            <input hidden type="text" id="foodcity_product_id" class="form-control" name="foodcity_product_id"
                                                value="{{ old('foodcity_product_id') }}" />
                                            <div>
                                                <input type="text" id="foodcity_product_name" name="foodcity_product_name"  readonly class="form-control"
                                                data-toggle="modal" data-target="#searchfoodcityProducts"
                                                   value="{{ old('foodcity_product_name') }}" placeholder="Select Product" required/>
                                                <p style="color:Tomato"> @error('foodcity_product_id'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Product Quantity</label>
                                            <div>
                                                <input type="number" class="form-control" id="foodcity_productquantity" name="foodcity_productquantity"
                                                placeholder="Enter Quantity"   value="{{ old('foodcity_productquantity') }}" required />
                                                <p style="color:Tomato"> 
                                                    @error ('foodcity_productquantity'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for=" name">Select Inventory Product Name</label>
                                            <input hidden type="text" id="inventory_product_id" class="form-control" name="inventory_product_id"
                                                value="{{ old('inventory_product_id') }}" />
                                            <div>
                                                <input type="text" id="inventory_product_name" name="inventory_product_name" readonly  class="form-control"
                                                data-toggle="modal" data-target="#searchProducts"   value="{{ old('inventory_product_name') }}"
                                                 placeholder="Select Product" required/>
                                                <p style="color:Tomato"> @error('inventory_product_id'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Change Product Quantity</label>
                                            <div>
                                                <input type="number" class="form-control" id="inventory_changequantity" name="inventory_changequantity" 
                                                    placeholder="Enter Quantity" value="{{ old('inventory_changequantity') }}" required />
                                                <p style="color:Tomato"> 
                                                    @error ('inventory_changequantity'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label >Divion By </label>
                                            <div>
                                                <input type="number" class="form-control" id="divion_by" name="divion_by" 
                                                    placeholder="Enter Quantity" value="{{ old('divion_by') }}" required />
                                                <p style="color:Tomato"> 
                                                    @error ('divion_by'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" align="right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- search foodcity products --}}
    <div class="modal fade" id="searchfoodcityProducts" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Search Sales Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Product  name &  barcode" id="foodcityproduct-Search">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="30%">Product</th>
                                    <th width="20%">Qty</th>
                                    <th width="20%">Selling Price</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="foodcityproducts">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- search inventory products --}}
    <div class="modal fade" id="searchProducts" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Search inventory Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Product id & name" id="product-Search">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="20%">Product</th>
                                    <th width="20%">Code</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="products">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function() {

        ProductSearch();
        foodcityProductSearch();

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#commdetails_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create ');
            } else {
                $('#formModal').empty().append('Update ');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#productquantity').val('');
            $('#changequantity').val('');
            $('#product_id').val('');//hidden
            $('#product').val('');
            $('#changeproduct_id').val('');//hidden
            $('#changeproduct').val('');

            $('#formModal').empty().append('Add ');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#productquantity').val($(this).attr('data-productquantity'));
            $('#changequantity').val($(this).attr('data-changequantity'));
            $('#product_id').val($(this).attr('data-product_id'));//hidden
            $('#product').val($(this).attr('data-product'));     

            $('#formModal').empty().append('Update ');
        });

        // search foodcity products
        $(document).on('keyup', '#foodcityproduct-Search', function() {
            foodcityProductSearch();
        });

        function foodcityProductSearch() {
            var query = $('#foodcityproduct-Search').val();

            $.ajax({
                url: "/sales/search-foodcity-products",
                method: 'GET',
                data: {
                    c: query
                },
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {

                    $('#foodcityproducts').html('');

                    if (res != '') {
                        $.each(res, function(index, row) {

                            foodcity_product_row = ` <tr>
                                                <td >` + row.name + `</td>
                                                <td >` + row.now_have_quantity + `</td>
                                                <td >` + row.sales_price + `</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-small btn-success foodcityproductSelect" href="#" style="padding:0px 20px" 
                                                    data-id=" ` + row.id + `" data-name =" ` + row.name +  `" data-now_have_quantity ="`+row.now_have_quantity+ `"   
                                                    >Select</a>
                                                </td>
                                            </tr>`;

                            $('#foodcityproducts').append(foodcity_product_row);

                        });

                    } else {

                        foodcity_product_row =
                            '<tr><td align="center" colspan="3">No Data Found</td></tr>';

                        $('#foodcityproducts').append(foodcity_product_row);
                    }

                    // select 
                    $('.foodcityproductSelect').on('click', function() {

                        $('#foodcity_product_id').val($(this).attr('data-id'));
                        $('#foodcity_product_name').val($(this).attr('data-name'));

                        $('#foodcity_productquantity').attr('max', $(this).attr('data-now_have_quantity'));

                        $('#searchfoodcityProducts').modal('hide'); // model hide
                    });

                }
            })
        }

        // search inventory products
        $(document).on('keyup', '#product-Search', function() {
            ProductSearch();
        });

        function ProductSearch() {
            var query = $('#product-Search').val();

            $.ajax({
                url: "/sales/search-products",
                method: 'GET',
                data: {
                    q: query
                },
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {

                    $('#products').html('');

                    if (res != '') {
                        $.each(res, function(index, row) {

                            product_row = ` <tr>
                                                <td >` + row.product_name + `</td>
                                                <td >` + row.product_code + `</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-small btn-success inventoryproductSelect" href="#" style="padding:0px 20px" 
                                                    data-id=" ` + row.id + `" data-product_name =" ` + row.product_name + `"  
                                                    >Select</a>
                                                </td>
                                            </tr>`;

                            $('#products').append(product_row);

                        });

                    } else {

                        product_row =
                            '<tr><td align="center" colspan="3">No Data Found</td></tr>';

                        $('#products').append(product_row);
                    }

                    // select product
                    $('.inventoryproductSelect').on('click', function() {

                        $('#inventory_product_id').val($(this).attr('data-id'));
                        $('#inventory_product_name').val($(this).attr('data-product_name'));

                        $('#searchProducts').modal('hide'); // model hide
                    });

                }
            })
        }

    });

    
</script>
