@extends('layouts.navigation')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>
    <div class="card">
        <div class="card-header">
            <h4>Expiry Alert Products</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Id</th>
                            <th>Product Name</th>
                            <th>Quantity Type</th>
                            <th>Purchased Item Quantity</th>
                            <th>Expiry Date</th>
                            <th>Expire Days</th>
                            <th>Purchased Order Id</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase_items as $item)
                            @if ($item->pur_item_qty > 0)
                                <tr>
                                    <td style="display: none">#</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->pur_item_qty_type }}</td>
                                    <td>{{ $item->pur_item_qty }}</td>
                                    <td>{{ $item->pur_item_expery_date }}</td>
                                    <td>{{ \Carbon\Carbon::parse(substr(now(), 0, 10))->diffInDays($item->pur_item_expery_date, false) }}
                                    </td>
                                    <td>{{ $item->purchase_order_id }}</td>
                                    <td>
                                        <button class="btn btn-info btn-edit" data-toggle="modal"
                                            data-target="#indoorTransfer" title="Indoor Transfer"
                                            data-subCatId="{{ $item->sub_cat_id }}"
                                            data-purId="{{ $item->purchase_order_id }}" data-id="{{ $item->id }}"
                                            data-product="{{ $item->product_name }}"
                                            data-qty_type="{{ $item->pur_item_qty_type }}"
                                            data-qty="{{ $item->pur_item_qty }}"
                                            data-exDate="{{ $item->pur_item_expery_date }}"
                                            data-proId="{{ $item->product_id }}"
                                            data-amount="{{ $item->pur_item_amount }}"
                                            data-proCode="{{ $item->product_code }}"><i class="fas fa-sign-in-alt"
                                                style="font-size: 15px"></i></button>
                                        <button class="btn btn-info btn-edit" data-toggle="modal"
                                            data-target="#outdoorReturn" title="Outdoor Return"
                                            data-subCatId="{{ $item->sub_cat_id }}"
                                            data-purId="{{ $item->purchase_order_id }}" data-id="{{ $item->id }}"
                                            data-product="{{ $item->product_name }}"
                                            data-qty_type="{{ $item->pur_item_qty_type }}"
                                            data-qty="{{ $item->pur_item_qty }}"
                                            data-exDate="{{ $item->pur_item_expery_date }}"
                                            data-proId="{{ $item->product_id }}"
                                            data-amount="{{ $item->pur_item_amount }}"
                                            data-proCode="{{ $item->product_code }}"><i class="fas fa-sign-in-alt"
                                                style="font-size: 15px"></i></button>

                                        {{-- <a href="purchase-order-view/{{$item->purchase_order_id}}" class="btn btn-info btn-edit"><i class="far fa-eye"></i></a>
                        <a href="purchase-order-edit/{{$item->purchase_order_id}}" class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a> --}}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('.btn-edit').on('click', function() {
            console.log('indoor');
            var id = $(this).attr('data-id');
            var purchaseId = $(this).attr('data-purId');
            var product = $(this).attr('data-product');
            var qty_type = $(this).attr('data-qty_type');
            var qty = $(this).attr('data-qty');
            var exDate = $(this).attr('data-exDate');
            var productId = $(this).attr('data-proId');
            var amount = $(this).attr('data-amount');
            var ProCode = $(this).attr('data-proCode');
            var subCatId = $(this).attr('data-subCatId');

            $('#id').val(id);
            $('#purchaseId').val(purchaseId);
            $('#product').val(product);
            $('#qty_type').val(qty_type);
            $('#qty').val(qty);
            $('#trans_qty').val(qty);
            $('#exDate').val(exDate);
            $('#productId').val(productId);
            $('#amount').val(amount);
            $('#ProCode').val(ProCode);
            $('#subCatId').val(subCatId);

            $("#trans_qty").attr({
                "max": qty
            });


            $('#outpurchaseId').val(purchaseId);
            $('#outproduct').val(product);
            $('#outqty_type').val(qty_type);
            $('#outqty').val(qty);
            $('#outtrans_qty').val(qty);
            $('#outexDate').val(exDate);
            $('#outproductId').val(productId);
            $('#outamount').val(amount);
            $('#outProCode').val(ProCode);
            $('#outsubCatId').val(subCatId);

            $("#outtrans_qty").attr({
                "max": qty
            });



        });
    </script>
