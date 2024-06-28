@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('balanceSheet', 'active')
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
                                <h4 class="header ">{{ $dept }} Statement of Financial Position
                                    {{ $to }}
                                </h4>
                            </div>
                        </div>

                        <div class="card-body">

                            <form action="/account-balance/balance/{{ $id }}" method="get">
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


                            <div class="table-responsive">
                                <table class="account">
                                    <thead>
                                        <tr>
                                            <td class="borderBottom accountTd">Description</td>
                                            <td class="tdWidth borderBottom accountTd">Cost<br><br>(Rs.)</td>
                                            <td class="tdWidth borderBottom accountTd">Accumulated Depreciation<br>(Rs.)
                                            </td>
                                            <td class="tdWidth borderBottom accountTd">Carrying <br>Value<br>(Rs.)</td>
                                        </tr>
                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Non Current Assets</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>
                                        @php
                                            $total_current_cost = 0.0;
                                            $total_deprection_cost = 0.0;
                                        @endphp

                                        @if (count($non_current_collection) > 0)

                                            @foreach ($non_current_collection as $assets)
                                                <tr>
                                                    <td class="accountTd">{{ $assets->product_cat_name }}</td>
                                                    @php
                                                        $total_current_cost += $assets->debit;
                                                        $total_deprection_cost += $assets->deprecian_amount;
                                                    @endphp
                                                    @if ($non_current_collection[count($non_current_collection) - 1] == $assets)
                                                        <td class="tdWidth borderTd tdright accountTd">
                                                            {{ number_format($assets->debit, 2) }}</td>
                                                        <td class="tdWidth borderTd tdright accountTd">
                                                            {{ number_format($assets->deprecian_amount, 2) }}</td>
                                                        <td class="tdWidth borderTd tdright accountTd">
                                                            {{ number_format($assets->debit - $assets->deprecian_amount, 2) }}
                                                        </td>
                                                    @else
                                                        <td class="tdWidth tdright accountTd">
                                                            {{ number_format($assets->debit, 2) }}</td>
                                                        <td class="tdWidth tdright accountTd">
                                                            {{ number_format($assets->deprecian_amount, 2) }}</td>
                                                        <td class="tdWidth tdright accountTd">
                                                            {{ number_format($assets->debit - $assets->deprecian_amount, 2) }}
                                                        </td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="accountTd">No Non Current Assets</td>
                                                <td class="tdWidth tdright accountTd">0.00</td>
                                                <td class="tdWidth tdright accountTd">0.00 </td>
                                                <td class="tdWidth tdright accountTd">0.00</td>
                                            </tr>
                                        @endif

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="dash tdright accountTd">{{ number_format($total_current_cost, 2) }}
                                            </td>
                                            <td class="dash tdright accountTd">
                                                {{ number_format($total_deprection_cost, 2) }}</td>
                                            <td class="tdright accountTd">
                                                {{ number_format($total_current_cost - $total_deprection_cost, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Current Assets</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Stocks</td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth tdright accountTd">
                                                {{ number_format($final_stock, 2) }}
                                            </td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Debtors<button type="submit"
                                                    class="btn btn-info btn-edit" id="foodcityDebtors" style="float: right"
                                                    data-toggle="modal" data-target="#debtorsModal"><i
                                                        class="far fa-eye"></i></button></td>
                                            <td class="tdWidth tdright accountTd">
                                                {{ number_format($debtor, 2) }}
                                            </td>
                                            <td class="tdWidth accountTd"> </td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Provision for doubtfuldebts</td>
                                            <td class="tdWidth borderTd tdright accountTd">(0.00)</td>
                                            <td class="tdWidth tdright accountTd">
                                                {{-- {{ number_format($Debtors, 2) }} --}}
                                            </td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Insurance compensation receivable</td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth tdright accountTd"> 0.00</td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Prepaid advertising charge</td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth tdright accountTd"> 0.00</td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Cash<button type="submit" class="btn btn-info btn-edit"
                                                    id="foodcityCash" style="float: right" data-toggle="modal"
                                                    data-target="#cashModal"><i class="far fa-eye"></i></button></td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth borderTd tdright accountTd">
                                                {{ number_format($cash, 2) }}
                                            </td>
                                            <td class="tdWidth borderTd tdright accountTd">
                                                {{ number_format($current_assets, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"><b>Total Assets</b></td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth accountTd"> </td>
                                            <td class="tdWidth dash tdright accountTd">
                                                {{ number_format($total_assets, 2) }}
                                            </td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Equity & Liabilities</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Capital</td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd">0.00</td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">+Net Profit</td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd">
                                                {{ number_format($net_profit, 2) }}
                                            </td>
                                            <td class="accountTd"></td>
                                        </tr>


                                        <tr>
                                            <td class="accountTd">-Drawings</td>
                                            <td class="accountTd"></td>
                                            <td class="borderTd tdright accountTd">
                                               0.00
                                            </td>
                                            <td class="tdright accountTd">
                                                {{ number_format($equity_and_liabilities, 2) }}
                                            </td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Current Liabilities</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Creditors</td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd">0.00</td>
                                            <td class="accountTd"></td>
                                        </tr>
                                        {{-- for empty row start --}}
                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>
                                        {{-- for empty row end --}}

                                        {{-- -------------------- other expense start----------- --}}
                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Expenses</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        @if (count($accured_exp_collection) > 0)
                                            @foreach ($accured_exp_collection as $row)
                                                {{-- expense category name --}}
                                                <tr>
                                                    <td class="accountTd">
                                                        <h6><b>{{ $row->cat_name }}</b></h6>
                                                    </td>
                                                    <td class="accountTd"></td>
                                                    <td class=" accountTd"></td>
                                                    <td class="accountTd"></td>
                                                </tr>
                                                @php
                                                    $exp_sub_totals = 0.00;
                                                @endphp
                                                @foreach ($row->datas as $subcat)
                                                @php
                                                    $exp_sub_totals+=$subcat->ACCURED_SERVICES;
                                                @endphp
                                                    {{-- expense sub category name --}}
                                                    <tr>
                                                        <td class="accountTd">Accured {{ $subcat->sub_cat_name }}</td>

                                                        {{-- this if use to css  and unique to last element --}}
                                                        @if ($row->datas[count($row->datas) - 1] == $subcat)
                                                            <td class="accountTd tdright borderTd">
                                                                {{ number_format($subcat->ACCURED_SERVICES, 2) }}</td>
                                                            <td class="accountTd tdright"> {{ number_format($exp_sub_totals, 2) }}</td>
                                                            <td class="accountTd tdright"></td>
                                                        @else
                                                            <td class="accountTd tdright">
                                                                {{ number_format($subcat->ACCURED_SERVICES, 2) }}</td>
                                                            <td class="accountTd tdright"></td>
                                                            <td class="accountTd tdright"></td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="accountTd">No Other Expenses</td>
                                                <td class="accountTd tdright">0.00</td>
                                                <td class="tdright accountTd">0.00</td>
                                                <td class="accountTd tdright">0.00</td>
                                            </tr>

                                        @endif
                                        {{-- -------------------- other expense end----------- --}}

                                        {{-- for empty row start --}}
                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>
                                        {{-- for empty row end --}}

                                        {{-- -------------------- service expense start----------- --}}
                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Service Expense</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        @php
                                            $ser_accur_total =0.00;
                                        @endphp

                                        @if (count($accured_service_types) > 0)
                                            @foreach ($accured_service_types as $row)
                                            @php
                                                $ser_accur_total+=$row->ACCURED_SERVICES;
                                            @endphp
                                                <tr>
                                                    <td class="accountTd">Accured {{ $row->name }}</td>
                                                    @if ($accured_service_types[count($accured_service_types) - 1] == $row)
                                                        <td class="accountTd tdright borderTd">
                                                            {{ number_format($row->ACCURED_SERVICES, 2) }}</td>
                                                        <td class="tdright accountTd borderTd">
                                                            {{ number_format($ser_accur_total, 2) }}
                                                        </td>
                                                        <td class="accountTd tdright borderTd">
                                                            {{ number_format($accur_oth_expen, 2) }}
                                                        </td>
                                                    @else
                                                        <td class="accountTd tdright">
                                                            {{ number_format($row->ACCURED_SERVICES, 2) }}</td>
                                                        <td class="tdright accountTd"></td>
                                                        <td class="accountTd"></td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="accountTd">No Service Expenses</td>
                                                <td class="accountTd">0.00</td>
                                                <td class="tdright accountTd">0.00</td>
                                                <td class="accountTd">0.00</td>
                                            </tr>
                                        @endif
                                        {{-- -------------------- service expense end----------- --}}

                                        <tr>
                                            <td class="accountTd"><b>Total Equility and Libalities</b></td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth accountTd"> </td>
                                            <td class="tdWidth dash tdright accountTd">
                                                {{ number_format($total_equity_and_liabilities, 2) }}
                                            </td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#debtors_table').DataTable();
        });
    </script>
@endsection


@section('model')

    {{-- ------------ cash Modal start ----------------------- --}}
    <div class="modal fade" id="cashModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title row" id="exampleModalLabel">
                        <div style="margin-left: 50px">{{ $dept }} Cash </div>
                        <div style="margin-left: 550px"> Total : {{ number_format($cash, 2) }}</div>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Date</th>
                                        <th style="text-align: center">Description</th>
                                        @switch($id)
                                            {{-- foodcity --}}
                                            @case(1)
                                                <th style="text-align: center">Note</th>
                                                <th style="text-align: center">Credit</th>
                                                <th style="text-align: center">Debit</th>
                                                <th style="text-align: center">Cash</th>
                                                <th style="text-align: center">Card</th>
                                                <th style="text-align: center">Cheque</th>
                                                <th style="text-align: center">Customer</th>
                                            @break

                                            {{-- front office --}}
                                            @case(3)
                                                <th style="text-align: center">Note</th>
                                                <th style="text-align: center">Credit</th>
                                                <th style="text-align: center">Debit</th>
                                                <th style="text-align: center">Cash</th>
                                                <th style="text-align: center">Card</th>
                                                <th style="text-align: center">Cheque</th>
                                                <th style="text-align: center">Customer</th>
                                            @break

                                            {{-- inventory --}}
                                            @case(6)
                                                <th style="text-align: center">Product</th>
                                                <th style="text-align: center">Credit</th>
                                                <th style="text-align: center">Debit</th>
                                            @break

                                            {{-- Restaurant --}}
                                            @case(8)
                                                <th style="text-align: center">Note</th>
                                                <th style="text-align: center">Credit</th>
                                                <th style="text-align: center">Debit</th>
                                                <th style="text-align: center">Cash</th>
                                                <th style="text-align: center">Card</th>
                                                <th style="text-align: center">Cheque</th>
                                                <th style="text-align: center">Customer</th>
                                            @break

                                            {{-- Kurinchi One --}}
                                            @case(9)
                                                <th style="text-align: center">Note</th>
                                                <th style="text-align: center">Credit</th>
                                                <th style="text-align: center">Debit</th>
                                                <th style="text-align: center">Cash</th>
                                                <th style="text-align: center">Card</th>
                                                <th style="text-align: center">Cheque</th>
                                                <th style="text-align: center">Customer</th>
                                            @break

                                            {{-- Kurinchi --}}
                                            @case(10)
                                                <th style="text-align: center">Note</th>
                                                <th style="text-align: center">Credit</th>
                                                <th style="text-align: center">Debit</th>
                                                <th style="text-align: center">Cash</th>
                                                <th style="text-align: center">Card</th>
                                                <th style="text-align: center">Cheque</th>
                                                <th style="text-align: center">Customer</th>
                                            @break

                                            {{-- Pizza --}}
                                            @case(11)
                                                <th style="text-align: center">Note</th>
                                                <th style="text-align: center">Credit</th>
                                                <th style="text-align: center">Debit</th>
                                                <th style="text-align: center">Cash</th>
                                                <th style="text-align: center">Card</th>
                                                <th style="text-align: center">Cheque</th>
                                                <th style="text-align: center">Customer</th>
                                            @break

                                            @default
                                        @endswitch

                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($cash_datas as $item)
                                        <tr>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->description }}</td>
                                            @switch($id)
                                                {{-- foodcity --}}
                                                @case(1)
                                                    <td>{{ $item->detail }}</td>
                                                    <td style="text-align: right">{{ number_format($item->credit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cheque, 2) }}</td>
                                                    @if ($item->name == 'null')
                                                        <td></td>
                                                    @else
                                                        <td>{{ $item->name }}</td>
                                                    @endif
                                                @break

                                                {{-- front office --}}
                                                @case(3)
                                                    @if ($item->category == 22)
                                                        <td>{{ $item->events }}</td>
                                                    @elseif ($item->category == 23 || 24)
                                                        <td>{{ $item->games }}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td style="text-align: right">{{ number_format($item->credit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cheque, 2) }}</td>
                                                    @if ($item->name == 'null')
                                                        <td></td>
                                                    @else
                                                        <td>{{ $item->name }}</td>
                                                    @endif
                                                @break

                                                {{-- Inventory --}}
                                                @case(6)
                                                    <td>{{ $item->product_name }}</td>
                                                    <td style="text-align: right">{{ number_format($item->credit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                @break

                                                {{-- Restaurant --}}
                                                @case(8)
                                                    @if ($item->description == 'Inventory Transfer' || $item->description == 'Inventory Return')
                                                        <td>{{ $item->detail }}</td>
                                                    @else
                                                        @if ($item->category == 3)
                                                            <td>Customer</td>
                                                        @elseif ($item->category == 1)
                                                            <td>Employee</td>
                                                        @elseif ($item->category == 2)
                                                            <td>Hotel Customer</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                    <td style="text-align: right">{{ number_format($item->credit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cheque, 2) }}</td>
                                                    @if ($item->category == 3)
                                                        @if ($item->name == 'null')
                                                            <td></td>
                                                        @else
                                                            <td>{{ $item->name }}</td>
                                                        @endif
                                                    @elseif ($item->category == 1)
                                                        <td>{{ $item->emp_name }}</td>
                                                    @elseif ($item->category == 2)
                                                        <td>{{ $item->sub_category }}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @break




                                                {{-- Kurinchi One --}}
                                                @case(9)
                                                    @if ($item->description == 'Inventory Transfer' || $item->description == 'Inventory Return')
                                                        <td>{{ $item->detail }}</td>
                                                    @else
                                                        @if ($item->category == 3)
                                                            <td>Customer</td>
                                                        @elseif ($item->category == 1)
                                                            <td>Employee</td>
                                                        @elseif ($item->category == 2)
                                                            <td>Hotel Customer</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                    <td style="text-align: right">{{ number_format($item->credit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cheque, 2) }}</td>
                                                    @if ($item->category == 3)
                                                        @if ($item->name == 'null')
                                                            <td></td>
                                                        @else
                                                            <td>{{ $item->name }}</td>
                                                        @endif
                                                    @elseif ($item->category == 1)
                                                        <td>{{ $item->emp_name }}</td>
                                                    @elseif ($item->category == 2)
                                                        <td>{{ $item->sub_category }}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @break


                                                {{-- Kurinchi --}}
                                                @case(10)
                                                    @if ($item->description == 'Inventory Transfer' || $item->description == 'Inventory Return')
                                                        <td>{{ $item->detail }}</td>
                                                    @else
                                                        @if ($item->category == 3)
                                                            <td>Customer</td>
                                                        @elseif ($item->category == 1)
                                                            <td>Employee</td>
                                                        @elseif ($item->category == 2)
                                                            <td>Hotel Customer</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                    <td style="text-align: right">{{ number_format($item->credit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cheque, 2) }}</td>
                                                    @if ($item->category == 3)
                                                        @if ($item->name == 'null')
                                                            <td></td>
                                                        @else
                                                            <td>{{ $item->name }}</td>
                                                        @endif
                                                    @elseif ($item->category == 1)
                                                        <td>{{ $item->emp_name }}</td>
                                                    @elseif ($item->category == 2)
                                                        <td>{{ $item->sub_category }}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @break

                                                {{-- Pizza --}}
                                                @case(11)
                                                    @if ($item->description == 'Inventory Transfer' || $item->description == 'Inventory Return')
                                                        <td>{{ $item->detail }}</td>
                                                    @else
                                                        @if ($item->category == 3)
                                                            <td>Customer</td>
                                                        @elseif ($item->category == 1)
                                                            <td>Employee</td>
                                                        @elseif ($item->category == 2)
                                                            <td>Hotel Customer</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                    <td style="text-align: right">{{ number_format($item->credit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                    <td style="text-align: right">{{ number_format($item->cheque, 2) }}</td>
                                                    @if ($item->category == 3)
                                                        @if ($item->name == 'null')
                                                            <td></td>
                                                        @else
                                                            <td>{{ $item->name }}</td>
                                                        @endif
                                                    @elseif ($item->category == 1)
                                                        <td>{{ $item->emp_name }}</td>
                                                    @elseif ($item->category == 2)
                                                        <td>{{ $item->sub_category }}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @break

                                                @default
                                            @endswitch


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
    {{-- ----------------- cash Modal end ------------------------------ --}}

    {{-- ------------ Debtors Modal start -------------------------}}
    <div class="modal fade" id="debtorsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title row" id="exampleModalLabel">
                        <div style="margin-left: 50px">{{ $dept }} Debtors </div>
                        <div style="margin-left: 550px"> Total : {{ number_format($debtor, 2) }}</div>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="debtors_table">
                                <thead>
                                    <tr>
                                        {{-- Restaurant --}}
                                        @if ($id == 8)
                                            <th style="text-align: center">Type</th>
                                        @endif
                                        <th style="text-align: center">Customer</th>
                                        <th style="text-align: center">Total</th>
                                        <th style="text-align: center">Paid</th>
                                        <th style="text-align: center">Payable</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($debtor_datas as $item)
                                        <tr>
                                            @if ($id == 8)
                                                @if ($item->category == 3)
                                                    @if ($item->name == 'null')
                                                        <td></td>
                                                    @else
                                                        <td>{{ $item->name }}</td>
                                                    @endif
                                                @elseif ($item->category == 1)
                                                    <td>{{ $item->f_name }}</td>
                                                @elseif ($item->category == 2)
                                                    <td>{{ $item->sub_category }}</td>
                                                @endif
                                            @else
                                                @if ($item->name == 'null')
                                                    <td></td>
                                                @else
                                                    <td>{{ $item->name }}</td>
                                                @endif
                                            @endif


                                            {{-- Restaurant --}}
                                            @if ($id == 8)
                                                @if ($item->category == 3)
                                                    <td>Customer</td>
                                                @elseif ($item->category == 1)
                                                    <td>Employee</td>
                                                @elseif ($item->category == 2)
                                                    <td>Hotel Customer</td>
                                                @endif
                                            @endif



                                            <td style="text-align: right">{{ number_format($item->TOTAL_DEBIT, 2) }}</td>
                                            <td style="text-align: right">{{ number_format($item->TOTAL_CREDIT, 2) }}</td>
                                            <td style="text-align: right">
                                                {{ number_format($item->TOTAL_DEBIT - $item->TOTAL_CREDIT, 2) }}</td>
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
    {{-- ----------------- Debtors Modal end ------------------------------ --}}

@endsection
