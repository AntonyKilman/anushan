@extends('layouts.navigation')
@section('productReport', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Sales Products Report</h4>
        </div>
        <div class="card-body">
           <form action="/sales-product-reports" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <strong>Date From</strong>
                            <input type="date" name="from_date" id="from" value="@if (isset($from)){{ $from }}@endif" class="form-control"
                            required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <strong>Date To</strong>
                            <input type="date" name="to_date" id="to" value="@if (isset($to)){{ $to }}@endif" class="form-control"
                            required>
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top:24px">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <br>

            <div class="card-body">
                <div class="card-body">
                    <h3 style="text-align: right ">Total: {{ number_format($salesReportView->sum('sub_total'),2) }}</h3>
                  <div class="table-responsive">
                    <table class="table table-striped" id="salesProductTable">
                      <thead>
                        <tr>
                          <th  style="text-align: center">#</th>
                          <th  style="text-align: center">Item</th>
                          <th  style="text-align: center">Purchase Price</th>
                          <th  style="text-align: center">Qty</th>
                          <th  style="text-align: center">Sales Price</th>
                          <th  style="text-align: center">Discount</th>
                          <th  style="text-align: center">Sub Total</th>
                          @if (in_array('ViewProfitAmount', session('Access')))
                               <th  style="text-align: center">Profit</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @php
                          $i=1;
                        @endphp
                        <tr>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: right">Total:</th>
                            <th  style="text-align: right">{{ number_format($salesReportView->sum('sub_total'),2) }}</th>
                            @if (in_array('ViewProfitAmount', session('Access')))
                                   <th  style="text-align: center"></th>
                            @endif
                          </tr>

                          <tr>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: center"></th>
                            <th  style="text-align: right"></th>
                            <th  style="text-align: right"></th>
                            @if (in_array('ViewProfitAmount', session('Access')))
                                    <th  style="text-align: right"></th>      
                            @endif
                          </tr>

                          @foreach ($salesReportView as $item)
                              <tr>
                                  <td>{{ $i++ }}</td>
                                  <td>{{$item->product_name}}</td>
                                  <td>{{number_format($item->purchase_price,2)}}</td>
                                  <td>{{$item->quantity_sum}}</td>
                                  <td style="text-align: center">
                                    @if ($item->min_amount == $item->max_amount)
                                    {{number_format($item->min_amount,2)}}
                                    @else
                                    {{number_format($item->min_amount,2)}} - {{number_format($item->max_amount,2)}}
                                    @endif
                                    </td>
                                  <td  style="text-align: center">
                                    @if ($item->min_discount == $item->max_discount)
                                    {{number_format($item->min_discount,2)}}
                                    @else
                                    {{number_format($item->min_discount,2)}} - {{number_format($item->max_discount,2)}}
                                    @endif
                                    </td>
                                  <td  style="text-align: right">{{number_format($item->sub_total,2)}}</td>
                                  @if (in_array('ViewProfitAmount', session('Access')))
                                          <td  style="text-align: right">{{number_format(( ($item->sub_total) - ($item->purchase_price * $item->quantity_sum)),2)}}</td>
                                  @endif
                              </tr>
                          @endforeach
                      </tbody>
                     
                    </table>
                  </div>
                </div>
            </div>

        </div>
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#salesProductTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Product Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Product Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>

