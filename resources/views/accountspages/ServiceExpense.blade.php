@extends('layouts.navigation')
@section('service_expenses','active')
@section('content')
<?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;

    // if (in_array('main_our_team.store', $Access)) {
    //     $c = true;
    // }

    // if (in_array('main_our_team.update', $Access)) {
    //     $u = true;
    // }

    // if (in_array('main_our_team.destroy', $Access)) {
    //     $d = true;
    // }

    ?>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div style="padding: 10px;">

                        <div class="card-header-bank">
                            <h4 class="header ">Service Expenses</h4>
                            <a href="" class="btn btn-success" data-toggle="modal" data-target="#AddExpense">Add</a>
                        </div>

                        @if (\Session::has('success'))
                        <div class="alert alert-success fade-message">
                            <p>{{ \Session::get('success') }}</p>
                        </div><br />
                        @endif


                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">

                                <thead>
                                    <tr>
                                        <th style="display: none"></th>
                                        <th>Service Provider</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Reference Number</th>
                                        <th class='action'>Action</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($service_expenses as $service_expense)
                                    <tr>
                                        <td style="display: none">#</td>
                                        <td>{{ $service_expense->name}}</td>
                                        <td>{{ $service_expense->date}}</td>
                                        <td>{{ $service_expense->amount}}</td>
                                        <td>{{ $service_expense->ser_exp_reference_no}}</td>

                                        {{-- @if(( {{ $service_expense->status }} )==0)
                                        <td>Active</td>
                                        @endif

                                        --}}


                                        <td class='action'>
                                            <a href="service-expenses-show/{{$service_expense->id}}" title="view"
                                                class="btn btn-info btn-edit"><i class="far fa-eye"></i></a>

                                            <button data-toggle="modal" data-id="{{ $service_expense->id }}"
                                                data-service_provider_id="{{ $service_expense->service_provider_id}}"
                                                data-date="{{ $service_expense->date }}"
                                                data-amount="{{ $service_expense->amount }}"
                                                data-ser_exp_cash="{{ $service_expense->ser_exp_cash }}"
                                                data-ser_exp_cheque="{{ $service_expense->ser_exp_cheque }}"
                                                data-ser_exp_cheque_no="{{ $service_expense->ser_exp_cheque_no}}"
                                                data-ser_exp_cheque_date="{{ $service_expense->ser_exp_cheque_date}}"
                                                data-ser_exp_cheque_date="{{ $service_expense->ser_exp_cheque_date}}"
                                                data-bank_id="{{ $service_expense->bank_id}}"
                                                data-ser_exp_reference_no="{{ $service_expense->ser_exp_reference_no}}"
                                                data-image="{{ $service_expense->image}}"
                                                data-status="{{ $service_expense->status}}"
                                                data-description="{{ $service_expense->description}}"
                                                data-target="#EditServiceExpense" title="edit"
                                                class="btn btn-primary btn-edit"><i class="far fa-edit"></i></button>

                                        </td>
                                    </tr>
                                    @endforeach




                                    {{-- second foreach --}}
                                    @foreach ($service_expense_1 as $service_expense_1)
                                    @if ($service_expense_1->bank_id==null || $service_expense_1->bank_id=='')
                                    <tr>
                                        <td style="display: none">#</td>
                                        <td>{{ $service_expense_1->name}}</td>
                                        <td>{{ $service_expense_1->date}}</td>
                                        <td>{{ $service_expense_1->amount}}</td>
                                        <td>{{ $service_expense_1->ser_exp_reference_no}}</td>

                                        {{-- @if(({{$service_expense->status}})==0)
                                        <td>Active</td>
                                        @endif

                                        --}}


                                        <td class='action'>
                                            <a href="service-expenses-show/{{$service_expense_1->id}}" title="view"
                                                class="btn btn-info btn-edit"><i class="far fa-eye"></i></a>

                                            <button data-toggle="modal" data-id="{{ $service_expense_1->id }}"
                                                data-service_provider_id="{{ $service_expense_1->service_provider_id}}"
                                                data-date="{{ $service_expense_1->date }}"
                                                data-amount="{{ $service_expense_1->amount }}"
                                                data-ser_exp_cash="{{ $service_expense_1->ser_exp_cash }}"
                                                data-ser_exp_cheque="{{ $service_expense_1->ser_exp_cheque }}"
                                                data-ser_exp_cheque_no="{{ $service_expense_1->ser_exp_cheque_no}}"
                                                data-ser_exp_cheque_date="{{ $service_expense_1->ser_exp_cheque_date}}"
                                                data-ser_exp_cheque_date="{{ $service_expense_1->ser_exp_cheque_date}}"
                                                data-bank_id="{{ $service_expense_1->bank_id}}"
                                                data-ser_exp_reference_no="{{ $service_expense_1->ser_exp_reference_no}}"
                                                data-image="{{ $service_expense_1->image}}"
                                                data-status="{{ $service_expense_1->status}}"
                                                data-description="{{ $service_expense_1->description}}"
                                                data-target="#EditServiceExpense" title="edit"
                                                class="btn btn-primary btn-edit"><i class="far fa-edit"></i></button>

                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    {{-- end second foreach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script>
        $(function() {
                    setTimeout(function() {
                        $('.fade-message').slideUp();
                    }, 1000);
                });

                $('.btn-edit').on('click', function() {
                    var id = $(this).attr('data-id');
                    var service_provider_id = $(this).attr('data-service_provider_id');
                    var date = $(this).attr('data-date');
                    var amount = $(this).attr('data-amount');
                    var ser_exp_cash = $(this).attr('data-ser_exp_cash');
                    var ser_exp_cheque = $(this).attr('data-ser_exp_cheque');
                    var ser_exp_cheque_no = $(this).attr('data-ser_exp_cheque_no');
                    var ser_exp_cheque_date = $(this).attr('data-ser_exp_cheque_date');
                    var bank_id = $(this).attr('data-bank_id');
                    var ser_exp_online = $(this).attr('data-ser_exp_online');
                    var ser_exp_reference_no = $(this).attr('data-ser_exp_reference_no');
                    var image = $(this).attr('data-image');
                    var status = $(this).attr('data-status');
                    var description = $(this).attr('data-description');


                    $('#id').val(id);
                    $('#provider').val(service_provider_id);
                    $('#date').val(date);
                    $('#amount').val(amount);
                    $('#ser_exp_cash').val(ser_exp_cash);
                    $('#ser_exp_cheque').val(ser_exp_cheque);
                    $('#ser_exp_cheque_no').val(ser_exp_cheque_no);
                    $('#ser_exp_cheque_date').val(ser_exp_cheque_date);
                    $('#bank_id').val(bank_id);
                    $('#ser_exp_online').val(ser_exp_online);
                    $('#ser_exp_reference_no').val(ser_exp_reference_no);
                    $('#image').val(image);
                    $('#status').val(status);
                    $('#description').val(description);


                });

                $(".add").click(function(){

                   let checkValue = $('.chequeVal').val();


                   if(checkValue>0){
                     $(".chequeNo").attr('required', 'true');
                     $(".chequeDate").attr('required', 'true');
                     $(".bank_id").attr('required', 'true');


                   }

                   else{
                    $(".chequeNo").removeAttr('required');
                     $(".chequeDate").removeAttr('required');
                     $(".bank_id").removeAttr('required');
                   }

                   let cardValue = $('.ser_exp_online').val();


                    if(cardValue>0){
                    $(".bank_id").attr('required', 'true');

                    }
                    else{
                    $(".bank_id").removeAttr('required');
                    }

                });

                $(".editadd").click(function(){

                   let editcheckValue = $('.editchequeVal').val();

                   if(editcheckValue>0){
                     $(".editchequeNo").attr('required', 'true');
                     $(".editchequeDate").attr('required', 'true');
                     $(".editbank_id").attr('required', 'true');


                   }

                   else{
                    $(".editchequeNo").removeAttr('required');
                     $(".editchequeDate").removeAttr('required');
                     $(".editbank_id").removeAttr('required');
                   }



                   let editCard = $('.editCard').val();

                   if(editCard>0){
                    $(".editbank_id").attr('required', 'true');

                   }
                   else{
                    $(".editbank_id").removeAttr('required');
                   }


                });



    </script>
</section>
@endsection



<!-- Add Service Expense-->
<div class="modal fade" id="AddExpense" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Service Expenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/service-expenses-store" method="post" class="needs-validation" novalidate=""
                    enctype="multipart/form-data">

                    <div class="card-body form">
                        @csrf

                        <div class="row">

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Service Provider</label>
                                    <select class="form-control" name="service_provider_id" required>
                                        <option value="" disabled selected>Service Provider</option>
                                        @foreach ($service_providers as $service_provider)
                                        <option name="service_provider_id" class="pro-type"
                                            value="{{ $service_provider->id}}">{{ $service_provider->name}}</option>
                                        @endforeach
                                        <span class="text-danger">@error('service_provider_id')
                                            {{ $message }}@enderror</span>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="date" max="{{now()->format('Y-m-d')}}"
                                        required>
                                </div>

                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" class="form-control" name="amount" pattern="^\d+(\.\d)?\d*$"
                                        required>
                                    <span class="text-danger">@error('amount')
                                        {{ $message }}@enderror</span>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Cash</label>
                                    <input type="text" class="form-control" name="ser_exp_cash"
                                        pattern="^\d+(\.\d)?\d*$">
                                    <span class="text-danger">@error('ser_exp_cash')
                                        {{ $message }}@enderror</span>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Cheque</label>
                                    <input type="text" class="form-control chequeVal" name="ser_exp_cheque"
                                        pattern="^\d+(\.\d)?\d*$">
                                    <span class="text-danger">@error('ser_exp_cheque')
                                        {{ $message }}@enderror</span>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Cheque Number</label>
                                    <input type="text" class="form-control chequeNo" name="ser_exp_cheque_no">
                                    <span class="text-danger">@error('ser_exp_cheque_no')
                                        {{ $message }}@enderror</span>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Cheque Date</label>
                                    <input type="date" class="form-control chequeDate" name="ser_exp_cheque_date">
                                    <span class="text-danger">@error('ser_exp_cheque_date')
                                        {{ $message }}@enderror</span>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Bank Number</label>
                                    <select class="form-control bank_id" name="bank_id">
                                        <option value="" disabled selected>Select Bank</option>
                                        @foreach ($accountBanks as $accountBank)
                                        <option name="bank_id" class="pro-type" value="{{ $accountBank->id }}">{{
                                            $accountBank->account_no}}</option>
                                        @endforeach
                                        <span class="text-danger">@error('bank_id')
                                            {{ $message }}@enderror</span>
                                    </select>
                                </div>
                            </div>


                            <div class="col-3">
                                <div class="form-group">
                                    <label>Card/Online</label>
                                    <input type="text" class="form-control ser_exp_online" name="ser_exp_online"
                                        pattern="^\d+(\.\d)?\d*$">
                                    <span class="text-danger">@error('ser_exp_online')
                                        {{ $message }}@enderror</span>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Reference Number</label>
                                    <input type="text" class="form-control" name="ser_exp_reference_no" required>
                                    <span class="text-danger">@error('ser_exp_reference_no')
                                        {{ $message }}@enderror</span>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-6">
                                    <div class="form-group hideClass">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="status"
                                            value="{{old('status')}}">
                                            <option value="" disabled selected>Select status</option>
                                            <option value="Active">Active</option>
                                            <option value="Deactive">Deactive</option>
                                        </select>
                                        <span class="text-danger">@error('status')
                                            {{ $message }}@enderror</span>
                                    </div>

                                </div>
                            </div> --}}
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description"></textarea>
                                    <span class="text-danger">@error('image')
                                        {{ $message }}@enderror</span>
                                </div>
                            </div>


                            <div class="col-6">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="file" name="image" placeholder="Choose image">
                                        @error('image')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div align="right">
                            <button class="btn btn-danger" id="reset" type="reset">Reset</button>
                            <button class="btn btn-success mr-1 add" id="add" type="submit">Submit</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<!-- Edit Service Expense-->
