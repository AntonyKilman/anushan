@extends('layouts.navigation')
@section('perchased_item', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $purchaseOrderAdd = false;
    $purchaseOrderEdit = false;
    $purchaseOrderView = false;
    if (in_array('inventory.purchaseOrderAdd', $Access)) {
        $purchaseOrderAdd = true;
    }
    if (in_array('inventory.purchaseOrderEdit', $Access)) {
        $purchaseOrderEdit = true;
    }
    if (in_array('inventory.purchaseOrderView', $Access)) {
        $purchaseOrderView = true;
    }
    ?>




    <div class="card">
        <div class="card-header">
            <h4>GR Item</h4>
            @if ($purchaseOrderAdd)
                <a href="/new-purchased-item-add" class="btn btn-success">Add</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>GR No</th>
                            <th style="text-align: center">Quantity</th>
                            <th style="text-align: center">Unit Price</th>
                            <th style="text-align: center">Total</th>
                            @if ($purchaseOrderEdit || $purchaseOrderView)
                                <th style="text-align: center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase_items as $item)
                            <tr>
                               
                                <td style="display: none">#</td>
                                <td>{{ $item->product_code }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->pur_item_qty_type }}</td>
                                <td>{{ $item->purchase_order_id }}</td>
                                <td style="text-align: right">{{ number_format($item->pur_item_qty,2) }}</td>
                                <td style="text-align: right">{{ number_format($item->pur_item_amount/$item->pur_item_qty,2) }}</td>
                                <td style="text-align: right">{{ number_format($item->pur_item_amount,2) }}</td>
                                
                                @if ($purchaseOrderEdit || $purchaseOrderView)
                                    <td align="center">
                                        @if ($purchaseOrderView)
                                            <a href="purchase-order-view/{{ $item->purchase_order_id }}/{{$item->product_id}}"
                                                class="btn btn-info btn-edit"><i class="far fa-eye"></i></a>
                                        @endif
                                        {{-- @if ($purchaseOrderEdit)
                          <a href="purchase-order-edit/{{$item->purchase_order_id}}" class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a>
                        @endif --}}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>