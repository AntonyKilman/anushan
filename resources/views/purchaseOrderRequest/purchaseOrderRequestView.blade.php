@extends('layouts.navigation')
@section('purchaseRequest', 'active')
@section('content')

    <?php
        $Access = session()->get('Access');
    ?>

    <!---------------------------- Main Content start ------------------------------>
    <div class="card">

        <div class="card-header">
            <div>
                <a href="/PurchaseRequestShow" class="btn btn-warning fas fa-arrow-left">Back</a>
            </div>

            <h4 class="header">View Purchase Order Request</h4>
        </div>


        <div class="card-body">


            <div class="row">

                <div class="col-3">
                    <label>Request No</label>
                    <input type="number" id="name" value="{{ $datas->id }}" name="request_no" class="form-control"
                        readonly>
                </div>

                <div class="col-3">
                    <label>Department</label>
                    <input type="text" id="name" value="{{ $department }}" name="request_no" class="form-control"
                        readonly>
                </div>

                <div class="col-6">
                    <label>Reason</label>
                    <textarea class="form-control" id="description" name="reason" readonly>{{ $datas->reason }}</textarea>
                </div>

            </div><br><br>


            {{-- ---------------- Add Product table start ------------------------ --}}


            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Type</th>
                            <th>Quantity</th>
                            <th>Approved Quantity</th>
                            <th>Store Keeper Status</th>
                            <th>Accountant Status</th>
                            <th>Managing Director Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <input type="text" id="name" name="" class="form-control"
                                        value="{{ $item->product_name }}" readonly>
                                </td>

                                <td>
                                    <input type="text" id="name" name="quantity[]" class="form-control"
                                        value="{{ $item->quantity_type }}" readonly>
                                </td>

                                <td>
                                    <input type="number" id="name" name="quantity[]" class="form-control"
                                        value="{{ $item->quantity }}" readonly>
                                </td>

                                <td>
                                    <input type="number" id="name" name="quantity[]" class="form-control"
                                        value="{{ $item->approved_quantiy }}" readonly>
                                </td>

                                <td>
                                    <input type="text" id="" name="" class="form-control"
                                        value="{{ $item->inv_man_status }}" readonly>
                                </td>

                                <td>
                                    <input type="text" id="name" name="quantity[]" class="form-control"
                                        value="{{ $item->acc_man_status }}" readonly>
                                </td>

                                <td>
                                    <input type="text" id="name" name="quantity[]" class="form-control"
                                        value="{{ $item->man_dir_status }}" readonly>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div> <br>



        </div>
    </div>

    <!---------------------------- Main Content end ------------------------------>


@endsection
