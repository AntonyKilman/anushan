@extends('layouts.navigation')
@section('other_expense', 'active')
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
                                <h4 class="header ">Other Expenses</h4>
                                <a href="" class="btn btn-success" data-toggle="modal"
                                    data-target="#customer_">Add</a>
                            </div>


                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="save-stage">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Expense Category</th>
                                            <th>Expense Subcategory</th>
                                            {{-- <th>Department</th> --}}
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Reference</th>
                                            <th class='action'>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($other_expenses_1 as $row)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $row->cat_name }}</td>
                                                <td>{{ $row->sub_name }}</td>
                                                {{-- <td>{{ $row->dept_name }}</td> --}}
                                                <td>{{ $row->date }}</td>
                                                <td style="text-align: right">
                                                    {{ number_format($row->oth_exp_amount, 2) }}</td>
                                                <td>{{ $row->oth_exp_reference_no }}</td>
                                                <td class="col-action">
                                                    <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                                        data-target="#customer_" data-id="{{ $row->id }}"
                                                        data-catname="{{ $row->cat_name }}" data-subname="{{ $row->sub_name }}"
                                                        data-oth_exp_amount="{{ $row->oth_exp_amount }}" data-date="{{ $row->date }}"
                                                        data-categeory_id="{{ $row->categeory_id }}" 
                                                        data-sub_categeory_id="{{ $row->sub_categeory_id }}"
                                                        data-oth_exp_cash="{{ $row->oth_exp_cash }}" data-oth_exp_credit="{{ $row->oth_exp_credit }}"
                                                        data-oth_exp_cheque="{{ $row->oth_exp_cheque }}" data-oth_exp_cheque_no="{{ $row->oth_exp_cheque_no }}"
                                                        data-oth_exp_cheque_date="{{ $row->oth_exp_cheque_date }}" data-bank_id="{{ $row->bank_id }}"
                                                        data-oth_exp_online="{{ $row->oth_exp_online }}" data-oth_exp_reference_no="{{ $row->oth_exp_reference_no }}"
                                                        data-oth_exp_reason="{{ $row->oth_exp_reason }}">
                                                        
                                                        <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-edit"></i>
                                                    </a>
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
@endsection


