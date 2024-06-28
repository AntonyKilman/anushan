@extends('layouts.navigation')
@section('Add Commision', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Add Commision</h4>
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
                            <th width="20%">Commision Customer Name</th>
                            <th width="15%">Invoice Number</th>
                            <th width="15%">Date</th>
                            <th width="15%">Commision Amount</th>
                            <th width="15%">Total Amount</th>
                            <th width="15%" class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($addcommisiondetails as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="Name">{{ $row->name }}</td>
                                <td data-th="invoiceno">{{ $row->invoice_no }}</td>
                                <td data-th="date">{{ $row->date }}</td>
                                <td data-th="commisionamount">{{ $row->commision_amount }}</td>
                                <td data-th="totalamount">{{ $row->total_amount }}</td>
                                <td class="col-action">
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#commdetails_" data-id="{{ $row->id }}"
                                            data-commision_cust_id="{{ $row->commision_cust_id }}" data-sales_id="{{ $row->sales_id }}"
                                            data-date="{{ $row->date }}" data-commision_amount="{{ $row->commision_amount }}"
                                            data-total_amount="{{ $row->total_amount }}" data-invoice_no="{{ $row->invoice_no }}"
                                            data-name="{{ $row->name }}">
                                            <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-edit"></i>
                                        </a>
                                </td>
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
                    <h5 class="modal-title" id="formModal">Commision Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('commisioncustomer.add_commision_details') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                        <div class="form-group">
                                            <label for=" name">Commision customer Name</label>
                                            <input hidden type="text" id="commision_cust_id" class="form-control" name="commision_cust_id"
                                                value="{{ old('commision_cust_id') }}" />
                                            <div>
                                                <input type="text" id="comm_customer" name="comm_customer" readonly required
                                                    data-toggle="modal" data-target="#searchcommcustomer" class="form-control"
                                                    value="{{ old('comm_customer') }}" placeholder="Select Commision Customer" required/>
                                                <p style="color:Tomato"> @error('commision_cust_id'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Sales</label>
                                            <input hidden type="text" id="sales_id" class="form-control" name="sales_id"
                                                value="{{ old('sales_id') }}" />
                                            <div>
                                                <input type="text" id="sales_invoice_no" name="sales_invoice_no" readonly required
                                                    data-toggle="modal" data-target="#searchSales" class="form-control"
                                                    value="{{ old('sales_invoice_no') }}" placeholder="Select Sales" required/>
                                                <p style="color:Tomato"> @error('sales_id'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Date</label>
                                            <div>
                                                <input type="date" class="form-control" id="date" name="date"
                                                     value="{{ old('date') }}" required />
                                                <p style="color:Tomato"> 
                                                    @error ('date'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Total Amount</label>
                                            <div>
                                                <input type="number" class="form-control" id="tamount" name="tamount" readonly
                                                    placeholder="Amount" value="{{ old('tamount') }}" required />
                                                <p style="color:Tomato"> 
                                                    @error ('tamount'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Commision Amount</label>
                                            <div>
                                                <input type="number" class="form-control" id="camount" name="camount"
                                                    placeholder="Enter the  Phone Number" value="{{ old('camount') }}" required />
                                                <p style="color:Tomato"> 
                                                    @error ('camount'){{ $message }} 
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

    {{-- search sales --}}
    <div class="modal fade" id="searchSales" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Search Sales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Invoice Number & Date" id="sales-Search">
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
                                    <th width="20%">Invoice Number</th>
                                    <th width="20%">Bill Date</th>
                                    <th width="20%">Amount</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sales">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- search Commision Customer --}}
    <div class="modal fade" id="searchcommcustomer" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Search Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Name & Phone Number" id="customer-Search">
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
                                    <th width="30%">Name</th>
                                    <th width="30%">Phone number</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="commcustomer">
                                
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

        salesSearch();
        commcustomerSearch();
        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#commdetails_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Commision');
            } else {
                $('#formModal').empty().append('Update Commision');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#name').val('');
            $('#date').val('');
            $('#camount').val('');
            $('#tamount').val('');
            $('#sales_id').val('');
            $('#sales_invoice_no').val('');
            $('#commision_cust_id').val('');
            $('#comm_customer').val('');

            $('#formModal').empty().append('Add Commision');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#date').val($(this).attr('data-date'));
            $('#camount').val($(this).attr('data-commision_amount'));
            $('#tamount').val($(this).attr('data-total_amount'));
            $('#sales_id').val($(this).attr('data-sales_id'));//hidden
            $('#sales_invoice_no').val($(this).attr('data-invoice_no'));
            $('#commision_cust_id').val($(this).attr('data-commision_cust_id'));//hidden
            $('#comm_customer').val($(this).attr('data-name'));        

            $('#formModal').empty().append('Update Commision');
        });

        // search sales
        $(document).on('keyup', '#sales-Search', function() {
            salesSearch();
        });

        function salesSearch() {
            var query = $('#sales-Search').val();

            $.ajax({
                url: "/sales/search-sales",
                method: 'GET',
                data: {
                    q: query
                },
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {

                    $('#sales').html('');

                    if (res != '') {
                        $.each(res, function(index, row) {

                            sales_row = ` <tr>
                                                <td >` + row.invoice_no + `</td>
                                                <td >` + row.billing_date + `</td>
                                                <td >` + row.amount + `</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-small btn-success salesSelect" href="#" style="padding:0px 20px" 
                                                    data-id=" ` + row.id + `" data-amount =" ` + row.amount + `" data-invoice_no =" ` + row.invoice_no + `" 
                                                    >Select</a>
                                                </td>
                                            </tr>`;

                            $('#sales').append(sales_row);

                        });

                    } else {

                        sales_row =
                            '<tr><td align="center" colspan="4">No Data Found</td></tr>';

                        $('#sales').append(sales_row);
                    }

                    // select sales
                    $('.salesSelect').on('click', function() {

                        $('#sales_id').val($(this).attr('data-id'));
                        $('#sales_invoice_no').val($(this).attr('data-invoice_no'));
                        $('#tamount').val(parseFloat($(this).attr('data-amount')));

                        $('#searchSales').modal('hide'); // model hide
                    });

                }
            })
        }

        // search commcustomer
        $(document).on('keyup', '#customer-Search', function() {
            commcustomerSearch();
        });

        function commcustomerSearch() {
            var query = $('#customer-Search').val();

            $.ajax({
                url: "/sales/search-commision-Customer",
                method: 'GET',
                data: {
                    c: query
                },
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {

                    $('#commcustomer').html('');

                    if (res != '') {
                        $.each(res, function(index, row) {

                            commcustomer_row = ` <tr>
                                                <td >` + row.name + `</td>
                                                <td >` + row.phone_number + `</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-small btn-success commcustomerSelect" href="#" style="padding:0px 20px" 
                                                    data-id=" ` + row.id + `" data-name =" ` + row.name + `" 
                                                    >Select</a>
                                                </td>
                                            </tr>`;

                            $('#commcustomer').append(commcustomer_row);

                        });

                    } else {

                        commcustomer_row =
                            '<tr><td align="center" colspan="3">No Data Found</td></tr>';

                        $('#commcustomer').append(commcustomer_row);
                    }

                    // select commcustomer
                    $('.commcustomerSelect').on('click', function() {

                        $('#commision_cust_id').val($(this).attr('data-id'));
                        $('#comm_customer').val($(this).attr('data-name'));

                        $('#searchcommcustomer').modal('hide'); // model hide
                    });

                }
            })
        }

    });

    
</script>
