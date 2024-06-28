@extends('layouts.navigation')
@section('bank_transaction','active')
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
                                <h4 class="header ">Bank Transaction</h4>
                                <a href="" class="btn btn-success addchargetype" data-toggle="modal"
                                    data-target="#transection">Add</a>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Account No</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($accountBankTransections as $accountBankTransection)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $accountBankTransection->account_no }}</td>
                                                <td style="text-align: right">{{ $accountBankTransection->amount }}</td>
                                                <td>{{ $accountBankTransection->type }}</td>
                                                <td>{{ $accountBankTransection->no }}</td>
                                                <td>{{ $accountBankTransection->date }}</td>
                                                <td>{{ $accountBankTransection->note }}</td>


                                                <td class='action'>
                                                    <button data-toggle="modal"
                                                        data-account_no="{{ $accountBankTransection->account_no }}"
                                                        data-amount="{{ $accountBankTransection->amount }}"
                                                        data-type="{{ $accountBankTransection->type }}"
                                                        data-no="{{ $accountBankTransection->no }}"
                                                        data-date="{{ $accountBankTransection->date }}"
                                                        data-note="{{ $accountBankTransection->note }}"
                                                        data-id="{{ $accountBankTransection->id }}"
                                                        data-bank_id="{{ $accountBankTransection->bank_id }}"
                                                        data-cheque_no="{{ $accountBankTransection->cheque_no }}"
                                                        data-cheque_date="{{ $accountBankTransection->cheque_date }}"
                                                        data-target="#transection" title="edit"
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

        $("#type").change(function(){
              console.log(('Selected value: ' + $(this).val()));

              if(($(this).val())=="Cheque Deposit" || ($(this).val())=="Cheque Withdraw" ){
                console.log("deposit");
                $("#cheque_no").attr('required', 'true'); 
                $("#cheque_date").attr('required', 'true'); 
              }
              else{
                $("#cheque_no").removeAttr('required');
                $("#cheque_date").removeAttr('required');
                 

              }
        });



        $(function() {
            setTimeout(function() {
                $('.fade-message').slideUp();
            }, 1000);
        });


        $(".addchargetype").on("click", function() {
            $('#id').val("");
            $('#account_no').val("");
            $('#bank_id').val("");

            $('#amount').val("");
            $('#type').val("");
            $('#no').val("");
            $('#date').val("");
            $('#note').val("");
            $('#cheque_no').val("");
            $('#cheque_date').val("");
        });

        $('.btn-edit').on('click', function() {
            var id = $(this).attr('data-id');
            var account_no = $(this).attr('data-account_no');
            var amount = $(this).attr('data-amount');
            var type = $(this).attr('data-type');
            var no = $(this).attr('data-no');
            var date = $(this).attr('data-date');
            var note = $(this).attr('data-note');
            var bank_id = $(this).attr('data-bank_id');
            var cheque_no = $(this).attr('data-cheque_no');
            var cheque_date = $(this).attr('data-cheque_date');



            $('#id').val(id);
            $('#bank_id').val(bank_id);
            $('#amount').val(amount);
            $('#type').val(type);
            $('#no').val(no);
            $('#date').val(date);
            $('#note').val(note);
            $('#cheque_no').val(cheque_no);
            $('#cheque_date').val(cheque_date);


        });

        $(document).ready(function() {


            if (!@json($errors->isEmpty())) {
                $('#transection').modal();
            }
        
          
        });

           



       
    </script>
@endsection



<!--modal-->
<div class="modal fade" id="transection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bank Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/bank-transection-store" method="post" class="needs-validation" novalidate="">

                    <div class="card-body form">
                        @csrf


                        <input type="hidden" class="form-control" name="id" id="id">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Account No</label>
                                    <select class="form-control" name="bank_id" id="bank_id" value="{{old('bank_id')}}" required>
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
                                    <label>Amount</label>
                                    <input type="number" class="form-control" name="amount" id="amount" step="0.001" value="{{old('amount')}}"
                                         required>
                                    <span class="text-danger">@error('amount')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Transaction Type</label>
                                    <select class="form-control ma" name="type" id="type" value="{{old('type')}}" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="Deposit">Deposit</option>
                                        <option value="Withdraw">Withdraw</option>
                                        <option value="Cheque Deposit" >Cheque Deposit</option>
                                        <option value="Cheque Withdraw" >Cheque Withdraw</option>
                                        <option value="Online Deposit">Online Deposit</option>
                                        <option value="Online Withdraw">Online Withdraw</option>
                                        <option value="Commission">Commission</option>
                                    </select>
                                    <span class="text-danger">@error('type')
                                            {{ $message }}
                                        @enderror</span>


                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Invoice No</label>
                                    <input type="string" class="form-control" name="no" id="no" value="{{old('no')}}" required>
                                    <span class="text-danger">@error('no')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Cheque No</label>
                                    <input type="number" class="form-control" name="cheque_no" id="cheque_no" value="{{old('cheque_no')}}">
                                    <span class="text-danger">@error('cheque_no')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Cheque Date</label>
                                    <input type="date" class="form-control" name="cheque_date" id="cheque_date" value="{{old('cheque_date')}}">
                                    <span class="text-danger">@error('cheque_date')
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