<div class="modal fade" id="EditServiceExpense" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Service Expenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/service-expenses-update" method="post" class="needs-validation" novalidate=""
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <input type="hidden" class="form-control" name="id" id="id">

                        <div class="col-3">
                            <div class="form-group">
                                <label>Service Provider</label>
                                <select class="form-control" id="provider" name="service_provider_id" required>
                                    <option value="" disabled selected>Select</option>
                                    @foreach ($service_providers as $service_provider)
                                    <option name="service_provider_id " class="pro-type"
                                        value="{{ $service_provider->id }}">{{ $service_provider->name }}</option>
                                    @endforeach
                                    <span class="text-danger">@error('service_provider_id ')
                                        {{ $message }}@enderror</span>
                                </select>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    max="{{now()->format('Y-m-d')}}" required>
                            </div>

                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount"
                                    pattern="^\d+(\.\d)?\d*$" required>
                                <span class="text-danger">@error('amount')
                                    {{ $message }}@enderror</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Cash</label>
                                <input type="text" class="form-control" id="ser_exp_cash" name="ser_exp_cash"
                                    pattern="^\d+(\.\d)?\d*$">
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Cheque</label>
                                <input type="text" class="form-control editchequeVal" id="ser_exp_cheque"
                                    pattern="^\d+(\.\d)?\d*$" name="ser_exp_cheque">
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Cheque Number</label>
                                <input type="text" class="form-control editchequeNo" id="ser_exp_cheque_no"
                                    name="ser_exp_cheque_no">
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Cheque Date</label>
                                <input type="date" class="form-control editchequeDate" id="ser_exp_cheque_date"
                                    name="ser_exp_cheque_date">
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Select Bank</label>
                                <select class="form-control editbank_id" id="bank_id" name="bank_id" required>
                                    <option value="" disabled selected>Select</option>
                                    @foreach ($accountBanks as $accountBank)
                                    <option name="bank_id " class="pro-type" value="{{ $accountBank->id }}">{{
                                        $accountBank->account_no }}</option>
                                    @endforeach
                                    <span class="text-danger">@error('bank_id ')
                                        {{ $message }}@enderror</span>
                                </select>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Card/Online</label>
                                <input type="text" class="form-control editCard" id="ser_exp_online"
                                    pattern="^\d+(\.\d)?\d*$" name="ser_exp_online">
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Reference/Invoice Number</label>
                                <input type="text" class="form-control" id="ser_exp_reference_no"
                                    name="ser_exp_reference_no" required>
                            </div>
                        </div>



                        <div class="col-3">
                            <div class="form-group hideClass">
                                <label>Status</label>
                                <select class="form-control" name="status" id="status" value="{{old('status')}}">
                                    <option value="" disabled selected>Select status</option>
                                    <option value="1">Paid</option>
                                    <option value="0">Unpaid</option>
                                </select>
                                <span class="text-danger">@error('status')
                                    {{ $message }}@enderror</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" id="description"></textarea>
                                <span class="text-danger">@error('image')
                                    {{ $message }}@enderror</span>
                            </div>
                        </div>

                        <div class="col-06">
                            <div class="form-group">
                                <input type="file" name="image" id="image">
                                @error('image')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div align="right">
                        <button class="btn btn-danger" id="reset" type="reset">Reset</button>
                        <button class="btn btn-success mr-1 editadd" id="add" type="submit">Submit</button>
                    </div>
            </div>
            </form>

        </div>

    </div>
</div>