@endsection


<!-- Indoor Transfer -->
<div class="modal fade" id="indoorTransfer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Indoor Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->


                <form action="dashIndoorTransfer" method="post" class="needs-validation" novalidate="">
                    @csrf

                    <div class="card-body form">

                        <div class="row">


                            <div class="form-group col-md-4">
                                <label>Select Department</label>
                                <select class="form-control" name="dept_id" id="dept_id" required>
                                    <option value="" disabled selected>Select Department</option>
                                    @foreach ($departments as $department)
                                        <option name="dept_id" class="dept_id" id="dept_id"
                                            value="{{ $department->id }}">
                                            {{ $department->dept_name }}</option>
                                    @endforeach
                                    <span class="text-danger">
                                        @error('dept_id')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </select>
                            </div>
                        </div>





                        <div class="table">
                            <table style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Purchase Id</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Transfer Quantity</th>
                                        <th>Expiry Date</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td><input type="text" id="purchaseId" name="purchase_id"
                                                class="form-control" readonly></td>
                                        <td><input type="text" id="product" name="product_name"
                                                class="form-control"readonly></td>
                                        <td><input type="text" id="qty" name="qty" class="form-control"
                                                readonly></td>
                                        <td><input type="number" id="trans_qty" name="trans_qty" min="0"
                                                max="" class="form-control"></td>
                                        <td><input type="text" id="exDate" name="exDate" class="form-control"
                                                readonly></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

                        <input type="hidden" name="productId" id="productId">
                        <input type="hidden" name="qty_type" id="qty_type">
                        <input type="hidden" name="amount" id="amount">
                        <input type="hidden" name="ProCode" id="ProCode">
                        <input type="hidden" name="subCatId" id="subCatId">
                        <div align="right">

                            <button class="btn btn-success mr-1" type="submit" id="submit">Submit</button>
                        </div>
                    </div>
                </form>


            </div>

        </div>
    </div>
</div>

<!--Indoor Transfer  -->


<!-- Outdoor return -->
<div class="modal fade" id="outdoorReturn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Outdoor Return</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->


                <form action="dashoutdoorReturn" method="post" class="needs-validation" novalidate="">
                    @csrf

                    <div class="card-body form">


                        <div class="table">
                            <table style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Purchase Order Id</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Return Quantity</th>
                                        <th>Reason</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td><input type="text" id="outpurchaseId" name="purchase_id"
                                                class="form-control" readonly></td>
                                        <td><input type="text" id="outProCode" name="procode"
                                                class="form-control" readonly></td>
                                        <td><input type="text" id="outproduct" name="product_name"
                                                class="form-control"readonly></td>
                                        <td><input type="text" id="outqty" name="qty" class="form-control"
                                                readonly></td>
                                        <td><input type="number" id="outtrans_qty" name="trans_qty" min="0"
                                                max="" class="form-control"></td>
                                        <td>
                                            <select class="form-control" name="reason_id" id="reason_id" required>
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($reasons as $reason)
                                                    <option name="dept_id" class="dept_id" id="dept_id"
                                                        value="{{ $reason->id }}">
                                                        {{ $reason->reason_name }}</option>
                                                @endforeach
                                                <span class="text-danger">
                                                    @error('dept_id')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </select>

                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

                        <input type="hidden" name="productId" id="outproductId">
                        <input type="hidden" name="qty_type" id="outqty_type">
                        <input type="hidden" name="amount" id="outamount">
                        <input type="hidden" name="exDate" id="outexDate">
                        <input type="hidden" name="subCatId" id="outsubCatId">
                        <div align="right">

                            <button class="btn btn-success mr-1" type="submit" id="submit">Submit</button>
                        </div>
                    </div>
                </form>


            </div>

        </div>
    </div>
</div>

<!--Outdoor return  -->
