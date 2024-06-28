@extends('layouts.navigation')
@section('service_charges', 'active')
@section('content')
    <?php
    use Carbon\Carbon;
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;

    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Service Charges</h4>
                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif


                        </div>


                        <div class="card-body">
                            <form action="/service-charge-Store" method="post" class="needs-validation" novalidate=""
                                enctype="multipart/form-data">

                                <div class="card-body form">
                                    @csrf


                                    <input type="hidden" class="form-control" name="id">

                                    <div class="row">

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Service</label>
                                                <select class="form-control selectedservice  provider"
                                                    value="{{ old('service_type_id') }}" name="service_type_id" required>
                                                    <option value="" selected>Select Service</option>
                                                    @foreach ($accountyServiceTypes as $accountyServiceType)
                                                        <option value="{{ $accountyServiceType->id }}"
                                                            @if (old('service_type_id') == $accountyServiceType->id) selected @endif>
                                                            {{ $accountyServiceType->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">
                                                    @error('service_type_id')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>


                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="number" class="form-control" id="amount" name="amount"
                                                    step="0.001" value="{{ old('amount') }}" required min="0">
                                                <span class="text-danger">
                                                    @error('amount')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>

                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Invoice No</label>
                                                <input type="text" class="form-control" name="invoice_no"
                                                    value="{{ old('invoice_no') }}" required>
                                                <span class="text-danger">
                                                    @error('invoice_no')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>

                                        </div>


                                    </div>

                                    <div class="row">


                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="date" class="form-control" name="payment_date"
                                                    value="{{ old('payment_date') }}"
                                                    max="{{ Carbon::now()->format('Y-m-d') }}" required>
                                                <span class="text-danger">
                                                    @error('payment_date')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>

                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Service Provider</label>
                                                <select class="form-control " name="service_provider_id"
                                                    value="{{ old('service_provider_id') }}"required>
                                                    <option value="" class="" disabled selected>Select
                                                        Provider</option>
                                                    @foreach ($accountServiceProviders as $accountServiceProvider)
                                                        <option
                                                            class="selectProvider pro_{{ $accountServiceProvider->service_type_id }}"
                                                            value="{{ $accountServiceProvider->id }}"
                                                            @if (old('service_provider_id') == $accountServiceProvider->id) selected @endif>
                                                            {{ $accountServiceProvider->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Month</label>
                                                <input type="month" class="form-control"
                                                    value="{{ Carbon::now()->format('Y-m') }}"
                                                    max="{{ Carbon::now()->format('Y-m') }}" name="month">
                                                <span class="text-danger">
                                                    @error('month')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                                @if (\Session::has('error'))
                                                    <span class="text-danger fade-message">{{ \Session::get('error') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Image</label>
                                                <input type="file" name="image" value="fileupload">

                                                <span class="text-danger">
                                                    @error('image')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>


                                        <div class="col-8">
                                            <div class="form-group">
                                                <label>Note</label>
                                                <textarea class="form-control" name="note" value="{{ old('note') }}"></textarea>
                                                <span class="text-danger">
                                                    @error('note')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- ------------- account departments -------------------------- --}}


                                    <h4 class="header ">Departments</h4>
                                    <div class="row">

                                        @foreach ($account_departments as $department)
                                            <div class="col-6 form-group">

                                                <div class="row">

                                                    <input type="hidden" class="form-control"
                                                        value="{{ $department->id }}" name="departments_id[]" readonly>
                                                    <input type="hidden" class="form-control"
                                                        value="{{ $department->acc_dept_id }}" name="acc_dept_id[]" readonly>

                                                    <div class="col-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $department->dept_name }}" name="departments[]"
                                                            readonly>
                                                    </div>

                                                    <div class="col-4">
                                                        <input type="number" step="0.01"
                                                            class="form-control dept_amount" value="0.00"
                                                            name="dept_charge[]" required min="0">
                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach

                                    </div>


                                    <div align="right">
                                        <button class="btn btn-danger" id="reset" type="reset">Reset</button>
                                        <button class="btn btn-success mr-1" id="add"
                                            type="submit">Submit</button>
                                    </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            $(function() {
                setTimeout(function() {
                    $('.fade-message').slideUp();
                }, 1000);
            });

        });


        $(document).on('click', '.provider', function() {
            $(".selectProvider").hide();
            service = $(".selectedservice").val();
            $(`.pro_${service}`).show();
        });


        $("#add").on('click', function(e) {

            let dept_total = 0;
            var dept_amount = document.getElementsByName('dept_charge[]');

            for (var i = 0; i < dept_amount.length; i++) {
                dept_total = Number(dept_total) + Number(dept_amount[i].value);

            }

            var amount = $("#amount").val();

            if (!(dept_total == amount)) {
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: 'Please Consider the Amount',
                });
                e.preventDefault();
            }


        });
    </script>
@endsection
