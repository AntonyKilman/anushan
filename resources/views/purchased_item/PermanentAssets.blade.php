@extends('layouts.navigation')
@section('permanent_assets', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $permanentAssetsAdd = false;
    $permanentAssetsEdit = false;
    $permanentAssetsView = false;
    if (in_array('inventory.permanentAssetsAdd', $Access)) {
        $permanentAssetsAdd = true;
    }
    if (in_array('inventory.permanentAssetsEdit', $Access)) {
        $permanentAssetsEdit = true;
    }
    if (in_array('inventory.purchaseOrderView', $Access)) {
        $permanentAssetsView = true;
    }
    ?>

    <div class="card">
        <div class="card-header">
            <h4>Permanent Assets</h4>
            @if ($permanentAssetsAdd)
                <a href="/permanent-assets-add" class="btn btn-success">Add</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            {{-- <th>Id</th> --}}
                            <th style="display: none">#</th>
                            <th style="text-align: center">Code</th>
                            <th style="text-align: center">Product Name</th>
                            <th style="text-align: center">GR Number</th>
                            <th style="text-align: center">Quantity</th>
                            <th style="text-align: center">Quantity Type</th>
                            <th style="text-align: center">Price</th>
                            <th style="text-align: center">Serial Number</th>
                            <th style="text-align: center">Warranty</th>
                            <th style="text-align: center">Description</th>
                            @if ($permanentAssetsEdit || $permanentAssetsView)
                                <th width="12%" class="col-action">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permanent_assets as $permanent_asset)
                            <tr>
                                {{-- <td>{{$permanent_asset->id}}</td> --}}
                                <td style="display: none">#</td>
                                <td>{{ $permanent_asset->product_code }}</td>
                                <td>{{ $permanent_asset->product_name }}</td>
                                <td>{{ $permanent_asset->purchase_order_id }}</td>
                                <td style="text-align: right">{{ number_format($permanent_asset->pur_item_qty,2) }}</td>
                                <td>{{ $permanent_asset->pur_item_qty_type }}</td>
                                <td style="text-align: right">{{number_format($permanent_asset->pur_item_amount,2) }}</td>
                                <td>{{ $permanent_asset->serial_number }}</td>
                                <td>{{ $permanent_asset->warranty }}</td>
                                <td>{{ $permanent_asset->description }}</td>
                                @if ($permanentAssetsEdit || $permanentAssetsView)
                                    <td style="text-align: center">
                                        @if ($permanentAssetsView)
                                            <a href="/purchase-order-view/{{ $permanent_asset->purchase_order_id }}"
                                                class="btn btn-info btn-edit"><i class="far fa-eye"></i></a>
                                        @endif
                                        @if ($permanentAssetsEdit)
                                            <a href="/permanent-asset-edit/{{ $permanent_asset->id }}"
                                                class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a>
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
@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>