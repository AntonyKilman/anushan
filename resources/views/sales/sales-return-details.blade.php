@extends('layouts.navigation')
@section('Foodcity_Sales_Return_Details', 'active')
@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <div class="pl-4 pt-4">
                        <h4>Sales Return Details</h4>
                    </div>
                    <form action="/sales-return-details" method="get">
                        @csrf
                        <div class="card-body form">
                            {{-- <h6>Sales Return Details</h6> --}}
                            <div class="form-row">
                                <div class="form-group col-3">
                                    <label><i class="fa fa-filter"></i> Filter by Date :</label>
                                    <input type="date" id="from" name="from" value="{{$from}}" class="form-control" required>
                                </div>
                                <div class="form-group col-3" style="margin-top:30px">
                                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>Invoice No.</th>
                                        <th>Return Date</th>
                                        <th>Total Amount</th>
                                        <th>Reason</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($salesReturns as $salesReturn)
                                    <tr>
                                        <td class="text-center">{{ $i }}.</td>
                                        <td>{{ $salesReturn->invoice_no }}</td>
                                        <td>{{ $salesReturn->return_date }}</td>
                                        <td>{{ number_format($salesReturn->amount , 2) }}</td>
                                        <td>{{ $salesReturn->return_resaon }}</td>
                                        <td>
                                            @if ($salesReturn->cash == 'UnPaid')
                                            <button class="btn btn-sm btn-warning status" data-toggle="modal"
                                                data-target="#changeStatusModal" data-id="{{ $salesReturn->id }}"
                                                data-status="{{ $salesReturn->cash }}">
                                                UnPaid
                                            </button>

                                            @elseif($salesReturn->cash == 'ReturnThing')
                                                <button class="btn btn-sm btn-primary" disabled>
                                                    ReturnThing
                                                </button>
                                                @else
                                                    <button class="btn btn-sm btn-success" disabled>
                                                        Paid
                                                    </button>
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

@section('modal')
<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeStatusModalLabel">Change Payment
                    Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sales-return-details.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <input type="hidden" name="id" id="id">
                        <label for="payment_status">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control" required>
                            <option value="UnPaid" class="all-status UnPaid"> Unpaid</option>
                            <option value="Paid" class="all-status Paid">Paid</option>
                        </select>
                    </div>
                    <div class="float-right">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() { 
        $(".status").click(function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    $('#id').val(id);

    $('.all-status').attr('selected', false);
    $('.' + status).attr('selected', true);

});
});
</script>