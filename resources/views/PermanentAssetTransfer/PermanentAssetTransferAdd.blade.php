@extends('layouts.navigation')
@section('permanent_asset_transfer', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    use Carbon\Carbon;
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4>Permanent Asset Transfer</h4>
        </div>

        <form action="/permanent-asset-transfer-store" method="post" class="needs-validation" novalidate="">
            @csrf
            <div class="card-body form">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Select Permanent Asset</label>
                        <select class="form-control permanentTransfer" id="permanent_assets_id" required>
                            <option value="" disabled selected>Select Permanent Asset</option>
                            @foreach ($PermanentAssets as $PermanentAssets)
                                <option name="permanent_assets_id" class="permanent_assets_id" id="permanent_assets_id"
                                    value="{{ $PermanentAssets->product_id }}">
                                    {{ $PermanentAssets->product_name }}</option>
                            @endforeach
                            <span class="text-danger">
                                @error('permanent_assets_id')
                                    {{ $message }}
                                @enderror
                            </span>
                        </select>
                    </div>


                    <div class="form-group col-md-4">
                        <label>Select Department</label>
                        <select class="form-control department_id" name="department_id" id="department_id" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($departments as $department)
                                <option name="department_id" class="department_id" id="department_id"
                                    value="{{ $department->id }},{{ $department->acc_dept_id }}">{{ $department->dept_name }}</option>
                            @endforeach
                            <span class="text-danger">
                                @error('department_id')
                                    {{ $message }}
                                @enderror
                            </span>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" id="date"
                            max="{{ now()->format('Y-m-d') }}" value="{{ old('date') }}" required>
                        <span class="text-danger">
                            @error('date')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>



                    {{-- <div class="form-group col-md-3">
                        <label>Depreciation Persentage</label>
                        <input type="number" class="form-control" min="0" name="depreciation_persentage"
                            id="date" value="{{ old('depreciation_persentage') }}"
                            placeholder="Depreciation Persentage %" max="100" required>
                        <span class="text-danger">
                            @error('depreciation_persentage')
                                {{ $message }}
                            @enderror
                        </span>
                    </div> --}}
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Purchase Id</th>
                                <th>Permanent Asset</th>
                                <th>Quantity</th>
                                <th>Transfer</th>
                                <th>Depreciation Persentage</th>
                                {{-- <th>Selected</th> --}}
                            </tr>
                        </thead>

                        <tbody class="reset"> </tbody>
                    </table>
                </div>
                <div align='right'>
                    <button type="reset" class="btn btn-danger" id="reset">Reset</button>
                    <button class="btn btn-success mr-1" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".department_id").on("change", function() {

            let dept_id = $("#department_id").val();

            $(dept_id).show();

        });

        var selectedProduct = [];

        $(document).on('click', '#reset', function() {

            $('.reset').html('');
            selectedProduct.splice(0, selectedProduct.length);
        });



        $(document).on('change', '.permanentTransfer', function() {
            console.log("click");

            var product = document.getElementById('permanent_assets_id').value;

            if (selectedProduct.includes(product)) {
                // alert("Already Selected");
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'This Product Already Selected!',
                });
            } else {
                selectedProduct.push(product);

                $.ajax({
                    type: "GET",
                    url: "/permanent-assets/" + product,
                    dataType: "json",

                    success: function(response) {
                        console.log(response);

                        var html = '';
                        var checkData = 0;

                        for (const key in response) {
                            if (response[key]['stock'] > 0) {
                                checkData++;
                                var quantity = response[key]['pur_item_qty'];
                                var purchaseId = response[key]['purchase_order_id'];
                                var productName = response[key]['product_name'];
                                var productId = response[key]['product_id']; //inventory products.id
                                var unit_amount = response[key]['pur_item_amount']/response[key]['pur_item_qty']; //inventory products.id
                                

                                // var productId = response[key]['permanent_assets_id']; //inventory permanent.id
                                // var specific_id = response[key]['purchase_order_id'] + '-' + response[
                                // key]['permanent_assets_id'];

                                html += '<tr class="pro_' + productId + '">';
                                html +=
                                    '<td style="display: none"><input type="hidden" class="form-control" value="' +
                                    productId + '"  name="pro_id[]"></td>';

                                html +=
                                    '<td style="display: none"><input type="hidden" class="form-control" value="' +
                                        unit_amount + '"  name="pur_item_amount[]"></td>';



                                html += '<td><input type="text" id="purchaseId" value="' + purchaseId +
                                    '" class="form-control" name="purchase_id[]" readonly="readonly"></td>';

                                html += '<td><input type="text" value="' + productName +
                                    '" class="form-control" name="product_name[]" readonly="readonly"></td>';

                                html += '<td><input type="text" value="' + quantity +
                                    '" class="form-control" name="qty[]" readonly="readonly"></td>';

                                html += '<td><input type="number" value="' + quantity +
                                    '" class="form-control" name="transfer_qty[]"  min="0" max="' +
                                    quantity + '"></td>';

                                html +=
                                    '<td><input type="number"  placeholder="Depreciation Persentage %" class="form-control" min="0"  name="depreciation_persentage[]" ></td>';

                                // '<td><input type="number"
                                // '" class="form-control" name="depreciation_persentage[]"  min="0" "></td>';
                                // html +=
                                //     '<td><input type="date"  max="{{ now()->format('Y-m-d') }}" class="form-control" required name="date[]" ></td>';
                                html += '</tr>';
                            }
                        }

                        if (checkData > 0) {
                            html += '<tr class="pro_' + product + '">';
                            html +=
                                '<td></td><td></td><td><td></td></td><td></td><td></td><td><button class="btn btn-danger" onclick="remove_fun(' +
                                product + ')" type="button"><i class="fas fa-times"></i></button></td>';
                            html += '</td>';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Out Of Stock!',
                            });
                        }

                        $('tbody').append(html);

                        $('.__status').on('change', function() {
                            spec_id = $(this).attr('data-id');

                            if ($(this).prop('checked')) {

                                $('.' + spec_id).attr('required', true)
                            } else {

                                $('.' + spec_id).attr('required', false)
                            }
                        });
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }

        });

        function remove_fun(id) {

            console.log('remove id : ' + id);
            $('.pro_' + id).hide();

            let index = selectedProduct.findIndex(checkIndex);
            selectedProduct.splice(index, 1);

            function checkIndex(product) {
                return product == id;
            }

            $('#permanent_assets_id').val('');
        }
    </script>

@endsection
