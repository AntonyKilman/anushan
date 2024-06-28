@extends('layouts.navigation')
@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">
                            <h2> Loyalty Points</h2>
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
                                            <th>Loyalty Points Pecentage</th>
                                            <th>Expiry Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($loyalities as $row)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $row->percentage }}</td>
                                            <td>{{ $row->expire_date }}</td>
                                            <td>
                                                    <a class="btn btn-primary"
                                                        href="javascript:dofunction('{{ $row->id }}')">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                {{-- <!-- @if ($d == true) -->
                                                    <a class="btn btn-danger "
                                                        href="javascript:dofun('{{ $row->id }}')">
                                                        <i class="fas fa-trash"></i>
                                                </a>
                                                <!-- @endif --> --}}
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
                    <h5 class="modal-title" id="formModal">Edit Loyalty Points</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="___class_+?19___" method="POST" action="{{ route('loyality.update') }}" enctype="multipart/form-data" id="update-loyality">
                        @csrf
                            <input type="hidden" id="productId" name="id">       
                        <div class="row">           
                            <div class="form-group col-6">
                                <label>Loyalty Points Percentage:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-text-width"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="loyality-percentage" name="loyality-percentage">
                                </div>
                            </div>   

                            <div class="form-group col-6">
                                <label>Expiry Date: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-text-width"></i>
                                        </div>
                                    </div>
                                    <input type="date" min="0" class="form-control" id="expire-date" name="expire-date">
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
        $('#productEditModal').modal();
    }

    //update loyality points percentage
    // $('#update-loyality').submit(function(e){
    //     e.preventDefault();

    //     let expireDate = $('#expire-date').val();
    //     let loyalityPercentage = $('#loyality-percentage').val();
    //     let id = $('#id').val();

    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });

    //     $.ajax({
    //         type:'POST',
    //         url:"{{ route('loyality.update') }}",
    //         data:{
    //            id,
    //            loyalityPercentage,
    //            expireDate
    //         },
    //         success:function(data){
    //             alert(data.success);
    //         }
    //     });
    // });
</script>

