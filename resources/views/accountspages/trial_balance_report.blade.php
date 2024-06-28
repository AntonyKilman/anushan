@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('Accounts Trial Balance', 'active')

@section('content')

<?php
$Access = session()->get('Access');
?>

<section class="section" id="trial_balance">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="pb-0">
                        <div class="card-header-bank">
                            <h4 class="header p-4">
                                Trial Balance Report - <small>({{ $from }} <b>to</b> {{ $to }})</small>
                            </h4>
                        </div>
                    </div>

                    <div class="card-body pt-0">

                        <form action="/Accounts/ShowTrialBalanceReport" method="get">
                            @csrf
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>From :</label>
                                        <input type="date" class="form-control" name="from" id="from"
                                            value="{{ $from }}">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>To :</label>
                                        <input type="date" class="form-control" name="to" id="to" value="{{ $to }}">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <button class="btn btn-success mr-3 subButton" id="add"
                                        type="submit">Submit</button>
                                    <button class="btn btn-primary subButton"
                                        onclick="printContent();">Print</button>
                                </div>
                            </div>
                        </form>


                        <div class="table-responsive" id="printtrialBalanceTable">
                            <h6
                                style="text-align: left; margin:10px 0 10px 0; text-decoration:underline; line-height: 1.5;">
                                JAFFNA ELECTRICALS <br>
                                NO:94(6), STANLEY ROAD, <br>
                                JAFFNA. <br>
                                TRIAL BALANCE SHEET AS AT <b>{{ $to }}</b></h6>
                            <table class="account">
                                <thead>
                                    <tr class="tdcenter">
                                        <th class="borderBottom accountTd">No.</th>
                                        <th class="borderBottom accountTd">DESCRIPTION</th>
                                        <th class="borderBottom accountTd">NOTE</th>
                                        <th class="tdWidth borderBottom accountTd">AMOUNT <small>(Rs.)</small></th>
                                        <th class="tdWidth borderBottom accountTd">AMOUNT <small>(Rs.)</small></th>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 01. </td>
                                        <td class="accountTd"> PROPERTY PLANTS & EQUIPMENTS </td>
                                        <td class="accountTd tdcenter tdright">01</td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($property_plants_equipment, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 02. </td>
                                        <td class="accountTd"> FIXED DEPOSIT </td>
                                        <td class="accountTd tdcenter tdright">02</td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($fixdeposits, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 03. </td>
                                        <td class="accountTd"> DEBTORS </td>
                                        <td class="accountTd tdcenter tdright">03</td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($debtors, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 04. </td>
                                        <td class="accountTd"> BANK </td>
                                        <td class="accountTd tdcenter tdright">04</td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($cash_at_bank, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 05. </td>
                                        <td class="accountTd"> CASH </td>
                                        <td class="accountTd tdcenter tdright"></td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($cash_in_hand, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 06. </td>
                                        <td class="accountTd"> STOCK <small><b>({{ $from }})</b></td>
                                        <td class="accountTd tdcenter tdright"></td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($stock_amount, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 07. </td>
                                        <td class="accountTd"> MR.M.MUGUNTHAN </td>
                                        <td class="accountTd tdcenter tdright">05</td>
                                        <td class="tdWidth accountTd tdright"></td>
                                        <td class="tdWidth tdright accountTd">
                                            {{ number_format($mugunthan, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 08. </td>
                                        <td class="accountTd"> BANK LOAN </td>
                                        <td class="accountTd tdcenter tdright">06</td>
                                        <td class="tdWidth accountTd tdright"></td>
                                        <td class="tdWidth tdright accountTd">
                                            {{ number_format($bank_loan, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 09. </td>
                                        <td class="accountTd"> LEASING </td>
                                        <td class="accountTd tdcenter tdright">07</td>
                                        <td class="tdWidth accountTd tdright"></td>
                                        <td class="tdWidth tdright accountTd">
                                            {{ number_format($leasing, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 10. </td>
                                        <td class="accountTd"> SALES </td>
                                        <td class="accountTd tdcenter tdright">08</td>
                                        <td class="tdWidth accountTd tdright"></td>
                                        <td class="tdWidth tdright accountTd">
                                            {{ number_format($sales, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 11. </td>
                                        <td class="accountTd"> PURCHASE </td>
                                        <td class="accountTd tdcenter tdright">09</td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($purchase, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 12. </td>
                                        <td class="accountTd"> OTHER INCOME </td>
                                        <td class="accountTd tdcenter tdright">10</td>
                                        <td class="tdWidth accountTd tdright"></td>
                                        <td class="tdWidth tdright accountTd">
                                            {{ number_format($other_income, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 13. </td>
                                        <td class="accountTd"> ADMINISTRATIVE EXPENSES </td>
                                        <td class="accountTd tdcenter tdright">11</td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($administrative_expenses, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 14. </td>
                                        <td class="accountTd"> SELLING & DISTRIBUTION EXPENSES </td>
                                        <td class="accountTd tdcenter tdright">12</td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($selling_distribution_expenses, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 15. </td>
                                        <td class="accountTd"> FINANCIAL EXPENSES </td>
                                        <td class="accountTd tdcenter tdright">13</td>
                                        <td class="tdWidth accountTd tdright">
                                            {{ number_format($financial_expenses, 2) }}
                                        </td>
                                        <td class="tdWidth tdright accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd tdcenter"> 16. </td>
                                        <td class="accountTd"> CREDITORS </td>
                                        <td class="accountTd tdcenter tdright">14</td>
                                        <td class="tdWidth accountTd tdright"></td>
                                        <td class="tdWidth tdright accountTd">
                                            {{ number_format($creditors, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd borderTd tdcenter"> 17. </td>
                                        <td class="accountTd borderTd"> ACCRUED EXPENSES </td>
                                        <td class="accountTd borderTd tdcenter tdright">15</td>
                                        <td class="tdWidth borderTd accountTd tdright"></td>
                                        <td class="tdWidth borderTd tdright accountTd">
                                            {{ number_format($accrued_expenses, 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd" colspan="3"></td>
                                        <td class="tdWidth boldborderTd bgtd accountTd tdright">
                                            <b>
                                                {{ number_format($total_income, 2) }}
                                            </b>
                                        </td>
                                        <td class="tdWidth boldborderTd bgtd tdright accountTd">
                                            <b>
                                                {{ number_format($total_expense, 2) }}
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
        var body1 = `<!DOCTYPE html><html lang="en"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> <title>print</title> <style>.account{border: 2px solid black; width: 100%; color: black; font-size: 18px;}.accountTd{border: 1px solid rgb(216, 204, 204); border-right: 2px solid black; padding: 3px;}.borderTd{border-bottom: 1px solid black;}.borderTop{border-top: 1.1px solid black;}.tdBold{border-right: 3px solid black;}.tdWidth{width: 150px;}.borderBottom{border-bottom: 2px solid black;}.subButton{margin-top: 29px;}.dash{border-bottom: 4px solid black}.tdright{text-align: right;}.tdcenter{text-align: center;}.bgtd{background-color: rgb(227, 227, 227);}.borderBottom2{border-bottom: 1.1px solid black;}</style></head><body>`;
        var body2 = $('#printtrialBalanceTable').html();
        var body3 = `</body></html>`;

        var mywindow = window.open('', 'PRINT');
        mywindow.document.write(body1 + body2 + body3);
        mywindow.focus();
        mywindow.print();
        mywindow.close();
    }
</script>