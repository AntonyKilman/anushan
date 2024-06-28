@extends('layouts.navigation')
@section('expery_date_report', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Expiry Date</h4>
        </div>
        <form action="/expery-date-report" method="POST">
            @csrf
            <div class="card-body form">
                <div class="form-row">

                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" id="from" name="from" value="{{ $from }}" class="form-control"
                            required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" id="to" name="to" value="{{ $to }}" class="form-control"
                            required>
                    </div>
                    <div class="form-group col-md-3" style="margin-top: 28px">
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>

    
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="inventoryTable">
                    <thead>
                        <tr> 
                            <th style="display: none">#</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Quantity Type</th>
                            <th>Expiry Date</th>
                            <th style="text-align: center">Expiry Days</th>
                            <th>GR No</th>
                            <th style="text-align: center">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase_items as $purchase_item)
                            @if ($purchase_item->pur_item_qty > 0)
                                <tr
                                    style="{{ date('Y-m-d', strtotime(now())) > $purchase_item->pur_item_expery_date ? 'background-color: rgba(255, 0, 0, 0.452);' : '' }}  {{ date('Y-m-d', strtotime(now())) == $purchase_item->pur_item_expery_date ? 'background-color: orange' : '' }}">
                                    <td style="display: none">#</th>
                                        <td>{{ $purchase_item->product_code }}</td>
                                    <td>{{ $purchase_item->product_name }}</td>
                                    <td>{{ $purchase_item->pur_item_qty_type }}</td>
                                    <td>{{ $purchase_item->pur_item_expery_date }}</td>
                                    <td style="text-align: right">{{ \Carbon\Carbon::parse(substr(now(), 0, 10))->diffInDays($purchase_item->pur_item_expery_date, false) }}
                                    </td>
                                    <td>{{ $purchase_item->purchase_order_id }}</td>
                                    <td style="text-align: right">{{ number_format($purchase_item->pur_item_qty, 2) }}</td>
                                    {{-- <th>
                          <form action="/purchase-order-view" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{$purchase_item->id}}">
                            <input type="hidden" name="from" value="report">
                            <button type="submit" class="btn btn-info btn-edit"><i class="far fa-eye"></i></button>
                          </form>
                        </th> --}}
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- @endif --}}
    </div>
    <script>
        $(document).ready(function() {
            $('#reset').click(function(e) {
                e.preventDefault();
                $('#from').val('');
                $('#to').val('');
            });
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
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
