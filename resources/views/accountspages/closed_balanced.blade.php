@extends('layouts.navigation')
@section('Closed Blance', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Closed Blance</h4>
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
                            <th >Date</th>
                            <th >Amount</th>
                            <th >Adjustment Amount</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($AddcloseBlance as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td data-th="">{{ $row->date }}</td>
                                <td data-th="">{{ $row->amount }}</td>
                                <td data-th="">{{ $row->adjustment_amount }}</td>
                                <td class="col-action">
                                        <a class="btn btn-primary edit" title="Edit" data-toggle="modal"
                                            data-target="#customer_" data-id="{{ $row->id }}"
                                            data-date="{{ $row->date }}" data-amount="{{ $row->amount }}" data-adjustamount="{{ $row->adjustment_amount }}">
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
                    <h5 class="modal-title" id="formModal">Close Balance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                        action="{{ route('Accounts.AddclosedBalance') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                        <div class="form-group">
                                            <label for=" name">Date<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="date" class="form-control" id="date" name="Date"
                                                    placeholder="Enter date" value="{{ old('date') }}" required/>
                                                <p style="color:Tomato"> 
                                                    @error('Date'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for=" name">Amount<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="number" class="form-control" id="amount" name="Amount"
                                                    placeholder="Enter the Amount" value="{{ old('amount') }}" required/>
                                                <p style="color:Tomato"> 
                                                    @error('Amount'){{ $message }} 
                                                    @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for=" name">Adjustment Amount<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="number" class="form-control" id="Adjustamount" name="Adjustment_Amount"
                                                    placeholder="Enter the Amount" value="{{ old('adjustment_amount') }}" required/>
                                                <p style="color:Tomato"> 
                                                    @error('Adjustamount'){{ $message }} 
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
                $('#formModal').empty().append('Create Closed Balance');
            } else {
                $('#formModal').empty().append('Update Closed Balance');
            }
        }

        // create
        $('#create_').on('click', function() {
            $('#id').val(0);
            $('#date').val('');
            $('#amount').val('');
            $('#Adjustamount').val('');

            $('#formModal').empty().append('Create Closed Balance');
        });

        // update
        $('#save-stage').on('click', '.edit', function() {
            $('#id').val($(this).attr('data-id'));
            $('#date').val($(this).attr('data-date'));
            $('#amount').val($(this).attr('data-amount'));
            $('#Adjustamount').val($(this).attr('data-adjustamount'));

            $('#formModal').empty().append('Update Closed Balance');
        });

    });
</script>
