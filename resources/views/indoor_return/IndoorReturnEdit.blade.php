@extends('layouts.navigation')
@section('indoor_return','active')
@section('content')
<?php
  $Access=session()->get('Access');
?>

      <!-- Main Content -->
      <div class="card">
    <form action="/indoor-return-update-process" method="post"  class="needs-validation" novalidate="">
      @csrf
        <div class="card-header">
            <h4>Indoor Return</h4>
        </div>
        <input type="hidden" name="id" value="{{$indoor_return->id}}">
        <input type="hidden" name="purchase_unit_price" value="{{$indoor_return->purchase_unit_price}}">

        <div class="card-body form" style="width: 100%">

          {{-- first row --}}
        <div class="form-row">
          <div class="form-group col-md-4">
            <label>Purchase Order Id</label>
              <input type="text" value="{{$indoor_return->purchase_order_id}}" id="purchase_order_id" name="purchase_order_id" class="form-control" readonly >
              @error('purchase_order_id')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
          </div>
          <div class="form-group col-md-4">
            <label>Product</label>
                <input type="hidden" value="{{$indoor_return->product_id}}" id="product_id" name="product_id" class="form-control" readonly>
                <input type="text" value="{{$indoor_return->product_name}}" id="product_name" name="product_name" class="form-control" readonly>
                @error('product_id')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
          </div>
          <div class="form-group col-md-4">
            <label>Supplier</label>
                <input type="hidden" value="{{$indoor_return->seller_id}}" id="seller_id" name="seller_id" class="form-control" readonly>
                <input type="text" value="{{$indoor_return->seller_name}}" id="seller_name" name="seller_name" class="form-control" readonly>
                @error('seller_id')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
          </div>
        </div>

          {{-- second row --}}
          <div class="form-row">
            <div class="form-group col-md-3">
              <label>Reason</label>
              <input type="hidden" value="{{$indoor_return->return_reason_id}}" id="return_reason_id" name="return_reason_id" class="form-control" readonly>
              <input type="text" value="{{$indoor_return->reason_name}}" id="reason_name" name="reason_name" class="form-control" readonly>
                @error('return_reason_id')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-3">
              <label>Quantity</label>
                <input type="text" value="{{$indoor_return->qty}}" id="qty" name="qty" class="form-control" readonly>
                @error('qty')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-3">
              <label>Return Quantity</label>
                <input type="text" value="{{$indoor_return->return_qty}}" id="return_qty" name="return_qty" class="form-control" readonly>
                @error('return_qty')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-3">
              <label>Select Status</label>
              <select id="status" name="status" class="form-control" required>
                <option value="" disabled selected>Select Status</option>
                <option value="0" {{$indoor_return->status==0?'selected':''}}>Pending</option>
                <option value="1" {{$indoor_return->status==1?'selected':''}}>Approved</option>
                <option value="2" {{$indoor_return->status==2?'selected':''}}>Rejected</option>
            </select>
            </div>
          </div>

            <div class="card-footer text-right">
              <button type="reset" class="btn btn-danger">Reset</button>
              <button class="btn btn-success mr-1" type="submit">Submit</button>
            </div>
        </div>
    </form>
      </div>

@endsection
