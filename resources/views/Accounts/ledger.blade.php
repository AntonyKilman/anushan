@extends('layouts.navigation')
@section('content')
@section('ledgers', 'active')

<?php
$Access = session()->get('Access');
?>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div style="padding: 10px;">
                        <div class="card-header-bank">
                            <h4 class="header ">{{ $heading }}</h4>
                        </div>
                    </div>



                    <div class="card-body">

                        @if ($table == 1 || $table == 4)
                            <div class="row">
                                <div class="col-4">
                                    <h5> Total Debit : {{ number_format($total_debit, 2) }}</h5>
                                </div>
                                <div class="col-4">
                                    <h5>Total Credit :{{ number_format($total_credit, 2) }}</h5>
                                </div>
                                <div class="col-4">
                                    <h5>Total:{{ number_format($total_debit - $total_credit, 2) }}</h5>
                                </div>
                            </div>
                        @elseif ($table == 2 || $table == 6)
                            <div class="row">
                                <div class="col-4">
                                    <h5> Total Amount : {{ number_format($total_credit, 2) }}</h5>
                                </div>
                            </div>
                        @elseif ($table == 3 || $table == 5)
                            <div class="row">
                                <div class="col-4">
                                    <h5> Total Amount : {{ number_format($total_debit, 2) }}</h5>
                                </div>
                            </div>
                        @endif


                        <div class="table-responsive">
                            <table class="table table-striped print_table">
                                {{-- --------------------table head  start---------------------------------- --}}
                                <thead>
                                    <tr>
                                        <th style="display: none">#</th>

                                        @switch($table)
                                            {{-- -------- cash account  start ------- --}}
                                            @case(1)
                                                {{-- ankadi --}}
                                                @if ($department == 1)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Debit</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Cash</th>
                                                    <th style="text-align: center">Card</th>
                                                    <th style="text-align: center">Cheque</th>
                                                    <th style="text-align: center">Customer</th>

                                                    {{-- front office --}}
                                                @elseif ($department == 3)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Debit</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Cash</th>
                                                    <th style="text-align: center">Card</th>
                                                    <th style="text-align: center">Cheque</th>
                                                    <th style="text-align: center">Customer</th>
                                                    {{-- restaurant --}}
                                                @elseif ($department == 8)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Debit</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>

                                                    {{-- Kurinchi --}}
                                                @elseif ($department == 8)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Debit</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>

                                                    {{-- Kurinchi One --}}
                                                @elseif ($department == 9)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Debit</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>

                                                    {{-- Pizza --}}
                                                @elseif ($department == 11)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Debit</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>
                                                @else
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Debit</th>
                                                    <th style="text-align: center">Credit</th>
                                                @endif
                                            @break

                                            {{-- -------- cash account  end ------- --}}

                                            {{-- ----------- sales account  start---- --}}
                                            @case(2)
                                                {{-- front office --}}
                                                @if ($department == 3)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Amount</th>
                                                    <th style="text-align: center">Cash</th>
                                                    <th style="text-align: center">Card</th>
                                                    <th style="text-align: center">Cheque</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>
                                                    {{-- Restaurant --}}
                                                @elseif ($department == 8)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Amount</th>
                                                    <th style="text-align: center">Cash</th>
                                                    <th style="text-align: center">Card</th>
                                                    <th style="text-align: center">Cheque</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>

                                                    {{-- Kurinchi One --}}
                                                @elseif ($department == 9)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Amount</th>
                                                    <th style="text-align: center">Cash</th>
                                                    <th style="text-align: center">Card</th>
                                                    <th style="text-align: center">Cheque</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>

                                                    {{-- Kurinchi  --}}
                                                @elseif ($department == 10)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Amount</th>
                                                    <th style="text-align: center">Cash</th>
                                                    <th style="text-align: center">Card</th>
                                                    <th style="text-align: center">Cheque</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>

                                                    {{-- Pizza --}}
                                                @elseif ($department == 11)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Amount</th>
                                                    <th style="text-align: center">Cash</th>
                                                    <th style="text-align: center">Card</th>
                                                    <th style="text-align: center">Cheque</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>
                                                @else
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Amount</th>
                                                    <th style="text-align: center">Cash</th>
                                                    <th style="text-align: center">Card</th>
                                                    <th style="text-align: center">Cheque</th>
                                                    <th style="text-align: center">Credit</th>
                                                    <th style="text-align: center">Customer</th>
                                                @endif
                                            @break

                                            {{-- ----------- sales account  end---- --}}

                                            {{-- ------------- sales return  start--- --}}
                                            @case(3)
                                                {{-- front office --}}
                                                @if ($department == 3)
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Note</th>
                                                    <th style="text-align: center">Amount</th>
                                                    <th style="text-align: center">Customer</th>
                                                @else
                                                    <th style="text-align: center">Date</th>
                                                    <th style="text-align: center">Description</th>
                                                    <th style="text-align: center">Amount</th>
                                                    <th style="text-align: center">Customer</th>
                                                @endif
                                            @break

                                            {{-- ------------- sales return  end--- --}}

                                            {{-- ------------- debtors  start----- --}}
                                            @case(4)
                                                <th style="text-align: center">Customer</th>
                                                @if ($department == 8)
                                                    <th style="text-align: center">Type</th>
                                                @endif
                                                <th style="text-align: center">Total</th>
                                                <th style="text-align: center">Paid</th>
                                                <th style="text-align: center">Payable</th>
                                            @break

                                            {{-- ------------- debtors  end----- --}}

                                            {{-- ------------- purchase  start----- --}}
                                            @case(5)
                                                <th style="text-align: center">Date</th>
                                                <th style="text-align: center">Description</th>
                                                <th style="text-align: center">Product</th>
                                                <th style="text-align: center">Amount</th>
                                            @break

                                            {{-- ------------- purchase  end----- --}}

                                            {{-- ------------- purchase return  start----- --}}
                                            @case(6)
                                                <th style="text-align: center">Date</th>
                                                <th style="text-align: center">Description</th>
                                                <th style="text-align: center">Product</th>
                                                <th style="text-align: center">Amount</th>
                                            @break

                                            {{-- ------------- purchase return end----- --}}

                                            @default
                                        @endswitch
                                    </tr>
                                </thead>

                                {{-- --------------------table head  end---------------------------------- --}}

                                {{-- --------------------table body  start---------------------------------- --}}
                                <tbody>

                                    @foreach ($datas as $item)
                                        <tr>
                                            <td style="display: none">#</td>

                                            @switch($table)
                                                {{-- ------ cash account start------------- --}}
                                                @case(1)
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->description }}</td>


                                                    {{-- Ankadi --}}
                                                    @if ($department == 1)
                                                        <td>{{ $item->product_name }}</td>
                                                        <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->credit, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->card, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->cheque, 2) }}
                                                        </td>
                                                        @if ($item->name == 'null')
                                                            <td></td>
                                                        @else
                                                            <td>{{ $item->name }}</td>
                                                        @endif

                                                        {{-- front office --}}
                                                    @elseif ($department == 3)
                                                        @if ($item->category == 22)
                                                            <td>{{ $item->events }}</td>
                                                        @elseif ($item->category == 23 || 24)
                                                            <td>{{ $item->games }}</td>
                                                        @endif

                                                        <td style="text-align: right">{{ number_format($item->debit, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->credit, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->cheque, 2) }}
                                                        </td>
                                                        @if ($item->name == 'null')
                                                            <td></td>
                                                        @else
                                                            <td>{{ $item->name }}</td>
                                                        @endif

                                                        {{-- restaurant --}}
                                                    @elseif($department == 8)
                                                        @if ($item->description == 'Inventory Transfer' || $item->description == 'Inventory Return')
                                                            <td>{{ $item->product_name }}</td>
                                                        @else
                                                            @if ($item->category == 1)
                                                                <td>Employee</td>
                                                            @elseif ($item->category == 2)
                                                                <td>HotelCustomer</td>
                                                            @elseif ($item->category == 3)
                                                                <td>Customer</td>
                                                            @endif
                                                        @endif

                                                        <td style="text-align: right">{{ number_format($item->debit, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->credit, 2) }}
                                                        </td>
                                                        @if ($item->category == 1)
                                                            <td>{{ $item->f_name }}</td>
                                                        @elseif ($item->category == 2)
                                                            <td>{{ $item->sub_category }}</td>
                                                        @elseif ($item->category == 3)
                                                            @if ($item->name == 'null')
                                                                <td></td>
                                                            @else
                                                                <td>{{ $item->name }}</td>
                                                            @endif
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @else
                                                        <td style="text-align: right">{{ number_format($item->debit, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->credit, 2) }}
                                                        </td>
                                                    @endif
                                                @break

                                                {{-- ------------------ cash account end------------- --}}

                                                {{-- ----------- sales account start------------------- --}}
                                                @case(2)
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->description }}</td>

                                                    {{-- front office --}}
                                                    @if ($department == 3)
                                                        @if ($item->category == 22)
                                                            <td>{{ $item->events }}</td>
                                                        @elseif ($item->category == 23 || 24)
                                                            <td>{{ $item->games }}</td>
                                                        @endif
                                                        <td style="text-align: right">{{ number_format($item->credit, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->cheque, 2) }}
                                                        </td>
                                                        <td style="text-align: right">
                                                            {{ number_format($item->credit_amount, 2) }}
                                                        </td>
                                                        @if ($item->name == 'null')
                                                            <td></td>
                                                        @else
                                                            <td>{{ $item->name }}</td>
                                                        @endif

                                                        {{-- Restaurant --}}
                                                    @elseif ($department == 8)
                                                        @if ($item->category == 1)
                                                            <td>Employee</td>
                                                        @elseif ($item->category == 2)
                                                            <td>Hotel Customer</td>
                                                        @elseif ($item->category == 3)
                                                            <td>Customer</td>
                                                        @endif
                                                        <td style="text-align: right">{{ number_format($item->credit, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->cheque, 2) }}
                                                        </td>
                                                        <td style="text-align: right">
                                                            {{ number_format($item->credit_amount, 2) }}
                                                        </td>
                                                        @if ($item->category == 1)
                                                            <td>{{ $item->f_name }}</td>
                                                        @elseif ($item->category == 2)
                                                            <td>{{ $item->sub_category }}</td>
                                                        @elseif ($item->category == 3)
                                                            @if ($item->name == 'null')
                                                                <td></td>
                                                            @else
                                                                <td>{{ $item->name }}</td>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <td style="text-align: right">{{ number_format($item->credit, 2) }}
                                                        </td>
                                                        <td style="text-align: right">{{ number_format($item->cash, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->card, 2) }}</td>
                                                        <td style="text-align: right">{{ number_format($item->cheque, 2) }}
                                                        </td>
                                                        <td style="text-align: right">
                                                            {{ number_format($item->credit_amount, 2) }}
                                                        </td>
                                                        @if ($item->name == 'null')
                                                            <td></td>
                                                        @else
                                                            <td>{{ $item->name }}</td>
                                                        @endif
                                                    @endif
                                                @break

                                                {{-- ----------- sales account end------------------- --}}

                                                {{-- -------------- sales return start------------------ --}}
                                                @case(3)
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    {{-- front office --}}
                                                    @if ($department == 3)
                                                        @if ($item->category == 22)
                                                            <td>{{ $item->events }}</td>
                                                        @elseif ($item->category == 23 || 24)
                                                            <td>{{ $item->games }}</td>
                                                        @endif
                                                    @endif
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                    @if ($item->name == 'null')
                                                        <td></td>
                                                    @else
                                                        <td>{{ $item->name }}</td>
                                                    @endif
                                                @break

                                                {{-- -------------- sales return end------------------ --}}

                                                {{-- ---------------- credit  start----------------- --}}
                                                @case(4)
                                                    @if ($item->name == 'null')
                                                        <td></td>
                                                    @else
                                                        <td>{{ $item->name }}</td>
                                                    @endif

                                                    @if ($department == 8)
                                                        @if ($item->category == 1)
                                                            <td style="text-align: center">Employee</th>
                                                            @elseif ($item->category == 2)
                                                            <td style="text-align: center">Hotel Customer</th>
                                                            @elseif ($item->category == 3)
                                                            <td style="text-align: center">Customer</th>
                                                        @endif
                                                    @endif
                                                    <td style="text-align: right">{{ number_format($item->TOTAL_DEBIT, 2) }}
                                                    </td>
                                                    <td style="text-align: right">{{ number_format($item->TOTAL_CREDIT, 2) }}
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{ number_format($item->TOTAL_DEBIT - $item->TOTAL_CREDIT, 2) }}</td>
                                                @break

                                                {{-- ---------------- credit  end----------------- --}}

                                                {{-- ---------------- purchase  start----------------- --}}
                                                @case(5)
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->product_name }}</td>
                                                    <td style="text-align: right">{{ number_format($item->debit, 2) }}</td>
                                                @break

                                                {{-- ---------------- purchase  end----------------- --}}

                                                {{-- ---------------- purchase  return start----------------- --}}
                                                @case(6)
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->product_name }}</td>
                                                    <td style="text-align: right">{{ number_format($item->credit, 2) }}</td>
                                                @break

                                                {{-- ---------------- purchase return end----------------- --}}

                                                @default
                                            @endswitch


                                        </tr>
                                    @endforeach
                                </tbody>

                                {{-- --------------------table body  end---------------------------------- --}}


                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
