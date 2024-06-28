@extends('layouts.navigation')
@section('Accounts_details', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Accounts Details</h4>
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
                            <th >Amount</th>
                            <th >Date</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Account_details as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="">{{ $row->cat_name }}</td>
                                <td data-th="">{{ $row->sub_name }}</td>
                                <td data-th="">{{ $row->amount }}</td>
                                <td data-th="">{{ $row->date }}</td>
                                <td class="col-action">
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#customer_" data-id="{{ $row->id }}"
                                            data-catname="{{ $row->cat_name }}" data-subname="{{ $row->sub_name }}"
                                            data-amount="{{ $row->amount }}" data-date="{{ $row->date }}"
                                            data-categeory_id="{{ $row->Categeory_id }}" data-sub_categeory_id="{{ $row->sub_categeory_id }}">
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
                    <h5 class="modal-title" id="formModal">Add Accounts Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('Accounts.AddAccountsDeatils') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                        <div class="form-group">
                                            <label for="property name">Categeory Name<span class="text-danger">*</span></label>
                                            <select class="form-control" name="Categeory_id" id="Categeory_id" onchange="showSubCategory()" required>
                                                <option value="" disabled selected>Select Categeory</option>
                                                @foreach ($add_categeory as $row)
                                                    <option class="Categeory" id="categeory_id_{{$row->id}}" value="{{ $row->id }}"
                                                        {{ $row->id == old('Categeory_id') ? 'selected' : '' }}>
                                                        {{ $row->cat_name }}</option>
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
                                            <select class="form-control" name="Sub_Categeory_id" id="Sub_Categeory_id" required>
                                                <option value="" selected>Select Sub Categeory</option>
                                                @foreach ($sub_categeory as $row)
                                                    <option class="Sub_Categeory categeory_id_2_{{ $row->categeory_id }}" id="Sub_Categeory_id_{{$row->id}}" value="{{ $row->id }}"
                                                        {{ $row->id == old('Sub_Categeory_id') ? 'selected' : '' }}>
                                                        {{ $row->sub_name }}</option>
                                                @endforeach
                                                <span class="text-danger">
                                                    @error('Sub_Categeory_id')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for=" name">Amount<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="number" class="form-control" id="amount" name="amount"
                                                    placeholder="Enter the Amount" value="{{ old('amount') }}" required/>
                                                <p style="color:Tomato"> 
                                                    @error('amount'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for=" name">Date<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="date" class="form-control" id="date" name="date"
                                                    placeholder="Enter date" value="{{ old('date') }}" required/>
                                                <p style="color:Tomato"> 
                                                    @error('date'){{ $message }} 
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

        showSubCategory();

        // show model back end validate
        if (!@json($errors->isEmpty())) {
            $('#customer_').modal();

            var id = $('#id').val();

            if (id == 0) {
                $('#formModal').empty().append('Create Accounts Details');
            } else {
                $('#formModal').empty().append('Update Accounts Details');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#Categeory_id').val('');
            $('#Sub_Categeory_id').val('');
            $('#amount').val('');
            $('#date').val('');

            showSubCategory();

            $('#formModal').empty().append('Create Accounts Details');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#amount').val($(this).attr('data-amount'));
            $('#date').val($(this).attr('data-date'));

            var categeory_id = $(this).attr('data-categeory_id');
            $('.categeory').attr('selected', false);
            $('#categeory_id_' + categeory_id).attr('selected', true);

            showSubCategory();

            var Sub_Categeory_id = $(this).attr('data-sub_categeory_id');
            $('.Sub_Categeory').attr('selected', false);
            $('#Sub_Categeory_id_' + Sub_Categeory_id).attr('selected', true);

            $('#formModal').empty().append('Accounts Details');
        });
    
    });

        function showSubCategory() {
            $('.Sub_Categeory').hide();

            var categeoryId = $('#Categeory_id').val();
            console.log(categeoryId);

            if (categeoryId) {
                $('.categeory_id_2_' + categeoryId).show();
            }
        }
</script>
