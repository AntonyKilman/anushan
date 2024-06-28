@extends('layouts.navigation')
@section('ProductPrice', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        {{-- <form action="/product-price" method="get">
            @csrf
            <div class="card-body form">
                <h6>Product Price Report</h6>

                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label>Supplier</label>
                        <select name="supplier" class="form-control" id="seller">
                            <option id="selectedAll" value="" >All</option>
                            @foreach ($inventory_sellers as $seller)
                                <option class="seller" value="{{ $seller->id }}" {{ $seller->id == $supplier ? 'selected' : '' }}>
                                    {{ $seller->seller_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Products</label>
                        <select name="products" class="form-control" id="products">
                            <option id="selectedAllProducts" value="" >All</option>
                            @foreach ($inventory_products as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $products ? 'selected' : '' }}>
                                    {{ $item->product_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3" style="margin-top: 30px">
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form> --}}

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($purchased_Items as $item)
                           <tr>
                                <td>{{ $item['seller_name']}}</td> 
                                <td>{{ $item['product_code']}}</td> 
                                <td>{{ $item['product_name']}}</td> 
                                <td>{{number_format($item['pur_item_amount']/$item['pur_item_qty'],2)}}</td>   
                            </tr> 
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
            $('#reset').click(function(e) {
                e.preventDefault();
                $("#selectedAll").attr("selected",true);
                $(".seller").attr("selected",false);

                $("#selectedAllProducts").attr("selected",true);
                $(".products").attr("selected",false);
               
            });
    </script>
    
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
                    messageTop: ' Report ',
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Report ' ,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>