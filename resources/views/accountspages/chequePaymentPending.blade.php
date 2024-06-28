@extends('layouts.navigation')
@section('cheque_pendings','active')
@section('content')
    <?php
    $Access = session()->get('Access');


    if (in_array('inventory.sellerTypeAddProcess', $Access)) {
    $sellerTypeAdd=true;
    }


    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Cheque Pending</h4>

                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Account</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Cheque No</th>
                                            <th>Cheque Date</th>
                                            <th>Date</th>
                                            <th>Note</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($accountChequePayments as $accountChequePayment)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $accountChequePayment->account_no }}</td>
                                                <td style="text-align: right">{{ $accountChequePayment->credit }}</td>
                                                <td style="text-align: right">{{ $accountChequePayment->debit }}</td>
                                                <td>{{ $accountChequePayment->cheque_no }}</td>
                                                <td>{{ $accountChequePayment->cheque_date }}</td>
                                                <td>{{ $accountChequePayment->date }}</td>
                                                <td>{{ $accountChequePayment->note }}</td>
                                                <td>
                                                    <button data-toggle="modal"
                                                    data-id = "{{ $accountChequePayment->id }}"
                                                    data-credit = "{{ $accountChequePayment->credit }}"
                                                    data-debit = "{{ $accountChequePayment->debit }}"
                                                    data-cheque_no = "{{ $accountChequePayment->cheque_no }}"
                                                    data-cheque_date = "{{ $accountChequePayment->cheque_date }}"

                                                        data-target="#checkPayment" title="edit"
                                                        class="btn btn-primary btn-edit"><i
                                                            class="far fa-edit"></i></button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>


                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
<script>





         $('.btn-edit').on('click', function() {
            var id = $(this).attr('data-id');
            var credit = $(this).attr('data-credit');
            var debit = $(this).attr('data-debit');
            var cheque_no = $(this).attr('data-cheque_no');
            var cheque_date = $(this).attr('data-cheque_date');

            $('#id').val(id);
            $('#credit').val(credit);
            $('#debit').val(debit);
            $('#cheque_no').val(cheque_no);
            $('#cheque_date').val(cheque_date);

        });

</script>

@endsection




<!--modal-->
<div class="modal fade" id="checkPayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cheque Pending</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main Content -->
                <form action="/cheque-payment-pending-update" method="post" class="needs-validation" novalidate="">

                    <div class="card-body form">
                        @csrf


                        <input type="hidden" class="form-control" name="id" id="id">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Credit</label>
                                    <input type="number" class="form-control" name="credit" id="credit" step="0.001"
                                         readonly>

                                    </select>
                                    <span class="text-danger">@error('credit')
                                            {{ $message }}
                                        @enderror</span>


                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Debit</label>
                                    <input type="number" class="form-control" name="debit" id="debit" step="0.001"
                                         readonly>
                                    <span class="text-danger">@error('amount')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>
                        </div>



                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Cheque No</label>
                                    <input type="number" class="form-control" name="cheque_no" id="cheque_no" readonly>
                                    <span class="text-danger">@error('cheque_no')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Cheque Date</label>
                                    <input type="date" class="form-control" name="cheque_date" id="cheque_date" readonly>
                                    <span class="text-danger">@error('cheque_date')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>
                        </div>

                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="date" id="date" max="{{now()->format('Y-m-d')}}" required>
                                    <span class="text-danger">@error('date')
                                            {{ $message }}
                                        @enderror</span>
                                </div>

                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control ma" name="editType" id="type" required>
                                        <option value="" disabled selected>Select status </option>
                                        <option value="0" >Pending</option>
                                        <option value="1" >Success</option>
                                        <option value="2" >Return</option>
                                        <option value="3" >Bounse</option>

                                    </select>
                                    <span class="text-danger">@error('editType')
                                            {{ $message }}
                                        @enderror</span>
                                </div>


                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" id="description"></textarea>
                                <span class="text-danger">@error('description')
                                        {{ $message }}
                                    @enderror</span>
                            </div>
                        </div>
                        </div>


                        <div align="right">
                            <button class="btn btn-danger" id="reset" type="reset">Reset</button>
                            <button class="btn btn-success mr-1" id="add" type="submit">Submit</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<!-- modal-->
