@extends('layouts.navigation')
@section('perchase_order_request','active')
@section('content')

<?php 
  $Access=session()->get('Access'); 
  $purchaseOrederReqAdd=false;
  $purchaseOrederReqEdit=false;
  if (in_array('inventory.purchaseOrderRequestAdd', $Access)) {
    $purchaseOrederReqAdd=true;
  }
  if (in_array('inventory.purchaseorderRequestUpdate', $Access)) {
    $purchaseOrederReqEdit=true;
  }
?>


    <!-- Main Content -->
<div class="card">
    <div class="card-header">
        <h4 class="header">Purchase Order Request</h4>
        @if ($purchaseOrederReqAdd)
            <a href="/PurchaseOrderRequestGet" class="btn btn-success" data-toggle="modal"
                data-target="#AddPurchaseOrderRequest">Add</a>
        @endif
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-1">
                <thead>
                    <tr>
                        <th style="display: none">#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Quantity Type</th>
                        <th>Department</th>
                        <th>Description</th>
                        @if ($purchaseOrederReqEdit)
                            <th class='action'>Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datas as $data)
                    {{--  {{$data}}  --}}
                        <tr>
                            <td style="display: none">#</td>
                            <td>{{ $data->product_name }}</td>
                            <td>{{ $data->pur_req_qty }}</td>
                            <td>{{ $data->pur_req_qty_type }}</td>
                            <td>{{ $data->dept_name }}</td>
                            <td>{{ $data->pur_req_des }}</td>
                            @if ($purchaseOrederReqEdit)
                                <td class='action'>
                                    <button data-toggle="modal" data-dept_name="{{ $data->dept_name }}"
                                        data-id="{{ $data->id }}" data-name="{{ $data->product_name }}"
                                        data-qty="{{ $data->pur_req_qty }}" data-type="{{ $data->pur_req_qty_type }}"
                                        data-des="{{ $data->pur_req_des }}" data-status="{{ $data->pur_req_status }}"
                                        data-pro="{{ $data->product_id }}" data-department_id="{{ $data->department_id }}"
                                        data-reason="{{ $data->pur_req_reason }}"
                                        data-target="#EditPurchaseOrderRequest" title="edit" class="btn btn-primary btn-edit"><i
                                            class="far fa-edit"></i></button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>


            </table>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {

            $('.btn-edit').on('click', function() {

                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                var qty = $(this).attr('data-qty');
                var type = $(this).attr('data-type');
                var des = $(this).attr('data-des');
                var products = $(this).attr('data-pro');
                var status = $(this).attr('data-status');
                var dept_name = $(this).attr('data-dept_name');
                var department_id = $(this).attr('data-department_id');
                var reason=$(this).attr('data-reason');

                $('#id').val(id);
                $('#name').val(name);
                $('#qty').val(qty);
                $('#type').val(type);
                $('#des').val(des);
                $('#status').val(status);
                $('#products').val(products);
                $('#departments').val(department_id);
                $('#reason').val(reason);


            });

        });
    </script>
@endsection

