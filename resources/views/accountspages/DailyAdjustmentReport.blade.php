@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('content')
@section('DailyAdjustment', 'active')

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
                            <h4 class="header "> Daily Adjustment Report
                                {{-- {{ $to }} --}}
                            </h4>
                        </div>
                    </div>

                    <div class="card-body">

                        <form action="/Accounts/ShowDailyCashReport" method="get">
                            @csrf

                            <div class="row">
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
                            </div>
                        </form>


                        <div class="table-responsive" id="dailyAdjustmentTable">

                            <table class="account">

                                <thead>
                                    <tr>
                                        <td class="borderBottom accountTd">Description</td>
                                        <td class="tdWidth borderBottom accountTd">(Rs.)</td>
                                        <td class="tdWidth tdBold borderBottom accountTd">(Rs.)</td>
                                    </tr>

                                    {{-- <tr>
                                        <td class="accountTd">
                                            <h6><b><u>Income</u></b></h6>
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                    </tr> --}}

                                    <tr>
                                        <td class="accountTd"> Adjustment Cash </td>
                                        <td class="tdWidth accountTd">
                                            {{ number_format($AdjustmentCash, 2) }}
                                        </td>
                                        <td class="tdWidth tdBold tdright accountTd"></td>
                                    </tr>


                                    {{-- <tr>
                                        <td class="accountTd">
                                            <h6><b><u>Expence</u></b></h6>
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                    </tr> --}}

                                    <tr>
                                        <td class="accountTd">Close Amount
                                            {{-- {{ $from }} --}}
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold tdright accountTd">
                                            {{ number_format($CloseCash, 2) }}

                                        </td>
                                    </tr>
                                    <tr style="height:30px">
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="borderTd tdBold tdright accountTd"> </td>

                                    </tr>

                                    <tr>
                                        <td class="accountTd">Diffrence
                                            {{-- {{ $from }} --}}
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold tdright accountTd">
                                            {{ number_format($Diffrence, 2) }}

                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="accountTd">
                                            {{-- {{ $from }} --}}
                                        </td>
                                        <td class="accountTd"></td>
                                        {{-- <td class="tdBold tdright accountTd"><strong>{{$NetAmount}}</strong></td>
                                        --}}
                                    </tr>
                                    @php
                                    $ser_exp = 0.00;
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
            var body1 = `<!DOCTYPE html><html lang="en"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> <title>print</title> <style>.account{border: 2px solid black; width: 100%; color: black; font-size: 18px;}.accountTd{border: 1px solid rgb(216, 204, 204); border-right: 2px solid black; padding: 3px;}.borderTd{border-bottom: 1px solid black;}.borderTop{border-top: 1.1px solid black;}.tdBold{border-right: 3px solid black;}.tdWidth{width: 150px;}.borderBottom{border-bottom: 2px solid black;}.subButton{margin-top: 29px;}.dash{border-bottom: 4px solid black}.tdright{text-align: right;}.tdcenter{text-align: center;}.bgtd{background-color: rgb(227, 227, 227);}.borderBottom2{border-bottom: 1.1px solid black;}</style></head><body>`;
            var body2 = $('#dailyAdjustmentTable').html();
            var body3 = `</body></html>`;
    
            var mywindow = window.open('', 'PRINT');
            mywindow.document.write(body1 + body2 + body3);
            mywindow.focus();
            mywindow.print();
            mywindow.close();
        }
</script>

