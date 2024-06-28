@extends('layouts.navigation')
@section('good_receive', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    use Carbon\Carbon;
    ?>

    <!-- Main Content -->

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Create Good Recive</h4>

                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif
                        </div>
                        <form action="/purchase-boucher-add-process" method="post" class="needs-validation" novalidate=""
                            enctype="multipart/form-data">
                            @csrf

                            {{-- card body start --}}
                            <div class="card-body" style="width:100%">
                                <input type="hidden" id="pat" value="^\d+(\.\d)?\d*$">
                                {{-- first row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Bill No</label>
                                        <input type="text" id="bill_no" name="bill_no" value="{{ old('bill_no') }}"
                                            class="form-control">
                                        @error('bill_no')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Total Payment</label>
                                        <input type="text" id="amount" name="amount" value="{{ old('amount') }}"
                                            pattern="^\d+(\.\d)?\d*$" class="form-control" required>
                                        @error('amount')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Supplier </label>
                                        <select class="form-control" name="seller_id" required>
                                            <option value="" disabled selected>Select Supplier</option>
                                            @foreach ($sellers as $seller)
                                                <option value="{{ $seller->id }}"
                                                    {{ $seller->id == old('seller_id') ? 'selected' : '' }}>
                                                    {{ $seller->seller_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('seller_id')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- second row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">

                                        <label>Cheque Payment</label>
                                        <input type="text" id="cheque_amount" value="{{ old('cheque_amount') }}"
                                            name="cheque_amount" pattern="^\d+(\.\d)?\d*$" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cheque No</label>
                                        <input type="text" id="cheque_no" name="cheque_no"
                                            value="{{ old('cheque_no') }}" class="form-control">
                                        @error('cheque_no')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cheque Date</label>
                                        <input type="date" id="cheque_date" name="cheque_date"
                                            value="{{ old('cheque_date') }}" class="form-control">
                                    </div>
                                </div>

                                {{-- third row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Online/Card Payment</label>
                                        <input type="text" value="{{ old('online_amount') }}" id="online_amount"
                                            name="online_amount" pattern="^\d+(\.\d)?\d*$" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Reference No</label>
                                        <input type="text" id="reference_no" name="reference_no"
                                            value="{{ old('reference_no') }}" class="form-control">
                                    </div>
                                </div>

                                {{-- fourth row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Credit Payment</label>
                                        <input type="text" id="credit_amount" name="credit_amount"
                                            value="{{ old('credit_amount') }}" pattern="^\d+(\.\d)?\d*$"
                                            class="form-control">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Cash Payment</label>
                                        <input type="text" id="cash_amount" name="cash_amount"
                                            value="{{ old('cash_amount') }}" pattern="^\d+(\.\d)?\d*$"
                                            class="form-control">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Purchase Date</label>
                                        <input type="date" name="date" value="{{ old('date') }}"
                                            class="form-control" max="{{ Carbon::now()->format('Y-m-d') }}" required>
                                    </div>

                                </div>

                                {{-- fifth row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Image 1</label>
                                        <input type="file" id="img_1" name="img_1" class="form-control">
                                        <span id="er_img_3">Max 2MB<br></span>
                                        @error('img_1')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Image 2</label>
                                        <input type="file" id="img_2" name="img_2" class="form-control">
                                        <span id="er_img_3">Max 2MB<br></span>
                                        @error('img_2')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Image 3</label>
                                        <input type="file" id="img_3" name="img_3" class="form-control">
                                        <span id="er_img_3">Max 2MB<br></span>
                                        @error('img_3')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                            </div>
                            {{-- card body end --}}
                            <div class="card-footer text-right">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button id="submit" class="btn btn-success">Submit</button>
                            </div>

                        </form>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $("#submit").on("click", function(e) {
           
            let online_amount = $("#online_amount").val();
            let cheque_amount = $("#cheque_amount").val();
            let credit_amount = $("#credit_amount").val();
            let cash_amount = $("#cash_amount").val();
            let amount = $("#amount").val();

            let total = Number(online_amount) + Number(cheque_amount) + Number(credit_amount) + Number(cash_amount);

            if (!(amount == total)) {
                alert("Please Consider the amount");
                e.preventDefault();
            }

        });
    </script>
@endsection
