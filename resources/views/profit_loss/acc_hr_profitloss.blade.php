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

@section('account_profit_loss_hr', 'active')
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
                                <h4 class="header ">HR<br>Income Statement for Ending {{ $to }}</h4>

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
                                            <td class="tdWidth borderBottom">(Rs.)</td>
                                            <td class="tdWidth tdBold borderBottom">(Rs.)</td>
                                            <td class="tdWidth borderBottom">(Rs.)</td>
                                        </tr>

                                        <tr>
                                            <td>Gross Profit</td>
                                            <td></td>
                                            <td class="tdBold tdright"></td>
                                            <td class="tdright">0.00</td>
                                        </tr>

                                        {{-- for empty row --}}
                                        <tr style="height:30px">
                                            <td></td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>
                                         {{-- -------Employee expenses start-------------- --}}
                                        <tr>
                                            <td>
                                                <h6><b><u>Employee Expense</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                        @if (count($datas)<1)
                                        <tr>
                                            <td>None</td>
                                            <td class="tdright borderTd">0.00</td>
                                            <td class="tdright tdBold">0.00</td>
                                            <td></td>
                                        </tr>

                                        @endif


                                        @foreach ($datas as $data)
                                            <?php
                                            $total_expense = 0;
                                            $total_expense = $data->TOTAL_CASH_OUT + $data->TOTAL_CHEQUE_OUT + $data->TOTAL_CARD_OUT;
                                            ?>

                                            <tr>
                                                @if ($data->type == 'HR_ADVANCE_SALARY')
                                                    <td>Employee Advance Salary</td>
                                                @else
                                                    <td>Employee Salary</td>
                                                @endif

                                                @if ($data == $datas[count($datas) - 1])
                                                    <td class="tdright borderTd">
                                                        {{ number_format($total_expense, 2) }}</td>
                                                    <td class="tdBold tdright">
                                                        {{ number_format($TOTAL_EMP_EXPENSE, 2) }}</td>

                                                @else
                                                    <td class="tdright">{{ number_format($total_expense, 2) }}</td>
                                                    <td class="tdBold tdright"></td>

                                                @endif
                                                <td></td>

                                            </tr>
                                        @endforeach

                                        {{-- -------Employee expenses end-------------- --}}

                                        {{-- for empty row --}}
                                        <tr style="height:30px">
                                            <td></td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>
                                        {{-- other expense start --}}
                                        <tr>
                                            <td>
                                                <h6><b><u>Other Expense</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                         {{-- other expense end --}}

                                        {{-- for empty row --}}
                                        <tr style="height:30px">
                                            <td></td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>


                                        {{-- ------------------------ get all service expense start -------------- --}}
                                        <tr>
                                            <td>
                                                <h6><b><u>Sevice Expense</u></b></h6>
                                            </td>
                                            <td></td>
                                            <td class="tdBold"></td>
                                            <td></td>
                                        </tr>

                                        {{-- if no datas for service expenses this is display --}}
                                        @if(count($service_expenses)<1)
                                            <tr>
                                                <td>None</td>
                                                <td class="tdright">0.00</td>
                                                <td class="tdright tdBold">0.00</td>
                                                <td></td>
                                            </tr>
                                        @endif

                                        @foreach ($service_expenses as $expenses)
                                            <tr>
                                                <td>{{ $expenses->name }}</td>
                                                <td class="tdright">{{ number_format($expenses->TotalCharge, 2) }}</td>

                                                @if ($service_expenses[count($service_expenses) - 1] == $expenses)
                                                    <td class="tdBold tdright">
                                                        {{ number_format($service_expenses->sum('TotalCharge'), 2) }}</td>
                                                @else
                                                    <td class="tdBold"></td>
                                                @endif

                                                @if ($service_expenses[count($service_expenses) - 1] == $expenses)
                                                    <td class="tdright">
                                                        ({{ number_format($total_profitloss_exp, 2) }})
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        {{-- -------------------- get all service expense  end ------------------- --}}


                                        <tr>
                                            <td>Net profit taken to capital a/c</td>
                                            <td class="borderTop"></td>
                                            <td class="tdBold borderTop"></td>
                                            <td class="borderTop  dash tdright">
                                                {{ number_format(-$total_profitloss_exp, 2) }}</td>
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
