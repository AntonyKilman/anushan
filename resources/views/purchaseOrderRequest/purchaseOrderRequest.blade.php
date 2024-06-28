@extends('layouts.navigation')
@section('purchaseRequest', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $createRequest = false;
    $viewRequest = false;
    $editRequest = false;
    $approveRequest = false;

    if (in_array('inventory.PurchaseRequestShowAdd', $Access)) {
        $createRequest = true;
    }
    if (in_array('inventory.PurchaseRequestView', $Access)) {
        $viewRequest = true;
    }
    if (in_array('inventory.PurchaseRequestChange', $Access)) {
        $editRequest = true;
    }
    if (in_array('inventory.PurchaseRequestEdit', $Access)) {
        $approveRequest = true;
    }
    
    ?>

    <!---------------------------- Main Content---------------------------- -->
    <div class="card">
        <div class="card-header">

            <h4 class="header">Purchase Order Request </h4>
            @if ($createRequest)
                <a href="/PurchaseRequestShowAdd" class="btn btn-success">Add</a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">

                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Request Id</th>
                            <th>Department</th>
                            <th>Reason</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->dept_name }}</td>
                                <td>{{ $data->reason }}</td>
                                <td style="text-align: center">

                                    @if($viewRequest)
                                    <a href="/PurchaseRequestView/{{ $data->id }}" title="view" class="btn btn-info "><i
                                            class="far fa-eye"></i></a>
                                    @endif

                                    @if($editRequest)
                                    <a href="/PurchaseRequestChange/{{ $data->id }}" title="Edit"
                                        class="btn btn-primary "><i class="far fa-edit"></i></a>
                                    @endif

                                    @if($approveRequest)
                                    <a href="/PurchaseRequestEdit/{{ $data->id }}" title="Approval"
                                        class="btn btn-warning "><i class="fas fa-check"></i></i></a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>