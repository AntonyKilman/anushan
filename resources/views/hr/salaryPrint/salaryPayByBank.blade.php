@extends('layouts.navigation')
@section('salary_pay_by_bank', 'active')
@section('content')


    <div class="card card-success">
        <div class="card-header">
            <h4>Salary Pay By Bank</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-3">
                    <input class="form-control date" value="{{ $month }}" name="month" type="month" id="month"
                        onchange="window.location.assign('/salary-pay-by-bank?month=' + this.value)" />
                </div>
                <div class="col-md-2">
                    <button type="button" onClick="printByBank()" class="btn btn-light float-right">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
            <br>

            <div class="table-responsive" id="table_print">
                <table  border="1" class="mb-0 table table-striped table">
                    <tr style="background-color:#afa35d;">
                        <th style="color:#ffffff;">No</th>
                        <th style="color:#ffffff;" width="35%">Name</th>
                        <th style="color:#ffffff;" width="20%">Account Number</th>
                        <th style="color:#ffffff;" width="15%">NIC Number</th>
                        <th style="color:#ffffff;" width="16%">Amount (LKRs)</th>
                    </tr>

                    <?php $i = 1; ?>
                    @foreach ($salary_pay_by_bank as $row)
                        <tr>
                            <td>{{ $i++ }} </td>
                            <td>{{ $row->emp_name }}</td>
                            <td>{{ $row->emp_account_number }}</td>
                            <td>{{ $row->emp_nic }}</td>
                            <td align="right"> {{ number_format((float) $row->net_salary, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan='4' align="center" style="font-size: 18px;font-weight:600;">TOTAL (LKRs)</td>
                        <td align="right" style="font-size: 18px;font-weight:600;">{{ number_format((float) $salary_pay_by_bank->sum('net_salary'), 2, '.', ',') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="hedding" style="display: none">
        <table style="border: none">
            <tr>
                <th colspan="3" style="font-size:20px;font-weight:600;">
                    FUND TRANSFER ORDER -  {{ date('M-Y', strtotime($month)) }}
                </th>
            </tr>
            <tr>
                <th colspan="3" align="left" style="font-size:17px;font-weight:600;">
                    <br>
                    Transfer from : …………….. / Cheque No: ………………….. / LKRs. {{ number_format((float) $salary_pay_by_bank->sum('net_salary'), 2, '.', ',') }}
                    <br>
                    Transfer to following commercial bank individual bank accounts ({{ $salary_pay_by_bank->count('emp_name') }} Nos)
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
    function printByBank() {
        var mywindow = window.open('', 'PRINT');

        mywindow.document.write(`<html><head> <style>body{width: 97%; margin: 20px auto 0px auto;}.main-body{border: 1px solid black; padding: 5px;}table{width: 100%; margin: 5px auto; border-collapse: collapse; border: 1px solid black;}table td{line-height: 25px; padding-left: 15px;}table th{color: #363636;}</style></head><body> <div class="main-body"> <table> <tr height="120px" style="text-align:center;font-size:18px; font-weight:600;background-color: #f1eb8b"> <td></td><td width="60%"> ReeCha Organic Farm (Pvt) Ltd <br>Kottaandaar Kulam, Iyakkachchi, Kilinochchi. <br>Company No - PV 00211037 <br>E-mail : .lk Tele </td><td width="20%"> 
            ${$('#image').html()} 
            </td></tr></table> 
            ${$('#hedding').html()} 
            ${$('#table_print').html()} 
            <br><span style="font-size: 15px;font-weight:600;"> Amount in words :</span> <br><br><br></div></body></html>` 
            );
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        // mywindow.close();
    }
    
</script>
