@extends('layouts.navigation')
@section('permanent_asset_transfer','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

    <!-- Main Content -->
<div class="card">
    <div class="card-header">
        <h4 class="header">Permanent Asset Transfer</h4>
            <a class="btn btn-success" href="permanent-asset-transfer-add">Add</a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-1">
                <thead>
                    <tr>
                        <th style="display: none">#</th>
                        <th>GR Number</th>
                        <th>Product Code</th>
                        <th>Permanent Asset</th>
                        <th>Department</th>
                        <th>Quantity</th>
                        <th>Depreciation Persentage %</th>
                        <th>Date</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($permanentAssets as $permanentAssets)
                        <tr>
                            <td style="display: none">#</td>
                            <td>{{ $permanentAssets->purchase_id }}</td>
                            <td>{{ $permanentAssets->product_code }}</td>
                            <td>{{ $permanentAssets->product_name }}</td>
                            <td>{{ $permanentAssets->department_name }}</td>
                            <td style="text-align: center">{{ $permanentAssets->quantity }}</td>
                            <td style="text-align: center">{{ $permanentAssets->depreciation_persentage }}</td>
                            <td>{{ $permanentAssets->date }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
