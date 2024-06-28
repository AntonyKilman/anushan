@extends('layouts.navigation')
@section('newProductPrice', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Product Price</h4>
        </div>

        <div class="card-body">

            <form action="/new-product-price" method="get">
                <div class="row">
                    <div class="col-4">
                        <label>Product Sub Categories</label>
                        <select name="sub_category" id=""class="form-control">
                            <option value="">Please Select SubCategories</option>
                            @foreach ($subCategories as $row)
                                <option value="{{ $row->id }}" {{ $row->id == $sub_cat ? 'selected' : '' }}>
                                    {{ $row->product_sub_cat_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-4" style="margin-top: 30px">
                        <button class="btn btn-success">Submit</button>
                    </div>

                </div>
            </form><br>
            <div class="table-responsive">
                <table class="table table-striped" id="inventoryTable">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Sub Category</th>
                            {{-- <th style="text-align: center">Action</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchase_product as $row)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $row->product_code }}</td>
                                <td>{{ $row->product_name }}</td>
                                <td>{{ $row->product_sub_cat_name }}
                                {{-- <td style="text-align: center"><button class="btn btn-info btn-sm view" data-toggle="modal"
                                        data-target="#priceModal" data-product_id={{ $row->product_id }}><i
                                            class="far fa-eye"></i></button></td> --}}
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
@section('model')

    <div class="modal fade" id="priceModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Product Price</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div id="total_div"></div>
                        <div class="table-responsive">
                            <table class="table table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Code</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>GR No</th>
                                        <th>Supplier</th>
                                        <th>Purchase Date</th>
                                        <th style="text-align: center">Qty</th>
                                        <th style="text-align: center">Unit Price</th>
                                        <th style="text-align: center">Total</th>
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="modal_tbody">

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {

        $(".view").on('click', function(e) {
            e.preventDefault();
            let product_id = $(this).attr("data-product_id");

            $.ajax({
                type: 'GET',
                url: `/last-price/${product_id}`,

                success: function(data) {

                    let html = "";
                    $.each(data, function(indexInArray, valueOfElement) {
                        html += `
            <tr>
               
                                <td>${valueOfElement.product_code}</td>
                                <td>${valueOfElement.product_name}</td>
                                <td>${valueOfElement.pur_item_qty_type}</td>
                                <td>${valueOfElement.pur_order_id}</td>
                                <td>${valueOfElement.seller_name}</td>
                                <td>${valueOfElement.date}</td>
                                <td style="text-align: right">${parseFloat(valueOfElement.pur_item_qty).toFixed(2)}</td>
                                <td style="text-align: right">${(parseFloat(valueOfElement.pur_item_amount)/parseFloat(valueOfElement.pur_item_qty)).toFixed(2)}</td>
                                <td style="text-align: right">${parseFloat(valueOfElement.pur_item_amount).toFixed(2)}</td>
                                <td style="text-align: center"> <a href="/purchase-order-view/${valueOfElement.pur_order_id}" class="btn btn-info btn-sm purchases" title="view"
                                 ><i
                                    class="far fa-eye"></i></a></td>
                            </tr>
            `;
                        $('#modal_tbody').empty().append(html);


                    });
                },

            });
        })

    });
</script>
