@extends('layouts.navigation')
@section('Billing', 'active')
@section('link')
    <link rel="stylesheet" href="{{ asset('assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <?php
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
                            <h2> Sales Product</h2>
                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                                <script>
                                    $(function() {
                                        setTimeout(function() {
                                            $('.fade-message').slideUp();
                                        }, 1000);
                                    });
                                </script>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row bg-dark pb-4 pt-3">
                                <div class="col-3">
                                    <h6 class="text-light">Customer Type:</h6>
                                    <select class="form-control" name="customer_type" id="customer_type"
                                        onchange="customerTypeChange()">
                                        <option value="Customer">Customer</option>
                                        {{-- <option value="Credit Customer">Credit Customer</option> --}}
                                    </select>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-light">Date:</h6><span
                                        class="form-control">{{ now()->toDateString() }}</span>
                                </div>
                                <div class="col-3 customerDetails showHide">
                                    <h6 class="text-light">Customer Name:</h6><input class="form-control" value=""
                                        name="customer_name" id="customer_name" placeholder="Customer Name" type="text">
                                </div>
                                <div class="col-3 customerDetails showHide">
                                    <h6 class="text-light">Phone No:</h6><input class="form-control" value=""
                                        name="customer_phone" id="customer_phone" placeholder="0771234567" type="tel">
                                    <div class="invalid-feedback"> Please enter customer's phone number </div>
                                </div>
                                <div class="col-6 cusDetails showHide" style="display:none;">
                                    <h6 class="text-light">Credit Customer Name:</h6>
                                    <select class="form-control select2" style="width:100%;" id="credit_customer_id">
                                        <option value="">Choose...</option>
                                        @foreach ($credit_customer as $c_customer)
                                            <option value="{{ $c_customer->id }}">
                                                {{ $c_customer->phone_number . ' (' . $c_customer->name . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"> Please Select Credit Customer. </div>
                                </div>

                                <div class="col-3">
                                    <h6 class="text-light">Invoice No:</h6><input class="form-control"
                                        value="{{ $invoice }}" name="invoice_no" id="invoice_no" type="text"
                                        readonly>
                                </div>
                                <div class="col-9 showHide complementNote" style="display:none;">
                                    <h6 class="text-light">Complement Note:</h6>
                                    <input class="form-control" name="complement_note" id="complement_note" type="text">
                                    <div class="invalid-feedback"> Please enter note. </div>
                                </div>
                            </div>
                            <div class="row border-top">
                                <div class="col-5 pb-3 pt-3 bg-dark border-right">
                                    <div class="row pb-2">
                                        <label for="barcode" class="col-3 text-light col-form-label">BARCODE:</label>
                                        <div class="col-9">
                                            <input class="form-control" value="" placeholder="Enter Product Code"
                                                onkeyup="searchItemsBarcode('1');" name="barcode" id="barcode"
                                                type="text" autofocus>
                                        </div>
                                    </div>
                                    <div class="row pb-2">
                                        <label for="search-product" class="col-3 text-light col-form-label">SEARCH:</label>
                                        <div class="col-9">
                                            <input class="form-control" onkeyup="searchItemsBarcode('2');"
                                                placeholder=" Enter Product Name" value=""
                                                name="search-product" id="search-product" type="text">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12" id="search-items-no-product" style="display: none;">
                                        </div>
                                        <div class="col-12" id="search-items-disply" style="display: none;">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-light" scope="col">#</th>
                                                        <th class="text-light" scope="col">Name</th>
                                                        <th class="text-light" scope="col"></th>
                                                        <th class="text-light" scope="col"></th>
                                                        <th class="text-light" scope="col">Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="search-items">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-7 pb-3 pt-3">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="text-center">BILLING AREA</h5>
                                        </div>
                                    </div>
                                    <div class="row border-left" id="print-area">
                                        <table class="table" align="center" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <p style="font-size: 11px;">#</p>
                                                    </th>
                                                    <th scope="col"style="width:40%;">
                                                        <p style="font-size:11px;text-align:left;">ITEM</p>
                                                    </th>
                                                    <th scope="col">
                                                        <p style="font-size: 11px;text-align:center;">QTY</p>
                                                    </th>
                                                    <th scope="col">
                                                        <p style="font-size: 11px;text-align:center;">RATE</p>
                                                    </th>
                                                    <th scope="col"style="width:10%;">
                                                        <p style="font-size: 11px;text-align:center;">DIS</p>
                                                    </th>
                                                    <th scope="col">
                                                        <p style="font-size: 11px;text-align:right;">AMOUNT</p>
                                                    </th>
                                                    <th scope="col" id="action">
                                                        <p style="font-size: 11px;">ACTION</p>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="bill-items">

                                            </tbody>
                                            <div class="">
                                                <tr>
                                                    <td scope="row">*</td>
                                                    <td colspan="4">
                                                        <h5 style="margin:0px;padding:0px;">Total</h5>
                                                    </td>
                                                    <td id="bill-items-total"></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">*</td>
                                                    <td colspan="4">
                                                        <h5 style="margin:0px;padding:0px">Total Discount</h5>
                                                    </td>

                                                    <td id="total-bill-items-discount">
                                                        <h5 style="text-align:right;margin:0px;padding:0px;">0.00</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">*</td>
                                                    <td colspan="4">
                                                        <h5 style="margin:0px;padding:0px ">Total payable</h5>
                                                    </td>

                                                    <td id="total-bill-items-total-payable">
                                                        <h5 style="text-align:right;margin:0px;padding:0px;">0.00</h5>
                                                    </td>
                                                </tr>
                                            </div>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-6"><h5>Enter Discount Amount:</h5></div>
                                        <div class="col-6"><input type="number" class="form-control" id="enterDiscountAmount" onkeyup="addDiscount()"></div>
                                    </div>
                                    <br>
                                    <div class="row d-flex flex-row-reverse">
                                        <button class="btn btn-primary mr-3" type="button"
                                            onclick="proceedToPay();">PAY</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <div id="image" style="display: none">
        <img width="100px" height="60px" src="https://www.jaffnaelectrical.skyrow.lk/assets/img/JE1.png" alt="">
    </div>

@endsection
<!-- ----------------------- -->
@section('model')
    <!-- Modal with form -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="paymentModal"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-4">
                            <label>Cash:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </div>
                                </div>
                                <input type="number" min="0" class="form-control" placeholder="00.00"
                                    name="cash" id="cash-payment" onchange="calculateBalance()">
                            </div>
                        </div>

                        <div class="form-group col-4">
                            <label>Card:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                </div>
                                <input type="number" min="0" class="form-control" placeholder="00.00"
                                    name="card" id="card-payment" onchange="calculateBalance()">
                            </div>
                        </div>

                        <div class="form-group col-4">
                            <label>Credit: </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-money-check-alt"></i>
                                    </div>
                                </div>
                                <input type="text" min="0" class="form-control" placeholder="00.00"
                                    name="credit" id="credit-payment" onchange="calculateBalance()" readonly>
                                <div class="invalid-feedback" id="creditPaymentError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-4">
                            <label>Online Payment:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-money-check"></i>
                                    </div>
                                </div>
                                <input type="number" min="0" class="form-control" placeholder="00.00"
                                    name="online" id="online-payment" onchange="calculateBalance()">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-4">
                            <label>Cheque:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-money-check"></i>
                                    </div>
                                </div>
                                <input type="number" min="0" class="form-control" placeholder="00.00"
                                    name="cheque" id="cheque-payment" onchange="calculateBalance()">
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-4">
                            <label>Cheque NO: </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-text-width"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" placeholder="000-000-000"
                                    name="cheque-number" id="cheque-number" onchange="calculateBalance()">
                            </div>
                        </div>
                        <div class="form-group col-4">
                            <label>Cheque Date: </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-text-width"></i>
                                    </div>
                                </div>
                                <input type="date" class="form-control" name="cheque-date" id="cheque-date"
                                    onchange="calculateBalance()">
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>Return Bills</label>
                            <select class="form-control" style="width:100%;" id="billDetails" onchange="sales()">
                                <option value="">Choose...</option>
                                @foreach ($sales_return_reference_no as $reference_no)
                                    <option value="{{ $reference_no->sales_id . '-' . $reference_no->total . '-' . $reference_no->foodcity_sales_return_details_id}}">
                                        {{ $reference_no->invoice_no . ' -(Rs.' . $reference_no->total .')'}}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="sales_id" id="sales_id">
                            <input type="hidden" name="foodcity_sales_return_details_id" id="foodcity_sales_return_details_id">
                        </div>
                        <div class="form-group col-6">
                            <label>Sales Return Amount </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-text-width"></i>
                                    </div>
                                </div>
                                <input type="number" value="0" class="form-control" name="return_amount" id="return_amount"
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>Advance Payment</label>
                            <select class="form-control" style="width:100%;" id="advanceDetails" onchange="advancepayment()">
                                <option value="">Choose...</option>
                                @foreach ($advance_payment_details as $row)
                                    <option value="{{ $row->id .'-'. $row->amount}}">
                                        {{ $row->name . ' -(' . $row->phone_number . ')' . ' -(Rs.' . $row->amount .')' }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="advance_payment_id" id="advance_payment_id">
                        </div>
                        <div class="form-group col-6">
                            <label>Advance Payment Amount </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-text-width"></i>
                                    </div>
                                </div>
                                <input type="number" value="0" step="0.01" class="form-control" name="advance_amount" id="advance_amount"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-4">
                            <label>Total: <span id="totalPayable">00.00</span></label>
                        </div>
                        <div class="form-group col-4">
                            <label>Total Discount: <span id="totalPayableDiscount">00.00</span></label>
                        </div>
                        <div class="form-group col-4">
                            <label>Balance: <span id="balancePayable">00.00</span></label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary m-t-15 waves-effect payment-proceed" disabled
                        onclick="placeOrder();">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------------------------------------------------------- -->
@endsection

@section('script')
    <script src="{{ asset('assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>


<script>
    function customerTypeChange() {
        $('.showHide').hide(); // all hide
        var customer_type = $('#customer_type').val();
        switch (customer_type) {
            case 'Credit Customer':
                $('.cusDetails').show();
                break;
            default:
                $('.customerDetails').show(); // Customer
                break;
        }
    }

    //validation
    function proceedToPay() {

        var customer_type = $('#customer_type').val();
        if (customer_type == 'Credit Customer') {
            var credit_customer_id = $('#credit_customer_id').val();
            if (credit_customer_id) {
                $('#credit_customer_id').removeClass('is-invalid');
                $('#paymentModal').modal('toggle');
            } else {
                $('#credit_customer_id').addClass('is-invalid');
            }
        } else {
            $('#paymentModal').modal('toggle');
        }
    }

    function addDiscount(){
        var total = $('#bill-items-total').children().html().trim();
        var enterDiscountAmount = $('#enterDiscountAmount').val();

        if (parseFloat(total) > parseFloat(enterDiscountAmount)) {
            var discount = enterDiscountAmount;
            var totalPayable = parseFloat(total) - parseFloat(enterDiscountAmount);
        } else {
            var discount = 0;
            var totalPayable = parseFloat(total).toFixed(2);
        }
        var discountHtml = `<h5 style="text-align:right;margin:0px;padding:0px;">${parseFloat(discount).toFixed(2)}</h5>`;
        var totalPayableHtml = `<h5 style="text-align:right;margin:0px;padding:0px;">${parseFloat(totalPayable).toFixed(2)}</h5>`;
        $('#total-bill-items-discount').html(discountHtml);
        $('#total-bill-items-total-payable').html(totalPayableHtml);

         //update modal total amount
         $('#totalPayable').html(totalPayableHtml);
    }

    function sales() {
        var billDetails = $('#billDetails').val();
        if (billDetails) {
            billDetails = billDetails.split('-');
            $('#sales_id').val(billDetails[0]);
            $('#return_amount').val(billDetails[1]);
            $('#foodcity_sales_return_details_id').val(billDetails[2]);
        } else {
            $('#sales_id').val('');
            $('#return_amount').val(0);
            $('#foodcity_sales_return_details_id').val('');
        }

        calculateBalance();
    }

    function advancepayment() {
        var advanceDetails = $('#advanceDetails').val();
        if (advanceDetails) {
            advanceDetails = advanceDetails.split('-');
            $('#advance_payment_id').val(advanceDetails[0]);
            $('#advance_amount').val(advanceDetails[1]);
        } else {
            $('#advance_payment_id').val('');
            $('#advance_amount').val(0);
        }
        calculateBalance();
    }

</script>

<script>
    //seach products by barcode or Name,Product code or categorey
    function searchItemsBarcode(searchType) {

        const barcode = $("#barcode").val().trim();
        const search = $("#search-product").val().trim();

        if (barcode.length > 0 || search.length > 2) {
            $("#search-items").html("");
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "/sales/search-product",
                type: "GET",
                data: {
                    searchType: searchType,
                    search: search,
                    barcode: barcode,
                    _token: _token
                },
                success: function(response) {


                    if (response.success.length > 0) {
                        $('#search-items-no-product').hide();
                        $('#search-items').html('');
                        $('#search-items-disply').show();
                        for (var i = 0; i < response.success.length; i++) {
                            var availableQty = (response.success[i].quantity - response.success[i]
                                .returns_quantity - response.success[i].sales_quantity + response
                                .success[i].cancel_quantity);
                            var button = '<p class="text-danger">Out of stock</p>';

                            if (availableQty > 0) {
                                // button =
                                //     `<button type="button" class="btn btn-success add-product" onclick="addProduct(` +
                                //     response.success[i].id + `, ` + response.success[i].sales_price +
                                //     `, '` + response.success[i].name + `', '` + response.success[i]
                                //     .bar_code + `','` + response.success[i].purchase_price +
                                //     `')">Add</button>`;
                                button =
                                    `<button type="button" class="btn btn-success add-product" 
                                    data-id = "${response.success[i].id}"
                                    data-sales_price = "${response.success[i].sales_price}"
                                    data-purchase_price = "${response.success[i].purchase_price}"
                                    data-bar_code = "${response.success[i].bar_code}"
                                    data-name = '${response.success[i].name}'
                                    >Add</button>`;
                            }

                            var row = $(`<tr class="border-top"><td class="text-light">` + (i + 1) +
                                `</td><td colspan="3" class="text-light">` + response.success[i].name + `<br/> Available - (`+ response.success[i].now_have_quantity + `),   Code - ` + response.success[i].product_code +
                                `</td><td class="text-light product-price">` + response.success[i]
                                .sales_price + `</td></tr>
                                <tr id="` + response.success[i].id +
                                `"><td></td><td class="pb-2"><span class="text-light">Qty:</span><input class="form-control product-qty" value="1" max="` +
                                availableQty +
                                `" name="product-qty" id="product-qty" type="number"></td><td class="pb-2"><span class="text-light">Dis.Type:</span><select class="form-control discount-type" id="discount-type"><option value="1">Amount</option><option value="2">Percentage</option></select></td><td class="pb-2"><span class="text-light">Discount:</span><input class="form-control product-discount" value="" placeholder="%" name="discount-percentage" id="discount-percentage" type="number"></td><td class="text-light">` +
                                button + `</td></tr>`);
                            $('#search-items').append(row);
                        }

                        $( ".add-product" ).click(function() {
                            var id = $(this).attr('data-id');
                            var sales_price = $(this).attr('data-sales_price');
                            var purchase_price = $(this).attr('data-purchase_price');
                            var bar_code = $(this).attr('data-bar_code');
                            var name = $(this).attr('data-name');
                            addProduct(id, sales_price, name, bar_code, purchase_price);
                        });
                        
                    } else {
                        $('#search-items-disply').hide();
                        $('#search-items-no-product').show();
                        $('#search-items-no-product').html('');
                        $('#search-items-no-product').html(
                            '<p class="text-danger">Product is not available</p>');
                    }
                    //$("#ajaxform")[0].reset();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        } else {
            $('#search-items-disply').hide();
        }
    }

    //add product to bill from seach result
    function addProduct(productId, productPrice, name, barCode, purchase_price) {

        if (productId) {
            let billFromStorage = JSON.parse(localStorage.getItem("BIILING-ITEMS") ?
                localStorage.getItem("BIILING-ITEMS") :
                "[]"
            );

            var productQty = $('#' + productId).children().children('.product-qty').val();
            var productDiscount = $('#' + productId).children().children('.product-discount').val();
            var discountType = $('#' + productId).children().children('.discount-type').val();
            var discount;

            if (productDiscount) {
                if (discountType == 1) {
                    discount = productDiscount;
                } else if (discountType == 2) {
                    discount = (productPrice / 100) * productDiscount;
                }
            } else {
                discount = 0;
            }
            let isThisItemAlreadyAvailableInBill = !1,
                thisItem = {
                    id: productId,
                    name: name,
                    price: productPrice,
                    discount: discount,
                    qty: productQty,
                    barCode: barCode,
                    purchase_price: purchase_price
                };

            $.each(billFromStorage, function(ii, it) {
                    it.id == thisItem.id &&
                        ((isThisItemAlreadyAvailableInBill = !0),
                            (it.qty = parseInt(it.qty + "") + parseInt(thisItem.qty + "")),
                            (it.discount = parseFloat(thisItem.discount)));
                }), isThisItemAlreadyAvailableInBill || billFromStorage.push(thisItem),
                localStorage.setItem("BIILING-ITEMS", JSON.stringify(billFromStorage))
        }
        updateBill();
    }

    //update product on billing
    function updateBill() {
        $('#bill-items').html('');
        let billFromStorage = JSON.parse(
            localStorage.getItem("BIILING-ITEMS") ?
            localStorage.getItem("BIILING-ITEMS") :
            "[]"
        );

        let total = 0;
        let discount = 0;
        var i = 1;
        $.each(billFromStorage, function(pi, p) {
            let thisTotal = (parseFloat(p.price + "") * parseFloat(p.qty + "")).toFixed(2);
            let thisDiscount = (parseFloat(p.discount + "") * parseFloat(p.qty + "")).toFixed(2);

            (thisTotal = isNaN(thisTotal) ? 0 : thisTotal);
            (thisDiscount = isNaN(thisDiscount) ? 0 : thisDiscount);

            //update billing product table
            var row = $('<tr><th scope="row"><p style="font-size:11px;">' + i +
                '</p></th><td><p style="font-size: 11px;text-align:left;">' + p.name +
                '</p></td><td><p style="font-size: 11px;text-align:right;">' + p.qty +
                '</p></td><td><p style="font-size: 11px;text-align:right;">' + parseFloat(p.price).toFixed(2) +
                '</p></td><td><p style="font-size: 11px;text-align:right;">' + thisDiscount +
                '</p></td><td><p style="font-size:11px;text-align:right;">' + parseFloat(thisTotal - thisDiscount)
                .toFixed(2) +
                '</p></td><td class="text-light remove-btn"><button type="button" class="btn btn-danger remove-product" onclick="removeProduct(' +
                p.id + ')">Remove</button></td></tr>');
            $('#bill-items').append(row);

            //calculate total amount
            total += (thisTotal - thisDiscount);
            //increase row number
            i++;
        });
        //update total amount
        var totalAmout = '<h5 style="text-align:right;margin:0px;padding:0px;">' + total.toFixed(2) + '</h5>';
        $('#bill-items-total').html(totalAmout);


        addDiscount();
    }

    //remove product on billing
    function removeProduct(id) {
        let billFromStorage = JSON.parse(
                localStorage.getItem("BIILING-ITEMS") ?
                localStorage.getItem("BIILING-ITEMS") :
                "[]"
            ),
            index = -1;
        $.each(billFromStorage, function(ii, it) {
                it.id == id && (index = ii);
            }),
            index >= 0 && billFromStorage.splice(index, 1),
            localStorage.setItem("BIILING-ITEMS", JSON.stringify(billFromStorage)),
            updateBill();
    }

    //show added porduct on billing
    $(document).ready(function() {
        updateBill();
    });
    

    //calculate payable amount by cash, card, credit, and loyality
    function calculateBalance() {
        var cashPayment = $('#cash-payment').val() ? $('#cash-payment').val() : 0;
        var cardPayment = $('#card-payment').val() ? $('#card-payment').val() : 0;
        var creditPayment = $('#credit-payment').val() ? $('#credit-payment').val() : 0;
        var onlinePayment = $('#online-payment').val() ? $('#online-payment').val() : 0;
        var chequePayment = $('#cheque-payment').val() ? $('#cheque-payment').val() : 0;
        var chequeNo = $('#cheque-number').val() ? $('#cheque-number').val() : 0;
        var chequeDate = $('#cheque-date').val() ? $('#cheque-date').val() : 0;
        var return_amount = $('#return_amount').val() ? $('#return_amount').val() : 0;
        var advance_amount = $('#advance_amount').val() ? $('#advance_amount').val() : 0;
        var totalPayable = $('#total-bill-items-total-payable').children().html().trim();

        var balancePayable = totalPayable - (parseFloat(cashPayment) + parseFloat(cardPayment) +
            parseFloat(creditPayment) +  parseFloat(onlinePayment) + parseFloat(chequePayment) + parseFloat(return_amount) + parseFloat(advance_amount));

        $('#balancePayable').html('<h5>' + balancePayable.toFixed(2) + '</h5>');

        //disable button if payment has balance
        if (balancePayable == 0) {
            $('.payment-proceed').prop('disabled', false);
        } else {
            $('.payment-proceed').prop('disabled', true);
        }
        //disable button if cheque number or date not enter
        if (chequePayment != 0) {
            if (chequeNo == 0 || chequeDate == 0) {
                $('.payment-proceed').prop('disabled', true);
            }
        }

    }

    //place order
    function placeOrder() {


        $('.payment-proceed').prop('disabled', true);

        //get payment details from modal
        var cashPayment = $('#cash-payment').val() ? $('#cash-payment').val() : 0;
        var cardPayment = $('#card-payment').val() ? $('#card-payment').val() : 0;
        var creditPayment = $('#credit-payment').val() ? $('#credit-payment').val() : 0;
        var onlinePayment = $('#online-payment').val() ? $('#online-payment').val() : 0;
        var chequePayment = $('#cheque-payment').val() ? $('#cheque-payment').val() : 0;
        var chequeNo = $('#cheque-number').val() ? $('#cheque-number').val() : 0;
        var chequeDate = $('#cheque-date').val() ? $('#cheque-date').val() : 0;
        var return_amount = $('#return_amount').val() ? $('#return_amount').val() : 0;
        var sales_id = $('#sales_id').val();
        var advance_amount = $('#advance_amount').val() ? $('#advance_amount').val() : 0;
        var advance_payment_id = $('#advance_payment_id').val();
        var foodcity_sales_return_details_id = $('#foodcity_sales_return_details_id').val();
        var total = $('#bill-items-total').children().html().trim();
        var totalPayableDiscount = $('#total-bill-items-discount').children().html().trim();
        var totalPayable = $('#total-bill-items-total-payable').children().html().trim();

        //customer data
        const name = $('#customer_name').val();
        const phone = $('#customer_phone').val();
        //get invoice number
        const invoiceNo = $('#invoice_no').val();

        // customer type
        const customerType = {
            customer_type: $('#customer_type').val(),
            credit_customer_id: $('#credit_customer_id').val()
        };

        const amount = {
            total : total,
            totalPayableDiscount : totalPayableDiscount,
            totalPayable : totalPayable,
        }

        const payments = {
                cashPayment: cashPayment,
                cardPayment: cardPayment,
                creditPayment: creditPayment,
                chequePayment: chequePayment,
                chequeNo: chequeNo,
                chequeDate: chequeDate,
                return_amount: return_amount,
                sales_id: sales_id,
                foodcity_sales_return_details_id: foodcity_sales_return_details_id,
                advance_amount: advance_amount,
                advance_payment_id: advance_payment_id,
                onlinePayment: onlinePayment,

            },
            //get products details from localstorage
            items = JSON.parse(
                localStorage.getItem("BIILING-ITEMS") ?
                localStorage.getItem("BIILING-ITEMS") :
                "[]"
            ),
            //get customer details
            customer = {
                name: name,
                phone: phone,
            }

        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/sales/store-product",
            type: "POST",
            data: {
                payments: payments,
                items: items,
                customer: customer,
                invoiceNo: invoiceNo,
                _token: _token,
                customerType: customerType,
                amount : amount
            },

            success: function(res) {
                console.log(res);
                if (res.success == 'success') {
                    $('#paymentModal').modal('toggle');
                    swal('Success!', 'Bill Added Successfully!', 'success');
                    var customerName = res.details.customerName;
                    var billUser = res.details.billUser;
                    var invoice_no = res.details.invoice_no;
                    printBill(billUser,customerName,invoice_no);
                } else {
                    swal('Error!', 'Something went wrong, please try again.', 'warning');
                }
            },
            error: function(error) {
                console.log(error);
                swal('Error!', 'Something went wrong, please try again.', 'warning');
            }
        });
    }


    //print bill
    function printBill(billUser,customerName,invoiceNo) {

        // get now date and time
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var ii = today.getMinutes();
        var ss = today.getSeconds();

        date = mm + '/' + dd + '/' + yyyy;
        time = hh + ':' + ii + ':' + ss;

        if (customerName != null) {
            customerName = customerName;
        } else {
            var customerName = 'New';
        }
        var billUser = billUser;


        $('.remove-btn').html('');
        $('#action').html('');
        var printArea = $('#print-area').html();
        var body =`<!DOCTYPE html><html lang="en"><head></head><body>
         <div style="display:flex;flex-direction:column;align-items: center;"> 
         ${$('#image').html()}
          <div style="font-size:15px; font-weight:600;">JAFFNA ELECTRICALS</div>
          <div style="font-size:7px; font-weight:600;">DEALERS IN QUALITY ELECTRICAL & ELECTRONIC ITEMS</div>
          </div>
          <div style="display:flex"> 
          <span style="flex: 50%; font-size:12px; text-align: left;font-weight:600;">
           <div>Tel : 021-2222353 </div></span>
           <span style="flex: 50%; font-size:12px; text-align: right;font-weight:600;">
           <div>Fax : 021-2224302 </div></span> </div>
           <div style="display: flex;border-bottom:0.2px solid black;">
           <span style="flex: 10%; font-size:12px; text-align: left;font-weight:600;">
           <div>No.94(6), Stanley Road, Jaffna. </div></span></div>


            <div style="display: flex;border-bottom:0px solid black;"> 
            <span style="flex: 40%; font-size:12px; text-align: left;font-weight:600;">
            <div>Date: ${date}</div><div>Time: ${time}</div></span>
            <span style="flex: 60%; font-size:12px; text-align: right;font-weight:600;"> 
            <div>Cus-Name: ${customerName}</div><div>User: ${billUser}</div></span>
            </div>

            <div style="display: flex;border-bottom:0.2px solid black;">
            <span style="flex: 100%; font-size:12px; text-align: left;font-weight:600;"> 
            <div>Invoice No: ${invoiceNo}</div></span></div>${printArea}





            <div style="padding-top:10px; font-size:14px;display:flex;flex-direction:column;align-items: center;"> 
            <div>Thank You ! Come Again</div></div>
            <div style="padding-top:4px; font-size:10px ; display:flex;flex-direction:column;align-items: center;"> 
            <div>System Developed BY Codevita (Pvt) Ltd</div></div></body></html>`;

        var mywindow = window.open('', 'PRINT');
        mywindow.document.write(body);
        mywindow.focus();
        mywindow.print();
        mywindow.close();

        localStorage.removeItem("BIILING-ITEMS");
        location.reload();
    }

   
    
</script>
