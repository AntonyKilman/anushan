@extends('layouts.navigation')
@section('purchase_order_report', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">

        <div class="card-header">
            <h4 class="header">GR Report</h4>
        </div>

        <form action="/purchase-order-report" method="get" class="needs-validation" novalidate="">
            @csrf

            <div class="card-body form">

                <div class="form-row">

                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" id="from" name="from" value="{{ $from }}"
                            class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" id="to" name="to" value="{{ $to }}"
                            class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Supplier</label>
                        <select name="supplier" required class="form-control" id="seller">
                            <option value="" disabled selected>Please Select</option>
                            @foreach ($inventory_sellers as $seller)
                                <option value="{{ $seller->id }}" {{ $seller->id == $supplier ? 'selected' : '' }}>
                                    {{ $seller->seller_name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-3" style="margin-top: 27px">
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>

                </div>

                <div class="row">
                    <div class="col-4">
                        <h5>Total Amount : Rs. {{ number_format($total_amount, 2) }}</h5>
                    </div>

                    <div class="col-4">
                        <h5>Cash : Rs. {{ number_format($total_cash, 2) }}</h5>
                    </div>

                    <div class="col-4">
                        <h5>Credit : Rs. {{ number_format($total_credit, 2) }}</h5>
                    </div>
                </div>


            </div>
        </form>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="inventorynewpurchaseTable">
                    <thead>
                        <tr>
                            <th style="display: none"></th>
                            <th>Date</th>
                            <th>GR Number</th>
                            <th>Supplier</th>
                            <th>Bill No</th>
                            <th style="text-align: center">Payment</th>
                            <th style="text-align: center">Cash</th>
                            <th style="text-align: center">Credit</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchase_order as $purchase_order)
                            <tr>
                                <td style="display: none"></td>
                                <td>{{ $purchase_order->date }}</td>
                                <td>{{ $purchase_order->id }}</td>
                                <td>{{ $purchase_order->seller_name }}</td>
                                <td>{{ $purchase_order->pur_ord_bill_no }}</td>
                                <td style="text-align: right">{{ number_format($purchase_order->pur_ord_amount, 2) }}</td>
                                <td style="text-align: right">{{ number_format($purchase_order->pur_ord_cash, 2) }}</td>
                                <td style="text-align: right">{{ number_format($purchase_order->pur_ord_credit, 2) }}</td>
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

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#inventorynewpurchaseTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script>
    $('#reset').click(function(e) {
        e.preventDefault();
        $('#seller').val('');

    });

    setMinDate();

    $('#from').change(function(e) {
        e.preventDefault();
        setMinDate();

    });

    function setMinDate() {
        var from = $('#from').val();
        if (from) {
            $('#to').attr('min', from);
        }
    }
</script>
