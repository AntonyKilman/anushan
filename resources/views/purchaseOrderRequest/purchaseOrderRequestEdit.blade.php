@extends('layouts.navigation')
@section('purchaseRequest', 'active')
@section('content')

    <?php
        $Access = session()->get('Access');
    ?>

    <!---------------------------- Main Content start ------------------------------>
    <div class="card">

        <div class="card-header">
            <h4 class="header">Approve Purchase Order Request </h4>
        </div>


        <div class="card-body">

            <form action="/PurchaseRequestUpdate" method="post" class="needs-validation" novalidate=""
                enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-3">
                        <label>Request No</label>
                        <input type="number" id="name" value="{{ $datas->id }}" name="request_no" class="form-control"
                            readonly>
                    </div>

                    <div class="col-3">
                        <label>Department</label>
                        <input type="text" id="name" value="{{ $department }}" name="department" class="form-control"
                            readonly>
                    </div>

                    <div class="col-6">
                        <label>Reason</label>
                        <textarea class="form-control" id="description" name="reason" readonly>{{ $datas->reason }}</textarea>
                    </div>

                </div><br><br>


    {{-- ----------------display Product  for request no ------------------------ --}}


                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity Type</th>
                                <th>Quantity</th>
                                <th>Approved Quantity</th>
                                @if ($storeKeeper)
                                    <th>Store Keeper</th>
                                @endif
                                @if ($Accountant)
                                    <th>Accountant</th>
                                @endif
                                @if ($ManagingDirector)
                                    <th>Managing Director</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @if (count($items) > 0)

                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <input type="text" id="name" name="product[]" class="form-control"
                                                value="{{ $item->product_name }}" readonly>
                                        </td>

                                        <td>
                                            <input type="text" id="name" name="qty_type[]" class="form-control"
                                                value="{{ $item->quantity_type }}" readonly>
                                        </td>

                                        <td>
                                            <input type="number" id="name" name="quantity[]" class="form-control"
                                                value="{{ $item->quantity }}" readonly>
                                        </td>

                                        <td>
                                            <input type="number" id="name" name="approved_quantiy[]" class="form-control"
                                                value="{{ $item->quantity }}" required>
                                        </td>

                                        <td>
                                            @if ($storeKeeper)
                                                <select name="inv_man_status[]" class="form-control">
                                                    <option value="Pending"
                                                        @if ('Pending' == $item->inv_man_status) selected @endif>
                                                        Pending</option>
                                                    <option value="Stock Available"
                                                        @if ('Stock Available' == $item->inv_man_status) selected @endif>Stock Available
                                                    </option>
                                                    <option value="Stock Not Available"
                                                        @if ('Stock Not Available' == $item->inv_man_status) selected @endif>Stock Not
                                                        Available
                                                    </option>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($Accountant)
                                                <select name="acc_man_status[]" class="form-control">
                                                    <option value="Pending"
                                                        @if ('Pending' == $item->acc_man_status) selected @endif>
                                                        Pending</option>
                                                    <option value="Accepted"
                                                        @if ('Accepted' == $item->acc_man_status) selected @endif>
                                                        Accepted</option>
                                                    <option value="Rejected"
                                                        @if ('Rejected' == $item->acc_man_status) selected @endif>
                                                        Rejected</option>
                                                </select>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($ManagingDirector)
                                                <select name="man_dir_status[]" class="form-control">
                                                    <option value="Pending"
                                                        @if ('Pending' == $item->man_dir_status) selected @endif>
                                                        Pending</option>
                                                    <option value="Accepted"
                                                        @if ('Accepted' == $item->man_dir_status) selected @endif>
                                                        Accepted</option>
                                                    <option value="Rejected"
                                                        @if ('Rejected' == $item->man_dir_status) selected @endif>
                                                        Rejected</option>
                                                </select>
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>No datas</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>


                            @endif
                        </tbody>

                    </table>


                </div> <br>

 
     {{-- ---------------end display Product  for request no ------------------------ --}}


                <div align="right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button class="btn btn-success mr-1" id="addSubmit" type="submit">Submit</button>
                </div>

            </form>
        </div>
    </div>

    <!---------------------------- Main Content end ------------------------------>

    <script>

        // validation for no products when click submit button
        $('#addSubmit').click(function(e) {

            var input = document.getElementsByName('product[]');

            if (input.length == 0) {
                e.preventDefault();
                alert("No Datas. You cann't update any change");
            }
        });
    </script>

@endsection
