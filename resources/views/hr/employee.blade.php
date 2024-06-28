@extends('layouts.navigation')
@section('employee', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $create = false;
    $update = false;
    
    if (in_array('employee.store', $Access)) {
        $create = true;
    }
    if (in_array('employee-edit', $Access)) {
        $update = true;
    }
    ?>


    <div class="card card-success">
        <div class="card-header">
            <h4>Employees</h4>
            <div class="card-header-action">
                @if ($create)
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#empAllDetails">
                        <span class="btn-icon-wrapper pr-2"> </span>
                        Add
                    </a>
                    {{-- <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#employee_">
                        <span class="btn-icon-wrapper pr-2"> </span>
                        Add
                    </a> --}}
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="10%">Emp-Code</th>
                            <th width="20%">Name</th>
                            <th width="15%">Department</th>
                            <th width="10%">Role</th>
                            <th width="15%">Employee Type</th>
                            <th width="10%">Work Type</th>
                            <th width="10%">Status</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                        ?>
                        @foreach ($employees as $row)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $row->emp_code }}</td>
                                <td>{{ $row->f_name }} {{ $row->l_name }}</td>
                                <td>{{ $row->department_name }}</td>
                                <td>{{ $row->role_name }}</td>
                                <td>{{ $row->employee_type_name }}</td>
                                <td>{{ $row->work_type_name }}</td>

                                <td data-th="Status">
                                    @if ($row->status == 'Active' || $row->status == 'active')
                                        <button data-url="{{ route('employee.change-status') }}"
                                            data-id="{{ $row->id }}" data-status="{{ $row->status }}"
                                            class="btn btn-success btn-sm w-100 changeStatus">Active</button>
                                    @else
                                        <button data-url="{{ route('employee.change-status') }}"
                                            data-id="{{ $row->id }}" data-status="{{ $row->status }}"
                                            class="btn btn-danger btn-sm w-100 changeStatus">Deactive</button>
                                    @endif
                                </td>

                                <td class="col-action">
                                    @if ($update)
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#employee_" data-id="{{ $row->id }}"
                                            data-f_name="{{ $row->f_name }}" data-l_name="{{ $row->l_name }}"
                                            data-nic="{{ $row->nic }}" data-dob="{{ $row->dob }}"
                                            data-email="{{ $row->email }}"
                                            data-department_id="{{ $row->department_id }}"
                                            data-role_id="{{ $row->role_id }}"
                                            data-employee_type_id="{{ $row->employee_type_id }}"
                                            data-work_type_id="{{ $row->work_type_id }}"
                                            data-mobile="{{ $row->mobile }}"
                                            data-office_number="{{ $row->office_number }}"
                                            data-joined_date="{{ $row->joined_date }}"
                                            data-bank_account_number="{{ $row->bank_account_number }}"
                                            data-bank_branch_name="{{ $row->bank_branch_name }}"
                                            data-address="{{ $row->address }}"
                                            data-leaving_date="{{ $row->leaving_date }}"
                                            data-leave_reason="{{ $row->leave_reason }}">
                                            <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-edit"></i>
                                        </a>
                                    @endif

                                    <br><br>
                                    <a class="btn btn-success view" title="View" data-emp_id="{{ $row->id }}">
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

    {{-- create model --}}
    <div class="modal fade" id="employee_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('employee.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> First Name<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="f_name" name="f_name"
                                                    value="{{ old('f_name') }}" required />
                                                <p style="color:Tomato"> @error('f_name'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Last Name</label>
                                            <div>
                                                <input type="text" class="form-control" id="l_name" name="l_name"
                                                    value="{{ old('l_name') }}" />
                                                <p style="color:Tomato"> @error('l_name'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <div>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email') }}" />
                                                <p style="color:Tomato"> @error('email'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Date of Birth</label>
                                            <div>
                                                <input type="Date" class="form-control" id="dob" name="dob"
                                                    value="{{ old('dob') }}" max="9999-12-31"/>
                                                <p style="color:Tomato"> @error('dob'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile Number<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="number" class="form-control" id="mobile" name="mobile"
                                                    value="{{ old('mobile') }}" required max="9999999999"/>
                                                    <div class="invalid-feedback"> Mobile Number Length Maximum 10 </div>
                                                <p style="color:Tomato"> @error('mobile'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Office Number</label>
                                            <div>
                                                <input type="number" class="form-control" id="office_number" name="office_number"
                                                    value="{{ old('office_number') }}" />
                                                <p style="color:Tomato"> @error('office_number'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Address</label>
                                            <div>
                                                <textarea name="address" id="address" class="form-control" cols="" rows="3">{{ old('address') }}</textarea>
                                                <p style="color:Tomato"> @error('address'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> NIC<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="nic" name="nic"
                                                    value="{{ old('nic') }}" required maxlength="12" minlength="10" />
                                                <p style="color:Tomato"> @error('nic'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Role<span class="text-danger">*</span></label>
                                            <div>
                                                <select required class="form-control" name="role_id">
                                                    <option  selected value="">Select role</option>
                                                    @foreach ($roles as $row)
                                                        <option class="class_role" id="role_id_{{ $row->id }}"
                                                            value="{{ $row->id }}"
                                                            {{ old('role_id') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->user_role }}</option>
                                                    @endforeach
                                                </select>
                                                <p style="color:Tomato"> @error('role_id'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Department<span class="text-danger">*</span></label>
                                            <div>
                                                <select required class="form-control" name="department_id">
                                                    <option  selected value="">Select department</option>
                                                    @foreach ($deparments as $row)
                                                        <option class="class_deparment"
                                                            id="department_id_{{ $row->id }}"
                                                            value="{{ $row->id }}"
                                                            {{ old('department_id') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                                <p style="color:Tomato"> @error('department_id'){{ $message }}
                                                    @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Employee Type<span class="text-danger">*</span></label>
                                            <div>
                                                <select required class="form-control" name="employee_type_id">
                                                    <option  selected value="">Select employee type</option>
                                                    @foreach ($employee_type as $row)
                                                        <option class="class_employee_type"
                                                            id="employee_type_id_{{ $row->id }}"
                                                            value="{{ $row->id }}"
                                                            {{ old('employee_type_id') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->type }}</option>
                                                    @endforeach
                                                </select>
                                                <p style="color:Tomato"> @error('employee_type_id'){{ $message }}
                                                    @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Work Type<span class="text-danger">*</span></label>
                                            <div>
                                                <select required class="form-control" name="work_type_id">
                                                    <option  selected value="">Select work type</option>
                                                    @foreach ($work_type as $row)
                                                        <option class="class_work_type"
                                                            id="work_type_id_{{ $row->id }}"
                                                            value="{{ $row->id }}"
                                                            {{ old('work_type_id') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                                <p style="color:Tomato"> @error('work_type_id'){{ $message }}
                                                    @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Join Date</label>
                                            <div>
                                                <input type="date" class="form-control" id="joined_date" name="joined_date"
                                                    value="{{ old('joined_date') }}" max="9999-12-31"/>
                                                <p style="color:Tomato"> @error('joined_date'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Account Number(Commercial Bank)</label>
                                            <div>
                                                <input type="number" class="form-control" id="bank_account_number" name="bank_account_number"
                                                    value="{{ old('bank_account_number') }}" max="9999999999"/>
                                                    <div class="invalid-feedback">Bank Account Length Maximum 10 </div>
                                                <p style="color:Tomato"> @error('bank_account_number'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Branch Name</label>
                                            <div>
                                                <input type="text" class="form-control" id="bank_branch_name" name="bank_branch_name"
                                                    value="{{ old('bank_branch_name') }}" maxlength="20"/>
                                                <p style="color:Tomato"> @error('bank_branch_name'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Leaving Date</label>
                                            <div>
                                                <input type="date" class="form-control" id="leaving_date" name="leaving_date"
                                                    value="{{ old('leaving_date') }}" max="9999-12-31"/>
                                                <p style="color:Tomato"> @error('leaving_date'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Leaving Reason</label>
                                            <div>
                                                <textarea name="leave_reason" id="leave_reason" class="form-control" cols="" rows="3">{{ old('leave_reason') }}</textarea>
                                                <p style="color:Tomato"> @error('leave_reason'){{ $message }} @enderror</p>
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

    {{-- empAllDetails --}}
    <div class="modal fade" id="empAllDetails" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allData">Employee All Data Create</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('employee.storeEmpAllData') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> First Name<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="f_name_" name="f_name_"
                                                    value="{{ old('f_name_') }}" required />
                                                <p style="color:Tomato"> @error('f_name_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Last Name</label>
                                            <div>
                                                <input type="text" class="form-control" id="l_name_" name="l_name_"
                                                    value="{{ old('l_name_') }}" />
                                                <p style="color:Tomato"> @error('l_name_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <div>
                                                <input type="email" class="form-control" id="email_" name="email_"
                                                    value="{{ old('email_') }}" />
                                                <p style="color:Tomato"> @error('email_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Date of Birth</label>
                                            <div>
                                                <input type="Date" class="form-control" id="dob_" name="dob_"
                                                    value="{{ old('dob_') }}" max="9999-12-31"/>
                                                <p style="color:Tomato"> @error('dob_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mobile Number<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="number" class="form-control" id="mobile_" name="mobile_"
                                                    value="{{ old('mobile_') }}" required max="9999999999"/>
                                                    <div class="invalid-feedback"> Mobile Number Length Maximum 10 </div>
                                                <p style="color:Tomato"> @error('mobile_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Address</label>
                                            <div>
                                                <textarea name="address_" id="address_" class="form-control" cols="" rows="1">{{ old('address_') }}</textarea>
                                                <p style="color:Tomato"> @error('address_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> NIC<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="nic_" name="nic_"
                                                    value="{{ old('nic_') }}" required maxlength="12" minlength="10" />
                                                <p style="color:Tomato"> @error('nic_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Role<span class="text-danger">*</span></label>
                                            <div>
                                                <select required class="form-control" name="role_id_">
                                                    <option  selected value="">Select role</option>
                                                    @foreach ($roles as $row)
                                                        <option value="{{ $row->id }}"
                                                            {{ old('role_id_') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->user_role }}</option>
                                                    @endforeach
                                                </select>
                                                <p style="color:Tomato"> @error('role_id_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Department<span class="text-danger">*</span></label>
                                            <div>
                                                <select required class="form-control" name="department_id_">
                                                    <option  selected value="">Select department</option>
                                                    @foreach ($deparments as $row)
                                                        <option value="{{ $row->id }}"
                                                            {{ old('department_id_') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                                <p style="color:Tomato"> @error('department_id_'){{ $message }}
                                                    @enderror</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Employee Type<span class="text-danger">*</span></label>
                                            <div>
                                                <select required class="form-control" name="employee_type_id_">
                                                    <option  selected value="">Select employee type</option>
                                                    @foreach ($employee_type as $row)
                                                        <option value="{{ $row->id }}"
                                                            {{ old('employee_type_id_') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->type }}</option>
                                                    @endforeach
                                                </select>
                                                <p style="color:Tomato"> @error('employee_type_id_'){{ $message }}
                                                    @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Work Type<span class="text-danger">*</span></label>
                                            <div>
                                                <select required class="form-control" name="work_type_id_">
                                                    <option  selected value="">Select work type</option>
                                                    @foreach ($work_type as $row)
                                                        <option value="{{ $row->id }}"
                                                            {{ old('work_type_id_') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                                <p style="color:Tomato"> @error('work_type_id_'){{ $message }}
                                                    @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Join Date</label>
                                            <div>
                                                <input type="date" class="form-control" id="joined_date_" name="joined_date_"
                                                    value="{{ old('joined_date_') }}" max="9999-12-31"/>
                                                <p style="color:Tomato"> @error('joined_date_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Account Number(Commercial Bank)</label>
                                            <div>
                                                <input type="number" class="form-control" id="bank_account_number_" name="bank_account_number_"
                                                    value="{{ old('bank_account_number_') }}" max="9999999999"/>
                                                    <div class="invalid-feedback">Bank Account Length Maximum 10 </div>
                                                <p style="color:Tomato"> @error('bank_account_number_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Branch Name</label>
                                            <div>
                                                <input type="text" class="form-control" id="bank_branch_name_" name="bank_branch_name_"
                                                    value="{{ old('bank_branch_name_') }}" maxlength="20"/>
                                                <p style="color:Tomato"> @error('bank_branch_name_'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Basic Salary<span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="number" name="basic_salary" min="0.00" step="100" required id="basic_salary" class="form-control"
                                                        required value="{{ old('basic_salary') }}">
                                                </div>
                                                <p style="color:Tomato"> @error('basic_salary'){{ $message }} @enderror
                                                </p>
                                            </div>
                                        </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>File Type</label>
                                            <div>
                                                <select class="form-control" name="file_type">
                                                    <option selected value="">Select type</option>
                                                    <option {{ old('file_type') == 'Photo' ? 'selected' : '' }} value="Photo">
                                                        Photo
                                                    </option>
                                                    <option {{ old('file_type') == 'Pdf' ? 'selected' : '' }} value="Pdf">Pdf
                                                    </option>
                                                </select>
                                                <p style="color:Tomato"> @error('file_type'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> File (pdf & photo)</span></label>
                                            <div>
                                                <input type="file" class="form-control" id="file" name="file" />
                                                <p style="color:Tomato"> @error('file'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
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

                        <div class="form-group" align="right">
                            <br>
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     {{-- view modal --}}
    <div class="modal fade" id="view_employee" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="view_">View Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Employee Code :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_emp_code" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> First Name :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_f_name" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Last Name :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_l_name" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Email :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_email" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Date of Birth :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_dob" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Mobile Number :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_mobile" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Address :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_address" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> NIC :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_nic" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Role :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_role_name" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Department :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_department_name" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Employee Type :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_employee_type_name" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Work Type :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_work_type_name" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Join Date :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_joined_date" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Account Number(Commercial Bank) :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_bank_account_number" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Basic Salary :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_basic_salary" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Leaving Date :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_leaving_date" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Leaving Reason :-</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control view-data" id="view_leave_reason" disabled />
                                    </div>
                                </div>

                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="background-color:#ecf8f3;" class="col-md-12">
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
                                                            <input type="number"  min="0.00" step="100"  value="0.00" class="form-control view_allowance_val" id="view_allowance_val_{{ $row->id }}" disabled/>
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
                            <hr>
                        </div>
                    </div>
                    <div class="form-group" align="right">
                        <br>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function() {

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            var whatSession = "{{ session('DataSave') }}";

            if (whatSession == 'EmpDataOnlySave') {
                var id = $('#id').val();
                if (id == 0) {
                    $('#formModal').empty().append('Create Employee');
                } else {
                    $('#formModal').empty().append('Update Employee');
                }
                $('#employee_').modal();
            } else { // AllDataSave
                $('#empAllDetails').modal();
            }
        }

        $(':input[type="number"]').click(function (e) { 
            $(this).select();
        });

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#f_name').val('');
            $('#l_name').val('');
            $('#email').val('');
            $('#dob').val('');
            $('#nic').val('');
            $('#mobile').val('');
            $('#joined_date').val('');
            $('#bank_account_number').val('');
            $('#bank_branch_name').val('');
            $('#address').val('');
            $('#leaving_date').val('');
            $('#leave_reason').val('');

            $('.class_role').attr('selected', false);
            $('.class_deparment').attr('selected', false);
            $('.class_employee_type').attr('selected', false);
            $('.class_work_type').attr('selected', false);

            $('#formModal').empty().append('Create Employee');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#f_name').val($(this).attr('data-f_name'));
            $('#l_name').val($(this).attr('data-l_name'));
            $('#email').val($(this).attr('data-email'));
            $('#dob').val($(this).attr('data-dob'));
            $('#nic').val($(this).attr('data-nic'));
            $('#mobile').val($(this).attr('data-mobile'));
            $('#joined_date').val($(this).attr('data-joined_date'));
            $('#bank_account_number').val($(this).attr('data-bank_account_number'));
            $('#bank_branch_name').val($(this).attr('data-bank_branch_name'));
            $('#address').val($(this).attr('data-address'));
            $('#leaving_date').val($(this).attr('data-leaving_date'));
            $('#leave_reason').val($(this).attr('data-leave_reason'));

            var role_id = $(this).attr('data-role_id');
            var department_id = $(this).attr('data-department_id');
            var employee_type_id = $(this).attr('data-employee_type_id');
            var work_type_id = $(this).attr('data-work_type_id');

            $('#role_id_' + role_id).attr('selected', true);
            $('#department_id_' + department_id).attr('selected', true);
            $('#employee_type_id_' + employee_type_id).attr('selected', true);
            $('#work_type_id_' + work_type_id).attr('selected', true);

            $('#formModal').empty().append('Update Employee');
        });

        // change status
        $('#save-stage').on('click', '.changeStatus', function() {
            var id = $(this).attr('data-id');
            var url = $(this).attr('data-url');
            var status = $(this).attr('data-status');

            swal({
                    title: 'Are you sure?',
                    text: 'Change Employee Status !',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: url,
                            method: 'get',
                            data: {
                                status: status,
                                id: id
                            },
                            success: function(res) {
                                console.log(res);
                                swal('Poof! Change Employee Status!', {
                                    icon: 'success',
                                    timer: 1000,
                                });
                                location.reload();
                            }
                        });
                    }
                });

        });

        // view
        $('#save-stage').on('click', '.view', function() {
            var emp_id = $(this).attr('data-emp_id');

            $('.view-data').val('');
            $('.view_allowance_val').val(0.00);

            $.ajax({
                type: "get",
                url: "/emp/view-data",
                data: {
                    emp_id : emp_id
                },
                dataType: "",
                success: function (res) {
                    var data = res.data;
                    var allow_amounts = res.allow_amounts;
                    var leave_counts = res.leave_counts;

                    $('#view_emp_code').val(data.emp_code);
                    $('#view_f_name').val(data.f_name);
                    $('#view_l_name').val(data.l_name);
                    $('#view_email').val(data.email);
                    $('#view_dob').val(data.dob);
                    $('#view_nic').val(data.nic);
                    $('#view_mobile').val(data.mobile);
                    $('#view_joined_date').val(data.joined_date);
                    var account = (data.bank_account_number != null ? data.bank_account_number : '')  + (data.bank_branch_name != null ? ' (' + data.bank_branch_name + ')' : '');
                    $('#view_bank_account_number').val(account);
                    $('#view_address').val(data.address);
                    $('#view_role_name').val(data.role_name);
                    $('#view_department_name').val(data.department_name);
                    $('#view_employee_type_name').val(data.employee_type_name);
                    $('#view_work_type_name').val(data.work_type_name);
                    $('#view_basic_salary').val(data.basic_salary);
                    $('#view_leaving_date').val(data.leaving_date);
                    $('#view_leave_reason').val(data.leave_reason);

                    allow_amounts.forEach(element => {
                        $('#view_allowance_val_' + element.allowance_type_id).val(element.amount);
                    });

                    $('#view_employee').modal('show');
        
                }
            });


        });

    });
</script>
