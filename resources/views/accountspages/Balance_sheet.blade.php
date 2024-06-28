@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('Accounts Balance Sheet', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    ?>

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Statement of Financial Position
                                    {{ $from }}  to {{ $to }}
                                </h4>
                            </div>
                        </div>

                        <div class="card-body">

                            <form action="/account-balance-sheet-Report" method="get">
                                @csrf

                                <div class="row">

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>From</label>
                                            <input type="date" class="form-control" name="from" id="from"
                                                value="{{ $from }}">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>To</label>
                                            <input type="date" class="form-control" name="to" id="to"
                                                value="{{ $to }}">
                                        </div>
                                    </div>

                                    <div align="right">
                                        <button class="btn btn-success mr-1 subButton" id="add"
                                            type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>

                            <div class="col-3">
                                <button class="btn btn-primary" onclick="printContent();">Print</button>
                            </div>

                            <div class="table-responsive" id="balancesheetTable">
                                <caption>BALANCE SHEET AS AT {{ $to }}</caption>
                                <table class="account">
                                    <thead>
                                        <tr>
                                            <td class="borderBottom accountTd">Description</td>
                                            <td class="tdWidth borderBottom accountTd">NOTE</td>
                                            <td class="tdWidth borderBottom accountTd">AMOUNT <br>(Rs.)
                                            </td>
                                            <td class="tdWidth borderBottom accountTd">AMOUNT <br>(Rs.)</td>
                                        </tr>
                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>FIXED ASSETS</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd">Property Plants & Equipments</td>
                                            <td class=" accountTd">01</td>
                                            <td class=" tdright accountTd"></td>
                                            <td class="tdright accountTd">
                                                {{ number_format($property_plants_equipment, 2) }}
                                            </td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd">Fixed Deposits</td>
                                            <td class=" accountTd">02</td>
                                            <td class=" tdright accountTd"></td>
                                            <td class="tdright borderTd accountTd">
                                                {{ number_format($fixdeposits, 2) }}
                                            </td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" tdright accountTd"></td>
                                            <td class="tdright accountTd">
                                                {{ number_format($property_plants_equipment + $fixdeposits, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>CURRENT ASSETS</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Stocks</td>
                                            <td class=" accountTd"></td>
                                            <td class="tdWidth tdright accountTd">
                                                {{ number_format($final_stock, 2) }}
                                            </td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Debtors</td>
                                            <td class="accountTd">03</td>
                                            <td class="tdWidth tdright accountTd">{{ number_format($debtors, 2) }}</td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr> 
                                            <td class="accountTd">Cash At Bank</td>
                                            <td class="accountTd">04</td>
                                            <td class="tdWidth tdright accountTd">
                                                {{ number_format($cash_at_bank, 2) }}
                                            </td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Cash In Hand</td>
                                            <td class=" accountTd"></td>
                                            <td class="tdWidth borderTd tdright accountTd">
                                                {{ number_format($cash_in_hand, 2) }}
                                            </td>
                                            <td class="tdWidth tdright borderTd accountTd">
                                                {{ number_format($final_stock+$debtors+$cash_at_bank+$cash_in_hand, 2) }}
                                            </td>
                                        </tr>   
                                        
                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" tdright accountTd"></td>
                                            @php
                                            $x=$property_plants_equipment + $fixdeposits +$final_stock+$debtors+$cash_at_bank+$cash_in_hand;
                                            @endphp
                                            <td class="tdright dash accountTd">{{ number_format($x, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>FINANCED BY</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd"></td>
                                            <td class="tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>CAPITAL & CURRENT ACCOUNTS</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">MR.M.Mugunthan</td>
                                            <td class="accountTd">05</td>
                                            <td class="tdright borderTd accountTd">{{ number_format($mugunthan, 2) }}</td>
                                            <td class=" tdright accountTd">{{ number_format($mugunthan, 2) }}</td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd"></td>
                                            <td class="tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>LONG TERM LOAN</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Bank Loan</td>
                                            <td class="accountTd">06</td>
                                            <td class="tdright accountTd">{{ number_format($bank_loan, 2) }}</td>
                                            <td class="tdright accountTd"></td>
                                        </tr>
                                        <tr>
                                            <td class="accountTd">Leasing</td>
                                            <td class="accountTd">07</td>
                                            <td class="tdright borderTd accountTd">{{ number_format($leasing, 2) }}</td>
                                            <td class="tdright accountTd">{{ number_format($bank_loan + $leasing, 2) }}</td>
                                        </tr>


                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd"></td>
                                            <td class="tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>CURRENT LIABILITIES</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Creditors</td>
                                            <td class="accountTd">08</td>
                                            <td class="tdright accountTd">{{ number_format($creditors, 2) }}</td>
                                            <td class="tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Accrued Expenses</td>
                                            <td class="accountTd">09</td>
                                            <td class="tdright borderTd accountTd">{{ number_format($accrued_expenses, 2) }}</td>
                                            <td class="tdright borderTd accountTd">
                                                {{ number_format($creditors+$accrued_expenses, 2) }}
                                            </td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd"></td>
                                            <td class="tdright dash accountTd">
                                                {{ number_format($mugunthan+$bank_loan + $leasing+$creditors+$accrued_expenses, 2) }}
                                            </td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd"></td>
                                            <td class="tdright accountTd"></td>
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
        var body2 = $('#balancesheetTable').html();
        var body3 = `</body></html>`;

        var mywindow = window.open('', 'PRINT');
        mywindow.document.write(body1 + body2 + body3);
        mywindow.focus();
        mywindow.print();
        mywindow.close();

    }

</script>

