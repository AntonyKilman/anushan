@extends('layouts.navigation')
@section('good_receive', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    use Carbon\Carbon;
    ?>
    <style>
        .dropbtn {
            background-color: #04aa6d96;
            color: white;
            padding: 16px;
            width: 100%;
            height: 45%;
            line-height: 1px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .dropbtn:hover,
        .dropbtn:focus {
            background-color: #91eb94;
        }

        #myInput {
            box-sizing: border-box;
            background-image: url('searchicon.png');
            background-position: 14px 12px;
            background-repeat: no-repeat;
            font-size: 16px;
            padding: 14px 20px 12px 45px;
            border: none;
            border-bottom: 1px solid #ddd;
        }

        #myInput:focus {
            outline: 3px solid #ddd;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f6f6f6;
            min-width: 230px;
            overflow: auto;
            border: 1px solid #ddd;
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown a:hover {
            background-color: #ddd;
        }

        .show {
            display: block;
        }
    </style>

    <!-- Main Content -->
    <div class="card">
        <form action="/purchase-boucher-add-process" method="post" class="needs-validation" novalidate=""
            enctype="multipart/form-data">
            @csrf

            <div class="card-header">
                <h4>Create Good Recive Boucher</h4>
            </div>

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
                        <select class="form-control select2" name="seller_id" required>
                          <option value="" disabled selected>Select Supplier</option>
                          @foreach ($sellers as $seller)
                            <option value="{{$seller->id}}" {{$seller->id==old('seller_id')?'selected':''}}>{{$seller->seller_name}}</option>
                          @endforeach
                        </select>

                        @error('seller_id')
                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <div class="dropdown col-md-4">
                      <label style="font-weight: 600;
                                    color: #34395e;
                                    font-size: 12px;
                                    letter-spacing: 0.5px;">Supplier </label>
                        <button onclick="myFunction()" class="dropbtn">Select</button>
                        <div id="myDropdown" class="dropdown-content" style="width: 97%">
                            <input type="text" placeholder="Search.." name="seller_id" id="myInput"
                                onkeyup="filterFunction()" style="width: 100%">
                            @foreach ($sellers as $seller)
                                <a value="{{ $seller->id }}"
                                    {{ $seller->id == old('seller_id') ? 'selected' : '' }}>{{ $seller->seller_name }}</a>
                            @endforeach
                        </div>
                        @error('seller_id')
                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    
                </div>



                {{-- second row --}}
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <?php
                        //   if (old('cheque_amount')) {
                        //     $cheque_val_amount=old('cheque_amount');
                        //   }else {
                        //     $cheque_val_amount=0;
                        //   }
                        //   if (old('online_amount')) {
                        //     $online_val_amount=old('online_amount');
                        //   }else {
                        //     $online_val_amount=0;
                        //   }
                        //   if (old('credit_amount')) {
                        //     $credit_val_amount=old('credit_amount');
                        //   }else {
                        //     $credit_val_amount=0;
                        //   }
                        //   if (old('cash_amount')) {
                        //     $cash_val_amount=old('cash_amount');
                        //   }else {
                        //     $cash_val_amount=0;
                        //   }
                        ?>
                        <label>Cheque Payment</label>
                        <input type="text" id="cheque_amount" value="{{ old('cheque_amount') }}" name="cheque_amount"
                            pattern="^\d+(\.\d)?\d*$" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Cheque No</label>
                        <input type="text" id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}"
                            class="form-control">
                        @error('cheque_no')
                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Cheque Date</label>
                        <input type="date" id="cheque_date" name="cheque_date" value="{{ old('cheque_date') }}"
                            class="form-control">
                    </div>
                </div>

                {{-- third row --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Online/Card Payment</label>
                        <input type="text" value="{{ old('online_amount') }}" id="online_amount" name="online_amount"
                            pattern="^\d+(\.\d)?\d*$" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Reference No</label>
                        <input type="text" id="reference_no" name="reference_no" value="{{ old('reference_no') }}"
                            class="form-control">
                    </div>
                </div>

                {{-- fourth row --}}
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Credit Payment</label>
                        <input type="text" id="credit_amount" name="credit_amount" value="{{ old('credit_amount') }}"
                            pattern="^\d+(\.\d)?\d*$" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Cash Payment</label>
                        <input type="text" id="cash_amount" name="cash_amount" value="{{ old('cash_amount') }}"
                            pattern="^\d+(\.\d)?\d*$" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Purchase Date</label>
                        <input type="date" name="date" value="{{ old('date') }}" class="form-control"
                            max="{{ Carbon::now()->format('Y-m-d') }}" required>
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

                {{-- <div class="table">
            <table style="width: 100%">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Quantity Type</th>
                  <th>Quantity</th>
                  <th>Expiry Date</th>
                  <th>Price</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <select id="product_id" name="product_id[]" class="form-control" required>
                      <option value="" disabled selected>Select Product</option>
                      @foreach ($products as $product)
                        <option value="{{$product->id}}">{{$product->product_name}}</option>
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
                    <input type="text" id="qty" pattern="^\d+(\.\d)?\d*$" name="qty[]" class="form-control" placeholder="Enter the Quantity" required>
                  </td>
                  <td>
                    <input type="date" name="expery_date[]" id="expery_date" class="form-control">
                  </td>
                  <td>
                    <input type="text" id="price" name="price[]" class="form-control" placeholder="Enter the Price" pattern="^\d+(\.\d)?\d*$" required>
                  </td>
                  <td></td>
                </tr>

              </tbody>
            </table>
            <button type="button" id="pur_product" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp Add Row</button>
          </div> --}}
            </div>{{-- card body end  --}}
            <div class="card-footer text-right">
                <button type="reset" class="btn btn-danger">Reset</button>
                <button id="submit" class="btn btn-success">Submit</button>
            </div>

        </form>
    </div>

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

        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        function filterFunction() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            div = document.getElementById("myDropdown");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                txtValue = a[i].textContent || a[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
    </script>

    {{-- <script>
          $(document).ready(function() {
            $('#pur_product').on('click',function(){
              var html='';
              let pat=$('#pat').val();
              html+='<tr>';
              html+='<td>';
              html+='<select id="product_id" name="product_id[]" class="form-control" required>';
              html+='<option value="" disabled selected>Select Product</option>';
              html+='@foreach ($products as $product)';
              html+='<option value="{{$product->id}}">{{$product->product_name}}</option>';
              html+='@endforeach';
              html+='</select>';
              html+='</td>';
              html+='<td>';
              html+='<select name="qty_type[]" id="qty_type" class="form-control" required>';
              html+='<option value="" disabled selected>Select Quantity Type</option>';
              html+='<option value="count">count</option>';
              html+='<option value="liter">liter</option>';
              html+='<option value="kg">kg</option>';
              html+='<option value="meter">meter</option>';
              html+='</select>';
              html+='</td>';
              html+='<td>';
              html+='<input type="text" id="qty" pattern="'+ pat +'" name="qty[]" class="form-control" placeholder="Enter the Quantity" required>';
              html+='</td>';
              html+='<td>'
              html+='<input type="date" name="expery_date[]" id="expery_date" class="form-control">'
              html+='</td>'
              html+='<td>';
              html+='<input type="text" id="price" pattern="'+pat+'" name="price[]" class="form-control" placeholder="Enter the Price" required>';
              html+='</td>';
              html+='<td><button type="button" id="remove" class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button></td>';

              $('tbody').append(html);
            });
          });

        $(document).on('click','#remove',function(){
          $(this).closest('tr').remove();
        });

                $(document).on('click','#submit',function(){
          var total_product_amount=parseInt(0);
          var total_amount=parseInt(document.getElementById('amount').value);
          var cheque_amount=parseInt(document.getElementById('cheque_amount').value);
          var online_amount=parseInt(document.getElementById('online_amount').value);
          var credit_amount=parseInt(document.getElementById('credit_amount').value);
          var cash_amount=parseInt(document.getElementById('cash_amount').value);
          var calculated_amount=cheque_amount+online_amount+credit_amount+cash_amount;
          var input = document.getElementsByName('price[]');

            for (var i = 0; i < input.length; i++) {
                var a = input[i].value;
                total_product_amount=total_product_amount+parseInt(a);
            }


          if (total_amount<1) {
            alert('Please enter the total payment');
            return false;
          }

          if (total_amount!=calculated_amount) {
            alert('Please consider the payments');
            return false;
          }

          if (cheque_amount>0) {
            if (document.getElementById('cheque_no').value=='') {
              alert('Please enter the cheque no');
              return false;
            }
            if (document.getElementById('cheque_date').value=='') {
              alert('Please enter the cheque date');
              return false;
            }
          }

          if (total_amount!=total_product_amount) {
            alert('Please consider the product amounts');
            return false;
          }


        });

    </script> --}}




@endsection
