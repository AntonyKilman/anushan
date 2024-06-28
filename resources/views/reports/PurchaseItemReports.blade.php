@extends('layouts.navigation')
@section('purchase_item_report', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Purchased Item </h4>
        </div>
        <form action="/purchased-item-report" method="POST">
            @csrf

            <div class="card-body form">


                <div class="form-row">
                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" id="from" value="{{ $from }}" name="from" class="form-control"
                            required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" id="to" value="{{ $to }}" name="to" class="form-control"
                            required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Filter By</label>
                        <select required id="filter_by" name="filter_by" class="form-control">
                            <option value="" disabled selected> Select </option>
                            <option value="1" {{ $filter_by == 1 ? 'selected' : '' }}>Brand Wise</option>
                            <option value="2" {{ $filter_by == 2 ? 'selected' : '' }}>Supplier Wise</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3" style="margin-top: 30px">
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
                            @if ($name != '')
                                <th>{{ $name }}</th>
                            @endif
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>GR No</th>
                            <th style="text-align: center">Quantity</th>
                            <th style="text-align: center">Unit Price</th>
                            <th style="text-align: center">Amount</th>
                            {{-- <th style="text-align: center">Action</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchase_item as $purchase_item)
                            <tr>
                                @if ($name != '')
                                    @if ($purchase_item->brand_name)
                                        <td>{{ $purchase_item->brand_name }}</td>
                                    @endif
                                    @if ($purchase_item->seller_name)
                                        <td>{{ $purchase_item->seller_name }}</td>
                                    @endif
                                @endif


                                <td>{{ $purchase_item->product_code }}</td>
                                <td>{{ $purchase_item->product_name }}</td>
                                <td>{{ $purchase_item->id }}</td>
                                <td style="text-align: right">{{ number_format($purchase_item->qty, 2) }}</td>
                                <td style="text-align: right">
                                    @if ($purchase_item->qty > 0)
                                        {{ number_format($purchase_item->pur_item_amount / $purchase_item->qty, 2) }}
                                    @endif
                                </td>
                                <td style="text-align: right">{{ number_format($purchase_item->pur_item_amount, 2) }}</td>


                                {{-- <th style="text-align: center">
                                    <a href="purchase-order-view/{{ $purchase_item->id }}/{{$purchase_item->product_id}}" class="btn btn-info btn-edit"><i
                                            class="far fa-eye"></i></a>
                                    <button data-toggle="modal" data-target="#otview"
                                        type="button"
                                        class="btn btn-info btn-edit btn-view"><i class="far fa-eye"></i></button>
                                </th> --}}
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        {{-- @endif --}}

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
                    messageTop: 'Purchased Item Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Purchased Item Report From - ' + date_from + ' to - ' + date_to,
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


        $('#submit').on('click', function() {
            let from = $('#from').val();
            let to = $('#to').val();
            let filter_by = $('#filter_by').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                type: 'post',
                url: '/getjs-post',
                dataType: 'json',
                data: {
                    from_date: from,
                    to_date: to,
                    filter: filter_by
                },
                success: function(data) {
                    console.log(data);

                }
            });

        });

        //view button 

        $('.btn-view').on('click', function() {

            $('#model-view').html('');
            var product_id = $(this).attr('data-pro_id');
            var brand_id = $(this).attr('data-brand_id');
            var html = '';


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/purchase-item-by-proid-brandid",
                data: {
                    product_id: product_id,
                    brand_id: brand_id
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);

                    for (const key in response) {
                        var brand_id = response[key]['brand_id'];
                        var brand_name = response[key]['brand_name'];
                        var product_code = response[key]['product_code'];
                        var product_id = response[key]['product_id'];
                        var product_name = response[key]['product_name'];
                        var purchase_order_id = response[key]['purchase_order_id'];
                        var seller_name = response[key]['seller_name'];
                        var qty = response[key]['pur_item_qty'];

                        html += '<tr>';
                        // html+='<td>'+brand_id+'</td>';
                        html += '<td>' + brand_name + '</td>';
                        html += '<td>' + product_code + '</td>';
                        // html+='<td>'+product_id+'</td>';
                        html += '<td>' + product_name + '</td>';
                        // html+='<td>'+purchase_order_id+'</td>';
                        html += '<td>' + seller_name + '</td>';
                        html += '<td>' + qty + '</td>';
                        html += '</tr>';

                    }
                    $('#model-view').append(html);
                }
            });
        });

        $('#reset').click(function(e) {
            e.preventDefault();
            $('#from').val('');
            $('#to').val('');
            $('#filter_by').val('');
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
