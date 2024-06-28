@extends('layouts.navigation')
@section('department', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $departmentAdd = false;
    $departmentEdit = false;
    if (in_array('inventory.departmentAdd', $Access)) {
        $departmentAdd = true;
    }
    if (in_array('inventory.departmentUpdate', $Access)) {
        $departmentEdit = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Department</h4>
            @if ($departmentAdd)
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AddDepartment">Add</button>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-striped" id="table-1" class="table-1">

                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            @if ($departmentEdit)
                                <th style="text-align: center">Action</th>
                            @endif
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $data->dept_name }}</td>
                                <td>{{ $data->dept_des }}</td>
                                @if ($departmentEdit)
                                    <td style="text-align: center">
                                        <button data-toggle="modal" data-id="{{ $data->id }}"
                                            data-name="{{ $data->dept_name }}" data-des="{{ $data->dept_des }}"
                                            data-target="#EditDepartment" title="edit" class="btn btn-primary btn-edit"><i
                                                class="far fa-edit"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('model')

    <!-- Add Product Category -->
    <div class="modal fade" id="AddDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="/departmentAdd" method="post" id="addForm" class="needs-validation" novalidate="">
                        <div class="card-body form">

                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{ old('dept_name') }}" name="dept_name"
                                    required>
                                @error('dept_name')
                                    <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="dept_des">{{ old('dept_des') }}</textarea>
                            </div>

                            <div align="right">
                                <button class="btn btn-danger" type="reset">Reset</button>
                                <button class="btn btn-success mr-1" type="submit">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Product Category -->

    <!-- Edit Product Category -->

    <div class="modal fade" id="EditDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/departmentUpdate" method="post" id="editForm" class="needs-validation" novalidate="">
                        <div class="card-body form">

                            @csrf
                            <div class="form-group">

                                <input type="hidden" class="form-control" id="id" value="{{ old('id') }}"
                                    name="id">

                                <label>Name</label>
                                <input type="text" class="form-control" id="name" value="{{ old('dept_name') }}"
                                    name="dept_name" required>
                                @error('dept_name')
                                    <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="description" name="dept_des">{{ old('dept_des') }}</textarea>
                                <span class="text-danger">
                                    @error('dept_des')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div align="right">
                                <button class="btn btn-danger" type="reset">Reset</button>
                                <button class="btn btn-success mr-1" type="submit">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        if (!@json($errors->isEmpty())) {
           
            var id = $('#id').val();
            
            if (id) {
                $('#EditDepartment').modal();

            } else {

                $('#AddDepartment').modal();
            }
        }
    });


    $(document).on('click', '.btn-edit', function() {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var des = $(this).attr('data-des');

        $('#id').val(id);
        $('#name').val(name);
        $('#description').val(des);
    });
</script>
