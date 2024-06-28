@extends('layouts.navigation')
@section('sales_ledgers', 'active')
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
                                <h4 class="header ">Sales Ledgers</h4>
                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif
                        </div>
                        <div class="card-body">
                            <form action="/sales-details-showall" method="GET">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="col-6">
                                            <label for="month">Select Month</label>
                                            <input type="month" class="form-control" value="{{$month}}" name="month" id="month" max="{{now()->format('Y-m')}}"
                                               onchange="window.location.assign('/sales-details-showall?month=' +this.value)" >
                                        </div>

                                     
                                    </div>
                                    <div class="col-3">
                                        <h6>Cash :- {{ $cash_total }}</h6>
                                        <h6>Online :- {{ $online_total }}</h6>
                                        <h6>Card :- {{ $card_total }}</h6>
                                        {{-- <h6>Credit :- {{$credit_total}}</h6> --}}
                                        <h6 style="text-decoration: underline">Credit :- {{ $credit_total }}</h6>
                                        <h6>Total :- {{ $cash_total + $online_total + $credit_total+$credit_total }}</h6>
                                    </div>
                                </div><br>
                            {{-- </form> --}}

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Cash</th>
                                            <th style="text-align: center">Online</th>
                                            <th style="text-align: center">Card</th>
                                            <th style="text-align: center">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table-bordered table-sm" width="100%">
                                                        <tr>
                                                            <th>Department</th>
                                                            <th>Amount</th>
                                                        </tr>

                                                        <?php $cash_total = 0; ?>
                                                        @foreach ($cash as $key => $value)
                                                            <tr>
                                                                <td>{{ $key }}</td>
                                                                <td>{{ $value }}</td>
                                                                <?php $cash_total += $value; ?>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <th>Total</th>
                                                            <th>{{ $cash_total }}</th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table-bordered table-sm" width="100%">
                                                        <tr>
                                                            <th>Department</th>
                                                            <th>Amount</th>
                                                        </tr>

                                                        <?php $online_total = 0; ?>
                                                        @foreach ($online as $key => $value)
                                                            <tr>
                                                                <td>{{ $key }}</td>
                                                                <td>{{ $value }}</td>
                                                                <?php $online_total += $value; ?>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <th>Total</th>
                                                            <th>{{ $online_total }}</th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table-bordered table-sm" width="100%">
                                                        <tr>
                                                            <th>Department</th>
                                                            <th>Amount</th>
                                                        </tr>

                                                        <?php $card_total = 0; ?>
                                                        @foreach ($card as $key => $value)
                                                            <tr>
                                                                <td>{{ $key }}</td>
                                                                <td>{{ $value }}</td>
                                                                <?php $card_total += $value; ?>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <th>Total</th>
                                                            <th>{{ $card_total }}</th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table-bordered table-sm" width="100%">
                                                        <tr>
                                                            <th>Department</th>
                                                            <th>Amount</th>
                                                        </tr>

                                                        <?php $credit_total = 0; ?>
                                                        @foreach ($credit as $key => $value)
                                                            <tr>
                                                                <td>{{ $key }}</td>
                                                                <td>{{ $value }}</td>
                                                                <?php $credit_total += $value; ?>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <th>Total</th>
                                                            <th>{{ $credit_total }}</th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
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