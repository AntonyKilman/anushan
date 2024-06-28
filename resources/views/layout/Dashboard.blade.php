@extends('layouts.navigation')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>
<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card">
      <div class="card-statistic-4">
        <div class="align-items-center justify-content-between">
          <div class="row ">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">Indo11or Return</h5>
                <h2 class="mb-3 font-18">02</h2>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
              <div class="banner-img">
                <img src="/assets/img/banner/1.png" alt="">
                <a href="/indoor-return-show-all" class="dropdown-item has-icon"> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>




    
@endsection