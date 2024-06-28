@extends('layouts.navigation')
@section('department', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $create = false;
    $update = false;
    
    if (in_array('department.store', $Access)) {
        $create = true;
    }
    if (in_array('department-edit', $Access)) {
        $update = true;
    }
    ?>

    <div class="card card-success">
        <div class="card-header">
            <h4>Departments</h4>
            <div class="card-header-action">
                @if ($create)
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#department_">
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
                            <th width="20%">Name</th>
                            <th width="15%">Code</th>
                            <th width="15%">Property</th>
                            <th>Description</th>
                            <th width="10%">Status</th>
                            <th width="15%" class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($department as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="Name">{{ $row->name }}</td>
                                <td data-th="Code">{{ $row->code }}</td>
                                <td data-th="Property">{{ $row->pro_name }}</td>
                                <td data-th="Description">{{ $row->description }}</td>
                                <td data-th="Status">
                                    @if ($row->status == 'Active' || $row->status == 'active')
                                        <button data-url="{{ route('department.change-status') }}"
                                            data-id="{{ $row->id }}" data-status="{{ $row->status }}"
                                            class="btn btn-success btn-sm w-100 changeStatus">Active</button>
                                    @else
                                        <button data-url="{{ route('department.change-status') }}"
                                            data-id="{{ $row->id }}" data-status="{{ $row->status }}"
                                            class="btn btn-danger btn-sm w-100 changeStatus">Deactive</button>
                                    @endif
                                </td>

                                <td class="col-action">
                                    @if ($update)
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#department_" data-id="{{ $row->id }}"
                                            data-name="{{ $row->name }}" data-property_id="{{ $row->property_id }}"
                                            data-description="{{ $row->description }}">
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
    <div class="modal fade" id="department_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="needs-validation" novalidate="" id="signupForm" method="POST"
                        action="{{ route('department.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                        <div class="form-group">
                                            <label for=""> Name<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter the  name" value="{{ old('name') }}" required
                                                    data-parsley-pattern="[a-zA-Z]+$" data-parsley-trigger="keyup" />
                                                <p style="color:Tomato"> @error('name'){{ $message }} @enderror</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Property</label>
                                            <div>
                                                <select required class="form-control" name="property_id">
                                                    <option  selected value="">Select property</option>
                                                    @foreach ($property as $row)
                                                        <option class="class_property" id="property_{{ $row->id }}"
                                                            value="{{ $row->id }}"
                                                            {{ old('property_id') == $row->id ? 'selected' : '' }}>
                                                            {{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <p style="color:Tomato"> @error('property_id'){{ $message }} @enderror
                                            </p>
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

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function() {

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#department_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Department');
            } else {
                $('#formModal').empty().append('Update Department');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#name').val('');
            $('#description').val('');

            $('.class_property').attr('selected', false);

            $('#formModal').empty().append('Create Department');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#name').val($(this).attr('data-name'));
            $('#description').val($(this).attr('data-description'));

            $('.class_property').attr('selected', false);
            var property_id = $(this).attr('data-property_id');
            $('#property_' + property_id).attr('selected', true);

            $('#formModal').empty().append('Update Department');
        });

        // change status
        $('#save-stage').on('click', '.changeStatus', function() {
            var id = $(this).attr('data-id');
            var url = $(this).attr('data-url');
            var status = $(this).attr('data-status');

            swal({
                    title: 'Are you sure?',
                    text: 'Change Deparment Status !',
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
                                swal('Poof! Change Deparment Status!', {
                                    icon: 'success',
                                    timer: 1000,
                                });
                                location.reload();
                            }
                        });
                    }
                });

        });

    });
</script>
