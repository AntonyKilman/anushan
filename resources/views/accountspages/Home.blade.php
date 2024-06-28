@extends('layouts.navigation')
@section('content')

<?php 
  $Access=session()->get('Access'); 
  $rest=false;
  $main=false;
  if(in_array('rest.show', $Access)){
  $rest=true;
  }
  if(in_array('main.show', $Access)){
    $main=true;
  }
?>

<div class="row ">
  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card">
      <div class="card-statistic-4">
        <div class="align-items-center justify-content-between">
          <div class="row ">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">Total Annual Income</h5>              
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">{{number_format(($totalSaleYear),2)}}</h5>              
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card">
      <div class="card-statistic-4">
        <div class="align-items-center justify-content-between">
          <div class="row ">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">Total Annual Expenses</h5>              
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">{{number_format(($totalExpense),2)}}</h5>              
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card">
      <div class="card-statistic-4">
        <div class="align-items-center justify-content-between">
          <div class="row ">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">Total Last Month Income</h5>              
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">{{number_format(($totalLastMonth),2)}}</h5>              
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card">
      <div class="card-statistic-4">
        <div class="align-items-center justify-content-between">
          <div class="row ">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">Total Last Month Expenses</h5>              
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">{{number_format(($totalLastMonthExpense),2)}}</h5>              
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


{{--<div class="row">
  <div class="col-12 col-md-6 col-lg-3">
    <div class="card card-primary">
      <div class="card-header">
        <h4>Reecha Rest</h4>
      </div>
      <div class="card-body">
        <p>
            <form method="POST" action="{{ route('login_access.store') }}">
               @csrf
               <input type="text" name="url" value="https://www.reechafoodorders.skyrow.lk" hidden>
               @if($rest==true)
               <button type="submit" class="btn btn-info pt-2" name="submit"> Reecha-Rest</button> 
               @else 
               <button type="submit" class="btn btn-info pt-2" name="submit" disabled> Reecha-Rest</button> 
               @endif
            </form>
        </p>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-lg-3">
    <div class="card card-secondary">
      <div class="card-header">
        <h4>Reecha Main</h4>
      </div>
      <div class="card-body">
        <p>
          <form method="POST" action="{{ route('login_access.store') }}">
             @csrf
             <input type="text" name="url" value="https://www.reechamain.skyrow.lk" hidden>
             @if($main==true)
             <button type="submit" class="btn btn-info pt-2" name="submit"> Reecha-Main</button> 
             @else 
             <button type="submit" class="btn btn-info pt-2" name="submit" disabled> Reecha-Main</button> 
             @endif
          </form>
      </p>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-lg-3">
    <div class="card card-danger">
      <div class="card-header">
        <h4>Reecha Hotel</h4>
      </div>
      <div class="card-body">
        <p>
          <form method="POST" action="{{ route('login_access.store') }}">
             @csrf
             <input type="text" name="url" value="https://www.adminreechabooking.skyrow.lk" hidden>
             @if($hotel==true)
             <button type="submit" class="btn btn-info pt-2" name="submit"> Reecha-Hotel</button> 
             @else 
             <button type="submit" class="btn btn-info pt-2" name="submit" disabled> Reecha-Hotle</button> 
             @endif
          </form>
      </p>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-lg-3">
    <div class="card card-warning">
      <div class="card-header">
        <h4>Reecha Farm
          <div class="card-body">
          
        </div>
        </h4>
      </div>
      <div class="card-body">
        <p>
          <form method="POST" action="{{ route('login_access.store') }}">
             @csrf
             <input type="text" name="url" value="#" hidden>
             @if($farm==true)
             <button type="submit" class="btn btn-info pt-2" name="submit"> Reecha-Farm</button> 
             @else 
             <button type="submit" class="btn btn-info pt-2" name="submit" disabled> Reecha-Farm</button> 
             @endif
          </form>
      </p>
      </div>
    </div>
  </div>
</div>--}}
@endsection