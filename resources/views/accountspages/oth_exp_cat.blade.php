@extends('layouts.navigation')
@section('other_exp_cat', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Other Expense Category</h4>
                                <a href="" class="btn btn-success add" data-toggle="modal"
                                    data-target="#chargesType">Add</a>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($datas as $row)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $row->category_name }}</td>
                                                <td>{{ $row->description }}</td>

                                                <td style="text-align: center">
                                                    <button data-toggle="modal" data-id="{{ $row->id }}"
                                                        data-name="{{ $row->category_name }}"
                                                        data-description="{{ $row->description }}"
                                                        data-target="#chargesType" title="edit"
                                                        class="btn btn-primary btn-edit"><i
                                                            class="far fa-edit"></i></button>
                                                </td>
                                            </tr>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script>
        // $(function() {
        //     setTimeout(function() {
        //         $('.fade-message').slideUp();
        //     }, 1000);
        // });

        $(".add").on("click", function() {
            $('#id').val("");
            $('#name').val("");
            $('#description').val("");
            $("#exampleModalLabel").empty().append("Create Other Expense Category");
        });

        $('.btn-edit').on('click', function() {
            $("#exampleModalLabel").empty().append("Update Other Expense Category");
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var description = $(this).attr('data-description');
            console.log(id,name,description);

            $('#id').val(id);
            $('#name').val(name);
            $('#description').val(description);
        });


        $(document).ready(function() {


            if (!@json($errors->isEmpty())) {
                $('#chargesType').modal();
            }

        });
    </script>
@endsection



<!-- Modal-->
<div class="modal fade" id="chargesType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/other-expenses-category-store" method="post" class="needs-validation" novalidate="">

                    <div class="card-body form">
                        @csrf


                        <input type="hidden" class="form-control" name="id" id="id">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ old('name') }}" required>
                            <span class="text-danger">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>



                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" value="{{ old('description') }}" id="description"></textarea>
                            <span class="text-danger">
                                @error('description')
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

<!-- Modal-->
