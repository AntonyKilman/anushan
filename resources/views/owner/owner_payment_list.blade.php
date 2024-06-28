@extends('layouts.navigation')
@section('Owners Transaction Payment', 'active')
@section('content')

<div class="card card-success">
    <div class="card-header">
        <h4>Owners Transaction Details</h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Owner Name</th>
                        <th>Option</th>
                        <th>Transaction Amount</th>
                        <th>Paid Amount</th>
                        <th class="col-action">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td data-th="">{{ $row->date }}</td>
                        <td data-th="">{{ $row->owner_name }}</td>
                        <td data-th="">{{ $row->option }}</td>
                        <td data-th="">{{ $row->amount }}</td>
                        <td data-th="">{{ $row->tillPayAmount }}</td>
                        <td class="col-action">
                            @if ($row->option == 'Credit')
                                <button type="button" class="btn btn-primary pay" data-toggle="modal"
                                    data-target="#commdetails_" data-id="{{ $row->id }}" 
                                    data-tillPayableAmount="{{ $row->tillPayableAmount }}"
                                    data-owner_name="{{ $row->owner_name }}"> Pay
                                </button>
                            @else
                                <button type="button" class="btn btn-success pay" data-toggle="modal"
                                    data-target="#commdetails_" data-id="{{ $row->id }}" 
                                    data-tillPayableAmount="{{ $row->tillPayableAmount }}"
                                    data-owner_name="{{ $row->owner_name }}"> Get
                                </button>
                                    
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
<div class="modal fade" id="commdetails_" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal"> Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="signupForm" class="needs-validation" novalidate="" method="POST"
                    action="{{ route('owner.saveownertransactionpayment') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=" ">Owner Name</label>
                                        <input hidden type="text" id="owners_transaction_id" class="form-control" name="owners_transaction_id"
                                            value="{{ old('owners_transaction_id') }}" />
                                        <div>
                                            <input type="text" id="owners_transaction_name" name="owners_transaction_name" readonly
                                                class="form-control" value="{{ old('owners_transaction_name') }}"
                                                placeholder="" required />
                                            <p style="color:Tomato"> @error('owners_transaction_name'){{ $message }} @enderror</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Payable Amount</label>
                                        <div>
                                            <input type="number" id="payable_amount" name="payable_amount" readonly class="form-control"
                                                value="{{ old('payable_amount') }}" placeholder="" required />
                                            <p style="color:Tomato"> @error(''){{ $message }} @enderror</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label>Amount:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </div>
                                        </div>
                                        <input type="number" min="0" step="0.01" class="form-control" placeholder="00.00"
                                            name="pay_amount" id="pay_amount" onchange="checkAMount()">
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label>Date </label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{date('Y-m-d')}}">
                                        <div class="invalid-feedback" id=""></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" align="right">
                        <button type="submit" class="btn btn-success payment-proceed">Save</button>
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
            $('#commdetails_').modal();
        }

        // update
        $('#save-stage').on('click', '.pay', function() {

            $('.payment-proceed').prop('disabled', true);

            $('#payable_amount').val($(this).attr('data-tillPayableAmount'));
            $('#owners_transaction_id').val($(this).attr('data-id'));//hidden
            $('#owners_transaction_name').val($(this).attr('data-owner_name'));
            
            $('#pay_amount').val('');
        });

    });

    function checkAMount() {
        var pay_amount = $('#pay_amount').val() ? $('#pay_amount').val() : 0;
        var tillPayableAmount = $('#payable_amount').val() ? $('#payable_amount').val() : 0;

        var totalPay = parseFloat(pay_amount);

        if (totalPay <= tillPayableAmount) {
            $('.payment-proceed').prop('disabled', false);
        } else {
            $('.payment-proceed').prop('disabled', true);
        }
    }
</script>