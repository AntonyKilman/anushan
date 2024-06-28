@extends('layouts.navigation')
@section('sales-temp-report', 'active')
@section('content')
    <!-- Main Content -->
    <form action="/sales/temp-sales-report" method="get">
        @csrf
        <div class="card-body form">
            <h6>Temp Sales Report</h6>
        </div>
    </form>        
    <div class="card-body">
        <div class="row">
            <div class="col-4"><p class="h6 text-dark">Number of Sales: {{$temp_salesReport->count()}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Sales: {{number_format($temp_salesReport->sum('totalAmount'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Product Discount: {{number_format($temp_salesReport->sum('discount_amount'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Cash Payment: {{number_format($temp_salesReport->sum('cash_payment'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Card Payment: {{number_format($temp_salesReport->sum('card_payment'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Credit Payment: {{number_format($temp_salesReport->sum('credit_payment'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Total Cheque Payment: {{number_format($temp_salesReport->sum('cheque_payment'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Sales Return Amount: {{number_format($temp_salesReport->sum('sales_return_amount'),2)}}</p></div>
            <div class="col-4"><p class="h6 text-dark">Advance Payment Amount: {{number_format($temp_salesReport->sum('advance_payment_amount'),2)}}</p></div>
            @if (in_array('ViewProfitAmount', session('Access')))
                <div class="col-4"><p class="h6 text-dark">Total Profit: {{number_format($temp_salesReport->sum('totalAmount') - $temp_salesReport->sum('totalPurchasePrice'),2)}}</p></div>
            @endif
        </div>
    </div>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">
                            <h2>Temp Sales Report</h2>
                            <div class="row">
                                <div class="col-10" >
                                </div>
                                <div class="col-2" >
                                    <button class="btn btn-danger delete"  {{!$lastId ? 'disabled' : ''}} id="delete" data-id="{{ $lastId }}">Delete</button>
                                </div>
                            </div>
                            @if ($startDateTime)
                                <h5 style="text-align: center;">Date Time ({{$startDateTime}} - {{$endDateTime}})</h5>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="salesTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Date & Time</th>
                                            <th>Invoice No</th>
                                            <th>Cash</th>
                                            <th>Card</th>
                                            <th>Cheque</th>
                                            <th>Credit</th>
                                            <th>Online Amount</th>
                                            <th>Sales Return Amount</th>
                                            <th>Advance Payment Amount</th>
                                            <th>Total Product Discount</th>
                                            <th>Total Bill Discount</th>
                                            <th>Total Amount</th>
                                            <th>Total Paid</th>
                                            @if (in_array('ViewProfitAmount', session('Access')))
                                                <th>Profit</th>
                                            @endif
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{number_format($temp_salesReport->sum('cash_payment'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('card_payment'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('cheque_payment'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('credit_payment'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('online_payment'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('sales_return_amount'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('advance_payment_amount'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('discount_amount'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('total_bill_discount'),2)}} <hr> </td>
                                        <td>{{number_format($temp_salesReport->sum('totalAmount'),2)}} <hr> </td>
                                        <td></td>
                                        @if (in_array('ViewProfitAmount', session('Access')))
                                            <td></td>
                                        @endif
                                        <td></td>
                                    </tr>
                                    @foreach ($temp_salesReport as $item)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{$item->created_at}}</td>
                                            <td>{{$item->invoice_no}}</td>
                                            <td>{{number_format($item->cash_payment,2)}}</td>
                                            <td>{{number_format($item->card_payment,2)}}</td>
                                            <td>{{number_format($item->cheque_payment,2)}}</td>
                                            <td>{{number_format($item->credit_payment,2)}}</td>
                                            <td>{{number_format($item->online_payment,2)}}</td>
                                            <td>{{number_format($item->sales_return_amount,2) }}</td>
                                            <td>{{number_format($item->advance_payment_amount,2) }}</td>
                                            <td>{{number_format($item->discount_amount,2)}}</td>
                                            <td>{{number_format(($item->total_bill_discount),2)}}</td>
                                            <td>{{number_format(($item->totalAmount),2)}}</td>
                                            <td>{{number_format(($item->cash_payment + $item->card_payment + $item->loyality_payment + $item->credit_payment + $item->cheque_payment + $item->sales_return_amount),2)}}</td>
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
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>


<script>
    $(document).ready(function() {

        var date_from = '{{ $startDateTime }}';
        var date_to = '{{ $endDateTime }}';

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
                    messageTop: 'Temp - Sales Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Temp - Sales Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });


        // delete
        $('#delete').on('click', function() {
                var id = $(this).attr('data-id');
    
                swal({
                    title: 'Are you sure?',
                    text: 'Once deleted, you will not be able to recover this data!',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
    
                        $.ajax({
                            url: "/sales/temp-sales-delete/" + id,
                            method: 'GET',
                            data: {},
                            dataType: '',
                            contentType: "",
                            success: function(res) {
                                swal('Poof! Your data has been deleted!', {
                                    icon: 'success',
                                    timer: 2000,
                                });
                                location.reload();
                            }
                        });
                        
                    }
                })   
        });
    });

</script>