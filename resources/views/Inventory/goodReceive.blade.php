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


    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Good Receive</h4>
                                <a href="/purchase-order-add" class="btn btn-success addchargetype">Add</a>
                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>No</th>
                                            <th>Bill No</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Supplier</th>
                                            @if ($purchaseOrderEdit || $purchaseOrderView)
                                                <th class="action">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchase_orders as $purchase_order)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $purchase_order->id }}</td>
                                                <td>{{ $purchase_order->pur_ord_bill_no }}</td>
                                                <td>{{ $purchase_order->pur_ord_amount }}</td>
                                                <td>{{ $purchase_order->date }}</td>
                                                <td>{{ $purchase_order->seller_name }}</td>
                                                @if ($purchaseOrderEdit || $purchaseOrderView)
                                                    <td class="action">
                                                        @if ($purchaseOrderView)
                                                            <a href="purchase-order-view/{{ $purchase_order->id }}"
                                                                title="view" class="btn btn-info btn-edit"><i
                                                                    class="far fa-eye"></i></a>
                                                        @endif
                                                        @if ($purchaseOrderEdit)
                                                            <a href="purchase-order-edit/{{ $purchase_order->id }}"
                                                                title="edit" class="btn btn-primary btn-edit"><i
                                                                    class="far fa-edit"></i></a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
