@extends('layouts.navigation')
@section('electric_used','active')
@section('content')
<?php
  $Access=session()->get('Access');
?>

<!-- Main Content -->
<div class="card">
    <div class="card-header">
        <h4>Electric & Electronic Use</h4>
    </div>

    <form action="/electric-use-update-process" method="post" class="needs-validation" novalidate="">
        @csrf
        <div class="card-body form">
            <div class="row">
                {{-- <div class="form-group col-md-4">
                    <label>Select Product</label>
                    <select class="form-control" id="product_id">
                        <option value="" disabled selected>Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                        <span class="text-danger">@error('product_id') {{ $message }}@enderror</span>
                    </select>
                </div> --}}
                <div class="form-group col-md-4">
                    <label>Select Date</label>
                    <input onchange="window.location.assign('/electric-use-edit?date=' +this.value)" type="date" name="date" id="date" value="{{$date}}" class="form-control" max="{{now()->format('Y-m-d')}}" required>
                </div>
            </div>

            <div class="table table-responsive">
                <table style="width: 100%">
                    <thead>
                        <tr>
                            <th>Purchase Order Id</th>
                            <th>Product Name</th>
                            <th>Qty Type</th>
                            {{-- <th>Quantity</th> --}}
                            <th>Used Quantity</th>
                            {{-- <th>Expiry Date</th> --}}
                            <th>Reason</th>
                            <th>Return Qty</th>
                        </tr>
                    </thead>
                    <tbody class="body">
                        @foreach ($filter_date as $data)
                            <tr>
                                <td style="display: none">
                                    <input type="text" name="id[]" value="{{$data->id}}" hidden>
                                </td>
                                <td>{{$data->purchase_id}}</td>
                                <td>{{$data->product_name}}</td>
                                <td>{{$data->qty_type}}</td>
                                {{-- <td>{{$data->quantity}}</td> --}}
                                <td>{{$data->used_qty}}</td>
                                <td>{{$data->reason}}</td>
                                <td><input type="number" name="return_qty[]" max="{{$data->used_qty}}" value="{{$data->return_qty}}" min="0" step="0.01" class="form-control"></td>
                                {{-- <td>{{$data->return_qty}}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div align="right">
                {{-- <button  class="btn btn-danger" id="reset">Reset</button> --}}
                <button class="btn btn-success mr-1" type="submit" id="submit">Submit</button>
            </div>
        </div>
    </form>
</div>


<script>

    let selectedProduct = [];
    let count=0;

    $('#reset').click(function (e) {
        e.preventDefault();
        $('.body').html('');
        $('#product_id').val('');
        selectedProduct.splice(0, selectedProduct.length);
    });

    // $(document).on('click', '#reset', function() {
    //     $('.body').html('');
    //     selectedProduct.splice(0, selectedProduct.length);
    // });

    $('#product_id').change(function (e) {
        e.preventDefault();
        var product = $(this).val();

        if (selectedProduct.includes(product)) {
            alert("Already Selected");
           
        } else {
            selectedProduct.push(product);

            $.ajax({
                type: "GET",
                url: "/electric-use-add-getdata/" + product,
                dataType: "json",
                success: function(response) {
                    var html = '';
                    var checkData = 0;
               
                    for (const key in response) {
                        if (response[key]['qty'] > 0) {
                            checkData++;
                            count++;

                            var exDate = response[key]['exDate'];
                            var purchaseId = response[key]['purchase_id'];
                            var productName = response[key]['product_name'];
                            var productCode = response[key]['product_code'];
                            var transfer_quantity = response[key]['qty'];
                            var productId = response[key]['product_id']; //inventory products.id

                            html += '<tr class="pro_' + count + '">';
                            html += '<td style="display: none"><input type="hidden" value="' +count + '" class="form-control"  name="count[]"></td>';
                            html += '<td style="display: none"><input type="hidden" value="' +productId + '"  class="form-control" name="pro_id[]"></td>';
                            // html += '<td style="display: none"><input type="hidden" value="' +purchaseAmount +'"  class="form-control" name="purchase_amount[]"></td>';
                            // html += '<td style="display: none"><input type="hidden" value="' +qtyType + '" class="form-control"  name="qtyType[]"></td>';
                            html += '<td><input type="text" value="' + purchaseId +'"  name="purchase_id[]" class="form-control" readonly></td>';
                            html += '<td><input type="text" value="' + productName +'"  name="product_name[]" class="form-control" readonly></td>';
                            html+='<td>';
                            html+='<select name="qty_type[]" id="qty_type_'+count+'" class="form-control">';
                            html+='<option value="" disabled selected>Select Quantity Type</option>';
                            html+='<option value="count">count</option>';
                            html+='<option value="liter">liter</option>';
                            html+='<option value="kg">kg</option>';
                            html+='<option value="meter">meter</option>';
                            html+='</select>';
                            html+='</td>';
                            html += '<td><input type="number" value="' + transfer_quantity +'" name="qty[]" class="form-control" step="0.01" readonly="readonly"></td>';
                            html += '<td><input type="number" value="' + transfer_quantity +'" name="use_qty[]" class="form-control" step="0.01"  min="0" max="' +transfer_quantity + '" required></td>';
                            html += '<td><input type="date"  value="' + exDate +'" name="exDate[]" class="form-control" readonly="readonly"></td>';
                            html+='<td><textarea name="reason[]" id="reason_'+count+'" data-id="'+count+'" cols="10" rows="1" class="form-control reason"></textarea></td>';
                            html +='<td style="text-align: center;"><input type="checkbox" id="chek_'+count+'" class="form-control chek" name="' +count+ '" value="'+true+'" style="width: 20px; height:20px; margin:10px auto;" data-id="'+count+'"></td>';
                            html += '</tr>';
                        }
                    }

                    if (checkData > 0) {
                        html += '<tr class="pro_' + count + '">';
                        html +='<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td style="text-align: center;"><button class="btn btn-danger" onclick="remove_fun(' +count + ','+product+')" type="button"><i class="fas fa-times"></i></button></td>';
                        html += '</td>';
                    }

                    $('tbody').append(html);

                    $('.reason').click(function (e) {
                        e.preventDefault();

                        $('#reason_modal').modal('show');
                        $('#m_reason').val('');

                        var count=$(this).attr("data-id");
                        let p_reason= $('#reason_'+count).val();
                        $('#m_reason').val(p_reason);
                        $('#m_id').val(count);

                    });

                    $('.chek').click(function (e) {

                        let chek_id=$(this).attr("data-id");

                        if ($(this).is(':checked')) {
                            $('#qty_type_'+chek_id).attr('required', true);
                            $('#reason_'+chek_id).attr('required', true);
                        } else {
                            $('#qty_type_'+chek_id).attr('required', false);
                            $('#reason_'+chek_id).attr('required', false);
                        }
                    });
                }
            });
        }
    });


    function remove_fun(id,pro) {
    
        $('.pro_' + id).html('');
        $('#product_id').val('');

        let index = selectedProduct.findIndex(checkIndex);
        selectedProduct.splice(index, 1);

        function checkIndex(product_) {
            return product_ == pro;
        }
    }

    $('#m_submit').click(function (e) {
        e.preventDefault();
        let m_id=$('#m_id').val();
        let m_reason=$('#m_reason').val();
        $('#reason_modal').modal('hide');
        $('#reason_'+m_id).val(m_reason);
    });
</script>


@endsection


{{-- reason modal start --}}
<div class="modal fade" id="reason_modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" class="needs-validation" novalidate="" method="post">
                    @csrf
                    <input type="hidden" name="m_id" id="m_id">

                    <div class="form-group">
                        {{-- <label>Reason</label> --}}
                        <textarea class="form-control" id="m_reason" name="m_reason"></textarea>
                    </div>

                    <div align="right">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" type="submit" id="m_submit">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
{{-- reason modal end --}}
