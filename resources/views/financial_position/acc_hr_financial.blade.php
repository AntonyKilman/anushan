@extends('layouts.navigation')

<style>
    table {
        border: 2px solid black;
        width: 100%;
        color: black;
        font-size: 18px;

    }

    td {
        border: 1px solid rgb(216, 204, 204);
        border-right: 2px solid black;
        padding: 3px;

    }

    .borderTd {
        border-bottom: 1px solid black;
    }

    .borderTop {

        border-top: 1.1px solid black;
    }

    .tdBold {
        border-right: 3px solid black;
    }

    .tdWidth {
        width: 150px;

    }

    .borderBottom {
        border-bottom: 2px solid black;
    }

    .subButton {
        margin-top: 29px;
    }

    .dash {
        border-bottom: 4px solid black
    }

    .tdright {
        text-align: right;
    }
</style>

@section('account_finance_hr', 'active')
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
                                <h4 class="header ">HR<br>Statement of Financial Position {{ $to }}
                                </h4>

                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif

                        </div>

                        <div class="card-body">

                            <form action="/account_finance_foodcity" method="get">
                                @csrf

                                <div class="row">

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>From</label>
                                            <input type="date" class="form-control" name="from"
                                                value="{{ $from }}">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>To</label>
                                            <input type="date" class="form-control" name="to"
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

                                <table>
                                    <thead>

                                        <tr>
                                            <td class="borderBottom">Description</td>
                                            <td class="tdWidth borderBottom">Cost<br><br>(Rs.)</td>
                                            <td class="tdWidth borderBottom">Accumulated Depreciation<br>(Rs.)</td>
                                            <td class="tdWidth borderBottom">Carrying <br>Value<br>(Rs.)</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h6><b><u>Non Current Assets</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>Land & Buildings</td>
                                            <td class="tdWidth tdright">0.00</td>
                                            <td class="tdWidth tdright">0.00 </td>
                                            <td class="tdWidth tdright">0.00</td>
                                        </tr>

                                        <tr>
                                            <td>Motor Vehicles</td>
                                            <td class="tdWidth tdright">0.00</td>
                                            <td class="tdWidth tdright">0.00 </td>
                                            <td class="tdWidth tdright">0.00</td>
                                        </tr>

                                        <tr>
                                            <td>Furniture</td>
                                            <td class="tdWidth borderTd tdright">0.00</td>
                                            <td class="tdWidth borderTd tdright">0.00 </td>
                                            <td class="tdWidth borderTd tdright">0.00</td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td></td>
                                            <td class="dash tdright">0.00</td>
                                            <td class="dash tdright">0.00</td>
                                            <td class="tdright">0.00</td>
                                        </tr>


                                        <tr>
                                            <td>
                                                <h6><b><u>Current Assets</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>Stocks</td>
                                            <td class="tdWidth"></td>
                                            <td class="tdWidth tdright">0.00</td>
                                            <td class="tdWidth"></td>
                                        </tr>

                                        <tr>
                                            <td>Debtors</td>
                                            <td class="tdWidth tdright">0.00</td>
                                            <td class="tdWidth"> </td>
                                            <td class="tdWidth"></td>
                                        </tr>

                                        <tr>
                                            <td>-Provision for doubtfuldebts</td>
                                            <td class="tdWidth borderTd tdright">(0.00)</td>
                                            <td class="tdWidth tdright">0.00 </td>
                                            <td class="tdWidth"></td>
                                        </tr>

                                        <tr>
                                            <td>Insurance compensation receivable</td>
                                            <td class="tdWidth"></td>
                                            <td class="tdWidth tdright"> 0.00</td>
                                            <td class="tdWidth"></td>
                                        </tr>

                                        <tr>
                                            <td>Prepaid advertising charge</td>
                                            <td class="tdWidth"></td>
                                            <td class="tdWidth tdright"> 0.00</td>
                                            <td class="tdWidth"></td>
                                        </tr>

                                        <tr>
                                            <td>Cash</td>
                                            <td class="tdWidth"></td>
                                            <td class="tdWidth borderTd tdright"> {{ number_format(-$TOTAL_EMP_EXPENSE-$TOTAL_PAID_AMOUNT, 2) }}</td>
                                            <td class="tdWidth borderTd tdright">{{ number_format(-$TOTAL_EMP_EXPENSE-$TOTAL_PAID_AMOUNT, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><b>Total Assets</b></td>
                                            <td class="tdWidth"></td>
                                            <td class="tdWidth"> </td>
                                            <td class="tdWidth dash tdright">{{ number_format(-$TOTAL_EMP_EXPENSE-$TOTAL_PAID_AMOUNT, 2) }}</td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h6><b><u>Equity & Liabilities</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>Capital</td>
                                            <td></td>
                                            <td class="tdright">0.00</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>+Net Profit</td>
                                            <td></td>
                                            <td class="tdright">{{ number_format(-$total_profitloss_exp, 2) }}</td>
                                            <td></td>
                                        </tr>


                                        <tr>
                                            <td>-Drawings</td>
                                            <td></td>
                                            <td class="borderTd tdright">0.00</td>
                                            <td class="tdright">{{ number_format(-$total_profitloss_exp, 2) }}</td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h6><b><u>Current Liabilities</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>Creditors</td>
                                            <td></td>
                                            <td class="tdright">0.00</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>Accured Transport Charge</td>
                                            <td></td>
                                            @if (count($deptCharges) < 1)
                                                <td class="borderTd tdright"> 0.00</td>
                                                <td class="borderTd tdright">0.00</td>
                                            @else
                                                <td class="tdright">0.00</td>
                                                <td></td>
                                            @endif

                                        </tr>

                                        @foreach ($deptCharges as $charge)
                                            <tr>
                                                <td>Accured {{ $charge->name }}</td>
                                                <td></td>
                                                @if ($deptCharges[Count($deptCharges) - 1] == $charge)
                                                    <td class="borderTd tdright"> {{ $charge->Accured_amount }}</td>
                                                    <td class="borderTd tdright">{{ $chargePaid }}</td>
                                                @else
                                                    <td class="tdright"> {{ $charge->Accured_amount }}</td>
                                                    <td class=" tdright"></td>
                                                @endif
                                            </tr>
                                        @endforeach



                                        <tr>
                                            <td><b>Total Equility and Libalities</b></td>
                                            <td class="tdWidth"></td>
                                            <td class="tdWidth"> </td>
                                            <td class="tdWidth dash tdright">{{ number_format(-$total_profitloss_exp+$chargePaid, 2) }}</td>
                                        </tr>

                                        <tr style="height:30px">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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



@endsection
