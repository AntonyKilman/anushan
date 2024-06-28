@extends('layouts.navigation')
@section('all_income','active')
@section('content')
     <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    
    // if (in_array('main_our_team.store', $Access)) {
    //     $c = true;
    // }
    
    // if (in_array('main_our_team.update', $Access)) {
    //     $u = true;
    // }
    
    // if (in_array('main_our_team.destroy', $Access)) {
    //     $d = true;
    // }
    
    ?> 


    <input type="hidden" id="foodcity-income" value="{{$foodCitySalesMonth->pluck('totalSaleAmount')}}">
    {{-- <input type="hidden" id="restaurant-income" value="{{$restaurantSalesMonth->pluck('totalSaleAmount')}}"> --}}
    {{-- <input type="hidden" id="reecha-main-income" value="{{$reechaMainSalesMonth->pluck('totalSaleAmount')}}"> --}}
    {{-- <input type="hidden" id="hotel-income" value="{{$hotelSalesMonth->pluck('totalSaleAmount')}}"> --}}
    {{-- <input type="hidden" id="farm-income" value="{{$farmSalesMonth->pluck('totalSaleAmount')}}"> --}}
    {{-- <input type="hidden" id="bank-income" value="{{$bankSalesMonth->pluck('totalSaleAmount')}}"> --}}
    {{-- <input type="hidden" id="other-income" value="{{$otherSalesMonth->pluck('totalSaleAmount')}}"> --}}
    {{-- <input type="hidden" id="total-income" value="{{$totalSalesMonth->pluck('totalSaleAmount')}}"> --}}
    {{-- <input type="hidden" id="total-income-pie" value="{{collect($totalAnualIncome)}}"> --}}
    <section class="section">
        <form action="/admin/income" method="POST">
            @csrf
            <div class="card-body form">
                <h6>Income Report</h6>
    
                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label>Select the Year</label>
                        <input type="text" class="yearpicker form-control" placeholder="" value="" name="year" required>
                    </div>
                    <div class="form-group col-md-3" style="margin-top:30px">
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="section-body">
            <div class="row">
                {{-- All income bar chart start --}}
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Total Income</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="totalChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($foodCitySalesToday->totalSaleAmount ),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($foodCitySalesWeek->totalSaleAmount ),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            {{-- <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($totalSalesMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div> --}}
                            {{-- <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($totalSalesMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div> --}}
                            </div>   
                        </div>
                    </div>
                </div>
                {{-- All income bar chart end --}}

                {{-- All income pie chart start --}}
                <div class="col-lg-6 col-md-6 col-6 col-sm-6">
                    <div class="card mt-sm-5 mt-md-0">
                      <div class="card-header">
                        <h4>Total Income by Year</h4>
                      </div>
                      <div class="card-body">
                        <canvas id="donutChart"></canvas>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-6 col-sm-6">
                                <ul class="p-t-30 list-unstyled">
                                    <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-black"></i></span>Food City<span
                                        class="float-right">{{ number_format(($foodCitySalesMonth->sum('totalSaleAmount')),2)}}</span></li>
                                    {{-- <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-green"></i></span>Restaurant<span
                                        class="float-right">{{ number_format(($restaurantSalesMonth->sum('totalSaleAmount')),2)}}</span></li> --}}
                                    {{-- <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-orange"></i></span>Reecha Main<span
                                        class="float-right">{{ number_format(($reechaMainSalesMonth->sum('totalSaleAmount')),2)}}</span></li> --}}
                                    {{-- <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-pink"></i></span>Other Income<span
                                        class="float-right">{{ number_format(($otherSalesMonth->sum('totalSaleAmount')),2)}}</span></li> --}}
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-6 col-6 col-sm-6">
                                <ul class="p-t-30 list-unstyled">
                                    {{-- <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-blue"></i></span>Hotel<span
                                        class="float-right">{{ number_format(($hotelSalesMonth->sum('totalSaleAmount')),2)}}</span></li> --}}
                                    {{-- <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-red"></i></span>Farm<span
                                        class="float-right">{{ number_format(($farmSalesMonth->sum('totalSaleAmount')),2)}}</span></li> --}}
                                    {{-- <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-yellow"></i></span>Bank<span
                                        class="float-right">{{ number_format(($bankSalesMonth->sum('totalSaleAmount')),2)}}</span></li> --}}
                                </ul>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
                {{-- All income pie chart end --}}
            </div>
            <div class="row">
                {{-- Food city income start --}}
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Food City</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="foodcityChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($foodCitySalesToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($foodCitySalesWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($foodCitySalesMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($foodCitySalesMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div>
                {{-- Food city income end --}}

                {{-- Restaurant income start --}}
                {{-- <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Restaurant</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="restaurantChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($restaurantSalesToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($restaurantSalesWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($restaurantSalesMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($restaurantSalesMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div> --}}
                {{-- Restaurant income end --}}
            </div>
            <div class="row">
                {{-- Reecha Main income start --}}
                {{-- <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Reecha Main</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="reechaMainChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($reechaMainSalesToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($reechaMainSalesWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($reechaMainSalesMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($reechaMainSalesMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div> --}}
                {{-- Reecha Main income end --}}

                {{-- Hotel income start --}}
                {{-- <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Hotel</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="hotelChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($hotelSalesToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($hotelSalesWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($hotelSalesMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($hotelSalesMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div> --}}
                {{-- Hotel income end --}}
            </div>
            <div class="row">
                {{-- Farm income start --}}
                {{-- <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Farm</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="farmChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($farmSalesToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($farmSalesWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($farmSalesMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($farmSalesMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div> --}}
                {{-- Farm income end --}}

                {{-- Bank income start --}}
                {{-- <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bank</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="bankChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($bankSalesToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($bankSalesWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($bankSalesMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($bankSalesMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div> --}}
                {{-- Bank income end --}}
            </div>
            <div class="row">
                {{-- Other income start --}}
                {{-- <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Other Income</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="otherChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($otherSalesToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($otherSalesWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($otherSalesMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($otherSalesMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div> --}}
                {{-- Other income end --}}
            </div>
        </div>
    </section>
@endsection
