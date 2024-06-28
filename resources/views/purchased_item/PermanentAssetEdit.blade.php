@extends('layouts.navigation')
@section('permanent_assets','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

      <!-- Main Content -->
      <div class="card">
    <form action="/permanent-asset-update-process" method="post" class="needs-validation" novalidate="" enctype="multipart/form-data">
      @csrf
        <div class="card-header">
            <h4>Permanent Assets</h4>
        </div>
        
        <div class="card-body form" style="width:100%">
            <input type="hidden" name="id" value="{{$permanent_assets->id}}">
            <input type="hidden" name="product_id" value="{{$permanent_assets->product_id}}">
             {{-- first row --}}
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Product Name</label>
              <input type="text" id="product_name" name="product_name" class="form-control" value="{{$permanent_assets->product_name}}">
              @error('product_name')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-4">
                <label>Product Code</label>
                <input type="text" id="product_code" name="product_code" class="form-control" value="{{$permanent_assets->product_code}}">
                @error('product_code')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
              </div>
            <div class="form-group col-md-4">
              <label>Purchase Order Id</label>
              <input type="text" id="purchase_order_id" name="purchase_order_id" class="form-control" value="{{$permanent_assets->purchase_order_id}}">
                @error('purchase_order_id')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
          </div>

           {{-- second row --}}
           <div class="form-row">
            <div class="form-group col-md-4">
                <label>Quantity Type</label>
                <input type="text" id="pur_item_qty_type" name="pur_item_qty_type" class="form-control" value="{{$permanent_assets->pur_item_qty_type}}">
                  @error('pur_item_qty_type')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-4">
                <label>Quantity</label>
                <input type="text" id="pur_item_qty" name="pur_item_qty" class="form-control" value="{{$permanent_assets->pur_item_qty}}">
                  @error('pur_item_qty')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-4">
                <label>Price</label>
                <input type="text" id="pur_item_amount" name="pur_item_amount" class="form-control" value="{{$permanent_assets->pur_item_amount}}">
                  @error('pur_item_amount')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
          </div>


          {{-- third row --}}
          <div class="form-row">
            <div class="form-group col-md-3">
                <label>Serial Number</label>
                <input type="text" id="serial_number" name="serial_number" class="form-control" value="{{$permanent_assets->serial_number}}">
                  @error('serial_number')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-3">
                <label>Warranty</label>
                <input type="date" id="warrenty" name="warrenty" class="form-control" value="{{$permanent_assets->warranty}}">
                  @error('warranty')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-6">
                <label>Description</label>
                <textarea name="description" id="description" cols="30" rows="1" class="form-control">{{$permanent_assets->description}}</textarea>
                @error('description')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
          </div>

          <div class="form-row">
              <div class="form-group col-md-3">
                <label for="Status">Select Status</label>
              <select class="form-control" name="status" id="status" required>
                <option value="" {{$status_id==''?'selected':''}} disabled selected>Select</option>
                @foreach ($assets_status as $status)
                    <option value="{{$status->id}}" {{$status_id==$status->id?'selected':''}}>{{$status->status_name}}</option>
                @endforeach
              </select>
              </div>
          </div>
           
            <div align="right">
                <button type="reset" class="btn btn-danger">Reset</button>
                <button class="btn btn-success mr-1" type="submit">Submit</button>
            </div>
        </div>
    </form>    
      </div>
@endsection