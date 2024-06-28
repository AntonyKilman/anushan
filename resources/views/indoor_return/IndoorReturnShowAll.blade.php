@extends('layouts.navigation')
@section('indoor_return', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $indoorReturnAdd = false;
    $indoorReturnEdit = false;
    $indoorReturnDelete = false;
    $indoorFoodcityReturnEdit = false;
    $indoorFoodcityReturnDelete = false;
    if (in_array('inventory.indoorReturnAdd', $Access)) {
        $indoorReturnAdd = true;
    }
    if (in_array('inventory.indoorReturnEdit', $Access)) {
        $indoorReturnEdit = true;
    }
    if (in_array('inventory.indoorReturnDelete', $Access)) {
        $indoorReturnDelete = true;
    }
    if (in_array('inventory.indoorFoodcityReturnEdit', $Access)) {
        $indoorFoodcityReturnEdit = true;
    }
    if (in_array('inventory.indoorFoodcityReturnDelete', $Access)) {
        $indoorFoodcityReturnDelete = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Indoor Return</h4>
            @if ($indoorReturnAdd)
                <a href="/indoor-return-add" class="btn btn-success">Add</a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th style="text-align: center">Product Code</th>
                            <th style="text-align: center">Product</th>
                            <th style="text-align: center">GR No</th>
                            <th style="text-align: center">Reason</th>
                            <th style="text-align: right">Return Quantity</th>
                            <th style="text-align: center">Department</th>
                            @if ($indoorReturnEdit || $indoorReturnDelete || $indoorFoodcityReturnEdit || $indoorFoodcityReturnDelete)
                                <th class="action">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($indoor_returns as $indoor_return)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $indoor_return->product_code }}</td>
                                <td>{{ $indoor_return->product_name }}</td>
                                <td>{{ $indoor_return->purchase_order_id }}</td>
                                <td>{{ $indoor_return->reason_name }}</td>
                                <td style="text-align: right">{{number_format($indoor_return->return_qty,2) }}</td>
                                <td>{{ $indoor_return->dept_name }}</td>
                                @if ($indoorReturnEdit || $indoorReturnDelete)
                                    <td class="action">
                                        @if ($indoorReturnEdit)
                                            <a href="/indoor-return-edit/{{ $indoor_return->id }}" title="edit"
                                                class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a>
                                        @endif
                                        {{-- @if ($indoorReturnDelete)
                                            <a href="/indoor-return-delete/{{ $indoor_return->id }}" title="delete"
                                                onclick="return confirm('Are you sure you want to delete this raw?');"
                                                class="btn btn-danger btn-edit"><i class="fas fa-trash-alt"></i></a>
                                        @endif --}}
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        @foreach ($foodcity_returns as $foodcity_return)
                            <tr>
                                <td style="display: none">#</td>
                                <td >{{ $foodcity_return->purchase_order_id }}</td>
                                <td>{{ $foodcity_return->product_name }}</td>
                                <td>{{ $foodcity_return->product_code }}</td>
                                <td>{{ $foodcity_return->reason_name }}</td>
                                <td>{{ $foodcity_return->return_qty }}</td>
                                <td>Foodcity</td>
                                {{-- <td>{{ $foodcity_return->status == 0 ? 'Pending' : '' }}</td> --}}
                                @if ($indoorFoodcityReturnEdit || $indoorFoodcityReturnDelete)
                                    <td class="action">
                                        @if ($indoorFoodcityReturnEdit)
                                            <a href="/indoor-Foodcity-return-edit/{{ $foodcity_return->id }}"
                                                title="edit" class="btn btn-primary btn-edit"><i
                                                    class="far fa-edit"></i></a>
                                        @endif
                                        {{-- @if ($indoorFoodcityReturnDelete)
                                            <a href="/indoor-foodcity-return-delete/{{ $foodcity_return->id }}"
                                                title="delete"
                                                onclick="return confirm('Are you sure you want to delete this raw?');"
                                                class="btn btn-danger btn-edit"><i class="fas fa-trash-alt"></i></a>
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
