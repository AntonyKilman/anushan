@extends('layouts.navigation')
@section('credit_payments', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">

        <div class="card-header">
            <h4 class="header">Credit Payment</h4>
        </div>


        <form action="/credit-payments" method="get" class="needs-validation" novalidate="">
            @csrf
            <div class="card-body form">

                <div class="row">

                    <div class="col-3">
                        <label>From</label>
                        <input type="date" name="from" id="from" value="{{ $from }}"
                            max="{{ now()->format('Y-m-d') }}" class="form-control" required>
                    </div>

                    <div class="col-3">
                        <label>To</label>
                        <input type="date" name="to" id="to" value="{{ $to }}"
                            max="{{ now()->format('Y-m-d') }}" class="form-control" required>
                    </div>

                    <div class="col-3">
                        <label>Supplier</label>
                        <select name="supplier" id="supplier" class="form-control" >
                            <option id="selectedAll" value="">All</option>
                            @foreach ($supplier as $item)
                                <option class="supplier" value="{{ $item->id }}"
                                    {{ $item->id == $seller ? 'selected' : '' }}>{{ $item->seller_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3" style="margin-top: 30px">
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>

                </div>

                <div class="col-3" style="margin-top: 40px">
                    <h5> Total Credit : {{ number_format($total_payment, 2) }}</h5>
                </div>



            </div>


        </form>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="inventoryTable">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Payment Date</th>
                            <th>Supplier</th>
                            <th>Purchase Order</th>
                            <th>Bill No</th>
                            <th>Cash Amount</th>
                            <th>Cheque Amount</th>
                            <th>Discount Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($payments as $item)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $item->payment_date }}</td>
                                <td>{{ $item->seller_name }}</td>
                                <td>{{ $item->purchase_order_id }}</td>
                                <td>{{ $item->pur_ord_bill_no }}</td>
                                <td>{{ number_format($item->cash_amount, 2) }}</td>
                                <td>{{ number_format($item->cheque_amount, 2) }}</td>
                                <td>{{ number_format($item->discount_amount, 2) }}</td>
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

        $('#inventoryTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Credit Payment From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Credit Payment From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {


        $("#reset").click(function() {
            let selectValue = $('#supplier').val();
            var date = new Date();
            var currentDate = date.toISOString().slice(0, 10);
            $("#to").val(currentDate);
            $("#from").val("");
            $(".supplier").attr("selected", false);
            $("#selectedAll").attr("selected", true);
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
    });
</script>
