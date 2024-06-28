@extends('layouts.navigation')
@section('cheque_payments', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    ?>
    @section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Cheque Payments</h4>
                            </div>
                        </div>

                        <form action="/cheque-payment-show" method="get">
                            @csrf
                            {{-- <form action="/bank-transection-show" method="post" class="needs-validation" novalidate="">
                                    @csrf --}}
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Account No</label>
                                        <select class="form-control" name="bank_id" required
                                            onchange="window.location.assign('/cheque-payment-show?bank_id=' + this.value)">
                                            <option value="" selected>All</option>
                                            @foreach ($accountChequePayments_acc_no as $accountBank)
                                                <option value="{{ $accountBank->bank_id }}">{{ $accountBank->account_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">
                                            @error('product_cat_id')
                                                {{ $message }}
                                            @enderror
                                        </span>

                                    </div>
                                </div>
                            </div>
                            {{-- </form> --}}

                            {{-- <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="date" class="form-control" name="from" id="from"
                                            value="{{ $from }}">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="date" class="form-control" name="to" id="to" value="{{ $to }}">
                                    </div>
                                </div>

                                <div class="col-3">

                                    <button class="btn btn-success mr-3 subButton" id="add"
                                        type="submit">Submit</button>
                                    <button class="btn btn-primary" id="printTable" onclick="printContent();"
                                        style="margin-top:29px">Print</button>
                                </div>
                            </div> --}}
                        </form>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Account</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Cheque No</th>
                                            <th>Cheque Date</th>
                                            <th>Date</th>
                                            <th style="text-align: center">Transaction</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($accountChequePayments as $accountChequePayment)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $accountChequePayment->account_no }}</td>
                                                <td style="text-align: right">{{ $accountChequePayment->credit }}</td>
                                                <td style="text-align: right">{{ $accountChequePayment->debit }}</td>
                                                <td>{{ $accountChequePayment->cheque_no }}</td>
                                                <td>{{ $accountChequePayment->cheque_date }}</td>
                                                <td>{{ $accountChequePayment->date }}</td>

                                                @if ($accountChequePayment->status == 1)
                                                    <td style="text-align: center"> <button
                                                            class="btn btn-success">Success</button> </td>
                                                @elseif ($accountChequePayment->status == 2)
                                                    <td style="text-align: center"> <button
                                                            class="btn btn-danger">Return</button> </td>
                                                @elseif ($accountChequePayment->status == 3)
                                                    <td style="text-align: center"> <button
                                                            class="btn btn-info">Bounse</button> </td>
                                                @endif

                                                <td style="text-align: center">
                                                    <a href="cheque-payment-update/{{ $accountChequePayment->id }}"
                                                        onclick="return confirm('Are you sure you want to move this item?');"
                                                        class="btn btn-warning btn-edit">Move to Pending
                                                    </a>
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
        </div>
    </section>
    @endsection

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <script>
        $('.btn-edit').on('click', function() {
            var id = $(this).attr('data-id');
            var credit = $(this).attr('data-credit');
            var debit = $(this).attr('data-debit');
            var cheque_no = $(this).attr('data-cheque_no');
            var cheque_date = $(this).attr('data-cheque_date');

            $('#id').val(id);
            $('#credit').val(credit);
            $('#debit').val(debit);
            $('#cheque_no').val(cheque_no);
            $('#cheque_date').val(cheque_date);


        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script>
        function printContent() {
            var body1 =
                `<!DOCTYPE html><html lang="en"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> <title>print</title> <style>.account{border: 2px solid black; width: 100%; color: black; font-size: 18px;}.accountTd{border: 1px solid rgb(216, 204, 204); border-right: 2px solid black; padding: 3px;}.borderTd{border-bottom: 1px solid black;}.borderTop{border-top: 1.1px solid black;}.tdBold{border-right: 3px solid black;}.tdWidth{width: 150px;}.borderBottom{border-bottom: 2px solid black;}.subButton{margin-top: 29px;}.dash{border-bottom: 4px solid black}.tdright{text-align: right;}.tdcenter{text-align: center;}.bgtd{background-color: rgb(227, 227, 227);}.borderBottom2{border-bottom: 1.1px solid black;}</style></head><body>`;
            var body2 = $('#dailyCashTable').html();
            var body3 = `</body></html>`;

            var mywindow = window.open('', 'PRINT');
            mywindow.document.write(body1 + body2 + body3);
            mywindow.focus();
            mywindow.print();
            mywindow.close();
        }
    </script>
