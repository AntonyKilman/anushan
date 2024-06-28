@extends('layouts.navigation')
@section('salary payable report' ,'active')
@section('content')

    
<div class="card card-success" >
  <div class="card-header">
      <h4>Salary Payable Report</h4>
  </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-9">
            </div>
            <div class="col-md-3">
                <input class="form-control date" value="{{ $month }}" name="month" type="month"
                    id="month" onchange="window.location.assign('/report/salary-payable-report?month=' + this.value)" />
            </div>
        </div>

      <div class="table-responsive">
          <table id="salary_report" class="mb-0 table table-striped table">
            <thead style="background-color:#a6d7ff">
                <tr>
                    <th>No</th>
                    <th>Emp Code</th>
                    <th>Employee</th>
                    <th>Basic Salary</th>
                    <th>Total Allowance</th>
                    <th>Exta Work Amount</th>
                    <th>Gross Salary</th>
                    <th>EPF</th>
                    <th>Advance Payment</th>
                    <th>Others</th>
                    <th>Net Salary</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align:right">{{ number_format($salary_payable->sum('basic_salary'),2) }} <hr></th>
                    <th style="text-align:right">{{ number_format($salary_payable->sum('total_allowance'),2) }} <hr></th>
                    <th style="text-align:right">{{ number_format($salary_payable->sum('over_time_work'),2) }} <hr></th>
                    <th style="text-align:right">{{ number_format($salary_payable->sum('gross_salary'),2) }} <hr></th>
                    <th style="text-align:right">{{ number_format($salary_payable->sum('epf'),2) }} <hr></th>
                    <th style="text-align:right">{{ number_format($salary_payable->sum('advance_payment_amount'),2) }} <hr></th>
                    <th style="text-align:right">{{ number_format($salary_payable->sum('others'),2) }} <hr></th>
                    <th style="text-align:right">{{ number_format($salary_payable->sum('net_salary'),2) }} <hr></th>
                </tr>
                @foreach ($salary_payable as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->emp_code }}</td>
                        <td>{{ $row->emp_name }}</td>
                        <td style="text-align:right">{{ number_format($row->basic_salary,2) }}</td>
                        <td style="text-align:right">{{ number_format($row->total_allowance,2) }}</td>
                        <td style="text-align:right">{{ number_format($row->over_time_work,2) }}</td>
                        <td style="text-align:right">{{ number_format($row->gross_salary,2) }}</td>
                        <td style="text-align:right">{{ number_format($row->epf,2) }}</td>
                        <td style="text-align:right">{{ number_format($row->advance_payment_amount,2) }}</td>
                        <td style="text-align:right">{{ number_format($row->others,2) }}</td>
                        <td style="text-align:right">{{ number_format($row->net_salary,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
          </table>
      </div>
      
    </div>
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var month = '{{ $month }}';

        $('#salary_report').DataTable( {
            dom: 'Brtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                { extend: 'print',
                    text: 'print',
                    title: 'Reecha HR',
                    messageTop: 'Salary Payable Report - Month :-' + month,
                    pageOrientation: 'landscape',
                    footer: true,
                    header: true,
                    customize: function ( doc ) {
                        $(doc.document.body).find('td').css('font-size', '11pt');
                        $(doc.document.body).find('th').css('font-size', '11pt');
                    }
                } ,  
                { extend: 'excel',
                    text: 'excel',
                    title: 'Reecha HR',
                    messageTop: 'Salary Payable Report - Month :-' + month,
                    footer: true,
                    header: true 
                },
                // { extend: 'pdf',
                //   text: 'pdf',
                //   title: 'Reecha HR',
                //   messageTop: 'Salary Payable Report - Month :-' + month,
                //   footer: true,
                //   header: true 
                // }     
            ],
        });

    });
</script>