@extends('layouts.navigation')
@section('purchaseRequest', 'active')
@section('content')

    <?php
        $Access = session()->get('Access');
    ?>

    <!---------------------------- Main Content start ------------------------------>
    <div class="card">

        <div class="card-header">
            <h4 class="header">Create Purchase Order Request </h4>
        </div>


        <div class="card-body">

            <form action="/PurchaseRequestAdd" method="post" class="needs-validation" novalidate=""
                enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-3">
                        <label>Request No</label>
                        <input type="text" id="name" value="{{ $RequestNo }}" name="request_no" class="form-control"
                            readonly>
                    </div>

                    <div class="col-3">
                        <label>Department</label>
                        <select id="" name="department" class="form-control" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6">
                        <label>Reason</label>
                        <textarea class="form-control" id="description" name="reason"></textarea>
                    </div>

                </div><br>


                {{-- ---------------- Add Product table start ------------------------ --}}


                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity Type</th>
                                <th>Quantity</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <select id="" name="product[]" class="form-control" required>
                                        <option value="" disabled selected>Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <select name="qty_type[]" id="qty_type" class="form-control" required>
                                        <option value="" disabled selected>Select Quantity Type</option>
                                        <option value="count">count</option>
                                        <option value="liter">liter</option>
                                        <option value="kg">kg</option>
                                        <option value="meter">meter</option>
                                    </select>
                                </td>

                                <td>
                                    <input min="0" type="number" id="name" name="quantity[]" class="form-control"
                                        placeholder="Enter Quantity" required>
                                </td>


                                <td style="display: none">
                                    <button type="button" id="remove" class="btn btn-icon btn-danger"><i
                                            class="fas fa-times"></i></button>
                                </td>
                            </tr>
                        </tbody>


                    </table>

                    <button type="button" id="addProduct" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp Add
                        Product</button>
                </div>




                {{-- ---------------- Add Product table end ------------------------ --}}


                <div align="right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button class="btn btn-success mr-1" type="submit">Submit</button>
                </div>

            </form>
        </div>
    </div>

    <!---------------------------- Main Content end ------------------------------>


    <script>
        $('#addProduct').click(function() {

            let html = "";
            html += `<tr>
                        <td>
                            <select id="" name="product[]" class="form-control" required>
                                <option value="" disabled selected>Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <select name="qty_type[]" id="qty_type" class="form-control" required>
                                <option value="" disabled selected>Select Quantity Type</option>
                                <option value="count">count</option>
                                <option value="liter">liter</option>
                                <option value="kg">kg</option>
                                <option value="meter">meter</option>
                              </select>
                        </td>

                        <td>
                            <input type="number" id="name" name="quantity[]" class="form-control" placeholder="Enter Quantity"  required>
                        </td>

                        <td>
                            <button type="button" id="remove" class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>`;

            $('tbody').append(html);


        })


        $(document).on('click', '#remove', function() {
            $(this).closest('tr').remove();
        });
        
    </script>
@endsection
