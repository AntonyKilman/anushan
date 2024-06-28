@extends('layouts.navigation')
@section('salesReturn', 'active')
@section('content')
<!-- Main Content -->
<a href="/sales/sale-return" class="btn btn-info">Back</a>
<div class="card-body">
  <div class="pt-3">
    <h5>Invoice NO: {{$invoiceNo->invoice_no}}</h5>
  </div>
  <input type="hidden" id="invoiceNo" value="{{$invoiceNo->invoice_no}}">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-striped" id="table-1">
        <thead>
          <tr>
            <th>#</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Sub Total</th>
            <th>Cancel Product Qty</th>
            <th>Select Cancal</th>
          </tr>
        </thead>
        <tbody>
          @php
          $totalBill =0.00;
          $i=1;
          @endphp
          @foreach ($finalSales as $item)
          <tr>
            <td>{{ $i }}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->sale_quantity}}</td>
            <td>{{number_format(($item->amount - $item->discount),2)}}</td>
            <td>{{number_format($item->discount,2)}}</td>
            <td>{{number_format((($item->amount - $item->discount)*($item->sale_quantity)),2)}}</td>
            <td>
              <input class="form-control return returnQuantity_{{$item->id}}" type="number" step="0.001"
                value="{{$item->sale_quantity}}" max="{{$item->sale_quantity}}" data-maxVal="{{$item->sale_quantity}}" style="text-align: right">
            </td>
            <td>
              <input class="cancal" type="checkbox" name="cancalItem" value="{{$item->id}}">
            </td>
          </tr>
          @php
          $totalBill += (($item->amount - $item->discount)*$item->sale_quantity);
          $i++;
          @endphp
          @endforeach
        </tbody>
        <tr>
          <td>*</td>
          <td colspan="4">
            <h5 style="margin:0px;padding:0px;">Total Bill:</h5>
          </td>
          <td>
            <h5 style="margin:0px;padding:0px;"> {{number_format($totalBill,2)}}</h5>
          </td>
        </tr>
      </table>
    </div>
    <div class="row">
      <button type="button" class="btn btn-danger mr-3" data-toggle="modal" data-target="#cancelModal" disabled
        id="Cancel">Cancel</button>
    </div>
  </div>
</div>
@endsection
@section('model')
<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelModalLabel">Please Confirm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="text" class="form-control" name="sales_id" id="sales_id" value="{{$invoiceNo->id}}" hidden>
        </div>
        <div class="form-group">
          <label for="reason" class="col-form-label">Reason for cancel:</label>
          <textarea class="form-control" name="reason" id="reason" required></textarea>
          <div class="text-danger" id="reason-error"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="btn-cancel">Cancel</button>
      </div>
    </div>
  </div>
</div>
@endsection

<div id="image" style="display: none">
  <img width="100px" height="60px" src="https://www.jaffnaelectrical.skyrow.lk/assets/img/electrical.jpg" alt="">
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>

