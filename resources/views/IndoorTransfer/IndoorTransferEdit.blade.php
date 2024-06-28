@extends('layouts.navigation')
@section('indoor_transfer','active')
@section('content')
<?php
  $Access=session()->get('Access');
?>


      <!-- Main Content -->
      <div class="card">

      <div class="card-header">
        <h4>Indoor Transfer</h4>
    </div>


    <form action="/IndoorTransferUpdate" method="post" class="needs-validation" novalidate="">
        @csrf

        <div class="card-body form">

            <div class="row">
                @foreach ($Transfers as $Transfer)

                    <input type="hidden" value="{{ $Transfer->id }}" name="id" class="form-control">

                    <div class="form-group col-md-4">
                        <label>GR Number</label>
                        <input type="text" value="{{ $Transfer->purchase_id }}" name="purchase_order_id"
                            class="form-control" readonly>

                    </div>

                    <div class="form-group col-md-4">
                        <label>Product</label>
                        <input type="text" value="{{ $Transfer->product_name }}" name="product_name" class="form-control"
                            readonly>
                        <input type="hidden" value="{{ $Transfer->product_id }}" name="product_id" class="form-control">
                    </div>


                    <div class="form-group col-md-4">
                        <label>Department</label>
                        <input type="text" value="{{ $Transfer->dept_name }}" name="department_name" class="form-control"
                            readonly>
                        <input type="hidden" value="{{ $Transfer->department_id }}" name="department_id"
                            class="form-control">
                    </div>
            </div>

            <div class="row">

                <div class="form-group col-md-4">
                    <label>Transfer Quantity</label>
                    <input type="text" value="{{ $Transfer->transfer_quantity }}" name="transfer_qty" class="form-control"
                        readonly>
                    <input type="hidden" value="{{ $Transfer->quantity }}" name="qty" class="form-control">
                    @error('purchase_order_id')<span
                        style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>@enderror
                </div>

                <div class="form-group col-md-4">
                    <label>Expiry Date</label>
                    <input type="text" value="{{ $Transfer->exDate }}" purchase_order_id" name="ex_date"
                        class="form-control" readonly>
                    @error('purchase_order_id')<span
                        style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>@enderror
                </div>

                <div class="form-group col-md-4">
                    <label>Unit Price</label>
                    <input type="number" step="0.01" value="{{ $Transfer->purchase_unit_price }}"  name="purchase_unit_price"
                        class="form-control" readonly>
                </div>

            </div>

            <div class="row">

                <div class="form-group col-md-4">
                    <label>Select Status</label>
                    <select class="form-control productTransfer" id="product_id" name="status" required>
                        <option value="" disabled selected>Select Status</option>
                        <option name="dept_id" class="dept_id" id="dept_id" value="1">Accepted</option>
                        <option name="dept_id" class="dept_id" id="dept_id" value="2">Rejected</option>
                    </select>
                </div>


                @if($Transfer->department_id==2)
                <div class="form-group col-md-4">
                    <label>sales Price</label>
                    <input type="number" step="0.01"   name="sales_price" required
                        class="form-control">
                </div>


                @endif




            </div>



            <div align="right">
                <button type="reset" class="btn btn-danger" id="reset">Reset</button>
                <button class="btn btn-success mr-1" type="submit" id="submit">Submit</button>
            </div>
        </div>
        @endforeach
    </form>

      </div>

@endsection