<!-- Edit Purchase Order Request -->
<div class="modal fade" id="EditPurchaseOrderRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Purchase Order Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/purchaseorderRequestUpdate" method="post" class="needs-validation" novalidate="">


                    <div class="card-body form">
                        @csrf

                        <input type="hidden" class="form-control" name="id" id="id" required>

                        <div class="form-row">

                            <div class="form-group col-md-4">

                                <div class="form-group">
                                    <label>Select Product</label>
                                    <select class="form-control" id="products" name="product_id" required>
                                        <option value="" disabled selected>Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">@error('product_id')
                                            {{ $message }}@enderror</span>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Quantity</label>
                                    <input type="text" class="form-control" id="qty" pattern="^\d+(\.\d)?\d*$" name="pur_req_qty">
                                    <span class="text-danger">@error('pur_req_qty') {{ $message }}@enderror</span>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Quantity Type</label>
                                        <select class="form-control" name="pur_req_qty_type" id="type" required>
                                            <option value="" disabled selected>Select Quantity Type</option>
                                            <option name="pur_req_qty_type" value="count">count</option>
                                            <option name="pur_req_qty_type" value="liter">liter</option>
                                            <option name="pur_req_qty_type" value="kg">kg</option>
                                            <option name="pur_req_qty_type" value="meter">meter</option>
                                            <span class="text-danger">@error('pur_req_qty_type')
                                                    {{ $message }}@enderror</span>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" name="pur_req_des" id="des"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <div class="form-group">
                                                <label>Reason</label>
                                                <textarea class="form-control" name="pur_req_reason" id="reason"></textarea>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-row">

                                        <div class="form-group col-md-4">

                                            <div class="form-group">
                                                <label>Select Department</label>
                                                <select class="form-control" id="departments" name="department_id" required>
                                                    <option value="" disabled selected>Select Department</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}">{{ $department->dept_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">@error('product_id')
                                                        {{ $message }}@enderror</span>
                                                </div>
                                            </div>



                                            <div class="form-group col-md-4">
                                                <label>Status</label>
                                                <select class="form-control" name="pur_req_status" id="status" required>
                                                    <option value="" disabled selected>Select Status</option>
                                                    <option name="pur_req_qty_type" value="0">Pending</option>
                                                    <option name="pur_req_qty_type" value="1">Accepted</option>
                                                    <option name="pur_req_qty_type" value="2">Rejected</option>
                                                    <span class="text-danger">@error('pur_req_qty_type')
                                                            {{ $message }}@enderror</span>
                                                    </select>

                                                </div>

                                                
                                                </div>


                                                <div align="right">
                                                    <button class="btn btn-danger" type="reset">Reset</button>
                                                    <button class="btn btn-success mr-1" type="submit">Submit</button>
                                                </div>


                                            </div>
                                        </form>


                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Edit Purchase Order Request -->


                        <!-- Add Purchase Order Request -->
                        <div class="modal fade" id="AddPurchaseOrderRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Purchase Order Request</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Main Content -->
                                        <form action="/PurchaseOrderRequestAdd" method="post" class="needs-validation" novalidate="">


                                            <div class="card-body form">
                                                @csrf

                                                <div class="form-row">

                                                    <div class="form-group col-md-6">

                                                        <div class="form-group">
                                                            <label>Select Product</label>
                                                            <select class="form-control" name="product_id" required>
                                                                <option value="" disabled selected>please select product</option>
                                                                @foreach ($products as $product)
                                                                    <option name="product_id" value="{{ $product->id }}" required>
                                                                        {{ $product->product_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger">@error('product_id')
                                                                    {{ $message }}@enderror</span>
                                                            </div>

                                                        </div>



                                                        <div class="form-group col-md-6">

                                                            <div class="form-group">
                                                                <label>Select Department</label>
                                                                <select class="form-control" name="department_id" required>
                                                                    <option value="" disabled selected>please select department</option>
                                                                    @foreach ($departments as $department)
                                                                        <option name="department_id" value="{{ $department->id }}" required>
                                                                            {{ $department->dept_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger">@error('department_id')
                                                                        {{ $message }}@enderror</span>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="row">

                                                            <div class="form-group col-md-6">
                                                                <label>Quantity</label>
                                                                <input type="text" class="form-control" pattern="^\d+(\.\d)?\d*$" name="pur_req_qty" required>
                                                                <span class="text-danger">@error('pur_req_qty') {{ $message }}@enderror</span>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label>Quantity Type</label>
                                                                    <select class="form-control" name="pur_req_qty_type" required>
                                                                        <option value="" disabled selected>please select quantity type</option>
                                                                        <option name="pur_req_qty_type" value="count">count</option>
                                                                        <option name="pur_req_qty_type" value="liter">liter</option>
                                                                        <option name="pur_req_qty_type" value="kg">kg</option>
                                                                        <option name="pur_req_qty_type" value="meter">meter</option>
                                                                        <span class="text-danger">@error('pur_req_qty_type')
                                                                                {{ $message }}@enderror</span>
                                                                        </select>
                                                                    </div>
                                                                </div>


                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <textarea class="form-control" name="pur_req_des"></textarea>
                                                                </div>


                                                                <div align="right">
                                                                    <button class="btn btn-danger" type="reset">Reset</button>
                                                                    <button class="btn btn-success mr-1" type="submit">Submit</button>
                                                                </div>


                                                            </div>
                                                        </form>


                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Add Purchase Order Request -->
