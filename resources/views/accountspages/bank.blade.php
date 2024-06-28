@extends('layouts.navigation')
@section('bank_details', 'active')
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
                                <h4 class="header ">Bank Details</h4>
                                <a href="/bank-add" class="btn btn-success addModel" data-toggle="modal"
                                    data-target="#Bank">Add</a>
                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif


                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Name</th>
                                            <th>Branch</th>
                                            <th>Account No</th>
                                            <th>Account Type</th>
                                            <th>Contact No</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($banks as $bank)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $bank->name }}</td>
                                                <td>{{ $bank->branch }}</td>
                                                <td>{{ $bank->account_no }}</td>
                                                <td>{{ $bank->account_type }}</td>
                                                <td>{{ $bank->contact_no }}</td>
                                                <td>{{ $bank->status }}</td>



                                                <td>
                                                    <button data-toggle="modal" data-id="{{ $bank->id }}"
                                                        data-name="{{ $bank->name }}" data-branch="{{ $bank->branch }}"
                                                        data-account_no="{{ $bank->account_no }}"
                                                        data-account_type="{{ $bank->account_type }}"
                                                        data-contact_no="{{ $bank->contact_no }}"
                                                        data-date="{{ $bank->date }}"
                                                        data-starting_balance="{{ $bank->starting_balance }}"
                                                        data-status="{{ $bank->status }}" data-target="#Bank"
                                                        title="edit" class="btn btn-primary btn-edit"><i
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

        $('.addModel').on('click', function() {
            $('#id').val("");
            $('#name').val("");
            $('#branch').val("");
            $('#account_no').val("");
            $('#account_type').val("");
            $('#contact_no').val("");
            $('#starting_balance').val("");
            $('#status').val("");
            $('.hideClass').hide();
            $('#date').val("");

        });

        $('.btn-edit').on('click', function() {

            $('.hideClass').show();
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var branch = $(this).attr('data-branch');
            var account_no = $(this).attr('data-account_no');
            var account_type = $(this).attr('data-account_type');
            var contact_no = $(this).attr('data-contact_no');
            var starting_balance = $(this).attr('data-starting_balance');
            var status = $(this).attr('data-status');
            var date = $(this).attr('data-date');

            $('#id').val(id);
            $('#name').val(name);
            $('#branch').val(branch);
            $('#account_no').val(account_no);
            $('#account_type').val(account_type);
            $('#contact_no').val(contact_no);
            $('#starting_balance').val(starting_balance);
            $('#status').val(status);
            $('#date').val(date);

        });


        $(document).ready(function() {

            if (!@json($errors->isEmpty())) {
                $('#Bank').modal();
            }

        });
    </script>

@endsection



@section('model')
    <!-- modal-->
    <div class="modal fade" id="Bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bank Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/bank-store" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">
                            @csrf
                            <input type="hidden" class="form-control" name="id" id="id">

                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" required>
                                        <span class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <input type="text" class="form-control" id="branch" name="branch"
                                            value="{{ old('branch') }}" required>
                                        <span class="text-danger">
                                            @error('branch')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Account No</label>
                                        <input type="number" class="form-control" min="0" id="account_no"
                                            name="account_no"
                                            value="{{ old('account_no') }}"
                                            required>
                                        <span class="text-danger">
                                            @error('account_no')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Account Type</label>
                                        <select class="form-control" name="account_type" id="account_type"
                                            value="{{ old('account_type') }}" required>
                                            <option value="" disabled selected>Select Category</option>
                                            <option value="saving">Saving</option>
                                            <option value="current">Current</option>
                                            <option value="fixed">Fixed</option>
                                            <option value="loan">Loan</option>
                                        </select>
                                        <span class="text-danger">
                                            @error('account_type')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Contact No</label>
                                        <input type="tel" class="form-control" name="contact_no" id="contact_no"
                                            value="{{ old('contact_no') }}" maxlength="10" minlength="10" required>
                                        <span class="text-danger">
                                            @error('contact_no')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Starting Balance</label>
                                        <input type="number" class="form-control" id="starting_balance"
                                            name="starting_balance" value="{{ old('starting_balance') }}" step="0.001"
                                            required>
                                        <span class="text-danger">
                                            @error('starting_balance')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="{{ old('date') }}" max="{{ now()->format('Y-m-d') }}" required>
                                        <span class="text-danger">
                                            @error('date')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>

                                </div>

                                <div class="col-6">
                                    <div class="form-group hideClass">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="status"
                                            value="{{ old('status') }}">
                                            <option value="" disabled selected>Select status</option>
                                            <option value="Active">Active</option>
                                            <option value="Deactive">Deactive</option>
                                        </select>
                                        <span class="text-danger">
                                            @error('status')
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
