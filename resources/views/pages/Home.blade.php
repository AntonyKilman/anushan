@extends('layouts.navigation')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

<?php 
  $Access=session()->get('Access'); 
  $rest=false;
  $hotel=false;
  $farm=false;
  $main=false;

  if(in_array('rest.show', $Access)){
  $rest=true;
  }

  if(in_array('hotel.show', $Access)){
  $hotel=true;
  }

  if(in_array('main.show', $Access)){
    $main=true;
  }

  if(in_array('farm.show', $Access)){
    $farm=true;
  }
?>

<div class="row ">
  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card">
      <div class="card-statistic-4">
        <div class="align-items-center justify-content-between">
          <div class="row ">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">New Booking</h5>
                <h2 class="mb-3 font-18">
                  <?php 
                  $c=0;
                  foreach ($orders as $order ) {
                    if($order->event_status==0){
                      $c=$c+1;
                    }
                    
                  }
                  
                  ?>
                  {{ $c }}
                </h2>
              
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
              <div class="banner-img">
                <img src="{{ asset('/assets/img/banner/1.png') }}" alt="">
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
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15"> Customers</h5>
                <h2 class="mb-3 font-18">{{ $customer->count() }}</h2>
            
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
              <div class="banner-img">
                <img src="{{ asset('/assets/img/banner/2.png') }}" alt="">
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
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">Event(s)</h5>
                <h2 class="mb-3 font-18">{{ $main_events->count() }}</h2>
             
            
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
              <div class="banner-img">
                <img src="{{ asset('/assets/img/banner/3.png') }}" alt="">
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
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
              <div class="card-content">
                <h5 class="font-15">Revenue</h5>
                <h2 class="mb-3 font-18">
                  <?php 
                  $t=0.00;
                  foreach ($orders as $order ) {
                    if($order->payment_status==1){
                      $t=$t+$order->total_amount;
                    }
                    
                  }
                  echo "Rs.".number_format($t, 2, '.', '');
                  ?>
                </h2>
             
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
              <div class="banner-img">
                <img src="{{ asset('/assets/img/banner/4.png') }}" alt="">
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
      <div class="card">
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
      <div class="card">
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
      <div class="card">
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
      <div class="card">
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