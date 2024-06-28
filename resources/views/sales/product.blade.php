@extends('layouts.navigation')
@section('All_pro', 'active')
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
                            <h2> Products</h2>
                            @if (\Session::has('sucess'))
                            <div class="alert alert-success fade-message">
                                <p>{{ \Session::get('sucess') }}</p>
                            </div><br />

                            <script>
                                $(function() {
                                    setTimeout(function() {
                                        $('.fade-message').slideUp();
                                    }, 1000);
                                });
                            </script>
                            @endif
                            {{-- @if ($c == true)
                                <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
                                    <i class="fas fa-plus"></i> Create New Farm Batches
                                </button>
                            @endif --}}

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
                                            <th>Qty</th>
                                            <th>Purchase Price</th>
                                            <th>Sales Price</th>
                                            <th>Action</th>
                                            <th>Barcode</th>
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
                                            <td>{{ $row->bar_code }}</td>
                                            <td>{{ $row->quantity }}</td>
                                            <td>{{ $row->purchase_price	}}</td>
                                            <td>{{ $row->sales_price}}</td>
                                            <td>
                                                <!-- @if ($u == true) -->
                                                    <a class="btn btn-primary"
                                                        href="javascript:dofunction('{{ $row->id }}','{{ $row->name }}','{{ $row->product_code }}','{{ $row->bar_code}}')">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                <!-- @endif -->
                                                {{-- <!-- @if ($d == true) -->
                                                    <a class="btn btn-danger "
                                                        href="javascript:dofun('{{ $row->id }}')">
                                                        <i class="fas fa-trash"></i>
                                                </a>
                                                <!-- @endif --> --}}

                                            </td>
                                            <td>
                                                <a class="btn btn-warning btn-sm" href="{{route('product.reprintBarcode',$row->id)}}">Barcode</a>
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
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="productEditModal"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="___class_+?19___" method="POST" action="{{ route('product.update') }}" enctype="multipart/form-data" id="update-price">
                        @csrf
                            <input type="hidden" id="productId" name="id">       
                        <div class="row">           
                            <div class="form-group col-6">
                                <label>Product Code:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-text-width"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="productCode" name="productCode" readonly>
                                </div>
                            </div>   

                            <div class="form-group col-6">
                                <label>Name: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-text-width"></i>
                                        </div>
                                    </div>
                                    <input type="text" min="0" class="form-control" id="productName" name="productName" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">           
                            <div class="form-group col-6">
                                <label>Barcode:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-text-width"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="barCode" name="barCode" readonly>
                                </div>
                            </div>   

                            <div class="form-group col-6">
                                <label>Sales Price: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-text-width"></i>
                                        </div>
                                    </div>
                                    <input type="text" min="0" class="form-control" id="salesPrice" name="salesPrice">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect" >Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- -------------------------------------------------------------------------------------------------------------- -->
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

    function dofunction(id,productName,productCode,barCode) { 
        document.getElementById("productId").value = id;
        document.getElementById("productName").value = productName;
        document.getElementById("productCode").value = productCode;
        document.getElementById("barCode").value = barCode;

        $('#productEditModal').modal();
    }

    //update selling price
    $('#update-price').submit(function(e){
        e.preventDefault();

        let price = $('#salesPrice').val();
        let id = $('#id').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url:"{{ route('product.update') }}",
            data:{
               id,
               price
            },
            success:function(data){
                alert(data.success);
            }
        });
    });
</script>

