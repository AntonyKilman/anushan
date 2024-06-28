@extends('layouts.navigation')
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
    <form>
    <section class="section">
     <div class="card-header">
          <h4>Service Expense</h4>
      </div>
      <input type="hidden" name="id" value="{{$service_expenses->id}}">
      <div class="card-body form" style="width: 100%">

      <div class="form-row">
        <div class="form-group col-md-3">
          <label>Service Provider</label>
            <input type="text" value="{{$service_expenses->name }}" id="service_provider_id " name="service_provider_id " class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
        </div>
        <div class="form-group col-md-3">
          <label>Date</label>
              <input type="text" value="{{$service_expenses->date}}" id="date" name="date" class="form-control" readonly>
        </div>
        <div class="form-group col-md-3">
          <label>Amount</label>
              <input type="text" value="{{$service_expenses->amount}}" id="amount" name="amount" class="form-control" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Cash</label>
            <input type="text" value="{{$service_expenses->ser_exp_cash}}" id="ser_exp_cash" name="ser_exp_cash" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
        </div>

      </div>


      <div class="form-row">
         
          <div class="form-group col-md-3">
            <label>Cheque</label>
                <input type="text" value="{{$service_expenses->ser_exp_cheque}}" id="ser_exp_cheque" name="ser_exp_cheque" class="form-control" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Cheque Number</label>
                <input type="text" value="{{$service_expenses->ser_exp_cheque_no}}" id="ser_exp_cheque_no" name="ser_exp_cheque_no" class="form-control" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Cheque Date</label>
              <input type="text" value="{{$service_expenses->ser_exp_cheque_date}}" id="ser_exp_cheque_date" name="ser_exp_cheque_date" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Bank</label>
              <input type="text" value="{{$service_expenses->account_no}}" id="bank_id" name="bank_id" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
          </div>

          
          <div class="form-group col-md-3">
            <label>Card/Online</label>
                <input type="text" value="{{$service_expenses->ser_exp_online}}" id="ser_exp_online" name="ser_exp_online" class="form-control" readonly>
          </div>

          <div class="form-group col-md-3">
            <label>Reference Number</label>
                <input type="text" value="{{$service_expenses->ser_exp_reference_no}}" id="ser_exp_reference_no" name="ser_exp_reference_no" class="form-control" readonly>
          </div>

          
          <div class="form-group col-md-3">
            <label>Status</label>
            <?php 
              if ($service_expenses->status=="1") {
                $status_val="Paid";
              }

              if ($service_expenses->status=="0") {
                $status_val="Unpaid";
              }
              
            ?>
            <input input type="text" class="form-control" name="status" id="status" value="{{$status_val}}"readonly >

            {{-- <select class="form-control" name="status" id="status" value="{{old('status')}}"readonly > --}}
              {{-- <option value="" disabled selected>Select status</option> --}}
              {{-- <option value="1" {{$service_expenses->status==1?'selected':''}}readonly>Active</option>
              <option value="0" {{$service_expenses->status==0?'selected':''}}readonly>Deactive</option>

            </select> --}}
          {{-- <input input type="text" class="form-control" name="status" id="status" value="{{$service_expenses->status}}"readonly > --}}
              {{-- <option value="" disabled selected>Select status</option>
              <option value="1" {{$service_expenses->status==1?'selected':''}} readonly>Active</option>
              <option value="0" {{$service_expenses->status==0?'selected':''}}readonly>Deactive</option> --}}
{{-- 
              <option value="1">Active </option>
              <option value="0">Deactive</option> --}}
          
            {{-- <input type="text" value="{{$service_expenses->status==1?'Active':''}}" id="status" name="status" class="form-control" readonly>
            <input type="text" value="{{$service_expenses->status==0?'Deactive':''}}" id="status" name="status" class="form-control" readonly> --}}
          </div>
          <div class="form-group col-md-6">
            <label>Description</label>
            <textarea class="form-control" id="description" name="description" readonly>{{$service_expenses->description}}</textarea>
          </div>  

          <div class="card-footer text-right">
               
          </div>

        </div>

          
       <div class="form-row">
         
        


        </div>
      

       <div class="form-row">
        {{-- @if ($file_1=='jpg' )
            
        @endif --}}
        <div class="form-group col-md-4">
          <label>Image</label><br>
          <img src="{{asset('ServiceExpenses/'.$service_expenses->image)}}" alt="image" width="50" height="50">
              {{-- <img src="ServiceExpenses/{{$service_expenses->image}}" class="css-class" alt="Null" style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;"> --}}
        </div>

       

      </div>
    </div>
  </form>        
    </section>
@endsection



