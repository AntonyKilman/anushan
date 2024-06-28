@extends('layouts.navigation')
@section('salary_payable' ,'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $create = false;
    $update = false;
    
    if (in_array('hr.salaryPayable.create', $Access)) {
        $create = true;
    }
    
    ?>

<div class="card card-success">
    <div class="card-header">
        <h4>Salary Payable</h4>
        <div class="card-header-action">
            @if ($create)
                <a class="btn btn-success" href="{{route('hr.salaryPayable.create')}}">
                    <span class="btn-icon-wrapper pr-2"> </span>
                    Add 
                </a>
            @endif
        </div>
    </div>

    <div class="card-body">
        <div align='right'>
            <div class="col-md-3">
                <input class="form-control" value="{{ $month }}" name="month" type="month"
                    onchange="window.location.assign('/emp/salary-payable?month=' + this.value)" />
            </div>
            <br>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                <thead style="background-color:#a6d7ff">
                    <tr>
                        <th>No</th>
                        <th>Emp Code</th>
                        <th>Employee</th>
                        <th>Basic Salary</th>
                        <th>Total Allowance</th>
                        <th>Gross Salary</th>
                        <th>Advance Payment Amount</th>
                        <th>Net Salary</th>
                        <th width="100">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salary_payable as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->emp_code }}</td>
                        <td>{{ $row->emp_name }}</td>
                        <td align="right">{{ number_format($row->basic_salary,2) }}</td>
                        <td align="right">{{ number_format($row->total_allowance,2) }}</td>
                        <td align="right">{{ number_format($row->gross_salary,2) }}</td>
                        <td align="right">{{ number_format($row->advance_payment_amount,2) }}</td>
                        <td align="right">{{ number_format($row->net_salary,2) }}</td>
                        <td  >
                            <a  href="{{route('hr.salaryPayable.view',['id'=>$row->id])}}" target="_blank" class="btn btn-info" id="view" style="padding: 2px 5px; margin-right: 10px;float: right;background-color: #0090e2;border-color: #0087d5;" title="View">
                                <i class="fa fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection