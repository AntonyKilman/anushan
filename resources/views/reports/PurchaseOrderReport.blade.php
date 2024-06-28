@extends('layouts.navigation')
@section('purchase_order_report','active')
@section('content')
<?php
  $Access=session()->get('Access');
?>

    <!-- Main Content -->
    <div class="card">
    <form action="/purchase-order-report" method="get" class="needs-validation" novalidate="">
        @csrf

        <div class="card-body form">
            <h6>Purchase Order Report</h6>

            <div class="form-row">

                <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input type="date" id="from" name="from" value="{{ $from }}" class="form-control">
                </div>

                <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date" id="to" name="to" value="{{ $to }}" class="form-control">
                </div>

                <div class="form-group col-md-3">
                    <label>Supplier</label>
                    <select id="seller" name="seller" class="form-control">
                        <option value="" disabled selected>Select Supplier</option>
                        @foreach ($sellers as $seller)
                        <option value="{{ $seller->id }}" {{ $seller_id == $seller->id ? 'selected' : '' }}>{{ $seller->seller_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-9" align="right">
                    <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>


        </div>
    </form>

    {{-- @if (count($purchase_order) > 0) --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="inventrypurchaseTable">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Bill No</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase_order as $purchase_order)
                            <tr>
                                <td>{{ $purchase_order->seller_name }}</td>
                                <td>{{ $purchase_order->pur_ord_bill_no }}</td>
                                <td>{{ $purchase_order->pur_ord_amount }}</td>
                                <td>{{ substr($purchase_order->created_at, 0, 10) }}</td>
                                <th>
                                    <form action="/purchase-order-view" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $purchase_order->id }}">
                                        <input type="hidden" name="from" value="report">
                                        <button type="submit" class="btn btn-info btn-edit"><i
                                                class="far fa-eye"></i></button>
                                    </form>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    {{-- @endif --}}
    </div>

    <script>
        $(document).ready(function() {
            var filter_by=$('#filter_by').val();
            if (filter_by!=null) {
                var html = '';
                html += '<label>Seller</label>';
                html += '<select id="seller" name="seller" class="form-control" required>';
                html += '@foreach ($sellers as $seller)';
                html += '<option value="" disabled selected>Select</option>';
                html += '<option value="{{ $seller->id }}" {{ $seller_id == $seller->id ? 'selected' : '' }}>{{ $seller->seller_name }}</option>';
                html += '@endforeach';
                html += '</select>';

                $('#seller_select').append(html);
            }
            $('#filter_by').change(function(e) {
                e.preventDefault();
                var html = '';
                html += '<label>Seller</label>';
                html += '<select id="seller" name="seller" class="form-control" required>';
                html += '@foreach ($sellers as $seller)';
                html += '<option value="" disabled selected>Select</option>';
                html += '<option value="{{ $seller->id }}" {{ $filter_by == $seller->id ? 'selected' : '' }}>{{ $seller->seller_name }}</option>';
                html += '@endforeach';
                html += '</select>';

                $('#seller_select').append(html);
                $('#seller').val('');
            });
            $('#reset').click(function (e) {
                e.preventDefault();
                $('#from').val('');
                $('#to').val('');
                $('#seller').val('');

            });
        });
    </script>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#inventrypurchaseTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Purchase Order Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Purchase Order Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
