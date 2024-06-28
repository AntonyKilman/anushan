@extends('layouts.navigation')
@section('Add Subcategory', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Sub Categeory</h4>
            <div class="card-header-action">
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#customer_">
                        <span class="btn-icon-wrapper pr-2"> </span>
                        Add
                    </a>
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th >Categeory Name</th>
                            <th >Sub Categeory Name</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($AddSubcategory as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="">{{ $row->categeory_name }}</td>
                                <td data-th="">{{ $row->name }}</td>
                                <td class="col-action">
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#customer_" data-id="{{ $row->id }}"
                                            data-name="{{ $row->name }}" data-categeory_id="{{ $row->categeory_id }}">
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


@endsection


@section('modal')

    {{-- create & update model --}}
    <div class="modal fade" id="customer_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Add Sub Categeory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('Accounts.AddSubcategory') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                        <div class="form-group">
                                            <label for="property name">Categeory Name<span class="text-danger">*</span></label>
                                            <select class="form-control" name="Categeory_id" id="Categeory_id" required>
                                                <option value="" disabled selected>Select Categeory</option>
                                                @foreach ($sub_categeory as $row)
                                                    <option name="Categeory_id" class="categeory" id="categeory_id_{{$row->id}}" value="{{ $row->id }}"
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for=" name">Sub Categeory Name<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="sub_name" name="sub_name"
                                                    placeholder="Enter the Sub Categeory name" value="{{ old('sub_name') }}" required/>
                                                <p style="color:Tomato"> 
                                                    @error('sub_name'){{ $message }} 
                                                    @enderror
                                                </p>
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

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function() {

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#customer_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Sub Categeory');
            } else {
                $('#formModal').empty().append('Update Sub Categeory');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#Categeory_id').val('');
            $('#sub_name').val('');

            $('#formModal').empty().append('Create Sub Categeory');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#sub_name').val($(this).attr('data-name'));

            var categeory_id = $(this).attr('data-categeory_id');
            $('.categeory').attr('selected', false);
            $('#categeory_id_' + categeory_id).attr('selected', true);

            $('#formModal').empty().append('Update Sub Categeory');
        });


    });
</script>
