@extends('layouts.navigation')
@section('good_receive', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Update Good Receive</h4>

                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif
                        </div>
                        <form action="/purchase-boucher-update-process" method="post" class="needs-validation"
                            novalidate="" style="width:100%" enctype="multipart/form-data">
                            @csrf

                            {{-- card body start --}}
                            <div class="card-body">
                                <input type="hidden" name="id" value="{{ $purchase_order->id }}">
                                <input type="hidden" id="pat" value="^\d+(\.\d)?\d*$">
                                {{-- first row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Bill No</label>
                                        <input type="text" id="bill_no" name="bill_no"
                                            value="{{ $purchase_order->pur_ord_bill_no }}" class="form-control">
                                        @error('bill_no')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Total Amount</label>
                                        <input type="text" id="amount" name="amount"
                                            value="{{ $purchase_order->pur_ord_amount }}" pattern="^\d+(\.\d)?\d*$"
                                            class="form-control" readonly>
                                        @error('amount')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Seller</label>
                                        <select class="form-control" name="seller_id" required>
                                            <option value="" disabled>Select Seller</option>
                                            @foreach ($sellers as $seller)
                                                <option value="{{ $seller->id }}"
                                                    {{ $purchase_order->seller_id == $seller->id ? 'selected' : '' }}>
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
                                        <label>Cheque Amount</label>
                                        <input type="text" id="cheque_amount"
                                            value="{{ $purchase_order->pur_ord_cheque }}" name="cheque_amount"
                                            pattern="^\d+(\.\d)?\d*$" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cheque No</label>
                                        <input type="text" value="{{ $purchase_order->pur_ord_cheque_no }}"
                                            id="cheque_no" name="cheque_no" class="form-control">
                                        @error('cheque_no')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cheque Date</label>
                                        <input type="date" value="{{ $purchase_order->pur_ord_cheque_date }}"
                                            id="cheque_date" name="cheque_date" class="form-control">
                                    </div>
                                </div>

                                {{-- third row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Online/Card Amount</label>
                                        <input type="text" value="{{ $purchase_order->pur_ord_online_or_card }}"
                                            id="online_amount" name="online_amount" pattern="^\d+(\.\d)?\d*$"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Reference No</label>
                                        <input type="text" value="{{ $purchase_order->pur_ord_reference_no }}"
                                            id="reference_no" name="reference_no" class="form-control">
                                    </div>
                                </div>

                                {{-- fourth row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Credit Amount</label>
                                        <input type="text" value="{{ $purchase_order->pur_ord_credit }}"
                                            id="credit_amount" name="credit_amount" value="0" pattern="^\d+(\.\d)?\d*$"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cash Amount</label>
                                        <input type="text" value="{{ $purchase_order->pur_ord_cash }}" id="cash_amount"
                                            name="cash_amount" value="0" pattern="^\d+(\.\d)?\d*$"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Purchase Date</label>
                                        <input type="date" name="date" class="form-control"
                                            value="{{ $purchase_order->date }}" required>
                                    </div>
                                </div>

                                {{-- fifth row --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Image 1</label><br>
                                        <img src="/bill/{{ $purchase_order->bill_img_1 }}" class="css-class"
                                            alt="Null"
                                            style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
                                        <input type="file" id="img_1" name="img_1" class="form-control">
                                        <span id="er_img_3">Max 2MB<br></span>
                                        @error('img_1')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Image 2</label><br>
                                        <img src="/bill/{{ $purchase_order->bill_img_2 }}" class="css-class"
                                            alt="Null"
                                            style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
                                        <input type="file" id="img_2" name="img_2" class="form-control">
                                        <span id="er_img_3">Max 2MB<br></span>
                                        @error('img_2')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Image 3</label><br>
                                        <img src="/bill/{{ $purchase_order->bill_img_3 }}" class="css-class"
                                            alt="Null"
                                            style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
                                        <input type="file" id="img_3" name="img_3" class="form-control">
                                        <span id="er_img_3">Max 2MB<br></span>
                                        @error('img_3')
                                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                            </div>
                            <div class="card-footer text-right">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button id="submit" class="btn btn-success">Submit</button>
                            </div>
                    </div>

                    {{-- card body end --}}



                    </form>

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
