@extends('layouts.navigation')
@section('customer', 'active')
@section('content')
    <div class="card">
        <div class="p-4">
            <div class="row">
                <div class="col-6">
                    <form action="/customer-tale-report/{{$customer_id}}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-7">
                                <div class="form-group">
                                    <label>From :</label>
                                    <input type="text" class="form-control yearpicker" name="year" id="year"
                                        value="{{ $year }}">
                                </div>
                            </div>
                            <div class="col-5">
                                <button class="btn btn-success mr-3 mt-4 subButton" id="add"
                                    type="submit">Submit</button>
                                
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-3">
                    <button class="btn btn-primary mt-4 subButton"
                    onclick="printContent();">Print</button>
                </div>
            </div>
        </div>

        <div id="printReport">
            <div class="card-header">
                <h4>Customer Payment Details -Year ({{$year}})</h4>
            </div>
            <div style="text-align: center;">
    
                <h6>Credit Customer Name - {{$customerDatails->name}}</h6>
                <h6>Mobile Number - {{$customerDatails->phone_number}}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" style="width:100%;">
                        <thead>
                            <tr>
                                <th >Date</th>
                                <th >Invioice Number</th>
                                <th style="text-align: right;">Credit Amount</th>
                                <th style="text-align: right;">Paid Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center;" colspan="3">Before Year Blance</td>
                                <td style="text-align: right;">{{ number_format($beforeYearBlance,2) }}</td>
                            </tr>
                            @foreach ($Tali as $row)
                                @foreach ($row->bills as $item)
                                    <tr>
                                        <td>{{ $item->billing_date }}</td>
                                        <td>{{ $item->invoice_no }}</td>
                                        <td style="text-align: right;">{{ number_format($item->credit_payment,2) }}</td>
                                        <td style="text-align: right;"></td>
                                    </tr>
                                @endforeach
                                @isset($row->date)
                                    <tr>
                                        <td>{{ $row->date }}</td>
                                        <td></td>
                                        <td style="text-align: right;"></td>
                                        <td style="text-align: right;">{{ number_format($row->totalPayedAmount,2) }}</td>
                                    </tr>
                                @endisset
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"></td>
                                    <td style="text-align: right; color: red;">{{ number_format($row->blancePay,2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function printContent() {
        var body1 = `<!DOCTYPE html><html lang="en"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> <title>print</title> <style>.account{border: 2px solid black; width: 100%; color: black; font-size: 18px;}.accountTd{border: 1px solid rgb(216, 204, 204); border-right: 2px solid black; padding: 3px;}.borderTd{border-bottom: 1px solid black;}.borderTop{border-top: 1.1px solid black;}.tdBold{border-right: 3px solid black;}.tdWidth{width: 150px;}.borderBottom{border-bottom: 2px solid black;}.subButton{margin-top: 29px;}.dash{border-bottom: 4px solid black}.tdright{text-align: right;}.tdcenter{text-align: center;}.bgtd{background-color: rgb(227, 227, 227);}.borderBottom2{border-bottom: 1.1px solid black;}</style></head><body>`;
        var body2 = $('#printReport').html();
        var body3 = `</body></html>`;

        var mywindow = window.open('', 'PRINT');
        mywindow.document.write(body1 + body2 + body3);
        mywindow.focus();
        mywindow.print();
        mywindow.close();
    }
</script>