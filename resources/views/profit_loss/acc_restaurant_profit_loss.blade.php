@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('account_profit_loss_restaurants', 'active')
@section('content')

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
                                <h4 class="header ">Restaurant<br>Income Statement for Ending {{ $to }}</h4>

                            </div>
                        </div>

                        <div class="card-body">

                            <form action="/account_profit_loss_restaurants/profitLoss" method="get">
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
                                            <td class="tdWidth borderBottom accountTd">(Rs.)</td>
                                            <td class="tdWidth tdBold borderBottom accountTd">(Rs.)</td>
                                            <td class="tdWidth borderBottom accountTd">(Rs.)</td>
                                        </tr>



                                        <tr>
                                            <td class="accountTd"> Sales <button type="submit"
                                                    class="btn btn-info btn-edit" id="Sale" style="float: right"
                                                    data-toggle="modal" data-target="#salesModal"><i
                                                        class="far fa-eye"></i></button></td>
                                            <td class="tdWidth accountTd"></td>


                                            <td class="tdWidth tdBold tdright accountTd">
                                                {{ number_format($SALES, 2) }}</td>


                                            <td class="tdWidth accountTd"></td>
                                        </tr>


                                        <tr>
                                            <td class="accountTd">-Sales Return</td>
                                            <td class="accountTd"></td>
                                            <td class="borderTd tdBold tdright accountTd">(0.00)
                                            </td>
                                            <td class="tdright accountTd">
                                                {{ number_format($SALES, 2) }}</td>
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
                                            <td class="accountTd">Stock at {{ $from }}</td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold tdright accountTd">0.00</td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">+Purchases<button type="submit"
                                                    class="btn btn-info btn-edit" id="purchase" style="float: right"
                                                    data-toggle="modal" data-target="#purchaseModal"><i
                                                        class="far fa-eye"></i></button></td>
                                            <td class="tdright accountTd">
                                                {{ number_format($TOTAL_PURCHASE, 2) }}</td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>


                                        <tr>
                                            <td class="accountTd">+Carriage inwards</td>
                                            <td class="borderTd tdright accountTd">0.00</td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"></td>

                                            <td class="tdright accountTd">
                                                {{ number_format($TOTAL_PURCHASE, 2) }}</td>


                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Purchase Return<button type="submit"
                                                    class="btn btn-info btn-edit" id="purchaseReturn" style="float: right"
                                                    data-toggle="modal" data-target="#purchaseReturnModal"><i
                                                        class="far fa-eye"></i></button></td>

                                            <td class="tdright accountTd">
                                                ({{ number_format($PURCHASE_RETURN, 2) }})
                                            </td>


                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Stock Damage</td>
                                            <td class="tdright accountTd">(0.00)</td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Stock Drawings</td>
                                            <td class="borderTd tdright accountTd">(0.00)</td>
                                            <td class="borderTd tdBold tdright accountTd">
                                                {{ number_format($FINAL_PURCHASE, 2) }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold tdright accountTd">
                                                {{ number_format($FINAL_PURCHASE, 2) }}</td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Stock at {{ $to }}</td>
                                            <td class="accountTd"></td>
                                            <td class="borderTd tdBold tdright accountTd">(0.00)</td>
                                            <td class="borderTd tdright accountTd">
                                                ({{ number_format($FINAL_PURCHASE, 2) }})</td>
                                        </tr>


                                        <tr>
                                            <td class="accountTd">Gross Profit</td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold tdright accountTd"></td>
                                            <td class="tdright accountTd">{{ number_format($GROSS_PROFIT, 2) }}</td>
                                        </tr>

                                        {{-- for empty row --}}
                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Other Expenses</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        @if (count($newOtherExpenseTypes) > 0)

                                            @foreach ($newOtherExpenseTypes as $item)
                                                <tr>
                                                    <td class="accountTd">{{ $item->name }}<button type="submit"
                                                            class="btn btn-info btn-edit otherExpensesClass"
                                                            data-id={{ $item->id }} data-name={{ $item->name }}
                                                            style="float: right" data-toggle="modal"
                                                            data-target="#otherExpensesModal"><i
                                                                class="far fa-eye"></i></button>
                                                    </td>

                                                    @if ($newOtherExpenseTypes[count($newOtherExpenseTypes) - 1] == $item)
                                                        <td class="tdright accountTd borderBottom2">
                                                            {{ number_format($item->TotalExpAmount, 2) }}</td>
                                                        <td class="tdBold tdright accountTd">
                                                            {{ number_format($finalOtherExpense, 2) }}</td>
                                                        <td class="tdright accountTd ">
                                                        </td>
                                                    @else
                                                        <td class="tdright accountTd">
                                                            {{ number_format($item->TotalExpAmount, 2) }}</td>
                                                        <td class="tdBold tdright accountTd">
                                                        </td>
                                                        <td class="tdright accountTd">
                                                        </td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="accountTd ">No Other Expense</td>
                                                <td class="tdBold tdright accountTd borderBottom2">0.00</td>
                                                <td class="tdBold tdright accountTd borderBottom2">0.00</td>
                                                <td class="tdBold tdright accountTd">0.00</td>
                                            </tr>

                                        @endif

                                        {{-- for empty row --}}
                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>


                                        <tr>
                                            <td class="accountTd">
                                                <h6><b><u>Service Expenses</u></b></h6>
                                            </td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>


                                        {{-- ------------------------ get all other expense start -------------- --}}
                                        @foreach ($otherexpenses as $expenses)
                                            <tr>
                                                <td class="accountTd">{{ $expenses->name }}</td>
                                                <td class="tdright accountTd">
                                                    {{ number_format($expenses->TotalCharge, 2) }}</td>

                                                @if ($otherexpenses[count($otherexpenses) - 1] == $expenses)
                                                    <td class="tdBold tdright accountTd">
                                                        {{ number_format($otherexpenses->sum('TotalCharge'), 2) }}</td>
                                                @else
                                                    <td class="tdBold accountTd"></td>
                                                @endif


                                                @if ($otherexpenses[count($otherexpenses) - 1] == $expenses)
                                                    <td class="tdright accountTd">
                                                        ({{ number_format($otherexpenses->sum('TotalCharge'), 2) }})
                                                    </td>
                                                @else
                                                    <td class="accountTd"></td>
                                                @endif

                                            </tr>
                                        @endforeach
                                        {{-- -------------------- get all other expense  end ------------------- --}}


                                        <tr>
                                            <td class="accountTd">Net profit taken to capital a/c</td>
                                            <td class="borderTop accountTd"></td>
                                            <td class="tdBold borderTop accountTd"></td>
                                            <td class="borderTop  dash tdright accountTd">
                                                {{ number_format($NET_PROFIT, 2) }}</td>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script>
        let from = $('#from').val();
        let to = $('#to').val();

         //  call ajax method for get other expense data
         $('.otherExpensesClass').on('click', function() {

            let id = $(this).attr('data-id');
            let name = $(this).attr('data-name');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/foodcity-otherExpense",
                data: {
                    from,
                    to,
                    id
                },

                success: function(data) {
                    console.log(data);

                    let otherExpenseHtml = "";
                    let oterexptotal = 0;

                    data.forEach(element => {
                        let oth_exp_amount = element.oth_exp_amount;
                        let cash_payment = element.oth_exp_cash;
                        let card_payment = element.oth_exp_online;
                        let cheque_payment = element.oth_exp_cheque;

                        if (card_payment == null) {
                            card_payment = 0.00;
                        }

                        if (cash_payment == null) {
                            cash_payment = 0.00;
                        }

                        if (cheque_payment == null) {
                            cheque_payment = 0.00;
                        }


                        oterexptotal += oth_exp_amount;

                        otherExpenseHtml += `<tr>
                    <td>${element.updated_at}</td>
                    <td>${parseFloat(oth_exp_amount).toFixed(2)}</td>
                    <td>${parseFloat(cash_payment).toFixed(2)}</td>
                    <td>${parseFloat(card_payment).toFixed(2)}</td>
                    <td>${parseFloat(cheque_payment).toFixed(2)}</td>
                    </tr>`

                    });

                    $('#otherExpenseTbody').html("");
                    $('#otherExpenseTbody').append(otherExpenseHtml);
                    $('#foodcityotherexpFinal').html(parseFloat(oterexptotal).toFixed(2));
                    $('#oterexpeseTitle').empty().append(`Restaurant Other Expenses - ${name}`);
                },

            });

        });

        //  call ajax method for get sales data
        $('#Sale').on('click', function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/restuarant-sales",
                data: {
                    from,
                    to
                },

                success: function(data) {
                    console.log(data);

                    let slaes = "";
                    let total = 0;
                    let i = 0;

                    data.forEach(element => {
                        let amount = element.total_amount;
                        let cash_payment = element.cash_payment;
                        let card_payment = element.card_payment;
                        let credit_payment = element.credit_payment;
                        total += parseFloat(amount);


                        slaes += `<tr>
                        <td>${i++}</td>
                        <td>${element.updated_at}</td>
                        <td>${parseFloat(amount).toFixed(2)}</td>
                        <td>${parseFloat(cash_payment).toFixed(2)}</td>
                        <td>${parseFloat(card_payment).toFixed(2)}</td>
                        <td>${parseFloat(credit_payment).toFixed(2)}</td>
                        </tr>`

                    });

                    $('#salesTbody').html("");
                    $('#salesTbody').append(slaes);
                    $('#foodcitySalesTotal').html(parseFloat(total).toFixed(2));
                },

            });

        });

        //  call ajax method for get purchase return data
        $('#purchaseReturn').on('click', function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/restuarant-purchaseReturn",
                data: {
                    from,
                    to
                },

                success: function(data) {
                    console.log(data);

                    let purchaseReturn = "";
                    let total = 0;
                    let i = 0;

                    data.forEach(element => {

                        let return_qty = element.return_qty;
                        let purchase_unit_price = element.purchase_unit_price;
                        let subTotal = parseFloat(return_qty) * parseFloat(
                            purchase_unit_price);
                        total += parseFloat(subTotal);


                        purchaseReturn += `<tr>
                        <td>${i++}</td>
                        <td>${element.updated_at}</td>
                        <td>${element.product_name}</td>
                        <td>${element.Qty_type}</td>
                        <td>${parseFloat(return_qty).toFixed(2)}</td>
                        <td>${parseFloat(purchase_unit_price).toFixed(2)}</td>
                        <td>${parseFloat(subTotal).toFixed(2)}</td>
                        </tr>`

                    });

                    $('#purchaseReturnTbody').html("");
                    $('#purchaseReturnTbody').append(purchaseReturn);
                    $('#purchaseReturnTotal').html(parseFloat(total).toFixed(2));
                },

                error: function(error) {
                    console.log(error);
                }

            });

        });

        //  call ajax method for get purchase data
        $('#purchase').on('click', function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/restuarant-purchase",
                data: {
                    from,
                    to
                },

                success: function(data) {
                    console.log(data);

                    let purchase = "";
                    let total = 0;
                    let i = 0;

                    data.forEach(element => {

                        let transfer_quantity = element.transfer_quantity;
                        let purchase_unit_price = element.purchase_unit_price;
                        let subTotal = parseFloat(transfer_quantity) * parseFloat(
                            purchase_unit_price);
                        total += parseFloat(subTotal);


                        purchase += `<tr>
            <td>${i++}</td>
            <td>${element.updated_at}</td>
            <td>${element.product_name}</td>
            <td>${element.Qty_type}</td>
            <td>${parseFloat(transfer_quantity).toFixed(2)}</td>
            <td>${parseFloat(purchase_unit_price).toFixed(2)}</td>
            <td>${parseFloat(subTotal).toFixed(2)}</td>
            </tr>`

                    });

                    $('#purchaseTbody').html("");
                    $('#purchaseTbody').append(purchase);
                    $('#purchaseTotal').html(parseFloat(total).toFixed(2));
                },

                error: function(error) {
                    console.log(error);
                }

            });

        });
    </script>

