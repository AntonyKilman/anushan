@extends('layouts.navigation')
@section('bank_intrests','active')
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
                                <h4 class="header ">Bank Interest</h4>
                                <a href="" class="btn btn-success addchargetype" data-toggle="modal"
                                    data-target="#intrest">Add</a>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Account No</th>
                                            <th>Interest Amount</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($accountBankIntrests as $accountBankIntrest)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $accountBankIntrest->account_no }}</td>
                                                <td style="text-align: right">{{ $accountBankIntrest->intrest_amount }}</td>
                                                <td>{{ $accountBankIntrest->date }}</td>
                                                <td>{{ $accountBankIntrest->note }}</td>


                                                <td style="text-align: center">
                                                    <button data-toggle="modal"
                                                        data-account_no="{{ $accountBankIntrest->account_no }}"
                                                        data-intrest_amount="{{ $accountBankIntrest->intrest_amount }}"
                                                        data-date="{{ $accountBankIntrest->date }}"
                                                        data-note="{{ $accountBankIntrest->note }}"
                                                        data-id="{{ $accountBankIntrest->id }}"
                                                        data-bank_id="{{ $accountBankIntrest->bank_id }}"
                                                        data-target="#intrest" title="edit"
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
            $('#bank_id').val("");
            $('#intrest_amount').val("");
            $('#date').val("");
            $('#note').val("");
        });

        $('.btn-edit').on('click', function() {
            var id = $(this).attr('data-id');
            var account_no = $(this).attr('data-account_no');
            var intrest_amount = $(this).attr('data-intrest_amount');
            var date = $(this).attr('data-date');
            var note = $(this).attr('data-note');
            var bank_id = $(this).attr('data-bank_id');


            $('#id').val(id);
            $('#bank_id').val(bank_id);
            $('#intrest_amount').val(intrest_amount);
            $('#date').val(date);
            $('#note').val(note);


        });




        $(document).ready(function() {


            if (!@json($errors->isEmpty())) {
                $('#intrest').modal();
            }
        });
    </script>
@endsection



<!--modal-->
<div class="modal fade" id="intrest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bank Interest</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/bank-intrest-store" method="post" class="needs-validation" novalidate="">

                    <div class="card-body form">
                        @csrf


                        <input type="hidden" class="form-control" name="id" id="id">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Account No</label>
                                    <select id="bank_id" class="form-control" name="bank_id" value="{{old('bank_id')}}" required>
                                        <option value="" disabled selected>Select Category</option>
                                        @foreach ($accountBanks as $accountBank)
                                            <option value="{{ $accountBank->id }}">{{ $accountBank->account_no }}
                                            </option>
                                        @endforeach

                                    </select>
                                    <span class="text-danger">@error('bank_id')
                                            {{ $message }}
                                        @enderror</span>


                                </div>


                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Interest Amount</label>
                                    <input type="number" step="0.001" class="form-control" name="intrest_amount" id="intrest_amount" value="{{old('intrest_amount')}}"
                                        required>
                                    <span class="text-danger">@error('intrest_amount')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="date" id="date" max="{{now()->format('Y-m-d')}}" value="{{old('date')}}" required>
                                    <span class="text-danger">@error('date')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea class="form-control" name="note" id="note" value="{{old('note')}}"></textarea>
                                    <span class="text-danger">@error('note')
                                            {{ $message }}
                                        @enderror</span>
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
