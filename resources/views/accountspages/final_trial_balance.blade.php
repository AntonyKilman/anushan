@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('Accounts Final Trial Balance', 'active')

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
                                    Final Trial Balance Report -
                                </h4>
                            </div>
                        </div>

                        <div class="card-body pt-0">

                            <form action="/Accounts/FinalTrialBalanceReport" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>From :</label>
                                            <input type="text" class="form-control yearpicker" name="year"
                                                id="year" value="{{ $year }}">
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <button class="btn btn-success mr-3 subButton" id="add"
                                            type="submit">Submit</button>
                                        <button class="btn btn-primary subButton" onclick="printContent();">Print</button>
                                    </div>
                                </div>
                            </form>


                            <div class="table-responsive" id="printtrialBalanceTable">
                                <h6
                                    style="text-align: left; margin:10px 0 10px 0; text-decoration:underline; line-height: 1.5;">
                                    JAFFNA ELECTRICALS <br>
                                    NO:94(6), STANLEY ROAD, <br>
                                    JAFFNA. <br>
                                    TRIAL BALANCE ({{ date('d/m/Y', strtotime($year_start_date)) }} -
                                    {{ date('d/m/Y', strtotime($year_end_date)) }})
                                </h6>
                                <table class="account">
                                    <thead>
                                        <tr class="tdcenter">
                                            <th class="borderBottom accountTd">No.</th>
                                            <th class="borderBottom accountTd">DESCRIPTION</th>
                                            <th class="borderBottom accountTd">NOTE</th>
                                            <th class="tdWidth borderBottom accountTd">AMOUNT <small>(Rs.)</small></th>
                                            <th class="tdWidth borderBottom accountTd">AMOUNT <small>(Rs.)</small></th>
                                        </tr>

                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($Property_Plants_Equipments as $item)
                                            <tr>
                                                <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                <td class=" accountTd  ">{{ $item->name }} </td>
                                                <td class="tdWidth tdright accountTd "></td>
                                                <td class="tdWidth  tdright accountTd">{{ number_format($item->amount, 2) }}
                                                </td>
                                                <td class="tdWidth accountTd"></td>
                                            </tr>
                                        @endforeach

                                        @foreach ($Fixed_Deposits as $item)
                                            <tr>
                                                <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                <td class=" accountTd  ">{{ $item->name }} </td>
                                                <td class="tdWidth tdright accountTd "></td>
                                                <td class="tdWidth  tdright accountTd">{{ number_format($item->amount, 2) }}
                                                </td>
                                                <td class="tdWidth accountTd"></td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Stock {{ date('d/m/Y', strtotime($year_start_date)) }}
                                            </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Stock, 2) }}</td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Debtors </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Debtors, 2) }}</td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Post Date Cheque </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Post_Date_Cheque, 2) }}
                                            </td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        @foreach ($Bank_Loan as $item)
                                            <tr>
                                                <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                <td class=" accountTd  ">{{ $item->name }} </td>
                                                <td class="tdWidth tdright accountTd "></td>
                                                <td class="tdWidth  tdright accountTd"></td>
                                                <td class="tdWidth accountTd tdright">{{ number_format($item->amount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        @foreach ($Leasing as $item)
                                            <tr>
                                                <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                <td class=" accountTd  ">{{ $item->name }} </td>
                                                <td class="tdWidth tdright accountTd "></td>
                                                <td class="tdWidth  tdright accountTd"></td>
                                                <td class="tdWidth accountTd tdright">{{ number_format($item->amount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        @foreach ($Bank as $item)
                                            <tr>
                                                <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                <td class=" accountTd  ">{{ $item->name }} </td>
                                                <td class="tdWidth tdright accountTd "></td>
                                                <td class="tdWidth  tdright accountTd">
                                                    {{ number_format($item->amount, 2) }}</td>
                                                <td class="tdWidth accountTd"></td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Cash in Hand </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            @if ($cash_in_hand > 0)
                                                <td class="tdWidth accountTd tdright">
                                                    {{ number_format(abs($cash_in_hand), 2) }}
                                                </td>
                                                <td class="tdWidth tdright accountTd"></td>
                                            @else
                                                <td class="tdWidth accountTd tdright">
                                                    ({{ number_format(abs($cash_in_hand), 2) }})
                                                </td>
                                                <td class="tdWidth tdright accountTd"></td>
                                            @endif
                                        </tr>

                                        @foreach ($mukunthan as $item)
                                            <tr>
                                                @if ($item->name == 'ADD-Net profit for the Year' || $item->sub_categeory_id == 8)
                                                    <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                    <td class=" accountTd  ">{{ $item->name }} </td>
                                                    <td class="tdWidth tdright accountTd "></td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth accountTd tdright">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                @else
                                                    <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                    <td class=" accountTd  ">{{ $item->name }} </td>
                                                    <td class="tdWidth tdright accountTd "></td>
                                                    <td class="tdWidth  tdright accountTd">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Creditors </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth tdright accountTd"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Creditors, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Sales </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth tdright accountTd"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Sales, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Sales Return </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Sales_return, 2) }}
                                            </td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Excess </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            @if ($excess > 0)
                                                <td class="tdWidth tdright accountTd"></td>
                                                <td class="tdWidth accountTd tdright">{{ number_format(abs($excess), 2) }}
                                                </td>
                                            @else
                                                <td class="tdWidth tdright accountTd"></td>
                                                <td class="tdWidth accountTd tdright">
                                                    ({{ number_format(abs($excess), 2) }})</td>
                                            @endif
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Other Income </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth tdright accountTd"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($other_income, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Purchase </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Purchase, 2) }}</td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Purchase Return</td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth tdright accountTd"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Purchase_return, 2) }}
                                            </td>
                                        </tr>

                                        @foreach ($Selling_Distribution_Expences as $item)
                                            <tr>
                                                <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                <td class=" accountTd  ">{{ $item->name }} </td>
                                                <td class="tdWidth tdright accountTd "></td>
                                                <td class="tdWidth accountTd tdright">
                                                    {{ number_format($item->amount, 2) }}</td>
                                                <td class="tdWidth  tdright accountTd"></td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Salary Staff </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Salary_staff, 2) }}
                                            </td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Epf </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Epf, 2) }}</td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Etf </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Etf, 2) }}</td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        @foreach ($Administrative_Expences as $item)
                                            <tr>
                                                <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                <td class=" accountTd  ">{{ $item->name }} </td>
                                                <td class="tdWidth tdright accountTd "></td>
                                                <td class="tdWidth accountTd tdright">
                                                    {{ number_format($item->amount, 2) }}</td>
                                                <td class="tdWidth  tdright accountTd"></td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> DISCOUNT ALLOWED </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">
                                                {{ number_format($Discount_allowed, 2) }}</td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        @foreach ($Common_expences as $item)
                                            <tr>
                                                <td class=" accountTd tdcenter">{{ $i++ }}</td>
                                                <td class=" accountTd  ">{{ $item->name }} </td>
                                                <td class="tdWidth tdright accountTd "></td>
                                                <td class="tdWidth accountTd tdright">
                                                    {{ number_format($item->amount, 2) }}</td>
                                                <td class="tdWidth  tdright accountTd"></td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Bank Charges </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Bank_charges, 2) }}
                                            </td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd"> Bank Int </td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright">{{ number_format($Bank_interest, 2) }}
                                            </td>
                                            <td class="tdWidth tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter">{{ $i++ }}</td>
                                            <td class="accountTd">Accounting Exp</td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd borderTd tdright">
                                                {{ number_format($Accounting_Exp, 2) }}
                                            </td>
                                            <td class="tdWidth tdright borderTd accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd tdcenter"></td>
                                            <td class="accountTd">Total</td>
                                            <td class="accountTd tdcenter tdright"></td>
                                            <td class="tdWidth accountTd tdright" style="color: green">
                                                {{ number_format($total_debit, 2) }}</td>
                                            <td class="tdWidth  tdright accountTd" style="color: green">
                                                {{ number_format($total_credit, 2) }}</td>
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
        var body1 =
            `<!DOCTYPE html><html lang="en"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> <title>print</title> <style>.account{border: 2px solid black; width: 100%; color: black; font-size: 18px;}.accountTd{border: 1px solid rgb(216, 204, 204); border-right: 2px solid black; padding: 3px;}.borderTd{border-bottom: 1px solid black;}.borderTop{border-top: 1.1px solid black;}.tdBold{border-right: 3px solid black;}.tdWidth{width: 150px;}.borderBottom{border-bottom: 2px solid black;}.subButton{margin-top: 29px;}.dash{border-bottom: 4px solid black}.tdright{text-align: right;}.tdcenter{text-align: center;}.bgtd{background-color: rgb(227, 227, 227);}.borderBottom2{border-bottom: 1.1px solid black;}</style></head><body>`;
        var body2 = $('#printtrialBalanceTable').html();
        var body3 = `</body></html>`;

        var mywindow = window.open('', 'PRINT');
        mywindow.document.write(body1 + body2 + body3);
        mywindow.focus();
        mywindow.print();
        mywindow.close();
    }
</script>
