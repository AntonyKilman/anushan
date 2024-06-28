@extends('layouts.navigation')
@section('good_receive', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        <form action="/purchase-order-update-process" method="post" style="width:100%" enctype="multipart/form-data">
            @csrf

            <div class="card-header">
                <h4>Indoor Transfer Details</h4>
            </div>

       

                <div class="table">
                    <table style="width: 100%">
                        <thead>
                            @if (count($purchase_items) > 0)
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Department</th>
                                    <th>Employee</th>
                                    <th>Quantity Type</th>
                                    <th>Opening Quantity</th>
                                    <th>Transfer Quantity</th>
                                    <th>Balance Quantity</th>
                                    <th></th>
                                </tr>
                            @endif

                            {{-- @if (count($permanent_purchase_items) > 0)
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity Type</th>
                                    <th>Quantity</th>
                                    <th>Warranty</th>
                                    <th>Serial No</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                            @endif --}}

                        </thead>
                        <tbody>
                            @foreach ($purchase_items as $purchase_item)
                                <tr>
                                    <td>
                                        <input type="text"  value="{{ $purchase_item->created_at }}" id="qty"
                                            name="qty[]" class="form-control" placeholder="Enter the Quantity"
                                            pattern="^(0|[1-9][0-9]*)$" readonly style="width: 95%; ">
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $purchase_item->product_name }}"
                                            id="price" name="price[]" class="form-control"
                                            pattern="^(0|[1-9][0-9]*)$" placeholder="Enter the Price" readonly>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $purchase_item->dept_name }}" id="qty"
                                            name="qty[]" class="form-control" placeholder="Enter the Quantity"
                                            pattern="^(0|[1-9][0-9]*)$" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="expery_date" id="expery_date"
                                            value="{{ $purchase_item->f_name }}" class="form-control"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $purchase_item->Qty_type }}"
                                            id="price" name="price[]" class="form-control"
                                            pattern="^(0|[1-9][0-9]*)$" placeholder="Enter the Price" readonly>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $purchase_item->quantity }}"
                                            id="price" name="price[]" class="form-control"
                                            pattern="^(0|[1-9][0-9]*)$" placeholder="Enter the Price" readonly>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $purchase_item->transfer_quantity }}"
                                            id="price" name="price[]" class="form-control"
                                            pattern="^(0|[1-9][0-9]*)$" placeholder="Enter the Price" readonly>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ ($purchase_item->quantity - $purchase_item->transfer_quantity) }}"
                                            id="price" name="price[]" class="form-control"
                                            pattern="^(0|[1-9][0-9]*)$" placeholder="Enter the Price" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach


                            @foreach ($permanent_purchase_items as $permanent_purchase_item)
                                <tr>
                                    <input type="hidden" name="item_id[]" value="{{ $permanent_purchase_item->id }}"
                                        readonly>
                                    <td>
                                        <select id="product_id" name="product_id[]" class="form-control" readonly>
                                            <option value="" disabled>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $product->id == $permanent_purchase_item->product_id ? 'selected' : '' }}>
                                                    {{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="qty_type[]" id="qty_type" class="form-control" readonly>
                                            <option value="" disabled selected>Select Quantity Type</option>
                                            <option value="count"
                                                {{ $permanent_purchase_item->pur_item_qty_type == 'count' ? 'selected' : '' }}>
                                                count</option>
                                            <option value="liter"
                                                {{ $permanent_purchase_item->pur_item_qty_type == 'liter' ? 'selected' : '' }}>
                                                liter</option>
                                            <option value="kg"
                                                {{ $permanent_purchase_item->pur_item_qty_type == 'kg' ? 'selected' : '' }}>kg
                                            </option>
                                            <option value="meter"
                                                {{ $permanent_purchase_item->pur_item_qty_type == 'meter' ? 'selected' : '' }}>
                                                meter</option>
                                        </select>
                                    </td> 
                                    <td>
                                        <input type="text" value="{{ $permanent_purchase_item->pur_item_qty }}"
                                            id="qty" name="qty[]" class="form-control"
                                            placeholder="Enter the Quantity" pattern="^\d+(\.\d)?\d*$" readonly>
                                    </td>
                                    <td>
                                        <input type="date" name="warrenty[]" id="warrenty"
                                            value="{{ $permanent_purchase_item->warranty }}" class="form-control"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $permanent_purchase_item->serial_number }}"
                                            id="serial_number" name="serial_number[]" class="form-control"
                                            placeholder="Enter the Serial Number" readonly>
                                    </td>
                                    <td>
                                        <textarea name="description[]" class="form-control" id="description" readonly cols="30" rows="1">{{ $permanent_purchase_item->description }}</textarea>
                                    </td>

                                    <td>
                                        <input type="text" readonly
                                            value="{{ number_format($permanent_purchase_item->pur_item_amount, 2) }}" id="price"
                                            name="price[]" class="form-control" pattern="^\d+(\.\d)?\d*$"
                                            required>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>{{-- card body end --}}

            @if ($from != 'report')
                <div class="card-footer text-right">
                    {{-- <a href="/purchase-order-edit/{{ $purchase_order->id }}" class="btn btn-primary btn-edit"><i
                            class="far fa-edit"></i></a> --}}
                </div>
            @endif



        </form>
    </div>
@endsection
