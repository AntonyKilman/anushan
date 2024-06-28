@extends('layouts.navigation')
@section('allowance_type', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    $create = false;
    $update = false;
    
    if (in_array('hr.allowanceType.store', $Access)) {
        $create = true;
    }
    if (in_array('hr.allowanceType-edit', $Access)) {
        $update = true;
    }
    ?>

    <div class="card card-success">
        <div class="card-header">
            <h4>Allowance Type</h4>
            <div class="card-header-action">
                @if ($create)
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#allowance_type_">
                        <span class="btn-icon-wrapper pr-2"> </span>
                        Add
                    </a>
                @endif
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="30%">Name</th>
                            <th>Description</th>
                            <th width="15%" class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allowance_type as $row)
                            <tr>
                                <td data-th="Name">{{ $row->name }}</td>
                                <td data-th="Description">{{ $row->description }}</td>

                                <td class="col-action">
                                    @if ($update)
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#allowance_type_" data-id="{{ $row->id }}"
                                            data-name="{{ $row->name }}" data-description="{{ $row->description }}">
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
    <div class="modal fade" id="allowance_type_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Allowance Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('hr.allowanceType.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                        <div class="form-group">
                                            <label for="property name"> Name<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter the  name" value="{{ old('name') }}" required />
                                                <p style="color:Tomato"> @error('name'){{ $message }} @enderror</p>
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

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#allowance_type_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Allowance Type');
            } else {
                $('#formModal').empty().append('Update Allowance Type');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#name').val('');
            $('#description').val('');

            $('#formModal').empty().append('Create Allowance Type');
        });

        // update
        $('.edit').on('click', function() {
            $('#id').val($(this).attr('data-id'));
            $('#name').val($(this).attr('data-name'));
            $('#description').val($(this).attr('data-description'));

            $('#formModal').empty().append('Update Allowance Type');
        });

        
    });
</script>
