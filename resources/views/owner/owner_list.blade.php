@extends('layouts.navigation')
@section('Owners List', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Owner List</h4>
            <div class="card-header-action">
                    <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#commdetails_">
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
                            <th>Name</th>
                            <th>Shop Name</th>
                            <th>Phone Number</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($owner_list as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="">{{ $row->owner_name }}</td>
                                <td data-th="">{{ $row->shop_name }}</td>
                                <td data-th="">{{ $row->phonenumber }}</td>
                                <td class="col-action">
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#commdetails_" data-id="{{ $row->id }}" data-owner_name="{{ $row->owner_name }}" 
                                            data-shop_name="{{ $row->shop_name }}" data-phonenumber="{{ $row->phonenumber }}">
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
    <div class="modal fade" id="commdetails_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Owners List Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('owner.StoreOwnerList') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Name</label>
                                            <div>
                                                <input type="name" class="form-control" id="name" name="name"
                                                     value="{{ old('name') }}" required />
                                                <p style="color:Tomato"> 
                                                    @error ('name'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Phone Number</label>
                                            <div>
                                                <input type="number" class="form-control" id="phonenumber" name="phonenumber" 
                                                    placeholder="phonenumber" value="{{ old('phonenumber') }}" required />
                                                <p style="color:Tomato"> 
                                                    @error ('phonenumber'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer ">Shop Name</label>
                                            <div>
                                                <input type="shop_name" class="form-control" id="shop_name" name="shop_name"
                                                     value="{{ old('shop_name') }}"  />
                                                <p style="color:Tomato"> 
                                                    @error ('shop_name'){{ $message }} 
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
            $('#commdetails_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Owner List');
            } else {
                $('#formModal').empty().append('Update Owner List');
            }
        }
        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#name').val('');
            $('#phonenumber').val('');
            $('#shop_name').val('');

            $('#formModal').empty().append('Add Owner List');
        });
        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#name').val($(this).attr('data-owner_name'));
            $('#phonenumber').val($(this).attr('data-phonenumber'));        
            $('#shop_name').val($(this).attr('data-shop_name'));        

            $('#formModal').empty().append('Update Owner List');
        });
    });
</script>
