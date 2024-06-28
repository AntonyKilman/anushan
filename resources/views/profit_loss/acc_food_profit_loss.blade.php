@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('account_profit_loss_foodcity', 'active')
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
                                <h4 class="header ">Food City<br>Income Statement for Ending {{ $to }}</h4>
                            </div>
                        </div>

                        <div class="card-body">

                            <form action="/account_profit_loss_foodcity/profitLoss" method="get">
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
                                                    class="btn btn-info btn-edit" id="foodcitySale" style="float: right"
                                                    data-toggle="modal" data-target="#salesModal"><i
                                                        class="far fa-eye"></i></button></td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth tdBold tdright accountTd"> {{ number_format($sales, 2) }}
                                            </td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Sales Return<button type="submit"
                                                    class="btn btn-info btn-edit" id="foodcitySaleReturn"
                                                    style="float: right" data-toggle="modal"
                                                    data-target="#foodcitySaleReturnModal"><i
                                                        class="far fa-eye"></i></button></td>
                                            <td class="accountTd"></td>
                                            <td class="borderTd tdBold tdright accountTd">
                                                ({{ number_format($salesReturn, 2) }})
                                            </td>
                                            <td class="tdright accountTd">{{ number_format($salesReturn4, 2) }}</td>
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
                                            <td class="tdBold tdright accountTd">{{ number_format($basicStocks, 2) }}</td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">+Purchases<button type="submit"
                                                    class="btn btn-info btn-edit" id="foodcityPurchases"
                                                    style="float: right" data-toggle="modal"
                                                    data-target="#foodcityPurchasesModal"><i
                                                        class="far fa-eye"></i></button></td>
                                            <td class="tdright accountTd">{{ number_format($purchases, 2) }}</td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>


                                        <tr>
                                            <td class="accountTd">+Carriage inwards</td>
                                            <td class="borderTd tdright accountTd">{{ number_format($carriageinwards, 2) }}
                                            </td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd">{{ number_format($CarriageDown, 2) }}</td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Purchase Return<button type="submit"
                                                    class="btn btn-info btn-edit" id="foodcityPurchasesReturn"
                                                    style="float: right" data-toggle="modal"
                                                    data-target="#foodcityPurchaseReturnModal"><i class="far fa-eye"></i>
                                            </td>
                                            <td class="tdright accountTd">({{ number_format($purchasesReturn, 2) }})</td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Stock Damage</td>
                                            <td class="tdright accountTd">({{ number_format($stockDamage, 2) }})</td>
                                            <td class="tdBold accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Stock Drawings</td>
                                            <td class="borderTd tdright accountTd">
                                                ({{ number_format($stockDrawings, 2) }})
                                            </td>
                                            <td class="borderTd tdBold tdright accountTd">
                                                {{ number_format($stockDrawing3, 2) }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold tdright accountTd">
                                                {{ number_format($costOfSales, 2) }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Stock at {{ $to }}</td>
                                            <td class="accountTd"></td>
                                            <td class="borderTd tdBold tdright accountTd">
                                                ({{ number_format($finalStocks, 2) }})</td>
                                            <td class="borderTd tdright accountTd">({{ number_format($stockat4, 2) }})
                                            </td>
                                        </tr>


                                        <tr>
                                            <td class="accountTd">Gross Profit</td>
                                            <td class="accountTd"></td>
                                            <td class="tdBold tdright accountTd"></td>
                                            <td class="tdright accountTd">{{ number_format($grossProfit, 2) }}</td>
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
                                                <h6><b><u>Service Expense</u></b></h6>
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
                                                {{ number_format($netProfit, 2) }}</td>
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

        $("#printTable").on('click', function(e) {
            e.preventDefault();
            let dataHtml = $("#profit_loss").html();
            console.log(dataHtml);

            let print = window.open('', '', 'height=7016, width=4960');
            print.document.write(
                `<!DOCTYPE html>
                        <html>
                        <head>
                            <link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
                            <link rel="stylesheet" href="/assets/admin/css/style.css">
                            <link rel="stylesheet" href="/assets/admin/css/components.css">
                            <link rel="stylesheet" href="/assets/admin/css/app.min.css">

                        </head>

                        <body>
                            <div style="display:flex;flex-direction:column;align-items: center;">
                                <img src="https://www.foodcity.reecha.lk/assets/img/reecha.png" alt="reecha" width="100"/>
                                <div style="font-size:13px">KILINOCHCHI</div>
                            </div>
                                ${dataHtml}
                        </body>
                    </html>`);
            print.document.close();
            print.print();
        })


        //  call ajax method for get other expense data
        $('.otherExpensesClass').on('click', function() {
            console.log("click");
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
                    $('#oterexpeseTitle').empty().append(`Foodcity Other Expenses - ${name}`);
                },

            });

        });

        //  call ajax method for get foodcity sales data
        $('#foodcitySale').on('click', function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/foodcity-sales",
                data: {
                    from,
                    to
                },

                success: function(data) {

                    let slaes = "";
                    let total = 0;

                    data.forEach(element => {
                        let amount = element.amount;
                        let cash_payment = element.cash_payment;
                        let card_payment = element.card_payment;
                        let cheque_payment = element.cheque_payment;
                        let credit_payment = element.credit_payment;

                        total += amount;

                        slaes += `<tr>
                            <td>${element.updated_at}</td>
                            <td>${parseFloat(amount).toFixed(2)}</td>
                            <td>${parseFloat(cash_payment).toFixed(2)}</td>
                            <td>${parseFloat(card_payment).toFixed(2)}</td>
                            <td>${parseFloat(cheque_payment).toFixed(2)}</td>
                            <td>${parseFloat(credit_payment).toFixed(2)}</td>
                            </tr>`

                    });

                    $('#salesTbody').html("");
                    $('#salesTbody').append(slaes);
                    $('#foodcitySalesTotal').html(parseFloat(total).toFixed(2));
                },

            });

        });

        //  call ajax method for get foodcity sales return data
        $('#foodcitySaleReturn').on('click', function() {
            console.log("click");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/foodcity-sales-return",
                data: {
                    from,
                    to
                },

                success: function(Returns) {
                    let salesReturn = "";
                    let salesReturntotal = 0;

                    Returns.forEach(element => {
                        let amount = element.return_amount;
                        salesReturntotal += amount;

                        salesReturn += `<tr>
                            <td>${element.updated_at}</td>
                            <td>${element.product_name}</td>
                            <td>${element.return_quantity}</td>
                            <td>${parseFloat(amount).toFixed(2)}</td>
                            </tr>`

                    });

                    $('#salesReturnTbody').html("");
                    $('#salesReturnTbody').append(salesReturn);
                    $('#foodcitySalesReturnTotal').html(parseFloat(salesReturntotal).toFixed(2));
                },
                error: function(data) {

                }
            });

        });

        //  call ajax method for get foodcity purchase data
        $('#foodcityPurchases').on('click', function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/foodcity-purchases",
                data: {
                    from,
                    to
                },

                success: function(purchases) {

                    let purchaseHtml = "";
                    let purchaseTotal = 0;

                    purchases.forEach(element => {
                        let unit_price = element.purchase_price;
                        let quantity = element.quantity;
                        let subTotal = parseFloat(unit_price).toFixed(2) * quantity;
                        purchaseTotal += subTotal;

                        purchaseHtml += `<tr>
                            <td>${element.updated_at}</td>
                            <td>${element.name}</td>
                            <td>${quantity}</td>
                            <td>${parseFloat(unit_price).toFixed(2)}</td>
                            <td>${parseFloat(subTotal).toFixed(2)}</td>
                            </tr>`

                    });

                    $('#purchasesTbody').html("");
                    $('#purchasesTbody').append(purchaseHtml);
                    $('#foodcitypurchasesTotal').html(parseFloat(purchaseTotal).toFixed(2));
                },
                error: function(res) {
                    console.log(res);
                }
            });

        });

        //  call ajax method for get foodcity purchase return data
        $('#foodcityPurchasesReturn').on('click', function() {
            console.log("click");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/foodcity-purchases-Return",
                data: {
                    from,
                    to
                },

                success: function(purchasesReurn) {
                    console.log(purchasesReurn);

                    console.log(purchasesReurn);
                    let purchaseReturnHtml = "";
                    let purchaseReturnTotal = 0;

                    purchasesReurn.forEach(element => {
                        let unit_price = element.unit_price;
                        console.log(unit_price);
                        let return_qty = element.return_qty;
                        let subTotal = parseFloat(unit_price).toFixed(2) * return_qty;
                        console.log(subTotal);
                        purchaseReturnTotal += subTotal;

                        purchaseReturnHtml += `<tr>
                            <td>${element.updated_at}</td>
                            <td>${element.product_name}</td>
                            <td>${return_qty}</td>
                            <td>${parseFloat(unit_price).toFixed(2)}</td>
                            <td>${parseFloat(subTotal).toFixed(2)}</td>
                            </tr>`

                    });

                    $('#purchasesReturnTbody').html("");
                    $('#purchasesReturnTbody').append(purchaseReturnHtml);
                    $('#foodcitypurchasesReturnTotal').html(parseFloat(purchaseReturnTotal).toFixed(2));
                },
                error: function(res) {

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
                    <h5 class="modal-title" id="oterexpeseTitle">Foodcity Other Expenses </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    Total : <h6 id="foodcityotherexpFinal"></h6>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- otherExpense Modal end ------------------------------ --}}


    {{-- ------------ Foodcity purchase Return Modal start ----------------------- --}}
    <div class="modal fade" id="foodcityPurchaseReturnModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Foodcity Purchases Return </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row" style="margin-left: 20px;">
                        Total : &nbsp;<h6 id="foodcitypurchasesReturnTotal"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Product</th>
                                        <th>Qauntity</th>
                                        <th>Unit Price</th>
                                        <th>Amount</th>
                                    </tr>

                                </thead>

                                <tbody id="purchasesReturnTbody"></tbody>


                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- Foodcity purchase Return Modal end ------------------------------ --}}

    {{-- ------------ Foodcity purchase Modal start ----------------------- --}}
    <div class="modal fade" id="foodcityPurchasesModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Foodcity Purchases</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row" style="margin-left: 20px;">
                        Total : &nbsp;<h6 id="foodcitypurchasesTotal"></h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Product</th>
                                        <th>Qauntity</th>
                                        <th>Unit Price</th>
                                        <th>Amount</th>
                                    </tr>

                                </thead>

                                <tbody id="purchasesTbody"></tbody>


                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- Foodcity purchase Modal end ------------------------------ --}}

    {{-- ------------ sales return Modal start ----------------------- --}}
    <div class="modal fade" id="foodcitySaleReturnModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Foodcity Sales Return</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row" style="margin-left: 20px;">
                        Total : &nbsp;<h6 id="foodcitySalesReturnTotal"></h6>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Product</th>
                                        <th>Qauntity</th>
                                        <th>Amount</th>
                                    </tr>

                                </thead>

                                <tbody id="salesReturnTbody"></tbody>


                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- sales Return Modal end ------------------------------ --}}

    {{-- ------------ sales Modal start ----------------------- --}}
    <div class="modal fade" id="salesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Foodcity Sales</h5>
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
                                        <th>Date & Time</th>
                                        <th>Amount</th>
                                        <th>Cash</th>
                                        <th>Card</th>
                                        <th>Cheque</th>
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
