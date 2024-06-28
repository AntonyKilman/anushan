@extends('layouts.navigation')
@section('assets_status_type', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $statusAdd = false;
    $statusEdit = false;
    if (in_array('inventory.assetStatusTypeAddProcess', $Access)) {
        $statusAdd = true;
    }
    if (in_array('inventory.assetStatusTypeUpdateProcess', $Access)) {
        $statusEdit = true;
    }
    ?>
    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Asset Status Type</h4>
            @if ($statusAdd)
                <button data-toggle="modal" data-target="#asset_status_type_add" class="btn btn-success">Add</button>
            @endif
            {{-- <a href="/product-type-add" data-toggle="modal" data-target="#exampleModal" class="btn btn-success">Add</a> --}}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            {{-- <th>id</th> --}}
                            <th style="display: none">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            @if ($statusEdit)
                                <th style="text-align: center">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($statuses as $status)
                            <tr>
                                {{-- <td >{{$status->id}}</td> --}}
                                <td style="display: none">#</td>
                                <td>{{ $status->status_name }}</td>
                                <td>{{ $status->description }}</td>
                                @if ($statusEdit)
                                    <td class='action'>
                                        <button data-toggle="modal" data-id="{{ $status->id }}"
                                            data-name="{{ $status->status_name }}" data-des="{{ $status->description }}"
                                            data-target="#asset_status_type_edit" title="edit"
                                            class="btn btn-primary btn-edit"><i class="far fa-edit"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            if (!@json($errors->isEmpty())) {
              
                var id = $('#id').val();
             
                if (id) {
                    $('#asset_status_type_edit').modal();

                } else {

                    $('#asset_status_type_add').modal();
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


            $('#editReset').click(function(e) {
                e.preventDefault();
                $('#id').val(id);
                $('#name').val(name);
                $('#description').val(des);

            });
        });
    </script>
@endsection



@section('model')

    {{-- update modal start --}}
    <div class="modal fade" id="asset_status_type_edit" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Asset Status Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/asset-status-type-update-process" class="needs-validation" novalidate="" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{old('id')}}"  id="id">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control" required>
                            @error('name')
                                <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                        </div>


                        <div align="right">
                            <button type="reset" id="editReset" class="btn btn-danger">Reset</button>
                            <button class="btn btn-success mr-1" type="submit">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- update modal end --}}




    {{-- add modal start --}}
    <div class="modal fade" id="asset_status_type_add" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Asset Status Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/asset-status-type-add-process" class="needs-validation" novalidate="" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control" required>
                            @error('name')
                                <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                        </div>


                        <div align="right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button class="btn btn-success mr-1" type="submit">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- add modal end --}}
@endsection
