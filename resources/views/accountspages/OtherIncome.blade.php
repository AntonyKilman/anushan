@extends('layouts.navigation')
@section('other_income', 'active')
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
                                <h4 class="header ">Other Income</h4>
                                <a href="/bank-add" class="btn btn-success" data-toggle="modal" data-target="#AddBank">Add</a>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Categeory Name</th>
                                            <th>Description</th>
                                            <th class='action'>Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($other_incomes as $other_income)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $other_income->date }}</td>
                                                <td style="text-align: right">{{ $other_income->amount }}</td>
                                                <td>{{ $other_income->categeory_name }}</td>
                                                <td>{{ $other_income->description }}</td>
                                                <td class='action'>
                                                    <button data-toggle="modal" data-id="{{ $other_income->id }}"
                                                        data-date="{{ $other_income->date }}"
                                                        data-amount="{{ $other_income->amount }}"
                                                        data-categeory_id="{{ $other_income->categeory_id }}"
                                                        data-description="{{ $other_income->description }}"
                                                        data-target="#EditOtherIncome" title="edit"
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

        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous"></script>

        <script>
            $(function() {
                setTimeout(function() {
                    $('.fade-message').slideUp();
                }, 1000);
            });

            $('.btn-edit').on('click', function() {
                var id = $(this).attr('data-id');
                var date = $(this).attr('data-date');
                var amount = $(this).attr('data-amount');
                var categeory_name = $(this).attr('data-categeory_id');
                var description = $(this).attr('data-description');

                $('#id').val(id);
                $('#date').val(date);
                $('#amount').val(amount);
                $('#Categeory_id').val(categeory_name);
                $('#description').val(description);

            });
        </script>
    </section>
@endsection

@section('modal')

    <!-- Add Other Income-->
    <div class="modal fade" id="AddBank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Other Income</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/other-income-add-process" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">
                            @csrf

                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" name="date" required>
                                    </div>

                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" name="amount" pattern="^\d+(\.\d)?\d*$"
                                            required>
                                        <span class="text-danger">
                                            @error('amount')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="property name">Categeory Name<span class="text-danger">*</span></label>
                                        <select class="form-control" name="Categeory_id" required>
                                            <option value="" disabled selected>Select Categeory</option>
                                            @foreach ($categeory as $row)
                                                <option class="categeory" id="categeory_id_{{ $row->id }}"
                                                    value="{{ $row->id }}"
                                                    {{ $row->id == old('Categeory_id') ? 'selected' : '' }}>
                                                    {{ $row->categeory_name }}</option>
                                            @endforeach
                                            <span class="text-danger">
                                                @error('Categeory_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description"></textarea>
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

    <!-- Add Other Incomes-->

    <!-- Edit Other Income-->
    <div class="modal fade" id="EditOtherIncome" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Other Income</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/other-income-update" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" id="amount" name="amount"
                                            pattern="^\d+(\.\d)?\d*$" required>
                                        <span class="text-danger">
                                            @error('amount')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="property name">Categeory Name<span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="Categeory_id" id="Categeory_id" required>
                                            <option value="" disabled selected>Select Categeory</option>
                                            @foreach ($categeory as $row)
                                                <option class="categeory" id="categeory_id_{{ $row->id }}"
                                                    value="{{ $row->id }}"
                                                    {{ $row->id == old('Categeory_id') ? 'selected' : '' }}>
                                                    {{ $row->categeory_name }}</option>
                                            @endforeach
                                            <span class="text-danger">
                                                @error('Categeory_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                        <span class="text-danger">
                                            @error('description')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
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
