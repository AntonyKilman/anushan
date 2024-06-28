@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('Accounts profitloss', 'active')

@section('content')

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
                            <h4 class="header ">
                                Accounts Profit Loss {{ $from }} to {{ $to }}
                            </h4>
                        </div>
                    </div>

                    <div class="card-body">

                        <form action="/account-profit-loss-Report" method="get">
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
                                        <input type="date" class="form-control" name="to" id="to"
                                            value="{{ $to }}">
                                    </div>
                                </div>

                                <div class="col-3">

                                    <button class="btn btn-success mr-1 subButton" id="add"
                                        type="submit">Submit</button>
                                </div>
                            </div>
                        </form>

                        <div class="col-3">
                            <button class="btn btn-primary" onclick="printContent();">Print</button>
                        </div>
                        
                        <div class="table-responsive" id="printprofitlossTable">
                            <caption>TRADING PROFIT & LOSS ACCOUNT FOR THE YEAR ENDED {{ $to }}</caption>
                            <table class="account">
                                <thead>
                                    <tr>
                                        <td class="borderBottom accountTd">Description</td>
                                        <td class="borderBottom accountTd">NOTE</td>
                                        <td class="tdWidth borderBottom accountTd">(Rs.)</td>
                                        <td class="tdWidth tdBold borderBottom accountTd">(Rs.)</td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd"> SALES </td>
                                        <td class="accountTd tdright">10</td>
                                        <td class="tdWidth accountTd"></td>
                                        <td class="tdWidth tdBold tdright accountTd">
                                            {{ number_format($salesamount, 2) }}
                                        </td>
                                    </tr>

                                    <tr style="height:30px">
                                        <td class="accountTd">OPENING STOCK ON ({{ $from }})</td>
                                        <td class="accountTd"> </td>
                                        <td class=" tdright accountTd">
                                            {{ number_format($opening_stockamount, 2) }}
                                        </td>
                                        <td class="tdBold accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">PURCHASES</td>
                                        <td class="accountTd tdright">11</td>
                                        <td class="borderTd tdright accountTd">
                                            {{ number_format($purchaseamount, 2) }}
                                        </td>
                                        <td class="tdBold accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd"> </td>
                                        <td class="accountTd"> </td>
                                        <td class="tdright accountTd">
                                            {{ number_format($purchaseamount + $opening_stockamount, 2) }}
                                        </td>
                                        <td class="tdBold tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">CLOSING STOCK ON ({{ $to }})</td>
                                        <td class="accountTd"> </td>
                                        <td class="borderTd tdright accountTd">
                                            ({{ number_format($closing_stockamount, 2) }})
                                        </td>
                                        <td class="borderTd tdBold tdright accountTd">
                                            ({{ number_format($purchaseamount + $opening_stockamount - $closing_stockamount, 2) }})
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="accountTd">GROSS PROFIT</td>
                                        <td class=" accountTd"></td>
                                        <td class=" accountTd"></td>
                                        <td class="tdright tdBold accountTd">
                                            @php
                                                $gross_profit = $salesamount - ($purchaseamount + $opening_stockamount - $closing_stockamount);
                                            @endphp
                                            <b> {{ number_format($gross_profit, 2) }} </b>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">OTHER INCOME</td>
                                        <td class="accountTd tdright">12</td>
                                        <td class="tdright accountTd"></td>
                                        <td class="tdright tdBold borderTd accountTd">
                                            {{ number_format($other_income_amount, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd"></td>
                                        <td class="accountTd"> </td>
                                        <td class="tdright accountTd"></td>
                                        <td class="tdBold tdright accountTd">
                                            {{ number_format($gross_profit + $other_income_amount, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">ADMINISTRATIVE EXPENSES</td>
                                        <td class="accountTd tdright">13</td>
                                        <td class=" tdright accountTd"></td>
                                        <td class=" tdBold tdright accountTd">
                                            ({{ number_format($administrative_expenses, 2) }})
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">SELLING & DISTRIBUTION EXPENSES</td>
                                        <td class="accountTd tdright">14</td>
                                        <td class="accountTd"></td>
                                        <td class=" tdBold tdright accountTd">
                                            ({{ number_format($selling_distribution_expenses, 2) }})
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">FINANCIAL EXPENSES</td>
                                        <td class="accountTd tdright">15</td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold borderTd tdright accountTd">
                                            ({{ number_format($financial_expenses, 2) }})
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">NET PROFIT</td>
                                        <td class="accountTd"> </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold tdright accountTd">
                                            <b> {{ number_format($gross_profit + $other_income_amount - ($administrative_expenses + $selling_distribution_expenses + $financial_expenses), 2) }}
                                            </b>
                                        </td>
                                    </tr>
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
        var body1 = `<!DOCTYPE html><html lang="en"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> <title>print</title> <style>.account{border: 2px solid black; width: 100%; color: black; font-size: 18px;}.accountTd{border: 1px solid rgb(216, 204, 204); border-right: 2px solid black; padding: 3px;}.borderTd{border-bottom: 1px solid black;}.borderTop{border-top: 1.1px solid black;}.tdBold{border-right: 3px solid black;}.tdWidth{width: 150px;}.borderBottom{border-bottom: 2px solid black;}.subButton{margin-top: 29px;}.dash{border-bottom: 4px solid black}.tdright{text-align: right;}.borderBottom2{border-bottom: 1.1px solid black;}</style></head><body>`;
        var body2 = $('#printprofitlossTable').html();
        var body3 = `</body></html>`;

        var mywindow = window.open('', 'PRINT');
        mywindow.document.write(body1 + body2 + body3);
        mywindow.focus();
        mywindow.print();
        mywindow.close();

    }

</script>
