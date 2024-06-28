@extends('layouts.navigation')
@section('employee_salary_part', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $create = false;
    $update = false;
    
    if (in_array('hr.empSalaryPart.store', $Access)) {
        $create = true;
    }
    if (in_array('hr.empSalaryPart-edit', $Access)) {
        $update = true;
    }
    ?>

    <div class="card card-success">
        <div class="card-header">
            <h4>Employee Salary Part</h4>
            <div class="card-header-action">
                @if ($create)
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#employee_salary_part_">
                        <span class="btn-icon-wrapper pr-2"> </span>
                        Add
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th width="15%">Emp Code</th>
                            <th>Employee Name</th>
                            <th width="20%" align="right">Total Allowance</th>
                            <th width="15%" class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                        ?>
                        @foreach ($emp_salary_part as $row)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $row->emp_code }}</td>
                                <td >{{ $row->emp_name }}  {{ $row->emp_last_name }}</td>
                                <td align="right">{{ number_format($row->total_allowance,2) }}</td>

                                <td class="col-action">
                                    @if ($update)
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#employee_salary_part_" data-id="1" data-emp_id="{{ $row->emp_id }}"
                                            data-emp_name="{{ $row->emp_name }}" >
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
    <div class="modal fade" id="employee_salary_part_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Employee Salary Part</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('hr.empSalaryPart.store') }}">
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
                                                    placeholder="select employee" data-toggle="modal" data-target="#searchEmp_"
                                                    class="form-control" value="{{ old('emp_name') }}" />
                                                <p style="color:Tomato"> @error('emp_id'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div style="background-color:#E9ECEF;" class="col-md-12">
                                            <h5 align="center">Allowance</h5>
                                            @foreach($allowance_type as $row)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>{{ $row->name}}</label>    
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <input hidden type="number"  class="form-control" value="{{ $row->id}}" name="allowance_id[]"/>
                                                                <input type="number"  min="0.00" step="100"  value="0.00" class="form-control allowance_val" id="allowance_val_{{ $row->id }}" name="allowance_val[]"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>     
                                            <br>      
                                            @endforeach 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>

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
            $('#employee_salary_part_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Employee Salary Part');
            } else {
                $('#formModal').empty().append('Update Employee Salary Part');
            }
        }

        // emp list get
        empSearch();

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#emp_id').val('');
            $('#emp_name').val('');
            $('.allowance_val').val('0.00');

            $('#formModal').empty().append('Create Employee Salary Part');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#emp_id').val($(this).attr('data-emp_id'));
            $('#emp_name').val($(this).attr('data-emp_name'));
            $('.allowance_val').val('0.00');

            var emp_id = $(this).attr('data-emp_id');
            $.ajax({
                url: "{{ route('allowance.amount.by-emp-id') }}",
                method: 'GET',
                data: {
                    emp_id: emp_id
                },
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {
                    res.forEach(element => {
                        var allowance_type_id = element.allowance_type_id;
                        var amount = element.amount;
                        $('#allowance_val_' + allowance_type_id).val(amount);
                    });
                }
            })

            $('#formModal').empty().append('Update Employee Salary Part');
        });


        // search employee
        $(document).on('keyup', '#employee-Search', function() {
            empSearch();
        });

        function empSearch() {
            var query = $('#employee-Search').val();

            $.ajax({
                url: "{{ route('search-employee.salary-part') }}",
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
