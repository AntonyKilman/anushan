@extends('layouts.navigation')
<link rel="stylesheet" href="{{ URL::asset('assets/css/accountTable.css') }}">
@section('account_finance_restuarant', 'active')
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
                                <h4 class="header ">Restaurant<br>Statement of Financial Position {{ $to }}
                                </h4>

                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif

                        </div>

                        <div class="card-body">
                            <form action="/account_profit_loss_restaurants/finance" method="get">
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
                                            <td class="tdWidth borderBottom accountTd">Accumulated Depreciation<br>(Rs.)</td>
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

                                        <tr>
                                            <td class="accountTd">Land & Buildings</td>
                                            <td class="tdWidth tdright accountTd">0.00</td>
                                            <td class="tdWidth tdright accountTd">0.00 </td>
                                            <td class="tdWidth tdright accountTd">0.00</td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Motor Vehicles</td>
                                            <td class="tdWidth tdright accountTd">0.00</td>
                                            <td class="tdWidth tdright accountTd">0.00 </td>
                                            <td class="tdWidth tdright accountTd">0.00</td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Furniture</td>
                                            <td class="tdWidth borderTd tdright accountTd">0.00</td>
                                            <td class="tdWidth borderTd tdright accountTd">0.00 </td>
                                            <td class="tdWidth borderTd tdright accountTd">0.00</td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="dash tdright accountTd">0.00</td>
                                            <td class="dash tdright accountTd">0.00</td>
                                            <td class="tdright accountTd">0.00</td>
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
                                            <td class="tdWidth tdright accountTd"> 0.00</td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">Debtors<button type="submit"
                                                class="btn btn-info btn-edit" id="Debtors" style="float: right"
                                                data-toggle="modal" data-target="#DebtorsModal"><i
                                                    class="far fa-eye"></i></button></td>
                                            <td class="tdWidth tdright accountTd">{{ number_format($TOTAL_CREDIT, 2) }}</td>
                                            <td class="tdWidth accountTd"> </td>
                                            <td class="tdWidth accountTd"></td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd">-Provision for doubtfuldebts</td>
                                            <td class="tdWidth borderTd tdright accountTd">(0.00)</td>
                                            <td class="tdWidth tdright accountTd">{{ number_format($TOTAL_CREDIT, 2) }} </td>
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
                                            <td class="accountTd">Cash</td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth borderTd tdright accountTd"> {{ number_format($cash, 2) }}</td>
                                            <td class="tdWidth borderTd tdright accountTd">{{ number_format($TOTALASSETS, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="accountTd"><b>Total Assets</b></td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth accountTd"> </td>
                                            <td class="tdWidth dash tdright accountTd">{{ number_format($TOTALASSETS, 2) }}</td>
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
                                            <td class="tdright accountTd">{{ number_format($NET_PROFIT, 2) }}</td>
                                            <td class="accountTd"></td>
                                        </tr>


                                        <tr>
                                            <td class="accountTd">-Drawings</td>
                                            <td class="accountTd"></td>
                                            <td class="borderTd tdright accountTd">0.00</td>
                                            <td class="tdright accountTd">{{ number_format($NET_PROFIT, 2) }}</td>
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

                                        <tr>
                                            <td class="accountTd">Accured Transport Charge</td>
                                            <td class="accountTd"></td>
                                            <td class="tdright accountTd">0.00</td>
                                            <td class="accountTd"></td>
                                        </tr>

                                        @foreach ($charges as $charge)
                                            <tr>
                                                <td class="accountTd">Accured {{ $charge->name }}</td>
                                                <td class="accountTd"></td>
                                                @if ($charges[Count($charges) - 1] == $charge)
                                                    <td class="borderTd tdright accountTd"> {{ $charge->TotalPaymemt }}</td>
                                                    <td class="borderTd tdright accountTd">{{ $chargePaid }}</td>
                                                @else
                                                    <td class="tdright accountTd"> {{ $charge->TotalPaymemt }}</td>
                                                    <td class=" tdright accountTd"></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td class="accountTd"><b>Total Equility and Libalities</b></td>
                                            <td class="tdWidth accountTd"></td>
                                            <td class="tdWidth accountTd"> </td>
                                            <td class="tdWidth dash tdright accountTd">{{ number_format($TotalEquality, 2) }}</td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                            <td class="accountTd"></td>
                                        </tr>

                                    </thead>


                                    <tbody>

                                    </tbody>
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

        //  call ajax method for get foodcity debtors data
        $('#Debtors').on('click', function() {
            console.log("click");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({

                type: 'POST',
                url: "/restuarant-debtors",
                data: {
                    from,
                    to
                },

                success: function(response) {
                    console.log(response);

                    let DebtorsHtml = "";
                    let DebtorsTotal = 0;
                    let i = 1

                    response.forEach(element => {
                        let total_amount = element.total_amount;
                        let cash_payment = element.cash_payment;
                        let card_payment = element.card_payment;
                        let credit_payment = element.credit_payment;

                        DebtorsHtml += `<tr>
                        <td>${i++}</td>
                        <td>${element.updated_at}</td>
                        <td>${parseFloat(total_amount).toFixed(2)}</td>
                        <td>${parseFloat(cash_payment).toFixed(2)}</td>
                        <td>${parseFloat(card_payment).toFixed(2)}</td>
                        <td>${parseFloat(credit_payment).toFixed(2)}</td>
                        </tr>`

                    });

                    $('#debtorsTbody').html("");
                    $('#debtorsTbody').append(DebtorsHtml);
                    // $('#debtorsFinalTotal').html(parseFloat(DebtorsTotal).toFixed(2));
                },

                error: function(error){
                    console.log(error);
                }

            });

        });
        </script>



@endsection


@section('model')


    {{-- ------------ Debtors Modal start ----------------------- --}}
    <div class="modal fade" id="DebtorsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="oterexpeseTitle">Restaurant Debtors </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row" style="margin-left: 20px;">
                        Total : <h6 id="debtorsFinal"></h6>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date & Time</th>
                                        <th>Total Amount</th>
                                        <th>Cash</th>
                                        <th>Card</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody id="debtorsTbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- Debtors Modal end ------------------------------ --}}

@endsection


@section('model')

    {{-- ------------ Cash Modal start ----------------------- --}}
    <div class="modal fade" id="foodcityCashModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="oterexpeseTitle">Cash</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    Total : <h6></h6>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Cash</th>
                                    </tr>

                                </thead>

                                <tbody>
                                    <tr>
                                        <td>Sales</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Sales Return</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Purchases</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Purchase Return</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Other Expenses</td>
                                        <td></td>
                                    </tr>

                                </tbody>
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
    {{-- ----------------- Cash Modal end ------------------------------ --}}

    {{-- ------------ Debtors Modal start ----------------------- --}}
    <div class="modal fade" id="foodcityDebtorsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="oterexpeseTitle">Foodcity Debtors </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    Total : <h6 id="debtorsFinalTotal"></h6>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Customer</th>
                                        <th>Sales</th>
                                        <th>Credit</th>
                                    </tr>

                                </thead>

                                <tbody id="DebtorsTbody">

                                </tbody>
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
    {{-- ----------------- Debtors Modal end ------------------------------ --}}




@endsection