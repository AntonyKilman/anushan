@extends('layouts.navigation')
@section('employee doc', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $create = false;
    $update = false;
    
    if (in_array('employeedoc.store', $Access)) {
        $create = true;
    }
    if (in_array('employeedoc-edit', $Access)) {
        $update = true;
    }
    ?>

    <div class="card card-success">
        <div class="card-header">
            <h4>Employee Documents</h4>
            <div class="card-header-action">
                @if ($create)
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#employee_doc_">
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
                            <th width="5%">No</th>
                            <th width="8%">Emp Code</th>
                            <th width="30%">Employee Name</th>
                            <th>Type</th>
                            <th width="30%" class="col-action" >Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        ?>
                        @foreach ($employee_doc as $row)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $row->emp_code }}</td>
                                <td data-th="Name">{{ $row->employee_name }} {{ $row->emp_last_name }}</td>
                                <td data-th="Name">{{ $row->name }}</td>

                                <td class="col-action">
                                    @if ($update)
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#employee_doc_" data-id="{{ $row->id }}"
                                            data-emp_name="{{ $row->employee_name }}"
                                            data-emp_id="{{ $row->employee_id }}" data-name="{{ $row->name }}">
                                            <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-edit"></i>
                                        </a>
                                    @endif

                                    <a class="btn btn-success view" title="View" data-toggle="modal"
                                        data-target="#view_employee_doc_" data-file="{{ $row->file }}"
                                        data-name="{{ $row->name }}" data-employee_name="{{ $row->employee_name }}">
                                        <i style="color:rgb(226, 210, 210);cursor: pointer" class="fas fa-eye"></i>
                                    </a>
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

    {{-- create & Update modal --}}
    <div class="modal fade" id="employee_doc_" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Employee Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" enctype="multipart/form-data" method="POST"
                        action="{{ route('employeedoc.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Employee</label>
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
                                            <label>File Type</label>
                                            <div>
                                                <select required class="form-control" name="name">
                                                    <option selected value="">Select type</option>
                                                    <option class="file_type" id="file_type_Photo"
                                                        {{ old('name') == 'Photo' ? 'selected' : '' }} value="Photo">
                                                        Photo
                                                    </option>
                                                    <option class="file_type" id="file_type_Pdf"
                                                        {{ old('name') == 'Pdf' ? 'selected' : '' }} value="Pdf">Pdf
                                                    </option>
                                                </select>
                                                <p style="color:Tomato"> @error('name'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> File<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="file" class="form-control" id="file" name="file" required />
                                                <p style="color:Tomato"> @error('file'){{ $message }} @enderror</p>
                                            </div>
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

    {{-- view modal --}}
    <div class="modal fade" id="view_employee_doc_" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="view_">View Employee Doc</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Employee Name :-</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="view_name" disabled />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> File :-</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <img src="{{ asset('assets/img/user.png') }}" id="view_image" alt=""
                                            style="max-width: 50%">

                                        <a href="" target="_blank" id="view_pdf">Open the pdf!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            $('#employee_doc_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Employee Document');
            } else {
                $('#formModal').empty().append('Update Employee Document');
            }
        }

        // emp list get
        empSearch();

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#emp_id').val('');
            $('#emp_name').val('');

            $('.file_type').attr('selected', false);

            $('#formModal').empty().append('Create Employee Document');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#emp_id').val($(this).attr('data-emp_id'));
            $('#emp_name').val($(this).attr('data-emp_name'));

            $('.file_type').attr('selected', false);

            var file_type = $(this).attr('data-name');
            $('#file_type_' + file_type).attr('selected', true);

            $('#formModal').empty().append('Update Employee Document');
        });

        // view
        $('#save-stage').on('click', '.view', function() {

            $('#view_image').hide();
            $('#view_pdf').hide();

            var name = $(this).attr('data-name');
            var emp_name = $(this).attr('data-employee_name');
            var file = $(this).attr('data-file');

            $('#view_name').val(emp_name);

            if (name == 'Photo') {
                $('#view_image').show();
                $('#view_image').attr('src', file);

            }

            if (name == 'Pdf') {
                $('#view_pdf').show();
                $('#view_pdf').attr('href', file);
            }


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
