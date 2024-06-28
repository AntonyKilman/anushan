@extends('layouts.navigation')
@section('content')
    <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    
  
    
    ?>
    <section class="section">
     <div class="card-header">
          <h4>Other Expense</h4>
      </div>
      <input type="hidden" name="id" value="{{$other_expenses->id}}">
      <div class="card-body form" style="width: 100%">

      {{-- first row --}}
      <div class="form-row">


        
        <div class="form-group col-md-3">
          <label>Expense Type</label>
            <input type="text" value="{{$other_expenses->name}}" id="name" name="name" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Employee</label>
            <input type="text" value="{{$other_expenses->f_name}}" id="name" name="name" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Department</label>
            <input type="text" value="{{$other_expenses->acc_dept_name}}" id="name" name="name" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Date</label>
              <input type="text" value="{{$other_expenses->date}}" id="date" name="date" class="form-control" readonly>
        </div>
        <div class="form-group col-md-3">
          <label>Amount</label>
              <input type="text" value="{{$other_expenses->oth_exp_amount}}" id="oth_exp_amount" name="oth_exp_amount" class="form-control"  readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Cash</label>
            <input type="text" value="{{$other_expenses->oth_exp_cash}}" id="oth_exp_cash" name="oth_exp_cash" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Cheaque</label>
              <input type="text" value="{{$other_expenses->oth_exp_cheque}}" id="oth_exp_cheque" name="oth_exp_cheque" class="form-control" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Cheaque Number</label>
              <input type="text" value="{{$other_expenses->oth_exp_cheque_no}}" id="oth_exp_cheque_no" name="oth_exp_cheque_no" class="form-control" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Cheaque Date</label>
            <input type="text" value="{{$other_expenses->oth_exp_cheque_date}}" id="oth_exp_cheque_date" name="oth_exp_cheque_date" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Bank</label>
            <input type="text" value="{{$other_expenses->account_no}}" id="bank_id" name="bank_id" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
        </div>

        <div class="form-group col-md-3">
          <label>Card/Online</label>
              <input type="text" value="{{$other_expenses->oth_exp_online}}" id="oth_exp_online" name="oth_exp_online" class="form-control" readonly>
        </div>
        <div class="form-group col-md-3">
          <label>Reference Number</label>
              <input type="text" value="{{$other_expenses->oth_exp_reference_no}}" id="oth_exp_reference_no" name="oth_exp_reference_no" class="form-control" readonly>
        </div>

        <div class="form-group col-md-6">
          <label>Reason</label>
          <textarea class="form-control" id="oth_exp_reason" name="oth_exp_reason" readonly>{{$other_expenses->oth_exp_reason}}</textarea>
        </div>
      </div>
      {{--End First row--}}

      {{-- second row --}}
      <div class="form-row">
          
          
         

        </div>
      {{--End Second row--}}

       {{-- third row --}}
       <div class="form-row">
          
         
        </div>
      {{--End third row--}}

       {{-- forth row --}}
       <div class="form-row">
          
          {{-- <div class="form-group col-md-8">
            <label>Description</label>
               <textarea class="form-control" id="oth_exp_description" name="oth_exp_description" readonly>{{$other_expenses->oth_exp_description}}</textarea>
          </div> --}}
          
      {{--End forth row--}}

     
    </section>
@endsection



