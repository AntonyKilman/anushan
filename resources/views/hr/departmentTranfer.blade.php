@extends('layouts.navigation')
@section('department tranfer', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $create = false;
    $update = false;
    
    if (in_array('departmentTranfer.store', $Access)) {
        $create = true;
    }
    if (in_array('departmentTranfer-edit', $Access)) {
        $update = true;
    }
    ?>


    <div class="card card-success">
        <div class="card-header">
            <h4>Department Transfer</h4>
            <div class="card-header-action">
                @if ($create)
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#department_trans_">
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
                            <th>No</th>
                            <th width="20%">Employee Name</th>
                            <th width="20%">Past Department</th>
                            <th width="20%">Present Department</th>
                            <th>Date & Time</th>
                            <th width="15%" class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                        ?>
                        @foreach ($department_tran as $row)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $row->emp_name }}</td>
                                <td>{{ $row->from_department_name }}</td>
                                <td>{{ $row->to_department_name }}</td>
                                <td>{{ $row->date_time }}</td>

                                <td class="col-action">
                                    @if ($update)
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#department_trans_" data-id="{{ $row->id }}"
                                            data-emp_id="{{ $row->emp_id }}"
                                            data-from_department_id="{{ $row->from_department_id }}"
                                            data-from_department_name="{{ $row->from_department_name }}"
                                            data-to_department_id="{{ $row->to_department_id }}"
                                            data-date_time="{{ $row->date_time }}"
                                            data-emp_name="{{ $row->emp_name }}"
                                            data-description="{{ $row->description }}" >
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

    {{-- create model --}}
    <div class="modal fade" id="department_trans_" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Department Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="needs-validation" novalidate="" id="signupForm" method="POST"
                        action="{{ route('departmentTranfer.store') }}">
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
                                                    value="{{ old('emp_name') }}" placeholder="Select employee"/>
                                                <p style="color:Tomato"> @error('emp_id'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Past Department</label>
                                            <div>
                                                <input hidden type="text" id="from_department_id" class="form-control" name="from_department_id"
                                                value="{{ old('from_department_id') }}" />

                                                <input type="text" id="from_department_name" name="from_department_name" readonly required
                                                     class="form-control"
                                                    value="{{ old('from_department_name') }}" placeholder="Select department"/>
                                            </div>
                                            <p style="color:Tomato"> @error('from_department_id'){{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Present Department</label>
                                            <div>
                                                <select required class="form-control" name="to_department_id">
                                                    <option selected value="">Select department</option>
                                                    @foreach ($department as $row)
                                                        <option class="class_to_department"
                                                            id="to_department_{{ $row->id }}"
                                                            value="{{ $row->id }}"
                                                            {{ old('to_department_id') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <p style="color:Tomato"> @error('to_department_id'){{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date & Time</label>
                                            <div>
                                                <input type="datetime-local" name="date_time" class="form-control"
                                                    id="date_time" value="{{ old('date_time') }}" required  max="9999-12-31T12:59"/>
                                                <p style="color:Tomato"> @error('date_time'){{ $message }} @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="Description">Description</label>
                                            <div>
                                                <textarea type="text" class="form-control" rows="1" id="description"
                                                    name="description"
                                                    placeholder="Enter the description">{{ old('description') }}</textarea>
                                            </div>
                                            <p style="color:Tomato"> @error('description'){{ $message }} @enderror
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
                            <tbody id="new_employee_">
                                
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
            $('#department_trans_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Department Transfer');
            } else {
                $('#formModal').empty().append('Update Department Transfer');
            }
        }

        // emp list get
        empSearch();

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#emp_id').val('');
            $('#emp_name').val('');
            $('#from_department_id').val('');
            $('#from_department_name').val('');
            $('#description').val('');

            $('.class_to_department').attr('selected', false);

            var now = new Date();
            var year = now.getFullYear();
            var month = now.getMonth() + 1;
            var day = now.getDate();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var second = now.getSeconds();

            var date_ = year + "-" + (("" + month).length < 2 ? "0" : "") + month + "-" + (("" + day).length < 2 ? "0" : "") + day;
            var time_ = (("" + hours).length < 2 ? "0" : "") + hours + ":" + (("" + minutes).length < 2 ? "0" : "") + minutes;
            var date_time_ = date_ + "T" + time_;
            $('#date_time').val(date_time_);

            $('#formModal').empty().append('Create Department Transfer');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#emp_id').val($(this).attr('data-emp_id'));
            $('#emp_name').val($(this).attr('data-emp_name'));
            $('#description').val($(this).attr('data-description'));

            var date_time_arry = $(this).attr('data-date_time').split(' ');
            var date_time = date_time_arry[0] + 'T' + date_time_arry[1];
            $('#date_time').val(date_time);

            $('#from_department_id').val($(this).attr('data-from_department_id'));
            $('#from_department_name').val($(this).attr('data-from_department_name'));

            $('.class_to_department').attr('selected', false);
            var to_department_id = $(this).attr('data-to_department_id');
            $('#to_department_' + to_department_id).attr('selected', true);

            $('#formModal').empty().append('Update Department Transfer');
        });


        // search employee
        $(document).on('keyup', '#employee-Search', function() {
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
                
                    $('#new_employee_').html('');

                    if (res != '') {
                        $.each(res, function(index,row){
                                
                            employee_row = ` <tr>
                                                <td >` +row.emp_code+ `</td>
                                                <td >`+row.f_name+ `</td>
                                                <td >` +row.department_name+ `</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-small btn-success empSelect" href="#" style="padding:0px 20px" 
                                                    data-emp_id=" `+row.id+`" data-emp_code=" `+row.emp_code+`" data-emp_name =" ` +row.f_name+`" 
                                                    data-now_department_id=" `+row.department_id+`" 
                                                    data-now_department_name=" ` +row.department_name+`" >Select</a>
                                                </td>
                                            </tr>`;

                            $('#new_employee_').append(employee_row);

                        });
                        
                    } else {

                        employee_row =  '<tr><td align="center" colspan="4">No Data Found</td></tr>';

                        $('#new_employee_').append(employee_row);
                    }

                    // select employee
                    $('.empSelect').on('click', function() {

                        $('#emp_id').val($(this).attr('data-emp_id'));
                        $('#emp_name').val($(this).attr('data-emp_name'));

                        $('#from_department_id').val($(this).attr('data-now_department_id'));
                        $('#from_department_name').val($(this).attr('data-now_department_name'));

                        $('#searchEmp_').modal('hide');  // model hide

                    });
                }
            })
        }

    });
</script>
