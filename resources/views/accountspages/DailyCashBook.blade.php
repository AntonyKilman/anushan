@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('Daily Cash Book', 'active')

<?php
$Access = session()->get('Access');
?>
@section('content')
    <section class="section" id="profit_loss">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header "> Daily Cash Book
                                </h4>
                            </div>
                        </div>

                        <div class="card-body">

                            <form action="/Accounts/ShowDailyCash" method="get">
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

                                        <button class="btn btn-success mr-3 subButton" id="add"
                                            type="submit">Submit</button>
                                        <button class="btn btn-primary" id="printTable" onclick="printContent();"
                                            style="margin-top:29px">Print</button>
                                    </div>
                                </div>
                            </form>


                            <div class="table-responsive" id="dailyCashTable">

                                <table class="account">

                                    <thead>
                                        <tr>
                                            <td class="borderBottom accountTd">Description</td>
                                            <td class="tdWidth borderBottom accountTd">(Rs.)</td>
                                            <td class="tdWidth borderBottom accountTd tdright">Cr-(Rs.)</td>
                                            <td class="tdWidth tdBold borderBottom accountTd tdright">De-(Rs.)</td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Income</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"> Day Start Amount </td>
                                            <td class="tdWidth accountTd borderTd"> {{ number_format($Closed_Balance, 2) }}
                                            </td>
                                            <td class="tdWidth  tdright accountTd">
                                                {{ number_format($Closed_Balance, 2) }}
                                            </td>
                                            <td class="tdBold accountTd"></td>

                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Advance</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>
                                        @foreach ($Advance_Amount as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->name }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd ">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth tdright accountTd ">
                                                        {{ number_format($Advance_Amount->sum('amount'), 2) }}</td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @else
                                                    <td class="tdWidth accountTd"> {{ number_format($item->amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Sales</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"> Cash Sale </td>
                                            <td class="tdWidth accountTd"> {{ number_format($Cash_sale, 2) }}</td>
                                            <td class="tdWidth  tdright accountTd"></td>
                                            <td class="tdWidth tdBold tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"> Card Sale </td>
                                            <td class="tdWidth accountTd"> {{ number_format($Card_sale, 2) }}</td>
                                            <td class="tdWidth  tdright accountTd"></td>
                                            <td class="tdWidth tdBold tdright accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"> Cheque Sale </td>
                                            <td class="tdWidth accountTd borderTd"> {{ number_format($Cheque_sale, 2) }}
                                            </td>
                                            <td class="tdWidth  tdright accountTd">
                                                {{ number_format($Cheque_sale + $Card_sale + $Cash_sale, 2) }}
                                            </td>
                                            <td class="tdWidth tdBold tdright accountTd"></td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Card Commission</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Card_Commission as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->name . -$item->account_no }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd ">
                                                        {{ number_format($Card_Commission->sum('amount'), 2) }}</td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @else
                                                    <td class="tdWidth accountTd "> {{ number_format($item->amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Credit Payment</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Credit_Payment as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->name }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->credit_cash, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd ">
                                                        {{ number_format($Credit_Payment->sum('credit_cash'), 2) }}</td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->credit_cash, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Bank Withdraw</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Bank_Withdraw as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->name . -$item->account_no }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd ">
                                                        {{ number_format($Bank_Withdraw->sum('amount'), 2) }}</td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @else
                                                    <td class="tdWidth accountTd "> {{ number_format($item->amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Cheque Income</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Cheque_Income as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->name . -$item->account_no }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd ">
                                                        {{ number_format($Cheque_Income->sum('amount'), 2) }}</td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @else
                                                    <td class="tdWidth accountTd "> {{ number_format($item->amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Other Income</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($other_income_details as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->categeory_name }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd ">
                                                        {{ number_format($other_income_details->sum('amount'), 2) }}</td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @else
                                                    <td class="tdWidth accountTd"> {{ number_format($item->amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Owner Transaction Credit</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($owner_transaction_credit as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->owner_name }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->owner_tran_credit_amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd ">
                                                        {{ number_format($owner_transaction_credit->sum('owner_tran_credit_amount'), 2) }}
                                                    </td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->owner_tran_credit_amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                            <h6><b><u>Bank</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Cheque Return</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($cheque_return as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->date . -$item->cheque_no }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->cheque_return_credit, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd "></td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($cheque_return->sum('cheque_return_credit'), 2) }}
                                                    </td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->cheque_return_credit, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Mukunthan Cash</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($mukunthan as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->date }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd "></td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($mukunthan->sum('amount'), 2) }}</td>
                                                @else
                                                    <td class="tdWidth accountTd "> {{ number_format($item->amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Bank Deposit</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Bank_Deposit as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->name . -$item->account_no }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd ">
                                                    </td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($Bank_Deposit->sum('amount'), 2) }}</td>
                                                @else
                                                    <td class="tdWidth accountTd "> {{ number_format($item->amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Expence</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Purchase Amount</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Purchase_Amount as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->seller_name . - $item->pur_ord_bill_no}} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->pur_ord_cash, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd ">
                                                    </td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($Purchase_Amount->sum('pur_ord_cash'), 2) }}</td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->pur_ord_cash, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>GR Credit Payment </b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Gr_Credit_Payment_Cash as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->pur_ord_bill_no }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->cash_amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd "></td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($Gr_Credit_Payment_Cash->sum('cash_amount'), 2) }}
                                                    </td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->cash_amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        @foreach ($Gr_Credit_Payment_Cheque as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->cheque_number }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->cheque_amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd "></td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($Gr_Credit_Payment_Cheque->sum('cheque_amount'), 2) }}
                                                    </td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->cheque_amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Other Expence </u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Other Expence cash</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Other_Expence_Cash as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->sub_categeory_name }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->oth_exp_cash, 2) }}</td>
                                                    <td class="tdWidth tdright accountTd "></td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($Other_Expence_Cash->sum('oth_exp_cash'), 2) }}
                                                    </td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->oth_exp_cash, 2) }}</td>
                                                    <td class="tdWidth tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Other Expence cheque</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Other_Expence__Cheque as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->categeory_name }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->oth_exp_cheque, 2) }}</td>
                                                    <td class="tdWidth tdright accountTd "></td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($Other_Expence__Cheque->sum('oth_exp_cheque'), 2) }}
                                                    </td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->oth_exp_cheque, 2) }}</td>
                                                    <td class="tdWidth tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Sales Return Amount</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($Return_Amount as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->invoice_no }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd "></td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($Return_Amount->sum('amount'), 2) }}</td>
                                                @else
                                                    <td class="tdWidth accountTd "> {{ number_format($item->amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b>Owner Transaction Debit</b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>

                                        @foreach ($owner_transaction_debit as $item)
                                            <tr>
                                                <td class="accountTd">{{ $item->owner_name }} </td>
                                                @if ($loop->last)
                                                    <td class="tdWidth accountTd borderTd">
                                                        {{ number_format($item->owner_tran_debit_amount, 2) }}</td>
                                                    <td class="tdWidth  tdright accountTd "></td>
                                                    <td class="tdWidth tdBold tdright accountTd">
                                                        {{ number_format($owner_transaction_debit->sum('owner_tran_debit_amount'), 2) }}
                                                    </td>
                                                @else
                                                    <td class="tdWidth accountTd ">
                                                        {{ number_format($item->owner_tran_debit_amount, 2) }}
                                                    </td>
                                                    <td class="tdWidth  tdright accountTd"></td>
                                                    <td class="tdWidth tdBold tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class=" accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                        </tr>
                                        @php
                                            $totalIncome = $Closed_Balance + $Advance_Amount->sum('amount') + $Cash_sale + $Card_sale + $Cheque_sale + $Card_Commission->sum('amount') + $Credit_Payment->sum('credit_cash') + $Bank_Withdraw->sum('amount') + $other_income_details->sum('amount') + $owner_transaction_credit->sum('owner_tran_credit_amount');
                                            $totalExpence = $cheque_return->sum('cheque_return_credit') + $mukunthan->sum('amount') + $Bank_Deposit->sum('amount') + $Purchase_Amount->sum('pur_ord_cash') + $Gr_Credit_Payment_Cash->sum('cash_amount') + $Gr_Credit_Payment_Cheque->sum('cheque_amount') + $Other_Expence_Cash->sum('oth_exp_cash') + $Other_Expence__Cheque->sum('oth_exp_cheque') + $Return_Amount->sum('amount') + $owner_transaction_debit->sum('owner_tran_debit_amount');
                                            $NetAmount = $totalIncome - $totalExpence;
                                        @endphp

                                        <tr>
                                            <td class="accountTd"><strong>Total </strong></td>
                                            <td class=" accountTd"></td>
                                            <td class=" tdright accountTd"><strong>{{ number_format ($totalIncome,2) }}</strong></td>
                                            <td class="tdBold accountTd tdright"><strong>({{ number_format ($totalExpence,2) }})</strong></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"><strong>Total Net Amount</strong></td>
                                            <td class="accountTd"></td>
                                            @if ($NetAmount < 0)
                                                <td class=" tdright accountTd" ></td>
                                            <td class="tdBold tdright accountTd tdright" style="color: red"><strong>{{ number_format($NetAmount,2) }}</strong></td>
                                            @else
                                                <td class=" tdright accountTd" style="color: green">
                                                    <strong>{{ number_format($NetAmount,2) }}</strong>
                                                </td>
                                            <td class="accountTd tdBold tdright tdright"></td>
                                            @endif
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
        var body2 = $('#dailyCashTable').html();
        var body3 = `</body></html>`;

        var mywindow = window.open('', 'PRINT');
        mywindow.document.write(body1 + body2 + body3);
        mywindow.focus();
        mywindow.print();
        mywindow.close();
    }
</script>