@endsection

@section('model')


    {{-- ------------ other expense Modal start ----------------------- --}}
    <div class="modal fade" id="otherExpensesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="oterexpeseTitle">Restaurant Other Expenses </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row" style="margin-left: 20px;">
                        Total : <h6 id="foodcityotherexpFinal"></h6>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Amount</th>
                                        <th>Cash</th>
                                        <th>Card</th>
                                        <th>Cheque</th>
                                    </tr>

                                </thead>

                                <tbody id="otherExpenseTbody"></tbody>


                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- otherExpense Modal end ------------------------------ --}}


    {{-- ------------ purchase return Modal start ----------------------- --}}
    <div class="modal fade" id="purchaseReturnModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Restaurant Purchases Return</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row" style="margin-left: 20px;">
                        Total : &nbsp;<h6 id="purchaseReturnTotal"></h6>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date & Time</th>
                                        <th>Product</th>
                                        <th>Quantity Type</th>
                                        <th>Quantity</th>
                                        <th>Unit price</th>
                                        <th>Amount</th>
                                    </tr>

                                </thead>

                                <tbody id="purchaseReturnTbody">

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- purchase Return Modal end ------------------------------ --}}

    {{-- ------------ purchase Modal start ----------------------- --}}
    <div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Restaurant Purchases</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row" style="margin-left: 20px;">
                        Total : &nbsp;<h6 id="purchaseTotal"></h6>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date & Time</th>
                                        <th>Product</th>
                                        <th>Quantity Type</th>
                                        <th>Quantity</th>
                                        <th>Unit price</th>
                                        <th>Amount</th>
                                    </tr>

                                </thead>

                                <tbody id="purchaseTbody">

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- purchase Modal end ------------------------------ --}}

    {{-- ------------ sales Modal start ----------------------- --}}
    <div class="modal fade" id="salesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Restaurant Sales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row" style="margin-left: 20px;">
                        Total : &nbsp;<h6 id="foodcitySalesTotal"></h6>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date & Time</th>
                                        <th>Amount</th>
                                        <th>Cash</th>
                                        <th>Card</th>
                                        <th>Credit</th>
                                    </tr>

                                </thead>

                                <tbody id="salesTbody">

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- sales Modal end ------------------------------ --}}
@endsection
