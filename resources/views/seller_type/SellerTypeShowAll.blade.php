@extends('layouts.navigation')
@section('seller_type', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $sellerTypeAdd = false;
    $sellerTypeEdit = false;
    if (in_array('inventory.sellerTypeAddProcess', $Access)) {
        $sellerTypeAdd = true;
    }
    if (in_array('inventory.sellerTypeUpdateProcess', $Access)) {
        $sellerTypeEdit = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Supplier Type</h4>

            @if ($sellerTypeAdd)
                <button data-toggle="modal" data-target="#seller_type_add" title="edit" class="btn btn-success">Add</button>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            @if ($sellerTypeEdit)
                                <th class='action'>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($seller_types as $seller_type)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $seller_type->seller_type_name }}</td>
                                <td>{{ $seller_type->seller_type_des }}</td>
                                @if ($sellerTypeEdit)
                                    <td class='action'>

                                        <button data-toggle="modal" data-id="{{ $seller_type->id }}"
                                            data-name="{{ $seller_type->seller_type_name }}"
                                            data-des="{{ $seller_type->seller_type_des }}" data-target="#seller_type_edit"
                                            title="edit" class="btn btn-primary btn-edit"><i
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

    <script>
        $(document).ready(function() {
            if (!@json($errors->isEmpty())) {

                var id = $('#id').val();

                if (id) {
                    $('#seller_type_edit').modal();

                } else {

                    $('#seller_type_add').modal();
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
    <div class="modal fade" id="seller_type_edit" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Supplier Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/seller-type-update-process" class="needs-validation" novalidate="" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ old('id') }}" id="id">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control" required>
                            @error('name')
                                <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
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
    <div class="modal fade" id="seller_type_add" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Supplier Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/seller-type-add-process" class="needs-validation" novalidate="" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control" required>
                            @error('name')
                                <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
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
