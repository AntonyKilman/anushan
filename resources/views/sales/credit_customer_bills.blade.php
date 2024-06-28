{{-- @extends('layouts.navigation')
@section('customer', 'active')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Customer Bills</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th >Invioice Number</th>
                            <th >Date</th>
                            <th >Credit Amount</th>
                            <th >Paid Amount</th>
                            <th >Payable Amount</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bills as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->invoice_no }}</td>
                                <td>{{ $row->billing_date }}</td>
                                <td style="text-align: right;">{{ number_format($row->credit_payment,2) }}</td>
                                <td style="text-align: right;">{{ number_format($row->tillPayAmount,2) }}</td>
                                <td style="text-align: right;">{{ number_format($row->PayableAmount,2) }}</td>
                                <td class="col-action">
                                    <a class="btn btn-primary view" title="View" data-sales_id="{{ $row->id }}" 
                                        data-invoice_no="{{ $row->invoice_no }}" data-tillPayAmount="{{ $row->tillPayAmount }}" data-PayableAmount="{{ $row->PayableAmount }}">
                                        <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection --}}

{{-- @section('modal') --}}

{{-- view order details --}}
{{-- <div class="modal fade" id="viewDetails" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Payments History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">Invioice Number:-</div>
                    <div class="col-6"><span id="invoice_no"></span></div>
                    <div class="col-6">Paid Amount:-</div>
                    <div class="col-6">Rs. <span id="tillPayAmount"></span></div>
                    <div class="col-6">Payable Amount:-</div>
                    <div class="col-6">Rs. <span id="PayableAmount"></span></div>
                </div>
                <br>
                <hr>
                <div class="row">
                    <h6>Payment List</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" style="width:100%;">
                            
                            <thead>
                                <tr style="background-color: #66639f; color:#fff">
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Cash</th>
                                    <th>Card</th>
                                    <th>Cheque</th>
                                    <th>Discount</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- @endsection --}}

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}


{{-- <script>
    $(document).ready(function() {

        // view
        $('#save-stage').on('click', '.view', function() {
            var sales_id = $(this).attr('data-sales_id');
            var invoice_no = $(this).attr('data-invoice_no');
            var tillPayAmount = $(this).attr('data-tillPayAmount');
            var PayableAmount = $(this).attr('data-PayableAmount');

            $('#invoice_no').html(invoice_no);
            $('#tillPayAmount').html(tillPayAmount);
            $('#PayableAmount').html(PayableAmount);

            $.ajax({
                url: "/bill-payment-details/" + sales_id,
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

                }
            }); 
        });
    });
</script> --}}


@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable1.css') }}">
@section('content')
@section('customer', 'active')

<?php
$Access = session()->get('Access');
?>

<section class="section" id="profit_loss">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div style="padding: 10px;">

                        <div class="card-header-bank">
                            <h4 class="header "> Customer Paid Payable Report
                            </h4>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- <form action="/customer-tale-report" method="get">
                            @csrf



                            </div>
                        </form> --}}


                        <div class="table-responsive" id="dailyCashTable">

                            <table class="account">

                                <thead>


                                    <tr>
                                        <td class="borderBottom accountTd" style="width:25px">Date</td>
                                        <td class="tdWidth  borderBottom accountTd">Paid Amount</td>
                                        <td class="tdWidth borderBottom accountTd">Invoice Number</td>
                                        <td class="tdWidth tdBold borderBottom accountTd">Credit Amount</td>
                                    </tr>



                                    {{-- @foreach ($Tali as $item)
                                    <tr>
                                        <td class="accountTd">{{$item->date}}
                                        </td>
                                        @foreach ($CreditAmount as $Camount)
                                        @if(in_array($Camount->id))
                                        <td class="accountTd">{{$Camount ->invoice_no}}</td>
                                        @endforeach
                                        <td class="accountTd">{{number_format($item->totalCreditAmount, 2)}}</td>
                                        <td class="tdBold accountTd"></td>
                                    </tr>                           
                                    @endforeach --}}





                                    @foreach ($Tali as $item)
                                    <tr>
                                        <td class="accountTd">{{$item->date}}
                                        </td>
                                        {{-- @foreach ($CreditAmount as $value) --}}
                                        {{-- @foreach ($CreditAmount as $Camount) --}}
                                        {{-- @if(in_array($Camount->id)) --}}
                                            
                                        {{-- <td class="accountTd">{{$value->invoice_no}}</td> --}}
                                        <td class="accountTd">{{number_format($item->totalCreditAmount)}}</td>
                                        {{-- <td class="tdBold accountTd">{{$value->credit_payment}}</td> --}}
                                        {{-- @endforeach --}}
                                    </tr>                           
                                    @endforeach
                                    
                                    <tr style="height:30px">
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">  </td>
                                        <td class="tdWidth accountTd">
                                        <td class="tdWidth  tdright accountTd">
                                            <td class="tdWidth tdBold tdright accountTd">
                                            {{-- {{ number_format($Return_Amount, 2) }} --}}
                                        </td>
                                        </td>
                                    </tr>

                                    <tr style="height:30px">
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                    </tr>
                                    @php
                                        $ser_exp = 0.0;
                                    @endphp
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

<div id="head">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/accountTable.css') }}">
</head>
</div>


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
