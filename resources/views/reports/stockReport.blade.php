@extends('layouts.navigation')
@section('stock', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    
    ?>
    <!-- Main Content -->
    <div class="card">
        <form action="/stock-report" method="POST">
            @csrf

            <div class="card-body form">
                <h6>Product Stock</h6>

                <div class="form-row">
                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" id="from" value="{{ $from }}" name="from" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" id="to" value="{{ $to }}" name="to"
                            class="form-control">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Filter By</label>
                        <select id="filter_by" name="filter_by" class="form-control">
                            <option value="" disabled selected> Select </option>
                            <option value="1" {{ $filter_by == 1 ? 'selected' : '' }}>Supplier Wise</option>
                        </select>
                    </div>

                    <div class="col-md-9" align="right">
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>

                    </div>
                </div>
            </div>
        </form>



        {{-- @if (count($purchasedItems) > 0) --}}

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="inventoryTable">
                    <thead>
                        <tr>
                            @if ($name != '')
                                <th>{{ $name }}</th>
                            @endif
                            <th style="display: none">id</th>
                            <th>Product Name</th>
                            <th>Quantity type</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchasedItems as $item)
                            <tr>
                                @if ($name != '')
                                    @if ($item->seller_name)
                                        <td>{{ $item->seller_name }}</td>
                                    @endif
                                @endif

                                <td style="display: none"></td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->pur_item_qty_type }}</td>
                                <td>{{ $item->totalQty }}</td>
                                <td>
                                @if ($item->totalQty > 0)
                                        {{ number_format($item->pur_item_amount / $item->totalQty, 2) }}
                                    @endif
                                </td>
                                <td>{{ number_format($item->pur_item_amount, 2) }}</td>
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
                    messageTop: 'Product Stock Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Product Stock Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>