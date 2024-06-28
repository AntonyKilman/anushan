@extends('layouts.navigation')
@section('Credit Payment', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Credit Payment</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer Name</th>
                            <th>Invoice Number</th>
                            <th>Date</th>
                            <th>Credit Amount</th>
                            <th>Payment</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($credit_payment as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="Name">{{ $row->customer_name }}</td>
                                <td data-th="invoiceno">{{ $row->invoice_no }}</td>
                                <td data-th="date">{{ $row->date }}</td>
                                <td data-th="commisionamount">{{ $row->amount }}</td>
                                <td data-th="totalamount">{{ $row->cash  + $row->card + $row->check_ }}</td>
                                <td class="col-action">
                                    <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                        data-target="#commdetails_" data-id="{{ $row->id }}"
                                        data-amount="{{ $row->amount }}" data-cash="{{ $row->cash }}"
                                        data-card="{{ $row->card }}" data-check_="{{ $row->check_ }}"
                                        data-check_number="{{ $row->check_number }}"
                                        data-check_date="{{ $row->check_date }}" data-date="{{ $row->date }}"
                                        data-sales_id="{{ $row->sales_id }}" data-customer_id="{{ $row->customer_id }}"
                                        data-invoice_no="{{ $row->invoice_no }}" data-name="{{ $row->customer_name }}">
                                        <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-edit"></i>
                                    </a>
                                    <a title="view" data-toggle="modal" data-target="#view_credit_details" class="btn btn-info view"
                                        data-id="{{ $row->id }}" data-amount="{{ $row->amount }}" data-cash="{{ $row->cash }}"
                                        data-card="{{ $row->card }}" data-check_="{{ $row->check_ }}"
                                        data-check_number="{{ $row->check_number }}"
                                        data-check_date="{{ $row->check_date }}" data-date="{{ $row->date }}"
                                        data-sales_id="{{ $row->sales_id }}" data-customer_id="{{ $row->customer_id }}"
                                        data-invoice_no="{{ $row->invoice_no }}" data-name="{{ $row->customer_name }}"><i
                                            style="color:rgb(226, 210, 210);cursor: pointer" class="far fa-eye"></i></a>
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
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Update Credit Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('credit_payment.save_credit_payment_details') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                        <div class="form-group">
                                            <label for=" ">Invoice Number</label>
                                            <input hidden type="text" id="sales_id" class="form-control" name="sales_id"
                                                value="{{ old('sales_id') }}" />
                                            <div>
                                                <input type="text" id="sales_invoice_no" name="sales_invoice_no" readonly
                                                    class="form-control" value="{{ old('sales_invoice_no') }}"
                                                    placeholder="" required />
                                                <p style="color:Tomato"> @error('')
                                                        {{ $message }}
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Name</label>
                                            <input hidden type="text" id="customer_id" class="form-control"
                                                name="customer_id" value="{{ old('customer_id') }}" />
                                            <div>
                                                <input type="text" id="cus_name" name="cus_name" readonly
                                                    class="form-control" value="{{ old('cus_name') }}" placeholder=""
                                                    required />
                                                <p style="color:Tomato"> @error('')
                                                        {{ $message }}
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for=" ">Amount</label>
                                            <div>
                                                <input type="number" id="amount" name="amount" readonly
                                                    data-toggle="modal" data-target="#" class="form-control"
                                                    value="{{ old('amount') }}" placeholder="" required />
                                                <p style="color:Tomato"> @error('')
                                                        {{ $message }}
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label>Cash:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-money-bill-alt"></i>
                                                </div>
                                            </div>
                                            <input type="number" min="0" class="form-control"
                                                placeholder="00.00" name="cash" id="cash-payment" onchange="()">
                                        </div>
                                    </div>

                                    <div class="form-group col-4">
                                        <label>Card:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-credit-card"></i>
                                                </div>
                                            </div>
                                            <input type="number" min="0" class="form-control"
                                                placeholder="00.00" name="card" id="card-payment" onchange="()">
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Cheque:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-money-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" min="0" class="form-control"
                                                placeholder="00.00" name="cheque" id="cheque-payment" onchange="()">
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="form-group col-4">
                                        <label>Cheque NO: </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-text-width"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" placeholder="000-000-000"
                                                name="cheque_number" id="cheque-number">
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Cheque Date: </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-text-width"></i>
                                                </div>
                                            </div>
                                            <input type="date" class="form-control" name="cheque_date"
                                                id="cheque-date">
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Date </label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" placeholder="" name="date"
                                                id="date" >
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
    {{-- view modal --}}
    <div class="modal fade" id="view_credit_details" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="view_">View Credit Payment Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Customer name :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_cus_name" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Invoice Number :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_sales_invoice_no" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Date :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_date" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Credit Amount :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_amount" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Cash :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_cash_payment" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Card :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_card_payment" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Check :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_cheque_payment" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Check Number :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_cheque_number" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Check Date :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_cheque_date" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" align="right">
                        <br>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#commdetails_').modal();

            var id = $('#id').val();
        }

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#amount').val($(this).attr('data-amount'));
            $('#cash-payment').val($(this).attr('data-cash'));
            $('#card-payment').val($(this).attr('data-card'));
            $('#cheque-payment').val($(this).attr('data-check_'));
            $('#cheque-number').val($(this).attr('data-check_number'));
            $('#cheque-date').val($(this).attr('data-check_date'));
            $('#sales_id').val($(this).attr('data-sales_id')); //hidden
            $('#sales_invoice_no').val($(this).attr('data-invoice_no'));
            $('#customer_id').val($(this).attr('data-customer_id')); //hidden
            $('#cus_name').val($(this).attr('data-name'));
            $('#date').val($(this).attr('data-date'));

        });

        // view
        $('#save-stage').on('click', '.view', function() {
            $('#view_amount').val($(this).attr('data-amount'));
            $('#view_cash_payment').val($(this).attr('data-cash'));
            $('#view_card_payment').val($(this).attr('data-card'));
            $('#view_cheque_payment').val($(this).attr('data-check_'));
            $('#view_cheque_number').val($(this).attr('data-check_number'));
            $('#view_cheque_date').val($(this).attr('data-check_date'));
            $('#view_sales_invoice_no').val($(this).attr('data-invoice_no'));
            $('#view_cus_name').val($(this).attr('data-name'));
            $('#view_date').val($(this).attr('data-date'));

        });

    });
</script>
