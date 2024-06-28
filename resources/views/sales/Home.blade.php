@extends('layouts.navigation')
@section('content')
<input type="text" id="chart-sales" value="{{$salesReportMonth->pluck('totalSales')}}" hidden>
  <div class="card-body">
    <div class="row pb-3">
      <div>
        <p class="h5">Today Sales</p>
      </div>
    </div>
  </div>
  <div class="row ">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Number of Sales</h5>
                  <h2 class="mb-3 font-18">{{$salesReport->count()}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15"> Revenue</h5>
                  <h2 class="mb-3 font-18">{{number_format(($salesReport->sum('totalAmount') - $salesReport->sum('discount_amount')),2)}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Discount</h5>
                  <h2 class="mb-3 font-18">{{number_format($salesReport->sum('discount_amount'),2)}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Cash Payment</h5>
                  <h2 class="mb-3 font-18">{{number_format($salesReport->sum('cash_payment'),2)}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Card Payment</h5>
                  <h2 class="mb-3 font-18">{{number_format($salesReport->sum('card_payment'),2)}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Loyalty Payment</h5>
                  <h2 class="mb-3 font-18">{{number_format($salesReport->sum('loyality_payment'),2)}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Credit Payment</h5>
                  <h2 class="mb-3 font-18">{{number_format($salesReport->sum('credit_payment'),2)}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Cheque Payment</h5>
                  <h2 class="mb-3 font-18">{{number_format($salesReport->sum('cheque_payment'),2)}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
      <div class="card ">
        <div class="card-header">
          <h4>Revenue chart - {{ $year }}</h4>
          <div class="card-header-action">
            <a href="/pages/sales-report" class="btn btn-primary">View All</a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-9">
              <div id="chart1"></div>
              <div class="row mb-0">
              </div>
            </div>
            <div class="col-lg-3">
              <div class="row mt-5">
                <div class="col-7 col-xl-7 mb-3">Total Sales</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">{{$totalNumberSales->numberSales}}</span>
                </div>
                <div class="col-7 col-xl-7 mb-3">Total Income</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">Rs. {{number_format($totalNumberSales->totalSales,2)}} </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
