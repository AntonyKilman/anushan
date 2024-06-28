@extends('layouts.navigation')
@section('indoor_transfer', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $indoorTransferAdd = false;
    $indoorTransferEdit = false;
    $indoorTransferDelete = false;
    if (in_array('inventory.GetProDept', $Access)) {
        $indoorTransferAdd = true;
    }
    if (in_array('inventory.IndoorTransferEdit', $Access)) {
        $indoorTransferEdit = true;
    }
    if (in_array('inventory.IndoorTransferDelete', $Access)) {
        $indoorTransferDelete = true;
    }
    ?>


    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Indoor Transfer</h4>
            @if ($indoorTransferAdd)
                <a href="GetProDept" class="btn btn-success"> Add</a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">

                    <thead>
                        <tr>
                            <th style="display: none"></th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>GR Number</th>
                            <th>Department</th>
                            <th>Employee</th>
                            <th>Expiry Date</th>
                            <th style="text-align: center">Transfer Quantity</th>
                            <th style="text-align: center">Unit Price</th>
                            <th style="text-align: center">Amount</th>    
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transfers as $transfer)
                            <tr>
                                <td style="display: none"></td>
                                <td>{{ $transfer->product_code }}</td>
                                <td>{{ $transfer->product_name }}</td>
                                <td>{{ $transfer->purchase_id }}</td>
                                <td>{{ $transfer->dept_name }}</td>
                                <td>{{ $transfer->f_name }}</td>
                                <td>{{ $transfer->exDate }}</td>
                                <td style="text-align: right">{{ number_format($transfer->transfer_quantity,2) }}</td>
                                <td style="text-align: right">{{ number_format($transfer->purchase_unit_price, 2) }}</td>
                                <td style="text-align: right">{{ number_format($transfer->transfer_quantity * $transfer->purchase_unit_price, 2) }}
                                </td> 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>