@extends('layouts.navigation')
@section('purchaseOrderNew', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $createOrder = false;

    if (in_array('inventory.newPurchaseOrderAdd', $Access)) {
        $createOrder = true;
    }



    ?>
    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Purchase Order</h4>

            @if($createOrder)
                <a href="/newPurchaseOrderAdd" class="btn btn-success">Add</a>
            @endif
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-4">
                    <label for="">Purchase Order</label>
                    <select name="" id="order" class="form-control" onchange="view()">
                        <option value="" disabled selected>Please Select Purchase Order</option>
                        @foreach ($groups as $group)
                            <option class="form-control" value="{{ $group->purchase_order_id }}">
                                {{ $group->purchase_order_id }}</option>
                        @endforeach

                    </select>
                </div>
            </div><br>


            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Purchase Order</th>
                            <th>Product</th>
                            <th>Quantity Type</th>
                            <th>Quantity</th>
                            <th>Supplier</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $data->purchase_order_id }}</td>
                                <td>{{ $data->product_name }}</td>
                                <td>{{ $data->quantity_type }}</td>
                                <td>{{ $data->quantity }}</td>
                                <td>{{ $data->seller_name }}</td>
                                <td style="text-align: center">
                                    <button data-toggle="modal" data-id="{{ $data->id }}"
                                        data-name="{{ $data->product_name }}"
                                        data-purchase_order_id="{{ $data->purchase_order_id }}"
                                        data-quantity_type="{{ $data->quantity_type }}"
                                        data-quantity="{{ $data->quantity }}"
                                        data-seller_name="{{ $data->seller_name }}"
                                        data-seller_id="{{ $data->seller_id }}" data-target="#editModal" title="edit"
                                        class="btn btn-primary btn-edit">
                                        <i class="far fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>
        </div>
    </div>


    <script>

        // for edit function
        $(document).on('click', '.btn-edit', function() {

            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var purchase_order_id = $(this).attr('data-purchase_order_id');
            var seller_name = $(this).attr('data-seller_name');
            var seller_id = $(this).attr('data-seller_id');
            var quantity = $(this).attr('data-quantity');
            var quantity_type = $(this).attr('data-quantity_type');

            $('#editOrder').val(purchase_order_id);
            $('#editId').val(id);
            $('#editProduct').val(name);
            $('#editType').val(quantity_type);
            $('#editQuantity').val(quantity);
            $('#editSeller').val(seller_id);


            $('#editReset').click(function(e) {
                e.preventDefault();

                $('#editOrder').val(purchase_order_id);
                $('#editId').val(id);
                $('#editProduct').val(name);
                $('#editType').val(quantity_type);
                $('#editQuantity').val(quantity);
                $('#editSeller').val(seller_id);

            });

        });



        // function for viewv data click select option
        let order = "";
        function view() {
            console.log("view");
            order = $('#order').val();
            $('#modalId').val(order);
            $('#valId').val(order)

            $.ajax({
                type: "GET",
                url: "/newPurchaseOrderView/" + order,
                dataType: "json",

                success: function(response) {
                    $('#orderModal').modal('show');

                    let html = "";
                    for (const key in response) {

                        let product = response[key]['product_name'];
                        let quantity = response[key]['quantity'];
                        let seller_name = response[key]['seller_name'];
                        let quantity_type = response[key]['quantity_type'];


                        html += `<tr>
                              <td>${product}</td>
                              <td>${quantity_type}</td>
                              <td>${quantity}</td>
                              <td>${seller_name}</td>
                            </tr>`;

                    }

                    $('.mTable').append(html);
                },

                error: function(error) {

                }
            });
        }


        // function for print
        $(document).ready(function() {
            $('#modal-print').click(function(e) {

                e.preventDefault();
                var printHtml = $('#print-html').html();
                var mywindow = window.open('', 'PRINT');
                mywindow.document.write(
                    '<!DOCTYPE html><html lang="en"><head><title>Purchase Order</title><style>table, td, th {border: 1px solid #ddd;text-align: left;}table {border-collapse: collapse;width: 100%;}th, td {padding: 15px;}</style></head><body><div class="form-group" id="modalOrder"><div class="col-2"><label>Purchase order</label><br><input type="text" id="modalId" value="' +
                    order + '"  class="form-control" > </div></div><br><table border="1">' + printHtml +
                    '</table></body></html>');
                mywindow.focus();
                mywindow.print();
                mywindow.close();
            });
        });

    </script>


@endsection


@section('model')

    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Purchase Order View</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button id="modal-print" class="btn btn-primary" style="float: right">Print</button>

                    <div class="form-group">
                        <div class="col-4">
                            <label>Purchase order</label>
                            <input type="text" id="modalId" value="" name="name" class="form-control" readonly>
                        </div>
                    </div>



                    <div class="table-responsive">
                        <table class="table table-striped" id="print-html">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity Type</th>
                                    <th>Quantity</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>

                            <tbody class="mTable">


                            </tbody>


                        </table>
                    </div>

                </div>

                </form>
            </div>
        </div>
    </div>
    </div>

    {{-- -------------------------- edit modal start--------------------------------------- --}}

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Purchase Order View</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="/newPurchaseOrderUpdate" method="post">
                        @csrf

                        <div class="form-group">
                            <div class="col-4">
                                <label>Update Purchase order</label>
                                <input type="text" id="editOrder" value="" name="orderId" class="form-control" readonly>
                            </div>
                        </div>

                        <input type="hidden" id="editId" name="id" class="form-control" readonly>

                        <div class="table-responsive">
                            <table class="table table-striped" id="print-html">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity Type</th>
                                        <th>Quantity</th>
                                        <th>Supplier</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td> <input type="text" id="editProduct" name="product" class="form-control"
                                                readonly></td>
                                        <td> <input type="text" id="editType" name="quantity_type" class="form-control"
                                                readonly></td>
                                        <td> <input type="text" id="editQuantity" name="quantity" class="form-control">
                                        </td>
                                        <td>
                                            <select name="seller" id="editSeller" class="form-control" required>

                                                @foreach ($sellers as $seller)
                                                    <option class="form-control" value="{{ $seller->id }}">
                                                        {{ $seller->seller_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                    </tr>

                                </tbody>

                            </table>
                        </div>

                        <div align="right">
                            <button class="btn btn-danger" id="editReset">Reset</button>
                            <button class="btn btn-success mr-1" type="submit">Submit</button>
                        </div>
                </div>

                </form>
            </div>
        </div>
    </div>
    </div>

    {{-- -------------------------- edit modal end--------------------------------------- --}}
@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>