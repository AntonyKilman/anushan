@extends('layouts.navigation')
@section('bank_charges', 'active')
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
                                <h4 class="header ">Bank Charges</h4>
                                <a href="" class="btn btn-success addcharge" data-toggle="modal"
                                    data-target="#bankcharges">Add</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Bank</th>
                                            <th>Bank Charges Type</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Note</th>
                                            <th class='action'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bank_charges as $bank_charges)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $bank_charges->bank_name }}</td>
                                                <td>{{ $bank_charges->name }}</td>
                                                <td>{{ $bank_charges->date }}</td>
                                                <td style="text-align: right">{{ $bank_charges->amount }}</td>
                                                <td>{{ $bank_charges->note }}</td>
                                                <td class='action'>
                                                    <button data-toggle="modal" data-id="{{ $bank_charges->id }}"
                                                        data-bank_id="{{ $bank_charges->bank_id }}"
                                                        data-bank_charges_type_id="{{ $bank_charges->bank_charges_type_id }}"
                                                        data-date="{{ $bank_charges->date }}"
                                                        data-amount="{{ $bank_charges->amount }}"
                                                        data-note="{{ $bank_charges->note }}" data-target="#chargesType"
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

        $(".addcharge").on("click", function() {
            $('#id').val("");
            $('#bank_id').val("");
            $('#bank_charges_type_id').val("");
            $('#date').val("");
            $('#amount').val("");
            $('#note').val("");
        });

        $('.btn-edit').on('click', function() {
            var id = $(this).attr('data-id');
            var bank_id = $(this).attr('data-bank_id');
            var bank_charges_type_id = $(this).attr('data-bank_charges_type_id');
            var date = $(this).attr('data-date');
            var amount = $(this).attr('data-amount');
            var note = $(this).attr('data-note');
            var description = $(this).attr('data-description');

            $('#id').val(id);
            $('#bank_id').val(bank_id);
            $('#bank_charges_type_id').val(bank_charges_type_id);
            $('#date').val(date);
            $('#amount').val(amount);
            $('#note').val(note);
        });

        $(document).ready(function() {
            if (!@json($errors->isEmpty())) {
                $('#chargesType').modal();
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
                    <h5 class="modal-title" id="exampleModalLabel">Bank Charges</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/bank-charges-store" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">
                            @csrf


                            {{-- <input type="hidden" class="form-control" name="id" id="id" > --}}
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Bank</label>
                                        <select class="form-control" name="bank_id" required>
                                            <option value="" disabled selected>Select Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach

                                        </select>
                                        <span class="text-danger">
                                            @error('product_cat_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Charges Type</label>
                                        <select class="form-control" name="bank_charges_type_id" required>
                                            <option value="" disabled selected>Select Category</option>
                                            @foreach ($bank_charges_types as $bank_charges_type)
                                                <option value="{{ $bank_charges_type->id }}">
                                                    {{ $bank_charges_type->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">
                                            @error('product_cat_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control"
                                            name="date" required>
                                        <span class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Charges Amount</label>
                                        <input type="text" class="form-control" name="charges_amount"
                                            pattern="^\d+(\.\d)?\d*$" required>
                                        <span class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea class="form-control" name="note"></textarea>
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

    <!-- Update BankCharges modal -->


    <div class="modal" id="chargesType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bank Charges</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/bank-charges-update" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">
                            @csrf


                            <input type="hidden" class="form-control" name="id" id="id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Bank</label>
                                        <select class="form-control" id="bank_id" name="bank_id" required>
                                            <option value="" disabled selected>Select Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Charges Type</label>
                                        <select class="form-control" id="bank_charges_type_id"
                                            name="bank_charges_type_id" required>
                                            <option value="" disabled selected>Select Category</option>
                                            @foreach ($bank_charges_types as $bank_charges_type)
                                                <option value="{{ $bank_charges_type->id }}">
                                                    {{ $bank_charges_type->name }}</option>
                                            @endforeach

                                        </select>
                                        <span class="text-danger">
                                            @error('product_cat_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control"
                                            name="date" id="date" required>
                                        <span class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Charges Amount</label>
                                        <input type="text" class="form-control" name="charges_amount" id="amount"
                                            pattern="^\d+(\.\d)?\d*$" required>
                                        <span class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>



                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea class="form-control" name="note" id="note"></textarea>
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
@endsection
<!-- Update BankCharges modal end-->
