@extends('layouts.navigation')
@section('product_in', 'active')
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
                            <h2> Products In</h2>
                            @if (session('sucess'))
                                <div class="alert alert-success fade-message">
                                    {{ session('sucess') }}
                                </div>
                            @endif
                            {{-- @if ($c == true)
                                <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
                                    <i class="fas fa-plus"></i> Create New
                                </button>
                            @endif --}}

                        </div>
                        <div>
                            <a type="button" class="btn btn-sm btn-primary pr-2 pl-2 ml-3" href="/admin/home"> Back</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            {{-- <th>Barcode</th> --}}
                                            <th>Batch ID</th>
                                            <th>Transaction ID</th>
                                            <th>Qty</th>
                                            <th>Expiry Date</th>
                                            <th>Purchase Price</th>
                                            {{-- <th>Sales Price</th> --}}
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1;?>
                                    
                                    @foreach ($products as $row)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->product_code }}</td>
                                            {{-- <td>{{ $row->bar_code }}</td> --}}
                                            <td>{{ $row->purchase_order_id }}</td>
                                            <td>{{ $row->transection_id }}</td>
                                            <td>{{ $row->quantity }}</td>
                                            <td>{{ $row->expire_date }}</td>
                                            <td>{{ $row->purchase_price }}</td>
                                            {{-- <td>{{ $row->sales_price }}</td> --}}
                                        
                                            <td>
                                                @if($row->status==0)
                                                <button type="button" class="btn btn-warning btn-sm">Pending</button>
                                                @elseif($row->status==1)
                                                <button type="button" class="btn btn-success btn-sm">Accepted</button>
                                                @elseif($row->status==2)
                                                <button type="button" class="btn btn-danger btn-sm">Rejected</button>
                                                @elseif($row->status==3)
                                                <button type="button" class="btn btn-info btn-sm ">Returned</button>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($row->status==1)
                                                <a class="btn btn-warning btn-sm" href="{{route('product.reprintBarcode',$row->id)}}">Barcode</a>
                                                @else
                                                <!-- @if ($u == true) -->
                                                <a class="btn btn-primary"
                                                href="javascript:dofunction('{{ $row->id }}','{{ $row->sales_price }}')">
                                                <i class="fa fa-check"></i>
                                                    </a>
                                                <!-- @endif -->
                                                <!-- @if ($d == true) -->
                                                    <a class="btn btn-danger "
                                                        href="javascript:dofun('{{ $row->id }}')">
                                                        <i class="fa fa-ban"></i>
                                                </a>
                                                <!-- @endif -->
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
    <!-- Approvel Modal ----------------------- -->
    <div class="modal fade" id="approvel" tabindex="-1" role="dialog" aria-labelledby="approvel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Approvel Comfirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to approve this?
                    <form action="{{route('product.approveProductIn')}}" method="POST">
                        @csrf
                        <div class="form-group col-12">
                            <label><b>Update Sell Price</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-text-width"></i>
                                    </div>
                                </div>
                                <input type="number" required class="form-control" placeholder="Sell Price" id="sell_price" name="sell_price" >
                            </div>
                        </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
           
                     

                       



                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="text" id="idApprovel" name="id" hidden>
                    <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <!-- <a href="#" id="deleteAllSelectedRecord" class="btn btn-danger">Delete</a> -->
                </div>
            </div>
        </div>
    </div>
<!-- -------------------------------------------------------------------------------------------------------------- -->
    <!-- Reject Modal ----------------------- -->
    <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="approvel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Rejection Comfirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reject this?
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <form action="{{route('product.rejectProductIn')}}" method="POST">
                        @csrf
                    <input type="text" id="idReject" name="id" hidden>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Reject</button>
                    <!-- <a href="#" id="deleteAllSelectedRecord" class="btn btn-danger">Delete</a> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    function dofunction(id,sell) 
    {
        document.getElementById("idApprovel").value = id;
        document.getElementById("sell_price").value =sell;

        $('#approvel').modal();
    }

    function dofun(id)
    {
        console.log(id);
        document.getElementById("idReject").value = id;
        $('#reject').modal();
    }

    //hide session success message
    $(function() {
        setTimeout(function() {
            $('.fade-message').slideUp();
        }, 2000);
    });

</script>
