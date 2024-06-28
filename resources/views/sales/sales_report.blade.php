@extends('layouts.navigation')
@section('sales-report', 'active')
@section('content')
    <!-- Main Content -->
    <form action="/sales/sales-report" method="get">
        @csrf
        <div class="card-body form">
            <h6>Sales Report</h6>

            <div class="form-row">
                <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input type="date" id="from" name="from" value="{{$from}}" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date" id="to" name="to" max="{{now()->format('Y-m-d')}}" value="{{$to}}" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Bill Type</label>
                    <select name="bill_type" id="bill_type" class="form-control">
                        <option  value="All" {{ ($bill_type == 'All') ? 'selected' : '' }}>All</option>
                        <option  value="Customer" {{ ($bill_type == 'Customer') ? 'selected' : '' }}>Normal Bills</option>
                        <option  value="Credit Customer" {{ ($bill_type == 'Credit Customer') ? 'selected' : '' }}>Credit Bills</option>
                    </select>
                </div>
                <div class="form-group col-md-3" style="margin-top:30px">
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>        
    <div class="card-body">
        <div class="row">
            <div class="col-4"><p class="h6 text-dark">Number of Sales: {{$salesReport->count()}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Sales: {{number_format($salesReport->sum('totalAmount'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Product Discount: {{number_format($salesReport->sum('discount_amount'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Cash Payment: {{number_format($salesReport->sum('cash_payment'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Card Payment: {{number_format($salesReport->sum('card_payment'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Credit Payment: {{number_format($salesReport->sum('credit_payment'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Cheque Payment: {{number_format($salesReport->sum('cheque_payment'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Sales Return Amount: {{number_format($salesReport->sum('sales_return_amount'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Advance Payment Amount: {{number_format($salesReport->sum('advance_payment_amount'),2)}}</p></div>
            @if (in_array('ViewProfitAmount', session('Access')))
                <div class="col-4"><p class="h6 text-dark">Total Profit: {{number_format($salesReport->sum('totalAmount') - ($salesReport->sum('totalPurchasePrice') + $salesReport->sum('total_bill_discount')),2)}}</p></div>
            @endif
        </div>
    </div>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">
                            <h2> Sales Report</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="salesTable">
                                    <thead>
                                        <tr>
                                        <th class="text-center">#</th>
                                        <th>Date & Time</th>
                                        <th>Invoice No</th>
                                        <th>Number of Items</th>
                                        <th>Total Product Discount</th>
                                        <th>Total Amount</th>
                                        <th>Total Bill Discount</th>
                                        <th>Total Paid</th>
                                        <th>Sales Return Amount</th>
                                        <th>Advance Payment Amount</th>
                                        @if (in_array('ViewProfitAmount', session('Access')))
                                            <th>Profit</th>
                                        @endif
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($salesReport as $item)
                                        <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->invoice_no}}</td>
                                        <td>{{$item->productQuantity}}</td>
                                        <td>{{number_format($item->discount_amount,2)}}</td>
                                        <td>{{number_format(($item->totalAmount),2)}}</td>
                                        <td>{{number_format(($item->total_bill_discount),2)}}</td>
                                        <td>{{number_format(($item->cash_payment + $item->card_payment + $item->loyality_payment + $item->credit_payment + $item->cheque_payment + $item->sales_return_amount),2)}}</td>
                                        <td>{{number_format($item->sales_return_amount,2) }}</td>
                                        <td>{{number_format($item->advance_payment_amount,2) }}</td>
                                        @if (in_array('ViewProfitAmount', session('Access')))
                                            <td>
                                                {{number_format(( ($item->totalAmount) - ($item->totalPurchasePrice + $item->total_bill_discount)),2)}}
                                            </td>
                                        @endif
                                        <td>
                                            <a  href="{{route('salesView.report', $item->id)}}" class="btn btn-primary"><i class="far fa-eye"></i></a>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#salesTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>