<script>
  $(document).ready(function() {

    $('.return').on('click', function() {
      $(this).select();
    });

    $('.cancal').on('change', function() {
      checkButton();
    });


    $('.return').on('keyup', function() {
      checkButton();
    });


    function checkButton() {
      $('#Cancel').attr('disabled', true);
      var buttonActive = true;
      $('.return').each(function(){    
        var maxVal = $(this).attr('data-maxVal');
        var val = $(this).val() ? $(this).val() : 0;
        if (val >= 0) {
          if (parseFloat(val) > parseFloat(maxVal)) {
            $(this).removeClass('is-valid');
            $(this).addClass('is-invalid');
            buttonActive = false;
          } else {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
          }
        } else {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid');
          buttonActive = false;
        }
      });

      if (buttonActive) {
        var count =  $('input:checkbox:checked').length;
        if (count > 0) {
          $('#Cancel').attr('disabled', false);
        } else {
          $('#Cancel').attr('disabled', true);
        }
      } else {
        $('#Cancel').attr('disabled', true);
      }
    }

    

    $('#btn-cancel').on('click', function() {

      if (!$('#reason').val()) {
        return;
      }

      var arry = [];
      $('input:checkbox[name=cancalItem]').each(function(){    
        if ($(this).is(':checked')) {
          var foodcity_product_sales_id = $(this).val();
          var this_product_qty = $('.returnQuantity_' + foodcity_product_sales_id).val();
          var body = {"foodcity_product_sales_id":foodcity_product_sales_id, "this_product_qty":this_product_qty};
          arry.push(body);
        }
      });

      let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/sales/sale-cancel",
            type: "post",
            data: {
              _token: _token,
              cancel_items: arry,
              sales_id: $('#sales_id').val(),
              reason: $('#reason').val()
          },
          success: function(res) {
            console.log(res); 
            if (res.success) {
              swal('Success!', 'Product Cancel Successfully!', 'success');
              print(res.printArry, res.details);
            } else {
              swal('Error!', 'Something went wrong, please try again.', 'warning');
            }
          }
        });
    });

    function print(body, details) {
      
      var productHtml = '';
      var i = 1;
      var amount = 0;
      body.forEach(element => {
        console.log(element.product_name);
        var row = '<tr><th scope="row"><p style="font-size:11px;">' + i++ +
                '</p></th><td><p style="font-size: 11px;text-align:left;">' + element.product_name +
                '</p></td><td><p style="font-size: 11px;text-align:right;">' + element.quantity +
                '</p></td><td><p style="font-size: 11px;text-align:right;">' + (element.rate).toFixed(2) +
                '</p></td><td><p style="font-size: 11px;text-align:right;">' + element.discount +
                '</p></td><td><p style="font-size:11px;text-align:right;">' + (element.amount).toFixed(2) +
                '</p></td></tr>';
          amount += element.amount;
          productHtml += row;
      });

      var massage = '<div style="text-align=center;">Cancel Product(s)</div>';
      var headHtml1 = '<table class="table" align="center" width="100%"> <thead> <tr> <th scope="col"> <p style="font-size: 11px;">#</p></th> <th scope="col"style="width:40%;"> <p style="font-size:11px;text-align:left;">ITEM</p></th> <th scope="col"> <p style="font-size: 11px;text-align:center;">QTY</p></th> <th scope="col"> <p style="font-size: 11px;text-align:center;">RATE</p></th> <th scope="col"style="width:10%;"> <p style="font-size: 11px;text-align:center;">DIS</p></th> <th scope="col"> <p style="font-size: 11px;text-align:right;">AMOUNT</p></th></tr></thead> <tbody>';
      var headHtml2 = '</tbody> <div class=""> <tr> <td scope="row">*</td><td colspan="4"> <h5 style="margin:0px;padding:0px;">Total</h5> </td><td id="bill-items-total">' + amount.toFixed(2) + '</td></tr></div></table>';



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

      if (details.customerName != null) {
          customerName = details.customerName;
      } else {
          var customerName = 'New';
      }
      var billUser = details.billUser;
      var invoiceNo = $('#invoiceNo').val();

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
              <div>Invoice No: ${invoiceNo}</div></span></div>${massage}${headHtml1}${productHtml}${headHtml2}

              <div style="padding-top:10px; font-size:14px;display:flex;flex-direction:column;align-items: center;"> 
              <div>Thank You ! Come Again</div></div>
              <div style="padding-top:4px; font-size:10px ; display:flex;flex-direction:column;align-items: center;"> 
              <div>System Developed BY Codevita (Pvt) Ltd</div></div></body></html>`;


              var mywindow = window.open('', 'PRINT');
              mywindow.document.write(body);
              mywindow.focus();
              mywindow.print();
              mywindow.close();

              location.replace('/sales/sale-return');

    }

  });
  
</script>