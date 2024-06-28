@extends('layouts.navigation')
@section('company_return', 'active')
@section('content')

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Company Return</h4>
                <a href="/company-return-add" class="btn btn-success">Add</a>
        </div>
        <form action="/company-return" method="get">
            @csrf
            <div class="card-body form">
                <div class="form-row">
                    <div class="form-group col-md-7">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Month</label>
                        <input type="month" id="month" name="month" max="{{now()->format('Y-m')}}" value="{{$month}}" class="form-control" required>
                    </div>
    
                    <div class="form-group col-md-2" style="margin-top:30px">
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form> 

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th style="text-align: center">Code</th>
                            <th style="text-align: center">Product</th>
                            <th style="text-align: center">GR Bill No</th>
                            <th style="text-align: center">Supplier</th>
                            <th style="text-align: center">Reason</th>
                            <th style="text-align: center">Return Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($company_returns as $company_returns)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $company_returns->product_code }}</td>
                                <td>{{ $company_returns->name }}</td>
                                <td>{{ $company_returns->pur_ord_bill_no }}</td>
                                <td>{{ $company_returns->seller_name }}</td>
                                <td>{{ $company_returns->reason }}</td>
                                <td style="text-align: right">{{ number_format($company_returns->return_qty,2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
