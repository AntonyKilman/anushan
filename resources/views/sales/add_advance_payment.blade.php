@extends('layouts.navigation')
@section('Advance Payment', 'active')
@section('link')
<link rel="stylesheet" href="{{ asset('assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection
@section('content')


<div class="card card-success">
    <form class="needs-validation" method="post" action="{{ route('advance_payment.add_advance_payment_create') }}"
        novalidate="">
        {{ csrf_field() }}
        <div class="card-header">
            <h4>Advance Payment Add</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label> Customer</label>
                    <select class="form-control select2" style="width:100%;" id="customer_id" name="customer_id">
                        <option value="">Choose...</option>
                        @foreach ($addcustomername as $c_customer)
                        <option value="{{ $c_customer->id }}">
                            {{ $c_customer->phone_number . ' (' . $c_customer->name . ')' }}
                        </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"> Please Select Customer. </div>
                    <p style="color:Tomato">
                        @error ('Date'){{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label> Amount<span class="text-danger">*</span></label>
                        <div>
                            <input type="number" class="form-control" id="Amount" name="Amount"
                                placeholder="Enter the  Amount" step="0.01" value="{{ old('Amount') }}" required />
                            <p style="color:Tomato">
                                @error ('Amount'){{ $message }}
                                @enderror
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label> Date<span class="text-danger">*</span></label>
                        <div>
                            <input type="date" class="form-control" id="Date" name="Date" value="{{ old('Date') }}"
                                required />
                            <p style="color:Tomato">
                                @error ('Date'){{ $message }}
                                @enderror
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="add-body">
                
            </div>

            <div class="row">
                <div class="col-md-2">
                    <button type="button" class="btn btn-success" id="add_button">Add</button>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="reset" class="btn btn-danger">Reset</button>
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </form>
</div>

@endsection

@section('modal')

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
                    <input type="text" class="form-control" placeholder="Search Product code & name" id="product-Search">
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

@section('script')
<script src="{{ asset('assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{-- <script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script> --}}

<script>
    $(document).ready(function() {
        var product_name = '';
        var click_body = '';

        addBody(0);

        // add body
        $('#add_button').click(function () { 
            addBody(1);
        });

        

        function addBody(value) {
            if (value == 1) {
                var body_ = `<div class="row">
                            <div class="col-md-6">
                                <label>Product </label>
                                <div class="form-group">
                                    <input type="text" class="form-control product" name="product_name[]" required />
                                    <input type="hidden" class="product-id" name="product_id[]" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Count </label>
                                <div class="form-group">
                                    <input type="number" step="0.001" value="1" class="form-control" name="count[]" required />
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top:30px">
                                <button type="button" class="btn btn-danger remove" >Remove</button>
                            </div>
                        </div>`;
            } else {
                var body_ = `<div class="row">
                            <div class="col-md-6">
                                <label>Product </label>
                                <div class="form-group">
                                    <input type="text" class="form-control product" name="product_name[]" required />
                                    <input type="hidden" class="product-id" name="product_id[]" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Count </label>
                                <div class="form-group">
                                    <input type="number" step="0.001" value="1" class="form-control" name="count[]" required />
                                </div>
                            </div>
                        </div>`;
            }
            

            $('.add-body').append(body_);

            // click product add body
            $('.product').click(function () { 
                click_body = $(this);
                allInventoryProduct(product_name, click_body);

                $('#searchProducts').modal('show');

                // ingredient serach
                $('#product-Search').keyup(function () { 
                    product_name = $(this).val();
                    // call function
                    allInventoryProduct(product_name, click_body);

                });
            });

            // remove body
            $('.remove').click(function () { 
                $(this).parent().parent().closest('div').remove(); 
            });
        }

        // allInventoryProduct
        function allInventoryProduct(name, click_body) {
            
            $.ajax({
                url: "/sales/search-products",
                method: 'GET',
                data: {
                    q: name
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

                        var product_id = $(this).attr('data-id');                        
                        var product_name = $(this).attr('data-product_name');                        
                        
                        $(click_body).val(product_name);
                        $(click_body).parent().children('input.product-id').val(product_id);

                        $('#searchProducts').modal('hide'); // model hide
                    });

                }
            });
        }
    });

    
</script>