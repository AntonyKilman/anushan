@extends('layouts.navigation')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>
    <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    if (in_array('user.store', $Access)) {
        $c = true;
    }
    
    if (in_array('user.destroy', $Access)) {
        $d = true;
    }
    
    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">


                            <h2>
                                Create User
                            </h2>
                            @if ($c == true)
                                <button class="btn btn-sm btn-primary float-right" data-toggle="modal"
                                    data-target="#exampleModal">

                                    <i class="fas fa-plus"></i> Create New
                                </button>
                            @endif

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($user_list as $row)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $row->f_name }}</td>
                                                <td>{{ $row->email }}</td>
                                                <td>
                                                    {{-- <button class="btn btn-primary edit" data-id="{{$row->user_id  }}" data-title="{{ $row->name }}" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-pen"></i></button> --}}
                                                    @if ($d == true)
                                                        <a class="btn btn-danger "
                                                            href="javascript:dofun('{{ $row->user_id }}')">
                                                            <i class="fas fa-trash">
                                                            </i>
                                                        </a>
                                                    @endif
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="___class_+?19___" method="POST" action="{{ route('user.store') }}">
                        @csrf
                        <input type="text" name="id" id="id" value="0" hidden>
                        <div class="form-group">



                            <div class="input-group pt-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-text-width"></i>
                                    </div>
                                </div>
                                <select class="mb-2 form-control" id="user" name="emp_id" required>
                                    <option value="">select Employee</option>
                                    @foreach ($employee_list as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->email }}</option>
                                    @endforeach

                                </select>

                            </div>
                            @error('emp_id')
                                <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                            @enderror

                            <div class="input-group pt-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-eye-slash"></i>
                                    </div>
                                </div>
                                <input type="password" class="form-control" placeholder="Password" id="password"
                                    name="password">
                            </div>
                            @error('password')
                                <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                            @enderror

                            <button type="submit" class="btn btn-primary m-t-15 waves-effect float-right">Submit</button>

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
                    <h5 class="modal-title" id="mySmallModalLabel">Remove User</h5>
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
                    <form action="{{ route('user.destroy') }}" method="POST">
                        @csrf
                        <input type="text" id="delete_id" name="delete_id" hidden>

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

    <script>
        $(document).ready(function() {

            if (!@json($errors->isEmpty())) {
                $('#exampleModal').modal();
            }

        });
    </script>
@endsection
<script type="text/javascript">
    // function dofunction(id, name) {
    //     document.getElementById("id").value = id;
    //     document.getElementById("name").value = name;
    //     $('#exampleModalUpdate').modal();
    // }
    function dofun(id) {
        document.getElementById("delete_id").value = id;
        $('.bd-example-modal-sm').modal();
    }
</script>
