@extends('layouts.navigation')
@section('good_receive', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $purchaseOrderEdit = false;
    $purchaseOrderView = false;
    if (in_array('inventory.purchaseOrderEdit', $Access)) {
        $purchaseOrderEdit = true;
    }
    if (in_array('inventory.purchaseOrderView', $Access)) {
        $purchaseOrderView = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Good Receive</h4>

            <a href="/purchase-order-add" class="btn btn-success">Add</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Date</th>
                            <th>GR Number</th>
                            <th>Bill No</th>
                            <th style="text-align: center">Amount</th>
                            <th>Supplier</th>
                            @if ($purchaseOrderEdit || $purchaseOrderView)
                                {{-- <th class="action">Action</th> --}}
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase_orders as $purchase_order)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $purchase_order->date }}</td>
                                <td>{{ $purchase_order->id }}</td>
                                <td>{{ $purchase_order->pur_ord_bill_no }}</td>
                                <td style="text-align: right">{{ number_format($purchase_order->pur_ord_amount,2) }}</td>
                                <td>{{ $purchase_order->seller_name }}</td>
                                @if ($purchaseOrderEdit || $purchaseOrderView)
                                    {{-- <td class="action">
                                        @if ($purchaseOrderView)
                                            <a href="purchase-order-view/{{ $purchase_order->id }}/{{ $purchase_order->id }}" title="view"
                                                class="btn btn-info btn-edit"><i class="far fa-eye"></i></a>
                                        @endif
                                        @if ($purchaseOrderEdit)
                                    <a href="purchase-order-edit/{{$purchase_order->id}}" title="edit" class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a>
                                  @endif
                                    </td> --}}
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