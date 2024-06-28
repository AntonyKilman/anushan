@extends('layouts.navigation')
@section('purchaseOrderNew', 'active')
@section('content')

    <?php
    $Access = session()->get('Access');
    ?>
    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Create Purchase Order</h4>

        </div>

        <form action="/newPurchaseOrderStore" method="POST" class="needs-validation" novalidate="">
            @csrf

            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <label for="">Purchase Order Id</label>
                        <input type="text" class="form-control" value="{{ $last }}" name="purchase_order_id"
                            readonly>

                    </div>
                </div><br>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>

                                <th>Product</th>
                                <th>Quantity Type</th>
                                <th>Approved Quantity</th>
                                <th>Quantity</th>
                                <th>Supplier</th>
                                <th style="display: none">#</th>
                                <th style="display: none">#</th>
                                <th></th>


                            </tr>
                        </thead>
                        <tbody>

                            @if (count($groupProducts) == 0)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>No Request Items</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @else
                                @foreach ($groupProducts as $groupProduct)
                                    <tr>

                                        <td>
                                            <select name="product[]" id="" class="form-control" readonly>
                                                @foreach ($products as $product)
                                                    <option class="form-control" value="{{ $product->id }}"
                                                        @if ($product->id == $groupProduct->product) selected @endif>
                                                        {{ $product->product_name }}</option>
                                                @endforeach
                                            </select>

                                        </td>

                                        <td>
                                            <select name="qty_type[]" id="qty_type" class="form-control" readonly>

                                                <option value="count" @if ('count' == $groupProduct->quantity_type) selected @endif>
                                                    count
                                                </option>
                                                <option value="liter" @if ('liter' == $groupProduct->quantity_type) selected @endif>
                                                    liter
                                                </option>
                                                <option value="kg" @if ('kg' == $groupProduct->quantity_type) selected @endif>kg
                                                </option>
                                                <option value="meter" @if ('meter' == $groupProduct->quantity_type) selected @endif>
                                                    meter
                                                </option>
                                            </select>

                                        </td>

                                        <td>
                                            <input type="number" class="form-control"
                                                value="{{ $groupProduct->totalQty }}" name="exp_quantity[]" readonly>
                                        </td>

                                        <td>
                                            <input type="number" class="form-control"
                                                value="{{ $groupProduct->totalQty }}" name="quantity[]">
                                        </td>

                                        <td>
                                            <select name="seller[]" id="" class="form-control" required>
                                                <option value="" disabled selected>Please Select Supplier</option>
                                                @foreach ($sellers as $seller)
                                                    <option class="form-control" value="{{ $seller->id }}">
                                                        {{ $seller->seller_name }}</option>
                                                @endforeach
                                            </select>

                                        </td>

                                        <td style="display: none">
                                            <input type="text" class="form-control"
                                                value="{{ implode(',', $groupProduct->reqiestIds) }}"
                                                name="purchase_request_id[]">
                                        </td>

                                        <td style="display: none">
                                            <input type="text" class="form-control"
                                                value="{{ implode(',', $groupProduct->reqiest_items_id) }}"
                                                name="purchase_request_items_id[]">
                                        </td>

                                        <td>
                                            <button type="button" id="remove" class="btn btn-icon btn-danger"><i
                                                    class="fas fa-times"></i></button>
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>


                    </table>
                </div>
                <button type="button" id="addProduct" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp Add
                    Product</button>

                <div align="right">
                    <button class="btn btn-danger" type="reset">Reset</button>
                    <button class="btn btn-success mr-1" type="submit">Submit</button>
                </div>
            </div>

        </form>

    </div>


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
                           <input type="number" id="name" name="exp_quantity[]" class="form-control" placeholder="Enter Quantity" value="0"  readonly>
                       </td>
                       <td>
                           <input type="number" id="name" name="quantity[]" class="form-control" placeholder="Enter Quantity"  required>
                       </td>

                       <td>
                                        <select name="seller[]" id="" class="form-control" required>
                                          <option value="" disabled selected>Please Select Supplier</option>
                                            @foreach ($sellers as $seller)
                                                <option class="form-control" value="{{ $seller->id }}">
                                                    {{ $seller->seller_name }}</option>
                                            @endforeach
                                        </select>

                                    </td>

                       <td style="display: none">
                                <input type="text" class="form-control" value="0"
                                        name="purchase_request_id[]">
                        </td>

                        <td style="display: none">
                                        <input type="text" class="form-control"
                                            value="0"
                                            name="purchase_request_items_id[]">
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
