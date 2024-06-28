@extends('layouts.navigation')
@section('Owners Transaction', 'active')
@section('content')


    <div class="card card-success">
        <div class="card-header">
            <h4>Owner Transaction</h4>
            <div class="card-header-action">
                <a class="btn btn-success" id="create_" data-toggle="modal" data-target="#customer_">
                    <span class="btn-icon-wrapper pr-2"> </span>
                    Add
                </a>
            </div>
        </div>

        <form action="/owner/owner-transaction" method="get">
            @csrf
            <div class="row">
                <div class="form-group col-md-1">
                </div>
                <div class="form-group col-md-3">
                    <label>Date</label>
                    <input type="date" id="from" name="from" value="{{ $from }}" class="form-control"
                        required>
                </div>
                <div class="form-group col-md-3" style="margin-top:30px">
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>
        </form>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Owner List Name</th>
                            <th>Option</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ownertransaction as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="">{{ $row->date }}</td>
                                <td data-th="">{{ $row->owner_name }}</td>
                                <td data-th="">{{ $row->option }}</td>
                                <td data-th="">{{ $row->amount }}</td>
                                <td data-th="">{{ $row->reason }}</td>
                                <td class="col-action">
                                    <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                        data-target="#customer_" data-id="{{ $row->id }}"
                                        data-owner_name="{{ $row->owner_name }}"
                                        data-owners_list_id="{{ $row->owners_list_id }}" data-option="{{ $row->option }}"
                                        data-amount="{{ $row->amount }}" data-reason="{{ $row->reason }}"
                                        data-date="{{ $row->date }}">
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
                    <h5 class="modal-title" id="formModal">Owner Transaction </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('owner.StoreOwnertransaction') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                        <div class="form-group">
                                            <label for="property name">Owner Name<span class="text-danger">*</span></label>
                                            <select class="form-control" name="owners_list_id" id="owners_list_id" required>
                                                <option value="" disabled selected>Select Owner Name</option>
                                                @foreach ($ownerlist as $row)
                                                    <option name="owners_list_id" class="OwnerList"
                                                        id="owners_list_id_{{ $row->id }}"
                                                        value="{{ $row->id }}"
                                                        {{ $row->id == old('owners_list_id') ? 'selected' : '' }}>
                                                        {{ $row->owner_name }}</option>
                                                @endforeach
                                                <span class="text-danger">
                                                    @error('owners_list_id')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for=" name">option<span class="text-danger">*</span></label>
                                                <select class="form-control" name="option" id="option" required>
                                                    <option value="" selected >Select </option>
                                                    <option value="Debit"  >Debit</option>
                                                    <option value="Credit">Credit</option>
                                                    <span class="text-danger">
                                                        @error('option')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for=" name">Date<span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="date" class="form-control" id="date"
                                                        name="date" placeholder="" value="{{ old('date') }}"
                                                        required />
                                                    <p style="color:Tomato">
                                                        @error('date')
                                                            {{ $message }}
                                                        @enderror
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for=" name">Amount<span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="number" class="form-control" id="amount"
                                                        name="amount" placeholder="" value="{{ old('amount') }}"
                                                        required />
                                                    <p style="color:Tomato">
                                                        @error('amount')
                                                            {{ $message }}
                                                        @enderror
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for=" name">Reason<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="reason" name="reason"
                                                    placeholder="" value="{{ old('reason') }}" />
                                                <p style="color:Tomato">
                                                    @error('reason')
                                                        {{ $message }}
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
                $('#formModal').empty().append('Create Sub OwnerList');
            } else {
                $('#formModal').empty().append('Update Sub OwnerList');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#owners_list_id').val('');
            $('#option').val('');
            $('#date').val('');
            $('#amount').val('');
            $('#reason').val('');

            $('#formModal').empty().append('Create OwnerTransaction');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#option').val($(this).attr('data-option'));
            $('#date').val($(this).attr('data-date'));
            $('#amount').val($(this).attr('data-amount'));
            $('#reason').val($(this).attr('data-reason'));

            var owners_list_id = $(this).attr('data-owners_list_id');
            $('.OwnerList').attr('selected', false);
            $('#owners_list_id_' + owners_list_id).attr('selected', true);

            $('#formModal').empty().append('Update OwnerTransaction');
        });


    });
</script>
