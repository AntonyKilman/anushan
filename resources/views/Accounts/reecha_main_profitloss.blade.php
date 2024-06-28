@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('content')
@section('profitloss', 'active')

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
                                {{-- {{ $dept }} --}}
                                 Income Statement for Ending
                                {{-- {{ $to }} --}}
                            </h4>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- <form action="/account-balance/profit_loss/{{ $id }}" method="get"> --}}
                            @csrf

                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>From</label>
                                        {{-- <input type="date" class="form-control" name="from" id="from"
                                            value="{{ $from }}"> --}}
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>To</label>
                                        {{-- <input type="date" class="form-control" name="to" id="to"
                                            value="{{ $to }}"> --}}
                                    </div>
                                </div>

                                <div class="col-3">

                                    <button class="btn btn-success mr-1 subButton" id="add"
                                        type="submit">Submit</button>
                                </div>

                                <div class="col-3">
                                    <button class="btn btn-primary" id="printTable"
                                        style="float: right; margin-top:30px">Print</button>
                                </div>

                            </div>
                        </form>


                        <div class="table-responsive">

                            <table class="account">

                                <thead>
                                    <tr>
                                        <td class="borderBottom accountTd">Description</td>
                                        <td class="tdWidth borderBottom accountTd">(Rs.)</td>
                                        <td class="tdWidth tdBold borderBottom accountTd">(Rs.)</td>
                                        <td class="tdWidth borderBottom accountTd">(Rs.)</td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd"> Sales <button type="submit"
                                                class="btn btn-info btn-edit" id="sales" style="float: right"
                                                data-toggle="modal" data-target="#salesModal"><i
                                                    class="far fa-eye"></i></button></td>
                                        <td class="tdWidth accountTd"></td>
                                        <td class="tdWidth tdBold tdright accountTd">
                                            {{-- {{ number_format($sale, 2) }} --}}
                                        </td>
                                        <td class="tdWidth accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">-Sales Return<button type="submit"
                                                class="btn btn-info btn-edit" id="SaleReturn" style="float: right"
                                                data-toggle="modal" data-target="#saleReturnModal"><i
                                                    class="far fa-eye"></i></button></td>
                                        <td class="accountTd"></td>
                                        <td class="borderTd tdBold tdright accountTd">
                                            {{-- ({{ number_format($sales_return, 2) }}) --}}
                                        </td>
                                        <td class="tdright accountTd">
                                            {{-- {{ number_format($sale - $sales_return, 2) }} --}}
                                        </td>
                                    </tr>

                                    <tr style="height:30px">
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">
                                            <h6><b><u>Cost of Sales</u></b></h6>
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">Stock at
                                            {{-- {{ $from }} --}}
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold tdright accountTd">
                                            {{-- {{ number_format($basic_stock, 2) }} --}}
                                        </td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">Purchases<button type="submit"
                                                class="btn btn-info btn-edit" style="float: right" data-toggle="modal"
                                                data-target="#purchaseModal"><i class="far fa-eye"></i></button></td>
                                        <td class="tdright accountTd">
                                            {{-- {{ number_format($purchase, 2) }} --}}
                                        </td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>


                                    <tr>
                                        <td class="accountTd">+Carriage inwards</td>
                                        <td class="borderTd tdright accountTd">0.00
                                        </td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd"></td>
                                        <td class="tdright accountTd">
                                            {{-- {{ number_format($purchase, 2) }} --}}
                                        </td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">-Purchase Return<button type="submit"
                                                class="btn btn-info btn-edit" id="foodcityPurchasesReturn"
                                                style="float: right" data-toggle="modal"
                                                data-target="#purchaseReturnModal"><i class="far fa-eye"></i></button>
                                        </td>
                                        <td class="tdright accountTd">
                                            {{-- ({{ number_format($purchase_return, 2) }}) --}}
                                        </td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">-Stock Damage</td>
                                        <td class="tdright accountTd">0.00 </td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">-Stock Drawings</td>
                                        <td class="borderTd tdright accountTd">0.00</td>
                                        <td class="borderTd tdBold tdright accountTd"> </td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold tdright accountTd">
                                            {{-- {{ number_format($purchase_in, 2) }} --}}
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">-Stock at
                                            {{-- {{ $to }} --}}
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="borderTd tdBold tdright accountTd">
                                            {{-- ({{ number_format($final_stock, 2) }}) --}}
                                        </td>
                                        <td class="borderTd tdright accountTd">
                                            {{-- ({{ number_format($cost_of_sales, 2) }}) --}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="accountTd">Gross Profit</td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold tdright accountTd"></td>
                                        <td class="tdright accountTd">
                                            {{-- {{ number_format($gross_profit, 2) }} --}}
                                        </td>
                                    </tr>

                                    {{-- for empty row start --}}
                                    <tr style="height:30px">
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>
                                    {{-- for empty row end --}}

                                    <tr>
                                        <td class="accountTd">Depreciation</td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold tdright accountTd">
                                            {{-- {{ number_format($non_current_deprection, 2) }} --}}
                                        </td>
                                        <td class="tdright accountTd"></td>
                                    </tr>


                                    {{-- --------------other expense start ----------------- --}}
                                    <tr>
                                        <td class="accountTd">
                                            <h6><b><u>Expenses</u></b></h6>
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    {{-- @if (count($exp_collection) > 0)
                                        @foreach ($exp_collection as $type) --}}
                                            {{-- expense category name --}}
                                            {{-- <tr>
                                                <td class="accountTd">
                                                    <h6><b>{{ $type->cat_name }}</b></h6>
                                                </td>
                                                <td class="accountTd"></td>
                                                <td class="tdBold accountTd"></td>
                                                <td class="accountTd"></td>
                                            </tr>
                                            @php
                                                $cat_exp_total = 0.00;
                                            @endphp

                                            @foreach ($type->datas as $row)
                                            @php
                                                $cat_exp_total +=$row->SERVICES;
                                            @endphp --}}
                                                {{-- expense sub category name --}}
                                                {{-- <tr>
                                                    <td class="accountTd">{{ $row->sub_cat_name }}</td> --}}
                                                    {{-- this if use to css  and unique to last element --}}
                                                    {{-- @if ($type->datas[count($type->datas) - 1] == $row)
                                                        <td class="accountTd tdright borderTd">
                                                            {{ number_format($row->SERVICES, 2) }}</td>
                                                        <td class="accountTd tdright tdBold">{{ number_format($cat_exp_total, 2) }}</td>
                                                        <td class="accountTd"></td>
                                                    @else
                                                        <td class="accountTd tdright">
                                                            {{ number_format($row->SERVICES, 2) }}</td>
                                                        <td class="accountTd tdBold"></td>
                                                        <td class="accountTd"></td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        @endforeach --}}
                                    {{-- @else
                                        <tr>
                                            <td class="accountTd ">No Expense</td>
                                            <td class="tdBold tdright accountTd borderBottom2">0.00</td>
                                            <td class="tdBold tdright accountTd borderBottom2">0.00</td>
                                            <td class="tdBold tdright accountTd">0.00</td>
                                        </tr>
                                    @endif --}}
                                    {{-- -------------------------other expense end ----------------- --}}


                                    {{-- for empty row start --}}
                                    <tr style="height:30px">
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>
                                    {{-- for empty row end --}}

                                    {{-- -------------------- service expense start----------- --}}
                                    <tr>
                                        <td class="accountTd">
                                            <h6><b><u>Service Expense</u></b></h6>
                                        </td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
                                        <td class="accountTd"></td>
                                    </tr>

                                    @php
                                        $ser_exp = 0.00;
                                    @endphp

                                    {{-- @if (count($service_types) > 0)
                                        @foreach ($service_types as $type)
                                        @php
                                            $ser_exp += $type->SERVICES;
                                        @endphp
                                            <tr>
                                                <td class="accountTd">{{ $type->name }}
                                                    <button type="submit"
                                                        class="btn btn-info btn-edit service_exp"
                                                        oth-exp-id="{{ $type->id }}"
                                                        oth-exp-amount="{{ $type->SERVICES }}"
                                                        oth-exp-name="{{ $type->name }}" style="float: right"
                                                        data-toggle="modal" data-target="#serviceExpenseModal"><i
                                                            class="far fa-eye"></i></button>
                                                        </td> --}}
                                                {{-- this if use to css and initialize last element --}}
                                                {{-- @if ($service_types[count($service_types) - 1] == $type)
                                                    <td class="accountTd tdright borderTd">
                                                        {{ number_format($type->SERVICES, 2) }}</td>
                                                    <td class="accountTd tdright tdBold">
                                                        {{ number_format($ser_exp, 2) }}
                                                    </td>
                                                    <td class="accountTd tdright">
                                                        ({{ number_format($total_expenses, 2) }})
                                                    </td>
                                                @else
                                                    <td class="accountTd tdright">
                                                        {{ number_format($type->SERVICES, 2) }}</td>
                                                    <td class="accountTd tdBold"> </td>
                                                    <td class="accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach --}}
                                    {{-- @else
                                        <tr>
                                            <td class="accountTd ">No Services</td>
                                            <td class="tdBold tdright accountTd borderBottom2">0.00</td>
                                            <td class="tdBold tdright accountTd borderBottom2">0.00</td>
                                            <td class="tdBold tdright accountTd">0.00</td>
                                        </tr>
                                    @endif --}}


                                    {{-- -------------------- service expense end----------- --}}

                                    <tr>
                                        <td class="accountTd">Net profit taken to capital a/c</td>
                                        <td class="borderTop accountTd"></td>
                                        <td class="tdBold borderTop accountTd"></td>
                                        <td class="borderTop  dash tdright accountTd">
                                            {{-- {{ number_format($net_profit, 2) }} --}}
                                        </td>
                                    </tr>

                                    {{-- for empty row --}}
                                    <tr style="height:30px">
                                        <td class="accountTd"></td>
                                        <td class="accountTd"></td>
                                        <td class="tdBold accountTd"></td>
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


<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>

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


    });
</script> --}}

@endsection

