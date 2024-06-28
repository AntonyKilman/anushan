@extends('layouts.navigation')
@section('other_exp_type', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;

    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Other Expense Subcategory</h4>
                                <a href="" class="btn btn-success addchargetype" data-toggle="modal"
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
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($otherExpenseTypes as $otherExpenseType)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $otherExpenseType->name }}</td>
                                                <td>{{ $otherExpenseType->category_name }}</td>
                                                <td>{{ $otherExpenseType->description }}</td>

                                                <td style="text-align: center">
                                                    <button data-toggle="modal" data-id="{{ $otherExpenseType->id }}"
                                                        data-name="{{ $otherExpenseType->name }}"
                                                        data-oth_exp_cat_id="{{ $otherExpenseType->oth_exp_cat_id }}"
                                                        data-description="{{ $otherExpenseType->description }}"
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
        $(function() {
            setTimeout(function() {
                $('.fade-message').slideUp();
            }, 1000);
        });

        $(".addchargetype").on("click", function() {
            $('#id').val("");
            $('#name').val("");
            $('#description').val("");
        });

        $('.btn-edit').on('click', function() {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var description = $(this).attr('data-description');
            var oth_exp_cat_id = $(this).attr('data-oth_exp_cat_id');

            $('#id').val(id);
            $('#name').val(name);
            $('#description').val(description);
            $("#oth_exp_sub_category").val(oth_exp_cat_id);

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
                <h5 class="modal-title" id="exampleModalLabel">Other Expense Subcategory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/other-expenses-type-store" method="post" class="needs-validation" novalidate="">

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
                            <label>Other Expense Sub Category</label>
                            <select class="form-control" name="oth_exp_sub_category" id="oth_exp_sub_category">
                                @foreach ($categories as $row)
                                <option  value="{{$row->id  }}">{{$row->category_name  }}</option>
                                @endforeach

                            </select>
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
