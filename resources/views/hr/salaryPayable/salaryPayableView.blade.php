@extends('layouts.navigation')
@section('salary_payable', 'active')
@section('content')

    <div class="app-main__inner">

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="main-card mb-3 card">
                    <div class="card-body" id="salary_payable_view">
                        <table class="table table-bordered">
                            <thead>
                                <th colspan="2" class="text-center">
                                    <h1>Electrical HR</h1>
                                </th>
                            </thead>
                            <tbody>

                                <tr style="background-color:#A6D7FF">
                                    <td style="font-weight:bold">Date</td>
                                    <td>{{ $salarypayable->date }}</td>
                                </tr>
                                <tr style="background-color:#A6D7FF">
                                    <td style="font-weight:bold">Salary Month</td>
                                    <td>{{ date('Y-M', strtotime($salarypayable->salary_month)) }}</td>
                                </tr>
                                <tr style="background-color:#A6D7FF">
                                    <td style="font-weight:bold">Employee Name</td>
                                    <td>{{ $salarypayable->f_name }} {{ $salarypayable->l_name }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Basic Salary</td>
                                    <td style="text-align:right">{{ number_format((float) $salarypayable->basic_salary, 2, '.', ',') }}</td>
                                </tr>
                                <tr style="background-color:#F1F4F6">
                                    <td style="font-weight:bold">Total Allowance</td>
                                    <td style="text-align:right">
                                        {{ $salarypayable->total_allowance ? number_format((float) $salarypayable->total_allowance, 2, '.', ',') : 0.0 }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Over Time Amount</td>
                                    <td style="text-align:right">{{ number_format((float) $salarypayable->over_time_work, 2, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Gross Salary</td>
                                    <td style="text-align:right">{{ number_format((float) $salarypayable->gross_salary, 2, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Advance Payment</td>
                                    <td style="text-align:right">{{ number_format((float) $salarypayable->advance_payment_amount, 2, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">EPF</td>
                                    <td style="text-align:right">{{ number_format((float) $salarypayable->epf, 2, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Others</td>
                                    <td style="text-align:right">{{ number_format((float) $salarypayable->others, 2, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Net Salary</td>
                                    <td style="text-align:right">{{ number_format((float) $salarypayable->net_salary, 2, '.', ',') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div style="padding-right:30px" class="col-md-3 mb-3">
                            <button type="button" onClick="printMe()" class="btn btn-light float-right">
                                <i class="fa fa-print"></i>
                                Print
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>


    <div id="salary_slip_body" style="display: none">
        <table border="1">
            <tr>
                <th align="left" width="50%">
                    <span style="font-size: 13px"> NAME:-  {{ $salarypayable->f_name }} {{ $salarypayable->l_name }}</span>
                    <br>
                    <span style="font-size: 13px"> EMPLOYEE ID:- {{ $salarypayable->emp_code }}</span>
                </th>
                <th colspan='2' align="left" >
                    <span style="font-size: 13px"> TITLE:- {{ $salarypayable->role_name }}</span>
                    <br>
                    <span style="font-size: 13px"> SALARY MONTH:- {{ date('Y-M', strtotime($salarypayable->salary_month)) }}</span> 
                </th>
            </tr>
        </table>
        <br />
        <table border="1">
            <tr style="background-color:#024621;">
                <th style="color:#ffffff;">DESCRIPTION</th>
                <th style="color:#ffffff;" width="25%">EARNINGS</th>
                <th style="color:#ffffff;" width="25%">DEDUCTION</th>
            </tr>
            <tr>
                <td>Basic Salary</td>
                <td align="right">{{ number_format((float) $salarypayable->basic_salary, 2, '.', ',') }}</td>
                <td align="right"></td>
            </tr>
            <tr>
                <td>Total Allowance</td>
                <td align="right">{{ number_format((float) $salarypayable->total_allowance, 2, '.', ',') }}</td>
                <td align="right"></td>
            </tr>
            <tr>
                <td>Over Time Work</td>
                <td align="right">{{ number_format((float) $salarypayable->over_time_work, 2, '.', ',') }}</td>
                <td align="right"></td>
            </tr>
            <tr>
                <td>Advance Payment</td>
                <td align="right"></td>
                <td align="right">{{ number_format((float) $salarypayable->advance_payment_amount, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td>EPF</td>
                <td align="right"></td>
                <td align="right">{{ number_format((float) $salarypayable->epf, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Othres</td>
                @if ($salarypayable->others > 0)
                    <td align="right">{{ number_format((float) $salarypayable->others, 2, '.', ',') }}</td>
                    <td align="right"></td>
                @else
                    <td align="right"></td>
                    <td align="right">{{ number_format((float) -$salarypayable->others, 2, '.', ',') }}</td>
                @endif
            </tr>
            <tr>
                <th align="center">TOTAL</th>
                @if ($salarypayable->others > 0)
                    <th align="right" >{{ number_format((float) ($salarypayable->gross_salary + $salarypayable->others), 2, '.', ',') }}</th>
                    <th align="right">{{ number_format((float) ($salarypayable->advance_payment_amount + $salarypayable->epf), 2, '.', ',') }}</th>
                    @else
                    <th align="right" >{{ number_format((float) ($salarypayable->gross_salary), 2, '.', ',') }}</th>
                    <th align="right">{{ number_format((float) ($salarypayable->advance_payment_amount + $salarypayable->epf - $salarypayable->others), 2, '.', ',') }}</th>
                @endif
            </tr>
        </table>
        <table border="1">
            <tr>
                <th align="left" width="50%">
                    <span style="font-size: 13px"> Payment  Date :- {{ $salarypayable->date }}</span>
                </th>
                <th style="background-color:#024621;color:#ffffff;">
                    Net Pay
                </th>
            </tr>
            <tr>
                <th align="left" width="50%">
                    <span style="font-size: 13px"> Bank Name :- Commercial Bank( {{ $salarypayable->bank_branch_name }} )</span>
                </th>
                <th style="background-color:#437c5d;color:#ffffff;">
                    {{ number_format((float) $salarypayable->net_salary, 2, '.', ',') }}
                </th>
            </tr>
            <tr>
                <th align="left" width="50%">
                    <span style="font-size: 13px"> Bank Account No :- {{ $salarypayable->bank_account_number }}</span>
                </th>
                <th>
                    
                </th>
            </tr>
        </table>
    </div>

    <div id="image" style="display: none">
        <img width="100px" height="60px" src="https://www.jaffnaelectrical.skyrow.lk/assets/img/electrical.jpg" alt="">
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    
    // print
    function printMe() {

        var mywindow = window.open('', 'PRINT');

        mywindow.document.write(`<html><head> <style>body{ margin: 20px auto 0px auto; width: 15cm;}.main-body{border: 1px solid black; padding: 5px;}table{width: 100%; margin: 5px auto; border-collapse: collapse; border: 1px solid black;}table td{line-height: 25px; padding-left: 15px;}table th{color: #363636;}</style></head><body> <div class="main-body"> <table border="1"> <tr height="100px" style="text-align:center;font-size:24px; font-weight:600;"> <td style="background-color:#024621;"></td><td width="40%">Salary Slip</td><td width="30%"> 
            ${$('#image').html()}
            </td></tr></table>
            ${$('#salary_slip_body').html()}
            </div></body></html>`
            );

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        // mywindow.close();
    };
</script>
