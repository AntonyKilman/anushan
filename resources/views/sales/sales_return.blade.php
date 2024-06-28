@extends('layouts.navigation')
@section('salesReturn', 'active')
@section('content')
    <!-- Main Content -->
    <form action="/sales/sale-return" method="get">
        @csrf
        <div class="card-body form">
            <h6>Sales Return</h6>

            <div class="form-row">
                <div class="form-group col-md-3 ">
                    <label>Date</label>
                    <input type="date" id="from" name="from" value="{{$from}}" class="form-control" required>
                </div>
                <div class="form-group col-md-3" style="margin-top:30px">
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>        
    <section class="section">
        
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">
                            <h2> Sales Return</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                        <th class="text-center">#</th>
                                        <th>Date & Time</th>
                                        <th>Invoice No</th>
                                        <th>Number of Items</th>
                                        <th>Total Amount</th>
                                        <th>Total Discount</th>
                                        <th>Total Bill Discount</th>
                                        <th>Total Paid</th>
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
                                        <td>{{number_format(($item->totalAmount + $item->discount_amount),2)}}</td>
                                        <td>{{number_format(($item->discount_amount),2) }}</td>
                                        <td>{{number_format($item->total_bill_discount,2)}}</td>
                                        <td>{{number_format(($item->cash_payment + $item->card_payment + $item->loyality_payment + $item->credit_payment + $item->cheque_payment),2)}}</td>
                                        <td>
                                            @if($item->is_cancelled != 1)
                                                <a  href="{{route('salesReturn.view', $item->id)}}" class="btn btn-primary"><i class="far fa-eye"></i></a>
                                            @else
                                                <p>Cancelled</p>
                                            @endif
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
    $(document).ready(function(){
        setTimeout(function(){
        $("div.alert").remove();
        }, 5000 ); // 5 secs
    });
</script>