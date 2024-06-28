@extends('layouts.navigation')
@section('stockReport', 'active')
@section('content')
    <!-- Main Content -->
    <form action="/sales/stock-report" method="get">
        @csrf
        <div class="card-body form">
            <h6>Stock Report</h6>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Product Sub Category</label>
                    <select name="product_sub_cat_id" id="" class="form-control">
                        <option  value="">All</option>
                        @foreach ($product_sub_categories as $sub)
                            <option value="{{ $sub->id }}" {{$sub_cat_id == $sub->id ? 'selected' : ''}}>{{ $sub->product_sub_cat_name }}
                            </option>
                        @endforeach
                    </select>
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
                            <h2> Products</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="salesstockTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Name</th>
                                            <th>Product Code</th>
                                            <th>Sub categeory Name</th>
                                            <th>Available Qty</th>
                                            <th>Purchase Price</th>
                                            <th>Sales Price</th>
                                            <th>Expiry Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($arry as $item)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->product_code }}</td>
                                                <td>{{$item->product_sub_cat_name}}</td>
                                                <td>{{ $item->now_have_quantity }}</td>
                                                <td>{{ $item->purchase_price }}</td>
                                                <td>{{ $item->sales_price }}</td>
                                                <td>{{ $item->expire_date }}</td>
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
    $(document).ready(function() {



        $('#salesstockTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort: true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Stock Report From - ',
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Stock Report ',
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
