@extends('layouts.navigation')
@section('product_category', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $add = false;
    $edit = false;
    
    if (in_array('inventory.productCatAdd', $Access)) {
        $add = true;
    }
    
    if (in_array('inventory.productCatUpdate', $Access)) {
        $edit = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Product Category</h4>
            @if ($add)
                <button type="button" class="btn btn-success" data-toggle="modal"
                    data-target="#AddProductCategory">Add</button>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">

                    <thead>
                        <tr>
                            <th width="40%">Name</th>
                            <th>Category Code</th>
                            <th>Description</th>
                            @if ($edit)
                                <th class='action'>Action</th>
                            @endif
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->product_cat_name }}</td>
                                <td>{{ $data->product_cat_code }}</td>
                                <td>{{ $data->product_cat_des }}</td>
                                @if ($edit)
                                    <td class='action'>
                                        <button data-toggle="modal" data-id="{{ $data->id }}"
                                            data-name="{{ $data->product_cat_name }}"
                                            data-product_cat_code="{{ $data->product_cat_code }}"
                                            data-des="{{ $data->product_cat_des }}" data-target="#EditProductCategory"
                                            title="edit" class="btn btn-primary btn-edit">
                                            <i class="far fa-edit"></i>
                                        </button>
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
                    $('#EditProductCategory').modal();

                } else {
                    $('#AddProductCategory').modal();
                }
            }
        });

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var product_cat_code = $(this).attr('data-product_cat_code');
            var des = $(this).attr('data-des');

            $('#id').val(id);
            $('#name').val(name);
            $('#product_cat_code').val(product_cat_code);
            $('#description').val(des);

            $('#editReset').click(function(e) {
                e.preventDefault();
                $('#id').val(id);
                $('#name').val(name);
                $('#product_cat_code').val(product_cat_code);
                $('#description').val(des);

            });
        });
    </script>
@endsection

@section('model')

    <!-- Add Product Category -->
    <div class="modal fade" id="AddProductCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="/productCatAdd" method="post" id="addForm" class="needs-validation" novalidate="">
                        <div class="card-body form">

                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{ old('product_cat_name') }}"
                                    name="product_cat_name" required>
                                <span class="text-danger">
                                    @error('product_cat_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Category Code</label>
                                <input type="number" min="0" class="form-control"
                                    value="{{ old('product_cat_code') }}" name="product_cat_code" required>
                                <span class="text-danger">
                                    @error('product_cat_code')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>


                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="product_cat_des">{{ old('description') }}</textarea>
                                <span class="text-danger errot-text des_error"></span>
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

    <div class="modal fade" id="EditProductCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Update Product Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/productCatUpdate" method="post" id="editForm" class="needs-validation" novalidate="">
                        <div class="card-body form">

                            @csrf
                            <div class="form-group">

                                <input type="hidden" class="form-control" id="id" name="id">

                                <label>Name</label>
                                <input type="text" class="form-control" id="name" name="product_cat_name"
                                    required>
                                <span class="text-danger">
                                    @error('product_cat_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Category Code</label>
                                <input type="number" min="0" class="form-control"
                                    value="{{ old('product_cat_code') }}" id="product_cat_code" name="product_cat_code"
                                    required>
                                <span class="text-danger">
                                    @error('product_cat_code')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>



                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="description" name="product_cat_des">{{ old('description') }}</textarea>
                            </div>

                            <div align="right">
                                <button class="btn btn-danger" id="editReset" type="reset">Reset</button>
                                <button class="btn btn-success mr-1" type="submit">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
