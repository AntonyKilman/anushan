@extends('layouts.navigation')
@section('customer', 'active')
@section('content')


<div class="card card-success">
    <div class="card-header">
        <h4>Credit Customer</h4>
        <div class="card-header-action">
            <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#customer_">
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
                        <th width="20%">Name</th>
                        <th width="15%">Phone Number</th>
                        <th>Address</th>
                        <th>Customer Code</th>
                        <th width="15%" class="col-action">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($credit_customers as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td data-th="Name">{{ $row->name }}</td>
                        <td data-th="Phonenumber">{{ $row->phone_number }}</td>
                        <td data-th="Address">{{ $row->address }}</td>
                        <td data-th="Customer_Code">{{ $row->customer_code }}</td>
                        <td class="col-action">
                            <a class="btn btn-info view" data-total_credit_amount="{{ $row->total_credit_amount }}"
                                data-total_pay_credit_amount="{{ $row->total_pay_credit_amount }}"
                                data-till_total_payable_amount="{{ $row->till_total_payable_amount }}"
                                >
                                <i style="color:rgb(226, 210, 210);cursor: pointer" class="far fa-eye"></i></a>
                            <a class="btn btn-info" target="_blank"
                                href="{{ route('customer.customerTaleReport', ['id'=>$row->id]) }}">
                                <i style="color:rgb(226, 210, 210);cursor: pointer" class="fas fa-arrow-right"></i></a>
                            <br> <br>
                            <a class="btn btn-primary edit" title="Edit" data-toggle="modal" data-target="#customer_"
                                data-id="{{ $row->id }}" data-name="{{ $row->name }}"
                                data-phone_number="{{ $row->phone_number }}" data-address="{{ $row->address }}"
                                data-customer_code="{{ $row->customer_code }}">
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
<div class="modal fade" id="customer_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Create Credit Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                    action="{{ route('customer.customer_create') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                    <div class="form-group">
                                        <label for="property name"> Name<span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter the  name" value="{{ old('name') }}" required />
                                            <p style="color:Tomato">
                                                @error('name')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="customer phone number"> Phone Number<span
                                                class="text-danger">*</span></label>
                                        <div>
                                            <input type="number" class="form-control" id="phonenumber"
                                                name="phonenumber" placeholder="Enter the  Phone Number"
                                                value="{{ old('phonenumber') }}" required />
                                            <p style="color:Tomato">
                                                @error('phonenumber')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer address"> Address</label>
                                        <div>
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="Enter the  Address" value="{{ old('address') }}" />
                                            <p style="color:Tomato">
                                                @error('address')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label> Customer Code</label>
                                        <div>
                                            <input type="text" class="form-control" id="Customer_Code"
                                                name="Customer_Code" placeholder="Enter the  code "
                                                value="{{ old('Customer_Code') }}" required />
                                            <p style="color:Tomato">
                                                @error('Customer_Code')
                                                {{ $message }}
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

{{-- view order details --}}
<div class="modal fade" id="viewDetails" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Customer Payments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">Total Credit Amount:-</div>
                    <div class="col-6">Rs. <span id="total_credit_amount"></span></div>
                    <div class="col-6">Total Pay Credit Amount:-</div>
                    <div class="col-6">Rs. <span id="total_pay_credit_amount"></span></div>
                    <div class="col-6">Total Payable Amount:-</div>
                    <div class="col-6">Rs. <span id="till_total_payable_amount"></span></div>
                    
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                        <thead>
                            {{-- <tr>
                                <th>No</th>
                                <th>Invioice Number</th>
                                <th>Date</th>
                                <th>Credit Amount</th>
                                <th>Paid Amount</th>
                                <th>Payable Amount</th>
                                <th class="col-action">Actions</th>
                            </tr> --}}
                        </thead>
                        {{-- <tbody>
                            @foreach ($credit_customers as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->invoice_no }}</td>
                                <td>{{ $row->billing_date }}</td>
                                <td style="text-align: right;">{{ number_format($row->credit_payment,2) }}</td>
                                <td style="text-align: right;">{{ number_format($row->tillPayAmount,2) }}</td>
                                <td style="text-align: right;">{{ number_format($row->PayableAmount,2) }}</td>
                                <td class="col-action">
                                    <a class="btn btn-primary view" title="View" data-sales_id="{{ $row->id }}"
                                        data-invoice_no="{{ $row->invoice_no }}"
                                        data-tillPayAmount="{{ $row->tillPayAmount }}"
                                        data-PayableAmount="{{ $row->PayableAmount }}">
                                        <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#customer_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Credit Customer');
            } else {
                $('#formModal').empty().append('Update Credit Customer');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#name').val('');
            $('#phonenumber').val('');
            $('#address').val('');
            $('#Customer_Code').val('');

            $('#formModal').empty().append('Create Customer');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#name').val($(this).attr('data-name'));
            $('#phonenumber').val($(this).attr('data-phone_number'));
            $('#address').val($(this).attr('data-address'));
            $('#Customer_Code').val($(this).attr('data-Customer_Code'));

            $('#formModal').empty().append('Update Customer');
        });

        $('#save-stage').on('click', '.view', function() {
            $('#total_credit_amount').html($(this).attr('data-total_credit_amount'));
            $('#total_pay_credit_amount').html($(this).attr('data-total_pay_credit_amount'));
            $('#till_total_payable_amount').html($(this).attr('data-till_total_payable_amount'));
            $('#tali_report').html($(this).attr('data-tali_report'));

            $('#viewDetails').modal();
        });

    });


    $(document).ready(function() {

// view
$('#save-stage').on('click', '.view', function() {
    var sales_id = $(this).attr('data-sales_id');
    var invoice_no = $(this).attr('data-invoice_no');
    var tillPayAmount = $(this).attr('data-tillPayAmount');
    var PayableAmount = $(this).attr('data-PayableAmount');
    // var PayableAmount = $(this).attr('data-PayableAmount');

    $('#invoice_no').html(invoice_no);
    $('#tillPayAmount').html(tillPayAmount);
    $('#PayableAmount').html(PayableAmount);

    $.ajax({
        url: "/customer-tale-report/" + customer_id,
        method: 'GET',
        data: {},
        dataType: 'json',
        contentType: "",
        success: function(res) {
            $('#tbody').html('');
            if (res[0]) {
                var i = 1;
                res.forEach(element => {
                    var body = `<tr>
                                <td>${i++}</td>
                                <td>${element.date}</td>
                                <td style="text-align: right;">${element.cash}</td>
                                <td style="text-align: right;">${element.card}</td>
                                <td style="text-align: right;">${element.check_}</td>
                                <td style="text-align: right;">${element.discount_amount}</td>
                            </tr>`;
                    $('#tbody').append(body);
                    
                });
                
            } else {
                var massage = `<tr>
                    <td colspan="6" style="text-align: center;">NO Payment</td>
                    </tr>`;
                    $('#tbody').append(massage);
                }
                
                $('#viewDetails').modal('show');
                console.log(11);

        }
    }); 
});
});
</script>