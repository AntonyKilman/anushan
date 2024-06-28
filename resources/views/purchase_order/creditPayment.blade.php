@extends('layouts.navigation')
@section('purchaseOrderCredit', 'active')
@section('content')

<?php
    $Access = session()->get('Access');
    ?>

<!-- Main Content -->
<div class="card">

    <div class="card-header">
        <h4 class="header">GR Credit</h4>
    </div>


    <form action="/credit-payment-show" method="get" class="needs-validation" novalidate="">
        @csrf
        <div class="card-body form">

            <div class="row">

                <div class="col-3">
                    <label>From</label>
                    <input type="date" name="from" id="from" value="{{ $from }}" class="form-control" required>
                </div>

                <div class="col-3">
                    <label>To</label>
                    <input type="date" name="to" id="to" value="{{ $to }}" class="form-control" required>
                </div>

                <div class="col-3">
                    <label>Supplier</label>
                    <select name="supplier" id="supplier" class="form-control" >
                        <option id="selectedAll" value="">All</option>
                        @foreach ($supplier as $item)
                        <option class="supplier" value="{{ $item->id }}" {{ $item->id == $seller ? 'selected' : '' }}>{{
                            $item->seller_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-3" style="margin-top: 30px">
                    <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>

            <div class="col-4" style="margin-top: 20px">
                <h5> Total Credit : {{ number_format($total_credit, 2) }}</h5>
            </div>

        </div>


    </form>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-1">
                <thead>
                    <tr>
                        <th style="display: none">#</th>
                        <th>Date</th>
                        <th>Delay Dates</th>
                        <th>Supplier</th>
                        <th>GR No</th>
                        <th>Bill No</th>
                        <th style="text-align: center">Credit</th>
                        <th style="text-align: center">Pending Credit</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($credits as $item)
                    <tr>
                        <td style="display: none">#</td>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->days }}</td>
                        <td>{{ $item->seller_name }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->pur_ord_bill_no }}</td>
                        <td style="text-align: right">{{ number_format($item->pur_ord_credit, 2) }}</td>
                        <td style="text-align: right">{{ number_format($item->pendingCreditAmount, 2) }}</td>

                        <td style="text-align: center">
                            <button data-toggle="modal" data-date="{{ $item->date }}" data-id="{{ $item->id }}"
                                data-pur_ord_bill_no="{{ $item->pur_ord_bill_no }}"
                                data-seller_name="{{ $item->seller_name }}"
                                data-pur_ord_amount="{{ $item->pur_ord_amount }}"
                                data-pendingCreditAmount="{{ $item->pendingCreditAmount }}" data-target="#edit_modal" title="Pay"
                                class="btn btn-primary btn-edit"><i class="far fa-edit"></i></button>
                        </td>
                    </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>
</div>
@endsection

@section('model')

<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/credit-payment-store" class="needs-validation" novalidate="" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Purchase Date</label>
                                <input type="text" id="date" class="form-control" readonly>
                            </div>

                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Bill No</label>
                                <input type="text" id="pur_ord_bill_no" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Supplier</label>
                                <input type="text" id="seller_name" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Purchase Order</label>
                                <input type="text" id="id" name="id" class="form-control" readonly>
                                <span class="text-danger">
                                    @error('id')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Total Amount</label>
                                <input type="text" id="pur_ord_amount" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Credit Amount</label>
                                <input type="text" id="pur_ord_credit" name="pur_ord_credit" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-4">
                            <div class="form-group">
                                <label>Cash Amount</label>
                                <input type="number" step="0.001" min="0" id="cash_amount" name="cash_amount"
                                    class="form-control" onchange="checkAMount()" >
                                <span class="text-danger">
                                    @error('cash_amount')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Cheque Amount</label>
                                <input type="number" step="0.001" min="0" id="cheque_amount" name="cheque_amount"
                                    class="form-control" onchange="checkAMount()">
                                <span class="text-danger">
                                    @error('cheque_amount')
                                    {{ $message }}
                                    @enderror
                                </span>
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

                        <div class="col-4">
                            <div class="form-group">
                                <label>Cheque No</label>
                                <input type="number" id="cheque_no" name="cheque_no" class="form-control"
                                    onchange="checkAMount()">
                                <span class="text-danger">
                                    @error('cheque_no')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Cheque Date</label>
                                <input type="date" id="cheque_date" name="cheque_date" class="form-control"
                                    onchange="checkAMount()">
                                <span class="text-danger">
                                    @error('cheque_date')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <div class="col-4">
                            <label>Date</label>
                            <input type="date" name="cash_date" class="form-control" max="{{ now()->format('Y-m-d') }}"
                                value="{{date('Y-m-d')}}" required>
                            <span class="text-danger">
                                @error('cash_date')
                                {{ $message }}
                                @enderror
                            </span>
                        </div>

                    </div>

                    {{-- <div class="row">

                    </div> --}}

                    <div class="row" style="margin-top: 15px; float: right;">
                        <div align="right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button class="btn btn-success mr-1 payment-proceed" type="submit">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {

        
        $('#table-1').on('click', '.btn-edit', function() {
            
            $('.payment-proceed').prop('disabled', true);

            let date = $(this).attr("data-date");
            let pur_ord_bill_no = $(this).attr("data-pur_ord_bill_no");
            let seller_name = $(this).attr("data-seller_name");
            let id = $(this).attr("data-id");
            let pur_ord_amount = $(this).attr("data-pur_ord_amount");
            let pendingCreditAmount = $(this).attr("data-pendingCreditAmount");

            $("#date").val(date);
            $("#pur_ord_bill_no").val(pur_ord_bill_no);
            $("#seller_name").val(seller_name);
            $("#id").val(id);
            $("#pur_ord_credit").val(parseFloat(pendingCreditAmount).toFixed(2));
            $("#pur_ord_amount").val(parseFloat(pur_ord_amount).toFixed(2));
            $("#cheque_amount").attr({
                "max": pendingCreditAmount
                // "min": pendingCreditAmount
            });

            $("#cash_amount").attr({
                "max": pendingCreditAmount
                // "min": pendingCreditAmount
            });
            
        });

        $("#reset").click(function() {
            let selectValue = $('#supplier').val();
            var date = new Date();
            var currentDate = date.toISOString().slice(0, 10);
            $("#to").val(currentDate);
            $("#from").val("");
            $(".supplier").attr("selected", false);
            $("#selectedAll").attr("selected", true);
        });

        setMinDate();

        $('#from').change(function(e) {
            e.preventDefault();
            setMinDate();
        });

        function setMinDate() {
            var from = $('#from').val();
            if (from) {
                $('#to').attr('min', from);
            }
        }

    });

    function checkAMount() {
            console.log(22222222222);
            var cashPayment = $('#cash_amount').val() ? $('#cash_amount').val() : 0;
            var chequePayment = $('#cheque_amount').val() ? $('#cheque_amount').val() : 0;
            var discountamount = $('#discount_amount').val() ? $('#discount_amount').val() : 0;
            var tillPayableAmount = $('#pur_ord_credit').val() ? $('#pur_ord_credit').val() : 0;

            var totalPay = (parseFloat(cashPayment) + parseFloat(chequePayment) + parseFloat(discountamount));

            if (totalPay <= tillPayableAmount) {
                $('.payment-proceed').prop('disabled', false);

                var chequeNo = $('#cheque_no').val() ? $('#cheque_no').val() : 0;
                var chequeDate = $('#cheque_date').val() ? $('#cheque_date').val() : 0;
                //disable button if cheque number or date not enter
                if (chequePayment != 0) {
                    if (chequeNo == 0 || chequeDate == 0) {
                        $('.payment-proceed').prop('disabled', true);
                    } else {
                        $('.payment-proceed').prop('disabled', false);
                    }
                }

            } else {
                $('.payment-proceed').prop('disabled', true);
                
            }

        }
</script>