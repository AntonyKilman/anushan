@extends('layouts.navigation')
@section('salary_pay_by_Hand', 'active')
@section('content')


    <div class="card card-success">
        <div class="card-header">
            <h4>Salary Pay By Hand</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-3">
                    <input class="form-control date" value="{{ $month }}" name="month" type="month" id="month"
                        onchange="window.location.assign('/salary-pay-by-hand?month=' + this.value)" />
                </div>
                <div class="col-md-2">
                    <button type="button" onClick="printByHand()" class="btn btn-light float-right">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
            <br>

            <div class="table-responsive" id="table_print">
                <table  border="1" class="mb-0 table table-striped table">
                    <tr style="background-color:#afa35d;">
                        <th  style="color:#ffffff;">Ser #</th>
                        <th style="color:#ffffff;" width="35%">Name</th>
                        <th style="color:#ffffff;" width="15%">NIC No</th>
                        <th  style="color:#ffffff;" width="12%">Amount Received</th>
                        <th style="color:#ffffff;" width="12%">Received Date</th>
                        <th style="color:#ffffff;" width="18%">Signature</th>
                    </tr>

                    <?php $i = 1; ?>
                    @foreach ($salary_pay_by_hand as $row)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $row->emp_name }}</td>
                            <td>{{ $row->emp_nic }}</td>
                            <td align="right"> {{ number_format((float) $row->net_salary, 2, '.', ',') }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan='3' align="center" style="font-size: 18px;font-weight:600;">TOTAL (LKRs)</td>
                        <td align="right" style="font-size: 18px;font-weight:600;"> {{ number_format((float) $salary_pay_by_hand->sum('net_salary'), 2, '.', ',') }}</td>
                        <td colspan='2'></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="hedding" style="display: none">
        <table style="border: none">
            <tr>
                <th colspan="3">
                    SALARY CASH DISTRIBUTION- {{ date('M-Y', strtotime($month)) }}
                    <br>
                    Advance taken by:............ / Cheque No: ...........  /  LKRs. {{ number_format((float) $salary_pay_by_hand->sum('net_salary'), 2, '.', ',') }}
                    <br>    
                    Handing over to following individual by hand ({{ $salary_pay_by_hand->count('emp_name') }} Employees)
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
    function printByHand() {
        var mywindow = window.open('', 'PRINT');

        mywindow.document.write(`<html><head> <style>body{width: 97%; margin: 20px auto 0px auto;}.main-body{border: 1px solid black; padding: 5px;}table{width: 100%; margin: 5px auto; border-collapse: collapse; border: 1px solid black;}table td{line-height: 25px; padding-left: 15px;}table th{color: #363636;}</style></head><body> <div class="main-body"> <table > <tr height="120px" style="text-align:center;font-size:18px; font-weight:600;background-color: #f1eb8b"> <td></td><td width="60%"> ReeCha Organic Farm (Pvt) Ltd <br>Kottaandaar Kulam, Iyakkachchi, Kilinochchi. <br>Company No - PV 00211037 <br>E-mail : prem@reecha.lk Tele: 077 222 3949 </td><td width="20%"> 
            ${$('#image').html()} 
            </td></tr></table> 
            ${$('#hedding').html()} 
            ${$('#table_print').html()} 
            <br><table border="1"> <tr> <th align="left" width="50%"> <br>Paid by:……...………………. <br><br>Date:………………………… </th> <th align="left"> <br>Checked by:……...……… <br><br>Date:………………………… </th> </tr><tr> <th align="left" colspan='2'> <br>Approved by:……...………... <br><br>Date:………………………… </th> </tr></table> </div></body></html>` 
            );
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        // mywindow.close();
    }
    
</script>
