@extends('layouts.navigation')
@section('equipment_transfer','active')
@section('content')
<?php
  $Access=session()->get('Access');
?>


    <!-- Main Content -->
    <div class="card">
    <div class="card-header">
        <h4>Equipment Transfer</h4>
    </div>

    <form action="/equipmentTransferUpdate" method="post" class="needs-validation" novalidate="">
        @csrf

        @foreach ($equipments as $equipment)

            <input type="hidden" name="id" value="{{ $equipment->equTransId }}" class="form-control">
            <input type="hidden" name="reason" value="{{ $equipment->reason }}" class="form-control">
            <input type="hidden" name="userEnter" value="{{ $equipment->userEnter }}" class="form-control">
            <input type="hidden" name="purchase_unit_price" value="{{ $equipment->purchase_unit_price }}" class="form-control"
                            >

            <div class="card-body form">


                <div class="row">
                    <div class="form-group col-md-4">
                        <label>GR Number</label>
                        <input type="text" name="purchase_id" value="{{ $equipment->purchase_id }}" class="form-control"
                            readonly>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Product</label>
                        <input type="text" name="product_name" value="{{ $equipment->product_name }}" class="form-control"
                            readonly>
                        <input type="hidden" name="product_id" value="{{ $equipment->product_id }}" class="form-control"
                            readonly>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Quantity</label>
                        <input type="text" name="quantity" value="{{ $equipment->quantity }}" class="form-control"
                            readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Purchase Date</label>
                        <input type="date" name="purchase_date" value="{{ $equipment->purchaseDate }}"
                            class="form-control" readonly>
                    </div>

                    <div class="form-group col-md-4">
                        <label>No Of Days</label>
                        <input type="text" name="no_days" value="{{ $equipment->noOfDays }}" class="form-control"
                            readonly>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Employee</label>
                        <input type="text" name="employee_code" value="{{ $equipment->emp_code }}" class="form-control"
                            readonly>
                        <input type="hidden" name="employee_id" value="{{ $equipment->employee_id }}"
                            class="form-control">
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-md-4">
                        <label>Select Status</label>
                        <select class="form-control" name="status" required>
                            <option value="" disabled selected>Select Status</option>
                            <option name="brand_id" value="1" {{$equipment->status==1?'selected':''}}>Pending</option>
                            <option name="brand_id" value="0" {{$equipment->status==0?'selected':''}}>Got it</option>
                            <span class="text-danger">@error('status') {{ $message }}@enderror</span>
                            </select>
                        </div>

                        <div class="form-group col-md-8">
                            <label>Discription</label>
                            <textarea name="discription" class="form-control">{{$equipment->discription}}</textarea>
                        </div>
                    </div>
                    <input type="hidden" name="department_id" value="{{ $equipment->department_id }}" class="form-control">




                    <div align='right'>
                        <button type="reset" class="btn btn-danger" id="reset">Reset</button>
                        <button class="btn btn-success mr-1" type="submit">Submit</button>
                    </div>
                </div>
            @endforeach
        </form>

    </div>

    @endsection
