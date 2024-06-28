@extends('layouts.navigation')
@section('product_brand', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $brandAdd = false;
    $brandEdit = false;
    if (in_array('inventory.brandAdd', $Access)) {
        $brandAdd = true;
    }
    if (in_array('inventory.brandUpdate', $Access)) {
        $brandEdit = true;
    }
    ?>


    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header id">Product Brand</h4>
            @if ($brandAdd)
                <a href="/brandGet" class="btn btn-success" data-toggle="modal" data-target="#AddProductBrand">Add</a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">

                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Brand</th>
                            <th>Subcategory</th>
                            <th>Description</th>
                            @if ($brandEdit)
                                <th class='action'>Action</th>
                            @endif
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $data->brand_name }}</td>
                                <td>{{ $data->product_sub_cat_name }}</td>
                                <td>{{ $data->brand_des }}</td>
                                @if ($brandEdit)
                                    <td class='action'>
                                        <button data-toggle="modal" data-id="{{ $data->id }}"
                                            data-brand="{{ $data->brand_name }}" data-des="{{ $data->brand_des }}"
                                            data-target="#EditProductBrand" title="edit"
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

            $('#add').on('click', function(e) {

                $("#editReset").hide();
                $("#AddReset").show();

                let Arr = [];

                $("input[type='checkbox']:checked").each(function() {
                    Arr.push(this.value);
                });

                if (Arr.length == 0) {
                    alert("Please Select Subcategory");
                    e.preventDefault();
                }

            });


        });

        $('.btn-edit').on('click', function() {

            $("#editReset").show();
            $("#AddReset").hide();

            $(".editsubCat").attr('checked', false);

            var id = $(this).attr('data-id');
            var brand = $(this).attr('data-brand');
            var des = $(this).attr('data-des');

            $('#id').val(id);
            $('#brand').val(brand);
            $('#des').val(des);

            $("#editReset").on('click', function(e) {
                e.preventDefault();
                $('#id').val(id);
                $('#brand').val(brand);
                $('#des').val(des);


            });

            $(".editsubCat").prop('checked', false);


            $.ajax({
                type: "GET",
                url: "/brandEdit/" + id,
                dataType: "json",

                success: function(response) {
                    for (let x in response) {


                        $('.subCat' + response[x]['product_sub_cat_id']).prop('checked', true);

                    }



                }

            });

        });


        $("#editReset").on('click', function(e) {

            let id = $('#id').val();
            $(".editsubCat").prop('checked', false);

            $.ajax({
                type: "GET",
                url: "/brandEdit/" + id,
                dataType: "json",

                success: function(response) {
                    for (let x in response) {

                        $('.subCat' + response[x]['product_sub_cat_id']).prop('checked', true);

                    }



                }

            });



        });
    </script>
@endsection



@section('model')

    <!-- Edit Product Brand -->
    <div class="modal fade" id="EditProductBrand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Product Brand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/brandUpdate" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">

                            @csrf
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="id" name="id" required>
                                <label>Name</label>
                                <input type="text" class="form-control" id="brand" name="brand_name" required>
                                <span class="text-danger">
                                    @error('brand_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="des" name="brand_des"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Subcategory</label>

                                <div class="row">
                                    @foreach ($prosubs as $data)
                                        <div class="form-group col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" name="Editproduct_sub_cat_name[]"
                                                    class="editsubCat  subCat{{ $data->id }}"
                                                    value="{{ $data->id }}"
                                                    data-editsubArray="{{ $data->product_sub_cat_name }}">
                                                <label class="form-check-label">
                                                    {{ $data->product_sub_cat_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="text-danger">
                                    @error('product_sub_cat_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div align="right">
                                <button class="btn btn-danger" type="reset" id="AddReset">Reset</button>
                                <button class="btn btn-danger" id="editReset">Reset</button>
                                <button class="btn btn-success mr-1" id="editSave" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>


                </div>

            </div>
        </div>
    </div>

    <!-- Edit Product Brand -->

    <!-- Add Product Brand -->
    <div class="modal fade" id="AddProductBrand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product Brand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/brandAdd" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">

                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="brand_name" id="addName" required>
                                <span class="text-danger">
                                    @error('brand_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="brand_des" id="addDes"></textarea>
                            </div>


                            <div class="form-group">
                                <label>Subcategory</label>

                                <div class="row">
                                    @foreach ($prosubs as $prosub)
                                        <div class="form-group col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="__click" id="addSub"
                                                    name="product_sub_cat_name[]" value="{{ $prosub->id }}"
                                                    data-subArray="{{ $prosub->product_sub_cat_name }}">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    {{ $prosub->product_sub_cat_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="text-danger">
                                    @error('product_sub_cat_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>


                            <div align="right">
                                <button class="btn btn-danger" id="reset" type="reset">Reset</button>
                                <button class="btn btn-success mr-1" id="add" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Add Product Brand -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
