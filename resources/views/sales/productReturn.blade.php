@extends('layouts.navigation')
@section('product_return', 'active')
@section('content')
<?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;

    // if (in_array('main_our_team.store', $Access)) {
    //     $c = true;
    // }

    // if (in_array('main_our_team.update', $Access)) {
    //     $u = true;
    // }

    // if (in_array('main_our_team.destroy', $Access)) {
    //     $d = true;
    // }

    ?>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div style="padding: 10px;">
                        <h2> Product Return</h2>
                        @if (session('sucess'))
                        <div class="alert alert-success fade-message">
                            {{ session('sucess') }}
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Product Name</th>
                                        <th>Expiry Date</th>
                                        <th>Product Code</th>
                                        <th>Barcode</th>
                                        <th>Available Qty</th>
                                        <th>Purchase Price</th>
                                        <th>Sales Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($products as $row)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->expire_date }}</td>
                                        <td>{{ $row->product_code }}</td>
                                        <td>{{ $row->bar_code}}</td>
                                        <td>{{ $row->quantity - $row->returns_quantity - $row->sales_quantity }}</td>
                                        <td>{{ $row->purchase_price }}</td>
                                        <td>{{ $row->sales_price}}</td>
                                        <td>
                                            @if(($row->quantity - $row->returns_quantity - $row->sales_quantity) >0)
                                            @if ($u == true)
                                            <a class="btn btn-primary"
                                                href="javascript:dofunction('{{ $row->id }}','{{ $row->purchase_order_id }}','{{ $row->name }}','{{ $row->bar_code }}','{{ $row->quantity - $row->returns_quantity - $row->sales_quantity }}','{{ $row->purchase_price }}')">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                            @endif
                                            @endif
                                        </td>
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
<!-- ----------------------- -->
@section('model')
<!-- Modal with form -->
<!-- -------------------------------------------------------------------------------------------------------------- -->
<div class="modal fade bd-return-modal-lg" tabindex="-1" role="dialog" id="returnModal"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Product Return</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="___class_+?19___" method="POST" action="{{route('product.indexProductItemReturn')}}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="text" id="id" name="id" hidden>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Product Name</label>
                            <div class="input-group">
                                <input type="text" required class="form-control" placeholder="Name" name="name"
                                    id="name" disabled>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>Barcode</label>
                            <div class="input-group">
                                <input type="text" min="0" required class="form-control" placeholder="Bar Code"
                                    name="barcode" id="barcode" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Batch ID</label>
                            <div class="input-group">
                                <input type="text" required class="form-control" placeholder="Batch ID"
                                    name="purchase_order_id" id="purchase_order_id" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>Quantity (available: <span id="availableQty"></span>)</label>
                            <div class="input-group">
                                <input type="number" max="" min="1" required class="form-control" placeholder="Quantity"
                                    name="quantity" id="quantity">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Reason</label>
                            <div class="input-group">
                                <select id="reason" name="reason_id" class="form-control">
                                    @foreach ($resons as $reson)
                                    <option value="{{$reson->id}}">{{$reson->reason_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>Description</label>
                            <div class="input-group">
                                <textarea class="form-control" placeholder="Description" name="description"
                                    id="description"></textarea>
                            </div>
                        </div>
                    </div>

                    <input type="double" id="purchase_price" name="purchase_price" hidden>

                    <button type="submit" class="btn btn-primary m-t-15 waves-effect"
                        onclick="return confirm('Are you sure you want to return this item?');">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Small Modal -->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Remove Farm Batche</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h1 align="center">

                    <i style="color:D10505; font-size:50px" class="fas fa-times-circle " aria-hidden="true"></i>
                </h1>
                <h4 align="center">Are you sure?</h4>
                <P align="center">Do you want to delete these records? This process can't be undone.</p>
                <form action="" method="Post">
                    @csrf
                    <input type="text" id="delete_id" name="id" hidden>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    function dofunction(id, purchase_order_id, name, barcode, availableQty,purchase_price)
    {
        document.getElementById("id").value = id;
        document.getElementById("purchase_order_id").value = purchase_order_id;
        document.getElementById("quantity").value = availableQty;
        document.getElementById("barcode").value = barcode;
        document.getElementById("name").value = name;
        document.getElementById("purchase_price").value = purchase_price;
        $("#availableQty").html(availableQty);
        $("#quantity").attr({"max" : availableQty,});
        $('#returnModal').modal();
    }

    //hide session success message
    $(function() {
        setTimeout(function() {
            $('.fade-message').slideUp();
        }, 2000);
    });
</script>