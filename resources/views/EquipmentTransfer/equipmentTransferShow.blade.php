@extends('layouts.navigation')
@section('equipment_transfer','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
  $equpmentTranferAdd=false;
  $equpmentTranferEdit=false;
  $equpmentTranferDelete=false;
  if (in_array('inventory.EquProduct', $Access)) {
    $equpmentTranferAdd=true;
  }
  if (in_array('inventory.equipmentTransferEdit', $Access)) {
    $equpmentTranferEdit=true;
  }
  if (in_array('inventory.equipmentTransferDelete', $Access)) {
    $equpmentTranferDelete=true;
  }
?>

    <!-- Main Content -->
<div class="card">
    <div class="card-header">
        <h4 class="header">Equipment Transfer</h4>
        @if ($equpmentTranferAdd)
            <a class="btn btn-success" href="EquProduct">Add</a>
        @endif
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-1">
                <thead>
                    <tr>
                        <th style="display: none">#</th>
                        <th style="text-align: center">GR Number</th>
                        <th style="text-align: center">Product Code</th>
                        <th style="text-align: center">Product</th>
                        <th style="text-align: center">Department</th>
                        <th style="text-align: center">Quantity</th>
                        <th style="text-align: center">Purchase Date</th>
                        <th style="text-align: center">Days</th>
                        <th style="text-align: center">Employee</th>
                        @if ($equpmentTranferEdit || $equpmentTranferDelete)
                            <th class='action'>Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($equipments as $equipment)
                        <tr>
                            <td style="display: none">#</td>
                            <td style="text-align: center">{{ $equipment->purchase_id }}</td>
                            <td style="text-align: center">{{ $equipment->product_code }}</td>
                            <td style="text-align: center">{{ $equipment->product_name }}</td>
                            <td style="text-align: center">{{ $equipment->mainDeptName }}</td>
                            <td style="text-align: right">{{ $equipment->quantity }}</td>
                            <td style="text-align: center">{{ $equipment->purchaseDate }}</td>
                            <td style="text-align: center">{{ $equipment->noOfDays }}</td>
                            <td style="text-align: center">{{ $equipment->f_name }}</td>
                            @if ($equpmentTranferEdit || $equpmentTranferDelete)
                                <td style="text-align: center">
                                    @if ($equpmentTranferEdit)
                                        <a class="btn btn-primary btn-edit"
                                        href="/equipmentTransferEdit/{{ $equipment->equTransId }}"><i
                                            class="far fa-edit"></i></a>
                                    @endif
                                    @if ($equpmentTranferDelete)
                                        <a class="btn btn-icon btn-danger btn-edit"
                                        onclick="return confirm('Are you sure you want to delete this raw?');"
                                        href="/equipmentTransferDelete/{{ $equipment->equTransId }}"><i
                                            class="fas fa-trash-alt"></i></a>
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
