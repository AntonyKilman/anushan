@extends('layouts.navigation')
@section('advance_payment', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $create = false;
    $update = false;
    
    if (in_array('hr.advancePayment.store', $Access)) {
        $create = true;
    }
    if (in_array('hr.advancePayment-edit', $Access)) {
        $update = true;
    }
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="card card-success">
        <div class="card-header">
            <h4>Advance Payment</h4>
            <div class="card-header-action">
                @if ($create)
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#advance_payment_">
                        <span class="btn-icon-wrapper pr-2"> </span>
                        Add
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                </div>
                <div class="col-md-3">
                    <input class="form-control date" value="{{ $month }}" name="month" type="month" id="month"
                        onchange="window.location.assign('/advance-payment-show?month=' + this.value)" />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Employee Name</th>
                            <th width="10%"> Date </th>
                            <th width="10%" align="right">Amount</th>
                            <th width="15%">Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        ?>
                        @foreach ($advance_payment as $payment)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $payment->emp_name }}</td>
                                <td>{{ $payment->month }}</td>
                                <td align="right">{{ number_format($payment->amount,2) }}</td>
                                
                                <td class="col-action" align="center">
                                    @if ($update)
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#advance_payment_" data-id="{{ $payment->id }}" data-emp_id="{{ $payment->emp_id }}"
                                            data-emp_name="{{ $payment->emp_name }}" data-month="{{ $payment->month }}"
                                            data-amount="{{ $payment->amount }}">
                                            <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-edit"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection


@section('modal')

    {{-- create & update model --}}
    <div class="modal fade" id="advance_payment_" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Advance Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('hr.advancePayment.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Employee</label>
                                            <input hidden type="text" id="id" class="form-control" name="id"
                                                value="{{ old('id') }}" />
                                            <input hidden type="text" id="emp_id" class="form-control" name="emp_id"
                                                value="{{ old('emp_id') }}" />
                                            <div>
                                                <input type="text" id="emp_name" name="emp_name" readonly required
                                                    data-toggle="modal" data-target="#searchEmp_" class="form-control"
                                                    value="{{ old('emp_name') }}" placeholder="Select Employee" />
                                                <p style="color:Tomato"> @error('emp_id'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div>
                                                <input type="date" name="month" id="month" class="form-control"
                                                    value="{{ old('month') }}" required>
                                            </div>
                                            <p style="color:Tomato"> @error('month'){{ $message }} @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <div>
                                                <input type="number" min="0.00" step="100" name="amount" id="amount" class="form-control"
                                                    required>
                                            </div>
                                            <p style="color:Tomato"> @error('amount'){{ $message }} @enderror
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group" align="right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- search employee --}}
    <div class="modal fade" id="searchEmp_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Search Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Custom Search..." id="employee-Search">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <br>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="20%">Code</th>
                                    <th width="20%">Employee Name</th>
                                    <th width="20%">Department Name</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employee_">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#advance_payment_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Advance Payment');
            } else {
                $('#formModal').empty().append('Update Advance Payment');
            }
        }

        // emp list get
        empSearch();

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#emp_id').val('');
            $('#emp_name').val('');
            $('#month').val('');
            $('#amount').val('');


            $('#formModal').empty().append('Create Advance Payment');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#emp_id').val($(this).attr('data-emp_id'));
            $('#emp_name').val($(this).attr('data-emp_name'));
            $('#amount').val($(this).attr('data-amount'));

            var month_ = $(this).attr('data-month');
            $('#month').val(month_);

            $('#formModal').empty().append('Update Advance Payment');
        });


        // search employee
        $(document).on('keyup', '#employee-Search', function() {
            // emp list get
            empSearch();
        });

        function empSearch() {
            var query = $('#employee-Search').val();

            $.ajax({
                url: "{{ route('common.search-employee') }}",
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {

                    $('#employee_').html('');

                    if (res != '') {
                        $.each(res, function(index, row) {

                            employee_row = ` <tr>
                                                <td >` + row.emp_code + `</td>
                                                <td >` + row.f_name + `</td>
                                                <td >` + row.department_name + `</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-small btn-success empSelect" href="#" style="padding:0px 20px" 
                                                    data-emp_id=" ` + row.id + `" data-emp_name =" ` + row.f_name + `" 
                                                    >Select</a>
                                                </td>
                                            </tr>`;

                            $('#employee_').append(employee_row);

                        });

                    } else {

                        employee_row =
                            '<tr><td align="center" colspan="4">No Data Found</td></tr>';

                        $('#employee_').append(employee_row);
                    }

                    // select employee
                    $('.empSelect').on('click', function() {

                        $('#emp_id').val($(this).attr('data-emp_id'));
                        $('#emp_name').val($(this).attr('data-emp_name'));

                        $('#searchEmp_').modal('hide'); // model hide
                    });

                }
            })
        }

    });
</script>
