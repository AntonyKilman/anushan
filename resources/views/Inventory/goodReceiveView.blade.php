@extends('layouts.navigation')
@section('good_receive', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>


    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Good Receive</h4>
                            </div>
                        </div>


                        {{-- card body start --}}
                        <div class="card-body">

                            <input type="hidden" name="id" value="{{ $purchase_order->id }}">
                            {{-- first row --}}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Bill No</label>
                                    <input type="text" id="bill_no" name="bill_no"
                                        value="{{ $purchase_order->pur_ord_bill_no }}" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Total Payment</label>
                                    <input type="text" id="amount" name="amount"
                                        value="{{ $purchase_order->pur_ord_amount }}" pattern="^(0|[1-9][0-9]*)$"
                                        class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Seller</label>
                                    <input type="text" id="amount" name="amount"
                                        value="{{ $purchase_order->seller_name }}" pattern="^(0|[1-9][0-9]*)$"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            {{-- second row --}}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Cheque Payment</label>
                                    <input type="text" id="cheque_amount" value="{{ $purchase_order->pur_ord_cheque }}"
                                        name="cheque_amount" pattern="^(0|[1-9][0-9]*)$" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Cheque No</label>
                                    <input type="text" value="{{ $purchase_order->pur_ord_cheque_no }}" id="cheque_no"
                                        name="cheque_no" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Cheque Date</label>
                                    <input type="text" value="{{ $purchase_order->pur_ord_cheque_date }}"
                                        id="cheque_date" name="cheque_date" class="form-control" readonly>
                                </div>
                            </div>

                            {{-- third row --}}
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Online/Card Payment</label>
                                    <input type="text" value="{{ $purchase_order->pur_ord_online_or_card }}"
                                        id="online_amount" name="online_amount" pattern="^(0|[1-9][0-9]*)$"
                                        class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Reference No</label>
                                    <input type="text" value="{{ $purchase_order->pur_ord_reference_no }}"
                                        id="reference_no" name="reference_no" class="form-control" readonly>
                                </div>
                            </div>

                            {{-- fourth row --}}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Credit Payment</label>
                                    <input type="text" value="{{ $purchase_order->pur_ord_credit }}" id="credit_amount"
                                        name="credit_amount" value="0" pattern="^(0|[1-9][0-9]*)$" class="form-control"
                                        readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Cash Payment</label>
                                    <input type="text" value="{{ $purchase_order->pur_ord_cash }}" id="cash_amount"
                                        name="cash_amount" value="0" pattern="^(0|[1-9][0-9]*)$" class="form-control"
                                        readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Purchase Date</label>
                                    <input type="date" name="date" class="form-control"
                                        value="{{ $purchase_order->date }}" readonly>
                                </div>
                            </div>

                            {{-- fifth row --}}
                            <div class="form-row">
                                @if ($purchase_order->bill_img_1)
                                    <div class="form-group col-md-4">
                                        <label>Image 1</label><br>
                                        <img src="/bill/{{ $purchase_order->bill_img_1 }}" class="css-class" alt="Null"
                                            style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
                                    </div>
                                @endif

                                @if ($purchase_order->bill_img_1)
                                    <div class="form-group col-md-4">
                                        <label>Image 2</label><br>
                                        <img src="/bill/{{ $purchase_order->bill_img_2 }}" class="css-class"
                                            alt="Null"
                                            style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
                                    </div>
                                @endif

                                @if ($purchase_order->bill_img_3)
                                    <div class="form-group col-md-4">
                                        <label>Image 3</label><br>
                                        <img src="/bill/{{ $purchase_order->bill_img_3 }}" class="css-class"
                                            alt="Null"
                                            style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
                                    </div>
                                @endif
                            </div>

                            <div class="table">
                                <table style="width: 100%">
                                    <thead>
                                        @if (count($purchase_items) > 0)
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity Type</th>
                                                <th>Quantity</th>
                                                <th>Expiry Date</th>
                                                <th>Price</th>
                                                <th></th>
                                            </tr>
                                        @endif



                                    </thead>
                                    <tbody>
                                        @foreach ($purchase_items as $purchase_item)
                                            <tr>
                                                <td>
                                                    <input type="text" value="{{ $purchase_item->product_name }}"
                                                        id="qty" name="qty[]" class="form-control"
                                                        placeholder="Enter the Quantity" pattern="^(0|[1-9][0-9]*)$"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $purchase_item->pur_item_qty_type }}"
                                                        id="price" name="price[]" class="form-control"
                                                        pattern="^(0|[1-9][0-9]*)$" placeholder="Enter the Price"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $purchase_item->pur_item_qty }}"
                                                        id="qty" name="qty[]" class="form-control"
                                                        placeholder="Enter the Quantity" pattern="^(0|[1-9][0-9]*)$"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="date" name="expery_date" id="expery_date"
                                                        value="{{ $purchase_item->pur_item_expery_date }}"
                                                        class="form-control" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $purchase_item->pur_item_amount }}"
                                                        id="price" name="price[]" class="form-control"
                                                        pattern="^(0|[1-9][0-9]*)$" placeholder="Enter the Price"
                                                        readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach


                                        @foreach ($permanent_purchase_items as $permanent_purchase_item)
                                            <tr>
                                                <input type="hidden" name="item_id[]"
                                                    value="{{ $permanent_purchase_item->id }}" readonly>
                                                <td>
                                                    <select id="product_id" name="product_id[]" class="form-control"
                                                        readonly>
                                                        <option value="" disabled>Select Product</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                {{ $product->id == $permanent_purchase_item->product_id ? 'selected' : '' }}>
                                                                {{ $product->product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="qty_type[]" id="qty_type" class="form-control"
                                                        readonly>
                                                        <option value="" disabled selected>Select Quantity Type
                                                        </option>
                                                        <option value="count"
                                                            {{ $permanent_purchase_item->pur_item_qty_type == 'count' ? 'selected' : '' }}>
                                                            count</option>
                                                        <option value="liter"
                                                            {{ $permanent_purchase_item->pur_item_qty_type == 'liter' ? 'selected' : '' }}>
                                                            liter</option>
                                                        <option value="kg"
                                                            {{ $permanent_purchase_item->pur_item_qty_type == 'kg' ? 'selected' : '' }}>
                                                            kg
                                                        </option>
                                                        <option value="meter"
                                                            {{ $permanent_purchase_item->pur_item_qty_type == 'meter' ? 'selected' : '' }}>
                                                            meter</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        value="{{ $permanent_purchase_item->pur_item_qty }}"
                                                        id="qty" name="qty[]" class="form-control"
                                                        placeholder="Enter the Quantity" pattern="^\d+(\.\d)?\d*$"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="date" name="warrenty[]" id="warrenty"
                                                        value="{{ $permanent_purchase_item->warranty }}"
                                                        class="form-control" readonly>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        value="{{ $permanent_purchase_item->serial_number }}"
                                                        id="serial_number" name="serial_number[]" class="form-control"
                                                        placeholder="Enter the Serial Number" readonly>
                                                </td>
                                                <td>
                                                    <textarea name="description[]" class="form-control" id="description" readonly cols="30" rows="1">{{ $permanent_purchase_item->description }}</textarea>
                                                </td>

                                                <td>
                                                    <input type="text" readonly
                                                        value="{{ $permanent_purchase_item->pur_item_amount }}"
                                                        id="price" name="price[]" class="form-control"
                                                        pattern="^\d+(\.\d)?\d*$" placeholder="Enter the Price" required>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- card body end --}}

                        @if ($from != 'report')
                            <div class="card-footer text-right">

                            </div>
                        @endif

                    </div>
                </div>
            </div>
    </section>
@endsection
