@extends('layouts.navigation')
@section('service_provider', 'active')
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
                                <h4 class="header ">Service Providers</h4>
                                <a href="" class="btn btn-success addserviceprovider" data-toggle="modal"
                                    data-target="#serviceProvider">Add</a>
                            </div>


                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Name</th>
                                            <th>Account No</th>
                                            <th>Join Date</th>
                                            <th>Contact No</th>
                                            <th>Service Type</th>
                                            <th>Description</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($accountServiceProviders as $accountServiceProvider)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $accountServiceProvider->name }}</td>
                                                <td>{{ $accountServiceProvider->account_no }}</td>
                                                <td>{{ $accountServiceProvider->join_date }}</td>
                                                <td>{{ $accountServiceProvider->contact_no }}</td>
                                                <td>{{ $accountServiceProvider->serviceName }}</td>
                                                <td>{{ $accountServiceProvider->description }}</td>


                                                <td style="text-align: center">
                                                    <button data-toggle="modal"
                                                        data-name="{{ $accountServiceProvider->name }}"
                                                        data-account_no="{{ $accountServiceProvider->account_no }}"
                                                        data-join_date="{{ $accountServiceProvider->join_date }}"
                                                        data-contact_no="{{ $accountServiceProvider->contact_no }}"
                                                        data-id="{{ $accountServiceProvider->id }}"
                                                        data-serviceName="{{ $accountServiceProvider->serviceName }}"
                                                        data-service_type_id="{{ $accountServiceProvider->service_type_id }}"
                                                        data-description="{{ $accountServiceProvider->description }}"
                                                        data-target="#serviceProvider" title="edit"
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

            $(".addserviceprovider").on("click", function() {
                $('#id').val("");
                $('#name').val("");
                $('#account_no').val("");
                $('#join_date').val("");
                $('#contact_no').val("");
                $('#service_type_id').val("");
                $('#description').val("");
            });

            $('.btn-edit').on('click', function() {
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                var account_no = $(this).attr('data-account_no');
                var join_date = $(this).attr('data-join_date');
                var contact_no = $(this).attr('data-contact_no');
                var service_type_id = $(this).attr('data-service_type_id');
                var description = $(this).attr('data-description');
                console.log(service_type_id);


                $('#id').val(id);
                $('#name').val(name);
                $('#account_no').val(account_no);
                $('#join_date').val(join_date);
                $('#contact_no').val(contact_no);
                $('#service_type_id').val(service_type_id);
                $('#description').val(description);


            });

            if (!@json($errors->isEmpty())) {
                $('#chargesType').modal();
            }
        });
    </script>

@endsection


@section('modal')
    <!--modal-->
    <div class="modal fade" id="serviceProvider" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Service Providers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/service-provider-store" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">
                            @csrf


                            <input type="hidden" class="form-control" name="id" id="id">

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" id="name" required>
                                        <span class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Account No</label>
                                        <input type="number" class="form-control" name="account_no" id="account_no"
                                            required>
                                        <span class="text-danger">
                                            @error('account_no')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>


                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Join Date</label>
                                        <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control"
                                            name="join_date" id="join_date" required>
                                        <span class="text-danger">
                                            @error('join_date')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Contact No</label>
                                        <input type="tel" class="form-control" name="contact_no" id="contact_no"
                                            maxlength="10" minlength="10" required>
                                        <span class="text-danger">
                                            @error('contact_no')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>


                                </div>
                            </div>


                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Service Type</label>
                                        <select class="form-control" name="service_type_id" id="service_type_id" required>
                                            <option value="" disabled selected>Select Service Type</option>
                                            @foreach ($accountyServiceTypes as $accountyServiceType)
                                                <option value="{{ $accountyServiceType->id }}">
                                                    {{ $accountyServiceType->name }}</option>
                                            @endforeach

                                        </select>
                                        <span class="text-danger">
                                            @error('service_type_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>


                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" id="description" class="form-control"></textarea>
                                        <span class="text-danger">
                                            @error('description')
                                                {{ $message }}
                                            @enderror
                                        </span>
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
    <!-- modal-->
    @endsection