@section('modal')
    <!-- create update Expense-->
    <div class="modal fade" id="customer_" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Other Expenses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/other-expenses-store" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">
                            @csrf

                            <div class="row">
                                <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control" name="Categeory_id" id="Categeory_id" onchange="showSubCategory()" required>
                                            <option value="" disabled selected>Select Category</option>
                                            @foreach ($categories as $row)
                                            <option class="Categeory" id="categeory_id_{{$row->id}}" value="{{ $row->id }}"
                                                {{ $row->id == old('Categeory_id') ? 'selected' : '' }}>
                                                {{ $row->name }}</option>
                                        @endforeach
                                            <span class="text-danger">
                                                @error('Categeory_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Subcategory</label>
                                        <select class="form-control" name="Sub_Categeory_id" id="Sub_Categeory_id" required>
                                            <option value="" disabled selected>Select Subcategory</option>
                                            @foreach ($oth_exp_types as $row)
                                                    <option class="Sub_Categeory categeory_id_2_{{ $row->categeory_id }}" id="Sub_Categeory_id_{{$row->id}}" value="{{ $row->id }}"
                                                        {{ $row->id == old('Sub_Categeory_id') ? 'selected' : '' }}>
                                                        {{ $row->name }}</option>
                                                @endforeach
                                            <span class="text-danger">
                                                @error('Sub_Categeory_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </select>
                                    </div>
                                </div>


                                {{-- <div class="col-3">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control" name="oth_dep_id" required>
                                            <option value="" disabled selected>Select Department</option>
                                            @foreach ($departments as $department)
                                                <option name="oth_dep_id" class="pro-type" value="{{ $department->id }}">
                                                    {{ $department->dept_name }}</option>
                                            @endforeach
                                            <span class="text-danger">
                                                @error('oth_dep_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </select>
                                    </div>
                                </div> --}}

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" name="date" id="date"
                                            max="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" name="amount" id="amount"
                                            pattern="^\d+(\.\d)?\d*$" required>
                                        <span class="text-danger">
                                            @error('amount')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Cash</label>
                                        <input type="text" class="form-control" name="cash" id="cash"
                                            pattern="^\d+(\.\d)?\d*$">
                                        <span class="text-danger">
                                            @error('cash')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Credit</label>
                                        <input type="text" class="form-control" name="credit" id="credit"
                                            pattern="^\d+(\.\d)?\d*$">
                                        <span class="text-danger">
                                            @error('credit')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Cheque</label>
                                        <input type="text" class="form-control " name="cheque" id="cheque"
                                            pattern="^\d+(\.\d)?\d*$">
                                        <span class="text-danger">
                                            @error('cheque')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Cheque Number</label>
                                        <input type="text" class="form-control " name="cheque_no" id="cheque_no">
                                        <span class="text-danger">
                                            @error('cheque_no')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Cheque Date</label>
                                        <input type="date" class="form-control " name="cheque_date" id="cheque_date">
                                        <span class="text-danger">
                                            @error('cheque_date')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Bank Number</label>
                                        <select class="form-control  " name="bank_id">
                                            <option value="" disabled selected>Select Bank</option>
                                            @foreach ($accountBanks as $accountBank)
                                                <option name="bank_id" class="bank" id="bank_id_{{$accountBank->id}}" value="{{ $accountBank->id }}"
                                                    {{ $accountBank->id == old('bank_id') ? 'selected' : '' }}>
                                                    {{ $accountBank->account_no }}</option>
                                            @endforeach
                                            <span class="text-danger">
                                                @error('bank_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Card/Online</label>
                                        <input type="text" class="form-control " name="oth_exp_online" id="onlineValue"
                                            pattern="^\d+(\.\d)?\d*$">
                                        <span class="text-danger">
                                            @error('oth_exp_online')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Reference Number</label>
                                        <input type="text" class="form-control " name="reference_no" id="reference_no">
                                        <span class="text-danger">
                                            @error('reference_no')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Reason</label>
                                        <textarea class="form-control" name="reason" id="reason"></textarea>
                                        <span class="text-danger">
                                            @error('reason')
                                                {{ $message }}
                                            @enderror
                                        </span>
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
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {

        showSubCategory();

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#customer_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Expense Details');
            } else {
                $('#formModal').empty().append('Update Expense Details');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#Categeory_id').val('');
            $('#Sub_Categeory_id').val('');
            $('#date').val('');
            $('#amount').val('');
            $('#cash').val('');
            $('#credit').val('');
            $('#cheque').val('');
            $('#cheque_no').val('');
            $('#cheque_date').val('');
            $('#bank_id').val('');
            $('#onlineValue').val('');
            $('#reference_no').val('');
            $('#reason').val('');

            showSubCategory();

            $('#formModal').empty().append('Create Expense Details');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#amount').val($(this).attr('data-oth_exp_amount'));
            $('#date').val($(this).attr('data-date'));
            $('#cash').val($(this).attr('data-oth_exp_cash'));
            $('#credit').val($(this).attr('data-oth_exp_credit'));
            $('#cheque').val($(this).attr('data-oth_exp_cheque'));
            $('#cheque_no').val($(this).attr('data-oth_exp_cheque_no'));
            $('#cheque_date').val($(this).attr('data-oth_exp_cheque_date'));
            $('#bank_id').val($(this).attr('data-bank_id'));
            $('#onlineValue').val($(this).attr('data-oth_exp_online'));
            $('#reference_no').val($(this).attr('data-oth_exp_reference_no'));
            $('#reason').val($(this).attr('data-oth_exp_reason'));

            var categeory_id = $(this).attr('data-categeory_id');
            $('.categeory').attr('selected', false);
            $('#categeory_id_' + categeory_id).attr('selected', true);

            showSubCategory();

            var Sub_Categeory_id = $(this).attr('data-sub_categeory_id');
            $('.Sub_Categeory').attr('selected', false);
            $('#Sub_Categeory_id_' + Sub_Categeory_id).attr('selected', true);

            var bank_id = $(this).attr('data-bank_id');
            $('.bank').attr('selected', false);
            $('#bank_id_' + bank_id).attr('selected', true);

            $('#formModal').empty().append('Expense Details');
        });
    
    });

        function showSubCategory() {
            $('.Sub_Categeory').hide();

            var categeoryId = $('#Categeory_id').val();
            console.log(categeoryId);

            if (categeoryId) {
                $('.categeory_id_2_' + categeoryId).show();
            }
        }
</script>
</section>
