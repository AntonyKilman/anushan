@extends('layouts.navigation')
@section('service_types','active')
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
                                <h4 class="header ">Service Types</h4>
                                <a href="" class="btn btn-success addcharge" data-toggle="modal" data-target="#bankcharges">Add</a>
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
                                            <th class='action'>Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($service_types as $service_type)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $service_type->name }}</td>
                                                <td>{{ $service_type->description}}</td>
                                                <td class='action'>
                                                    <button data-toggle="modal" data-id="{{ $service_type->id }}"
                                                        data-name="{{ $service_type->name}}"
                                                        data-description="{{ $service_type->description}}"
                                                        data-target="#serviceType" title="edit"
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
        $(document).ready(function() {

            $(function() {
                setTimeout(function() {
                    $('.fade-message').slideUp();
                }, 1000);
            });

            $(".addcharge").on("click", function(){
                $('#id').val("");
                $('#name').val("");
                $('#description').val("");
              
            });

            $('.btn-edit').on('click', function() {
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                var description = $(this).attr('data-description');
            
              

                $('#id').val(id);
                $('#name').val(name);
                $('#description').val(description);
               
                

            });

            if (!@json($errors->isEmpty())) {
            $('#serviceType').modal();
        }
        });



        

    </script>

@endsection


@section('modal')
<!--modal-->
<div class="modal fade" id="bankcharges" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Service Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/service-type-store" method="post" class="needs-validation" novalidate="">

                    <div class="card-body form">
                        @csrf


                        {{-- <input type="hidden" class="form-control" name="id" id="id" > --}}
                        <div class="row">
                      
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name"   required>
                                    <span class="text-danger">@error('name')
                                            {{ $message }}@enderror</span>
                                </div>
                                </div>


                        <div class="col-12">
                        <div class="form-group">
                             <label>Description</label>
                             <textarea class="form-control" name="description"  ></textarea>
                        </div>
                        </div>
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

    <!-- Update Service Type modal -->


    <div class="modal" id="serviceType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Service Types</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/service-type-update" method="post" class="needs-validation" novalidate="">

                    <div class="card-body form">
                        @csrf


                        <input type="hidden" class="form-control" name="id" id="id" >
                        <div class="row">
                      
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name"  id="name" required>
                                    <span class="text-danger">@error('name')
                                            {{ $message }}@enderror</span>
                                </div>
                                </div>


                        <div class="col-12">
                        <div class="form-group">
                             <label>Description</label>
                             <textarea class="form-control" name="description" id="description" ></textarea>
                        </div>
                        </div>
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

    <!-- Update BankCharges modal end-->
    @endsection