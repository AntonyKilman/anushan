@extends('layouts.navigation')
@section('outdoor_return', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $outdoorReturnAdd = false;
    $outdoorReturnEdit = false;
    $outdoorReturnDelete = false;
    if (in_array('inventory.outdoorReturnAdd', $Access)) {
        $outdoorReturnAdd = true;
    }
    if (in_array('inventory.outdoorReturnEdit', $Access)) {
        $outdoorReturnEdit = true;
    }
    if (in_array('inventory.outdoorReturnDelete', $Access)) {
        $outdoorReturnDelete = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Outdoor Return</h4>
            @if ($outdoorReturnAdd)
                <a href="/outdoor-return-add" class="btn btn-success">Add</a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th style="text-align: center">Code</th>
                            <th style="text-align: center">Product</th>
                            <th style="text-align: center">GR No</th>
                            <th style="text-align: center">Supplier</th>
                            <th style="text-align: center">Reason</th>
                            <th style="text-align: center">Return Quantity</th>
                            {{-- @if ($outdoorReturnEdit || $outdoorReturnDelete)
                                <th class="action">Action</th>
                            @endif --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outdoor_returns as $outdoor_return)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $outdoor_return->product_code }}</td>
                                <td>{{ $outdoor_return->product_name }}</td>
                                <td>{{ $outdoor_return->purchase_order_id }}</td>
                                <td>{{ $outdoor_return->seller_name }}</td>
                                <td>{{ $outdoor_return->reason_name }}</td>
                                <td style="text-align: right">{{ number_format($outdoor_return->return_qty,2) }}</td>
                                {{-- @if ($outdoorReturnEdit || $outdoorReturnDelete) --}}
                                    {{-- <td class="action"> --}}
                                        {{-- @if ($outdoorReturnEdit) --}}
                                            {{-- <a href="/outdoor-return-edit/{{ $outdoor_return->id }}" title="edit"
                                                class="btn btn-info btn-edit"><i class="far fa-eye"></i></a> --}}
                                            {{-- <button title="edit" data-toggle="modal" data-target="#edit"
                                                class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a> --}}
                                        {{-- @endif --}}
                                        {{-- @if ($outdoorReturnDelete)
                                    <a href="/outdoor-return-delete/{{$outdoor_return->id}}" title="delete" onclick="return confirm('Are you sure you want to delete this raw?');" class="btn btn-danger btn-edit"><i class="fas fa-trash-alt"></i></a> --}}
                                        {{-- @endif --}}
                                        {{-- <a href="/{{$outdoor_return->id}}"  title="view" class="btn btn-info btn-edit"><i class="far fa-eye"></i></a>  --}}
                                    {{-- </td> --}}
                                {{-- @endif --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@section('model')

    {{-- <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Update Outdoor Return</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">
                    <form action="/outdoor-return-update-process" method="post" class="needs-validation" novalidate="">
                        @csrf

                        <input type="hidden" name="purchase_unit_price" value="{{ $outdoor_return->purchase_unit_price }}">

                        <div class="card-body form" style="width: 100%"> --}}

                            {{-- first row --}}
                            {{-- <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Purchase Order Id</label>
                                    <input type="text" value="{{ $outdoor_return->purchase_order_id }}"
                                        id="purchase_order_id" name="purchase_order_id" class="form-control" readonly>
                                    @error('purchase_order_id')
                                        <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Product</label>
                                    <input type="hidden" value="{{ $outdoor_return->product_id }}" id="product_id"
                                        name="product_id" class="form-control" readonly>
                                    <input type="text" value="{{ $outdoor_return->product_name }}" id="product_name"
                                        name="product_name" class="form-control" readonly>
                                    @error('product_id')
                                        <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Supplier</label>
                                    <input type="hidden" value="{{ $outdoor_return->seller_id }}" id="seller_id"
                                        name="seller_id" class="form-control" readonly>
                                    <input type="text" value="{{ $outdoor_return->seller_name }}" id="seller_name"
                                        name="seller_name" class="form-control" readonly>
                                    @error('seller_id')
                                        <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> --}}

                            {{-- second row --}}
                            {{-- <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Reason</label>
                                    <input type="hidden" value="{{ $outdoor_return->return_reason_id }}"
                                        id="return_reason_id" name="return_reason_id" class="form-control" readonly>
                                    <input type="text" value="{{ $outdoor_return->reason_name }}" id="reason_name"
                                        name="reason_name" class="form-control" readonly>
                                    @error('return_reason_id')
                                        <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Quantity</label>
                                    <input type="text" value="{{ $outdoor_return->qty }}" id="qty" name="qty"
                                        class="form-control" readonly>
                                    @error('qty')
                                        <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Return Quantity</label>
                                    <input type="text" value="{{ $outdoor_return->return_qty }}" id="return_qty"
                                        name="return_qty" class="form-control" readonly>
                                    @error('return_qty')
                                        <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div> --}}

{{-- 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>