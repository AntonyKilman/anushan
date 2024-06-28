@extends('layouts.navigation')
@section('Credit List', 'active')
@section('content')

<div class="card card-success">
    <div class="card-header">
        <h4>Credit List Details</h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Invoice Number</th>
                        <th>Customer Name</th>
                        <th>Credit Amount</th>
                        <th>Paid Amount</th>
                        <th class="col-action">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td data-th="date">{{ $row->billing_date }}</td>
                        <td data-th="invoiceno">{{ $row->invoice_no }}</td>
                        <td data-th="customername">{{ $row->customer_name }}</td>
                        <td data-th="creditamount">{{ $row->credit_payment }}</td>
                        <td data-th="creditamount">{{ $row->tillPayAmount }}</td>
                        <td class="col-action">
                            <button type="button" class="btn btn-primary pay" data-toggle="modal"
                                data-target="#commdetails_" data-sales_id="{{ $row->id }}"
                                data-customer_id="{{ $row->customer_id }}" data-invoice_no="{{ $row->invoice_no }}"
                                data-name="{{ $row->customer_name }}"
                                data-tillPayableAmount="{{$row->tillPayableAmount}}">Pay</button>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Credit List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                    action="{{ route('credit_list.savecreditdetails') }}">
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
                                            <p style="color:Tomato"> @error(''){{ $message }} @enderror</p>
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
                                            <p style="color:Tomato"> @error(''){{ $message }} @enderror</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=" ">Amount</label>
                                        <div>
                                            <input type="number" id="amount" name="amount" readonly class="form-control"
                                                value="{{ old('amount') }}" placeholder="" required />
                                            <p style="color:Tomato"> @error(''){{ $message }} @enderror</p>
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
                                        <input type="number" min="0" class="form-control" placeholder="00.00"
                                            name="cash" id="cash-payment" onchange="checkAMount()">
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
                                        <input type="number" min="0" class="form-control" placeholder="00.00"
                                            name="card" id="card-payment" onchange="checkAMount()">
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
                                        <input type="number" min="0" class="form-control" placeholder="00.00"
                                            name="cheque" id="cheque-payment" onchange="checkAMount()">
                                    </div>
                                </div>
                                <div class="form-group col-4">
                                    <label>Discount Amount:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </div>
                                        </div>
                                        <input type="number" min="0" class="form-control" placeholder="00.00"
                                            name="discount_amount" id="discount_amount" onchange="checkAMount()">
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
                                            name="cheque_number" id="cheque-number" onchange="checkAMount()">
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
                                        <input type="date" class="form-control" name="cheque_date" id="cheque-date"
                                            onchange="checkAMount()">
                                    </div>
                                </div>
                                <div class="form-group col-4">
                                    <label>Date </label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" placeholder="" name="date" id="date"
                                            value="{{date('Y-m-d')}}">
                                        <div class="invalid-feedback" id=""></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" align="right">
                        <button type="submit" class="btn btn-success payment-proceed">Save</button>
                    </div>
                </form>
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
            $('#commdetails_').modal();
        }

        // update
        $('#save-stage').on('click', '.pay', function() {

            $('.payment-proceed').prop('disabled', true);

            $('#id').val($(this).attr('data-id'));
            $('#amount').val($(this).attr('data-tillPayableAmount'));
            $('#sales_id').val($(this).attr('data-sales_id'));//hidden
            $('#sales_invoice_no').val($(this).attr('data-invoice_no'));
            $('#customer_id').val($(this).attr('data-customer_id'));//hidden
            $('#cus_name').val($(this).attr('data-name'));    
            
            $('#cash-payment').val('');
            $('#card-payment').val('');
            $('#cheque-payment').val('');
            $('#discount_amount').val('');
            $('#cheque-number').val('');
            $('#cheque-date').val('');
        });

    });

    function checkAMount() {
        var cashPayment = $('#cash-payment').val() ? $('#cash-payment').val() : 0;
        var cardPayment = $('#card-payment').val() ? $('#card-payment').val() : 0;
        var chequePayment = $('#cheque-payment').val() ? $('#cheque-payment').val() : 0;
        var discountamount = $('#discount_amount').val() ? $('#discount_amount').val() : 0;
        var tillPayableAmount = $('#amount').val() ? $('#amount').val() : 0;

        var totalPay = (parseFloat(cashPayment) + parseFloat(cardPayment) + parseFloat(chequePayment) + parseFloat(discountamount));

        if (totalPay <= tillPayableAmount) {
            $('.payment-proceed').prop('disabled', false);
        } else {
            $('.payment-proceed').prop('disabled', true);
        }

        var chequeNo = $('#cheque-number').val() ? $('#cheque-number').val() : 0;
        var chequeDate = $('#cheque-date').val() ? $('#cheque-date').val() : 0;
        //disable button if cheque number or date not enter
        if (chequePayment != 0) {
            if (chequeNo == 0 || chequeDate == 0) {
                $('.payment-proceed').prop('disabled', true);
            }
        }
    }
</script>