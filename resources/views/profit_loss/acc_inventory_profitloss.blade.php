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

    .tdright{
        text-align: right;
    }

</style>

@section('account_profit_loss_inventory', 'active')
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
                                <h4 class="header ">Inventory<br>Income Statement for Ending {{ $to }}</h4>

                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif

                        </div>

                        <div class="card-body">

                            <form action="/account_profit_loss_foodcity" method="get">
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
                                            <input type="date" class="form-control" name="to" value="{{ $to }}">
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
                                            <td class="tdWidth borderBottom">(Rs.)</td>
                                            <td class="tdWidth tdBold borderBottom">(Rs.)</td>
                                            <td class="tdWidth borderBottom">(Rs.)</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h6><b><u>Cost of sales</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                             <td>Stock at {{ $from }}</td>
                                            <td></td>
                                            <td class="tdBold tdright">{{ number_format($TOTAL_BASICS, 2) }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>+Purchases</td>
                                            <td class="tdright">{{ number_format($PURCHASE, 2) }}</td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>


                                        <tr>
                                            <td>+Carriage inwards</td>
                                            <td class="borderTd tdright">0.00</td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td class="tdright">{{ number_format($PURCHASE, 2) }}</td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>-Purchase Return</td>
                                            <td class="tdright">({{ number_format($PURCHASE_RETURN, 2) }})</td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>-Stock Damage</td>
                                            <td class="tdright">(0.00)</td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>-Stock Drawings</td>
                                            <td class="borderTd tdright">(0.00)</td>
                                            <td class="borderTd tdBold tdright">{{ number_format($FINAL_PURCHASE, 2) }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="tdBold tdright">
                                                {{ number_format($STOCK_DOWN, 2) }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>-Stock at {{ $to }}</td>
                                            <td></td>
                                            <td class="borderTd tdBold tdright">({{ number_format($TOTAL_FINAL, 2) }})</td>
                                            <td class="borderTd tdright">({{ number_format($STOCK4, 2) }})</td>
                                        </tr>


                                        <tr>
                                            <td>Gross Profit</td>
                                            <td></td>
                                            <td class="tdBold tdright"></td>
                                            <td class="tdright">{{ number_format($GROSS_PROFIT, 2) }}</td>
                                        </tr>

                                         {{-- for empty row --}}
                                       <tr style="height:30px">
                                            <td></td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h6><b><u>Other Expense</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>


                                        {{-- ------------------------ get all other expense start -------------- --}}
                                        @foreach ($otherexpenses as $expenses)
                                            <tr>
                                                <td>{{ $expenses->name }}</td>
                                                <td class="tdright">{{ number_format($expenses->TotalCharge, 2) }}</td>

                                                @if ($otherexpenses[count($otherexpenses) - 1] == $expenses)
                                                    <td class="tdBold tdright">{{ number_format($otherexpenses->sum('TotalCharge'), 2) }}</td>
                                                @else
                                                    <td class="tdBold"></td>
                                                @endif


                                                @if ($otherexpenses[count($otherexpenses) - 1] == $expenses)
                                                    <td class="tdright">({{ number_format($otherexpenses->sum('TotalCharge'), 2) }})</td>
                                                @else
                                                    <td></td>
                                                @endif

                                            </tr>
                                        @endforeach
                                        {{-- -------------------- get all other expense  end ------------------- --}}


                                        <tr>
                                            <td>Net profit taken to capital a/c</td>
                                            <td class="borderTop"></td>
                                            <td class="tdBold borderTop"></td>
                                            <td class="borderTop  dash tdright">{{ number_format($NET_PROFIT, 2) }}</td>
                                        </tr>

                                        {{-- for empty row --}}
                                        <tr style="height:30px">
                                            <td></td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
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

@endsection
