@extends('layouts.navigation')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

<?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    // if (in_array('access_point.store', $Access)) {
    //     $c = true;
    // }
    
    // if (in_array('access_point.update', $Access)) {
    //     $u = true;
    // }
    
    // if (in_array('access_point.destroy', $Access)) {
    //     $d = true;
    // }
    
?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">


                            <h2>
                                Create Routes For {{ $access_model->name }}
                            </h2>
                            <a href="{{ route('access_model.index') }}" class="btn btn-sm btn-warning ">

                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            @if ($c == true)
                                <button class="btn btn-sm btn-primary float-right" data-toggle="modal"
                                    data-target="#exampleModal">

                                    <i class="fas fa-plus"></i> Create New Routes
                                </button>
                            @endif

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No </th>
                                            <th>Routes</th>
                                            <th>value</th>
                                            <th>Description</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($access_point as $row)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $row->display_name }}</td>
                                                <td>{{ $row->value }}</td>
                                                <td>{{ $row->description }}</td>
                                                <td>
                                                    <!-- @if ($u == true) -->
                                                        <a class="btn btn-primary "
                                                            href="javascript:dofunction('{{ $row->id }}','{{ $row->display_name }}','{{ $row->value }}','{{ $row->description }}')"><i
                                                                class="fas fa-pen"></i>
                                                        </a>
                                                    <!-- @endif -->
                                                    <!-- @if ($d == true) -->
                                                        <a class="btn btn-danger "
                                                            href="javascript:dofun('{{ $row->id }}')"><i
                                                                class="fas fa-trash"></i>
                                                        </a>
                                                    <!-- @endif -->

                                                </td>

                                            </tr>
                                            <?php $i += 1; ?>
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
@section('model')
    <!-- Modal with form -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Access point</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="___class_+?19___" method="POST" action="{{ route('access_point.store') }}">
                        @csrf
                        <div class="form-group">
                            <div class="input-group pt-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-text-width"></i>
                                    </div>
                                </div>

                                <input type="text" name="access_model_id" value="{{ $access_model->id }}" hidden>

                                <input type="text" class="form-control" placeholder="Display name" name="name">
                            </div>

                            <div class="form-group">
                                <div class="input-group pt-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-text-width"></i>
                                        </div>
                                    </div>

                                    <input type="text" class="form-control" placeholder="Value" name="value">
                                </div>


                                {{--  <div class="form-group">
                                    <div class="input-group pt-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-text-width"></i>
                                            </div>
                                        </div>

                                        <input type="text" class="form-control" placeholder="Description"
                                            name="description">
                                    </div>
                                </div>  --}}



                                <button type="submit"
                                    class="btn btn-primary m-t-15 waves-effect float-right">Submit</button>

                            </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalUpdate" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Update Access point</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="___class_+?19___" method="POST" action="{{ route('access_point.update') }}">
                        @csrf
                        <div class="form-group">
                            <div class="input-group pt-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-text-width"></i>
                                    </div>
                                </div>
                                <input type="text" name="id" id="id" hidden>
                                <input type="text" name="access_model_id" value="{{ $access_model->id }}" hidden>
                                <input type="text" class="form-control" placeholder="Display name" id="name" name="name">
                            </div>

                            <div class="form-group">
                                <div class="input-group pt-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-text-width"></i>
                                        </div>
                                    </div>

                                    <input type="text" class="form-control" placeholder="Value" id="value" name="value">
                                </div>


                                <div class="form-group">
                                    <div class="input-group pt-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-text-width"></i>
                                            </div>
                                        </div>

                                        <input type="text" class="form-control" placeholder="Description" id="description"
                                            name="description">
                                    </div>
                                </div>


                                <button type="submit"
                                    class="btn btn-primary m-t-15 waves-effect float-right">Submit</button>

                            </div>
                        </div>



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
                    <h5 class="modal-title" id="mySmallModalLabel">Remove User role</h5>
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
                    <form action="{{ route('access_point.destroy') }}" method="POST">
                        @csrf
                        <input type="text" id="delete_id" name="delete_id" hidden>
                        <input type="text" name="access_model_id" value="{{ $access_model->id }}" hidden>
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
<script type="text/javascript">
    function dofunction(id, name, value, description) {
        document.getElementById("id").value = id;
        document.getElementById("name").value = name;
        document.getElementById("value").value = value;
        document.getElementById("description").value = description;
        $('#exampleModalUpdate').modal();
    }

    function dofun(id) {
        document.getElementById("delete_id").value = id;
        $('.bd-example-modal-sm').modal();
    }
</script>
