@extends('layouts.navigation')
@section('product_type', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $productTypeAdd = false;
    $productTypeEdit = false;
    
    if (in_array('inventory.productTypeAddProcess', $Access)) {
        $productTypeAdd = true;
    }
    
    if (in_array('inventory.productTypeUpdateProcess', $Access)) {
        $productTypeEdit = true;
    }
    
    ?>
    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Product Type</h4>
            {{-- <a href="/product-type-add" data-toggle="modal" data-target="#exampleModal" class="btn btn-success">Add</a> --}}
            @if ($productTypeAdd)
                <button data-toggle="modal" data-target="#product_type_add" class="btn btn-success add">Add</button>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th width="20%">Name</th>
                            <th>Description</th>
                            @if ($productTypeEdit)
                                <th style="text-align: center">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($product_types as $product_type)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $product_type->product_type_name }}</td>
                                <td>{{ $product_type->product_type_des }}</td>
                                @if ($productTypeEdit)
                                    <td class='action'>
                                        <button data-toggle="modal" data-id="{{ $product_type->id }}"
                                            data-name="{{ $product_type->product_type_name }}"
                                            data-des="{{ $product_type->product_type_des }}"
                                            data-target="#product_type_edit" title="edit"
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
                    $('#product_type_edit').modal();
                } else {
                    $('#product_type_add').modal();
                }
            }
        });

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var des = $(this).attr('data-des');

            $('#id').val(id);
            $('#edit_name').val(name);
            $('#description').val(des);

            $('#editReset').click(function(e) {
                e.preventDefault();
                $('#id').val(id);
                $('#edit_name').val(name);
                $('#edit_des').val(des);
            });
        });

        $(".add").click(function() {
            $('#id').val("");
            $('#name').val("");
            $('#des').val("");
            $(".errorMsg").empty();
        });
    </script>
@endsection


@section('model')

    {{-- update modal start --}}
    <div class="modal fade" id="product_type_edit" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Update Product Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/product-type-update-process" class="needs-validation" novalidate="" method="post">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{ old('id') }}" id="id">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="edit_name" name="name" value="{{ old('name') }}"
                                class="form-control" required>
                            @error('name')
                                <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" id="edit_des" name="description">{{ old('description') }}</textarea>
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
    <div class="modal fade" id="product_type_add" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Product Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/product-type-add-process" class="needs-validation" novalidate="" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="name" value="{{ old('name') }}" name="name"
                                class="form-control" required>
                            @error('name')
                                <span style="color: rgb(151, 4, 4); font-weight:bolder"
                                    class="errorMsg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
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

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
