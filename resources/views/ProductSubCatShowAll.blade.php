@extends('layouts.navigation')
@section('product_subcategory', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $subCatAdd = false;
    $subCatEdit = false;
    if (in_array('inventory.productSubCatAdd', $Access)) {
        $subCatAdd = true;
    }
    if (in_array('inventory.productSubCatUpdate', $Access)) {
        $subCatEdit = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Product Subcategory</h4>
            @if ($subCatAdd)
                <a href="/productSubCatGet" class="btn btn-success" data-toggle="modal"
                    data-target="#AddProductSubCategory">Add</a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th width="25%">Subcategory</th>
                            <th width="25%">Product Category</th>
                            <th>Subcategory Code</th>

                            <th>Description</th>
                            @if ($subCatEdit)
                            @endif
                            <th class='action'>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $data->product_sub_cat_name }}</td>
                                <td>{{ $data->product_cat_name }}</td>
                                <td>{{ $data->product_sub_cat_code }}</td>
                                <td>{{ $data->product_sub_cat_des }}</td>
                                @if ($subCatEdit)
                                    <td class='action'>
                                        <button data-toggle="modal" data-Id="{{ $data->id }}"
                                            data-catId="{{ $data->product_cat_id }}"
                                            data-name="{{ $data->product_cat_name }}"
                                            data-subName="{{ $data->product_sub_cat_name }}"
                                            data-product_sub_cat_code="{{ $data->product_sub_cat_code }}"
                                            data-des="{{ $data->product_sub_cat_des }}"
                                            data-target="#EditProductSubCategory" title="edit"
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

    <script type="text/javascript">
        $(document).ready(function() {
            if (!@json($errors->isEmpty())) {

                var id = $('#id').val();

                if (id) {
                    $('#EditProductSubCategory').modal();

                } else {

                    $('#AddProductSubCategory').modal();
                }
            }
        });

        $('.btn-edit').on('click', function() {
            var id = $(this).attr('data-Id');
            var name = $(this).attr('data-name');
            var subNname = $(this).attr('data-subName');
            var product_sub_cat_code = $(this).attr('data-product_sub_cat_code');
            var des = $(this).attr('data-des');
            var SelCatId = $(this).attr('data-catId');


            $('#id').val(id);
            $('#productCatId').val(SelCatId);
            $('#subName').val(subNname);
            $('#product_sub_cat_code').val(product_sub_cat_code);
            $('#des').val(des);


            $('#editReset').click(function(e) {
                e.preventDefault();
                $('#id').val(id);
                $('#subName').val(subNname);
                $('#product_sub_cat_code').val(product_sub_cat_code);
                $('#productCatId').val(SelCatId);
                $('#des').val(des);

            });

        });
    </script>

@endsection

@section('model')

    <!-- Edit Product Sub Category -->
    <div class="modal fade" id="EditProductSubCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Product Subcategory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Main Content -->
                    <form action="/productSubCatUpdate" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">

                            @csrf
                            <input type="hidden" class="form-control" value="{{ old('id') }}" id="id"
                                name="id" value="">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{ old('product_sub_cat_name') }}"
                                    id="subName" name="product_sub_cat_name" required>
                                <span class="text-danger">
                                    @error('product_sub_cat_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Select Category</label>
                                <select class="form-control" name="product_cat_id" id="productCatId" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($proCats as $proCat)
                                        <option value="{{ $proCat->id }}">{{ $proCat->product_cat_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('product_cat_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Sub Category Code</label>
                                <input type="number" min="0" class="form-control"
                                    value="{{ old('product_sub_cat_code') }}" id="product_sub_cat_code"
                                    name="product_sub_cat_code" required>
                                <span class="text-danger">
                                    @error('product_sub_cat_code')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>


                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="des" name="product_sub_cat_des">{{ old('product_sub_cat_des') }}</textarea>
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

    <!-- Edit Product Sub Category -->



    <!-- Add Product Sub Category -->
    <div class="modal fade" id="AddProductSubCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product Subcategory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Main Content -->
                    <form action="/productSubCatAdd" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">

                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="product_sub_cat_name"
                                    value="{{ old('product_sub_cat_name') }}" required>
                                <span class="text-danger">
                                    @error('product_sub_cat_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Select Category</label>
                                <select class="form-control" name="product_cat_id" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($proCats as $proCat)
                                        <option value="{{ $proCat->id }}">{{ $proCat->product_cat_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('product_cat_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Sub Category Code</label>
                                <input type="number" min="0" class="form-control"
                                    value="{{ old('product_sub_cat_code') }}" name="product_sub_cat_code" required>
                                <span class="text-danger">
                                    @error('product_sub_cat_code')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="product_sub_cat_des">{{ old('product_sub_cat_des') }}</textarea>
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

    <!-- Add Product Sub Category -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