{{-- <script>
    $(document).ready(function() {
        $('#Sales_return_table').DataTable();
        $('#Purchases_table').DataTable();
        $('#Purchase_return_table').DataTable();
        $('#other_expense_table').DataTable();
        $('#ser_expense_table').DataTable();


        // service expense modal
        $('.service_exp').click(function(e) {
            e.preventDefault();

            let exp_id = $(this).attr("oth-exp-id");
            let expense = $(this).attr("oth-exp-name");
            let to = $("#to").val();
            let from = $("#from").val();
            let ser_exp_amount = $(this).attr("oth-exp-amount");

            $("#ser_exp_amounts").empty().append(`Total : ${parseFloat(ser_exp_amount).toFixed(2)}`);
            $("#ser_exp_header").html(`${expense} Charges`);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "/account-service-charges",
                data: {
                    exp_id,
                    from,
                    to,
                },
                success: function(data) {

                    sevice_exp_modal_html = "";
                    if (data.length > 0) {
                        const monthNames = ["January", "February", "March", "April", "May",
                            "June",
                            "July", "August", "September", "October", "November",
                            "December"
                        ];
                        $.each(data, function(indexInArray, valueOfElement) {
                            const d = new Date(valueOfElement.month);

                            sevice_exp_modal_html += `
                            <tr>
                                <td>${monthNames[d.getMonth()]}</td>
                                <td style="text-align: right">${parseFloat(valueOfElement.charge).toFixed(2)}</td>
                                </tr>
                            `;
                        });

                    } else {
                        sevice_exp_modal_html += `
                        <tr>
                            <td colspan="2" style="text-align: center">No Records Available</td>
                            </tr>
                        `;
                    }

                    $("#ser_exp_modal_tbody").empty().append(sevice_exp_modal_html);
                }

            });


        });


        // Other service expenses modal
        $(".other_exp").click(function(e) {
            e.preventDefault();

            let exp_id = $(this).attr("oth-exp-id");
            let expense = $(this).attr("oth-exp-name");
            let to = $("#to").val();
            let from = $("#from").val();
            let oth_exp_amount = $(this).attr("oth-exp-amount");


            $("#oth_exp_amount").empty().append(`Total : ${parseFloat(oth_exp_amount).toFixed(2)}`);
            $("#oth_exp_header").html(`${expense} Charges`);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "/account-other-exp",
                data: {
                    exp_id,
                    from,
                    to,
                },
                success: function(data) {


                    let other_exp_html = "";
                    if (data.length > 0) {
                        $.each(data, function(indexInArray, valueOfElement) {
                            let cash = valueOfElement.oth_exp_cash;
                            let credit = valueOfElement.oth_exp_credit;

                            if (cash == null) {
                                cash = 0.00;
                            }

                            if (credit == null) {
                                credit = 0.00;
                            }

                            other_exp_html += `
                        <tr>
                            <td>${valueOfElement.date}</td>
                            <td style="text-align: right">${parseFloat(valueOfElement.oth_exp_amount).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(cash).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(credit).toFixed(2)}</td>
                        </tr>
                        `;
                        });

                    } else {
                        other_exp_html += `
                        <tr>
                            <td colspan="4" style="text-align: center" >No Records Availabel</td>
                            </tr>
                        `;
                    }

                    $("#oter_exp_modal_tbody").empty().append(other_exp_html);
                }

            });

        });




        // Service Expense
        $(".ser_exp").click(function(e) {
            e.preventDefault();

            let exp_id = $(this).attr("ser-exp-id");
            let expense = $(this).attr("ser-exp-name");
            let type = $(this).attr("ser-exp-type");
            let to = $("#to").val();
            let from = $("#from").val();
            let ser_exp_amount = $(this).attr("ser-exp-amount");

            console.log(expense);
            console.log(type);
            $("#ser_exp_amount").empty().append(`Total : ${parseFloat(ser_exp_amount).toFixed(2)}`)

            $("#ser_exp_header").html(`${expense} Charges`);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "/account-ser-exp",
                data: {
                    exp_id,
                    from,
                    to,
                    type
                },
                success: function(data) {
                    console.log(data);

                    let ser_exp_html = "";
                    $.each(data, function(indexInArray, valueOfElement) {
                        let cash = valueOfElement.ser_exp_cash;
                        let credit = valueOfElement.ser_exp_credit;

                        if (cash == null) {
                            cash = 0.00;
                        }

                        if (credit == null) {
                            credit = 0.00;
                        }

                        ser_exp_html += `
                        <tr>
                            // <td>${valueOfElement.date}</td>
                            <td style="text-align: right">${parseFloat(valueOfElement.ser_exp_amount).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(cash).toFixed(2)}</td>
                            <td style="text-align: right">${parseFloat(credit).toFixed(2)}</td>
                        </tr>
                        `;
                    });
                    $("#ser_exp_modal_tbody").empty().append(ser_exp_html);
                }

            });

        });


    });
</script> --}}