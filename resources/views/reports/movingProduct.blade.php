@extends('layouts.navigation')
@section('ProductMoving', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>
    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Product Moving</h4>
        </div>

        <div class="card-body">

            <form action="/product-moving-count" method="get">

                <div class="row">
                    <div class="col-4">
                        <label for="bdaymonth">Month and year</label>
                        <input type="month" name="date" value="{{ $getDate }}" class="form-control">
                    </div>

                    <div class="col-4">
                        <label>Product Sub Category</label>
                        <select class="form-control" name="sub_category">
                            <option value="">All</option>
                            @foreach ($InventoryProductSubCategory as $row)
                                <option value="{{ $row->id }}" @if ($row->id == $sub_cat) selected @endif>
                                    {{ $row->product_sub_cat_name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-4">
                        <button type="submit" class="btn btn-success" style="margin-top: 30px">Submit</button>
                    </div>
                </div>
            </form>
            <br>

            <div class="table-responsive">
                <table class="table table-striped " id="inventoryTable">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Code</th>
                            <th>Product</th>
                            <th>Qty Type</th>
                            <th style="text-align: center">Qty</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($product_counts as $row)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $row->product_code }}</td>
                                <td>{{ $row->product_name }}</td>
                                <td>{{ $row->Qty_type }}</td>
                                <td style="text-align: right">{{ number_format($row->TOTAL_TRANSFER, 2) }}</td>
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
                    messageTop: ' Report From - ',
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Report From - ' ,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>