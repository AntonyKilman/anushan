@extends('layouts.navigation')
@section('dashboard','active')
@section('content')

<?php 
  $Access=session()->get('Access'); 
  $IndoorTransferShow=false;
  $indoorReturnShowAll=false;
  $outdoorReturnShowAll=false;
  $experyDateItemShow=false;
  $equipmentTransferShow=false;

  if (in_array('inventory.IndoorTransferShow', $Access)) {
    $IndoorTransferShow=true;
  }

  if (in_array('inventory.indoorReturnShowAll', $Access)) {
    $indoorReturnShowAll=true;
  }

  if (in_array('inventory.outdoorReturnShowAll', $Access)) {
    $outdoorReturnShowAll=true;
  }

  if (in_array('inventory.experyDateItemShow', $Access)) {
    $experyDateItemShow=true;
  }

  if (in_array('inventory.equipmentTransferShow', $Access)) {
    $equipmentTransferShow=true;
  }
          
?>

     <!-- Main Content -->
     <div>
        <section class="section">

          <div class="row">

            @if ($IndoorTransferShow)
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <a href="/IndoorTransferShow">
                <div class="card">
                  <div class="card-statistic-4">
                    <div class="align-items-center justify-content-between">
                      <div class="row ">
                        <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                          <div class="card-content">
                            <h5 class="font-15">Indoor Transfer</h5>
                            <h2 class="mb-3 font-18">{{$transfers}}</h2>
                            <p class="mb-0">Pending</p>
                          </div>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                          <div class="banner-img">
                            <img src="/assets/img/banner/1.png" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            @endif

            @if ($indoorReturnShowAll)
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <a href="/indoor-return-show-all">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15"> Indoor Return</h5>
                          <h2 class="mb-3 font-18">{{$indoorRetrun}}</h2>
                          <p class="mb-0">Pending</p>
                        </div>
                      </div>
                      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="/assets/img/banner/2.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div>
            @endif

            @if ($outdoorReturnShowAll)
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <a href="/outdoor-return-show-all">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Outdoor Return</h5>
                          <h2 class="mb-3 font-18">{{$outdoor_returns}}</h2>
                          <p class="mb-0">Pending</p>
                        </div>
                      </div>
                      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="/assets/img/banner/3.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div>
            @endif

            @if ($experyDateItemShow)
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <a href="/expery-products-show">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Expire Products</h5>
                          <h2 class="mb-3 font-18">{{$experydate_count}}</h2>
                          <p class="mb-0">10 days details</p>
                        </div>
                      </div>
                      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="/assets/img/banner/4.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div>
            @endif
          
          </div>
            
          <div class="row">

            @if ($equipmentTransferShow)
               <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <a href="/equipmentTransferShow">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Equipment Transfer</h5>
                          <h2 class="mb-3 font-18">{{$equipments_count}}</h2>
                          <p class="mb-0">Pending</p>
                        </div>
                      </div>
                      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="/assets/img/banner/5.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div> 
            @endif

            @if (true)
               <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <a href="/product-qty-alert-showall?id=1">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Product Danger Quantity</h5>
                          <h2 class="mb-3 font-18">{{$danger}}</h2>
                          <p class="mb-0">&nbsp;</p>
                        </div>
                      </div>
                      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="/assets/img/banner/5.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div> 
            @endif

            @if (true)
               <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <a href="/product-qty-alert-showall?id=2">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Product Minimum Quantity</h5>
                          <h2 class="mb-3 font-18">{{$min}}</h2>
                          <p class="mb-0">&nbsp;</p>
                        </div>
                      </div>
                      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="/assets/img/banner/5.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div> 
            @endif

            @if (true)
               <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <a href="/product-qty-alert-showall?id=3">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Product Max Quantity</h5>
                          <h2 class="mb-3 font-18">{{$max}}</h2>
                          <p class="mb-0">&nbsp;</p>
                        </div>
                      </div>
                      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="/assets/img/banner/5.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div> 
            @endif

            
          </div>
          </section>
       </div>

@endsection