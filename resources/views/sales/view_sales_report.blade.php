@extends('layouts.navigation')
@section('content')
    <!-- Main Content -->
    <a href="/sales/sales-report" class="btn btn-info">Back</a>
    <div class="card-body">
      <div>
        <h6>Invoice NO: {{$invoiceNo->invoice_no}}</h6>
        @if($invoiceNo->is_cancelled ==1)
          <h6 class="text-danger">This bill is Cancelled</h6>
        @endif
      </div>
        <div class="card-body">

          <div class="table-responsive">
            <table class="table table-striped" id="salesTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Item</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Discount</th>
                  <th>Sub Total</th>
                </tr>
              </thead>
              <tbody>
                @php 
                  $totalBill =0.00;
                  $i=1;
                @endphp
                  @foreach ($salesReportView as $item)
                      <tr>
                          <td>{{ $i }}</td>
                          <td>{{$item->name}}</td>
                          <td>{{$item->sale_quantity}}</td>
                          <td>{{number_format(($item->amount - $item->discount),2)}}</td>
                          <td>{{number_format($item->discount,2)}}</td>
                          <td>{{number_format((($item->amount - $item->discount)*$item->sale_quantity),2)}}</td>
                      </tr> 
                      @php 
                        $totalBill += (($item->amount - $item->discount)*$item->sale_quantity);
                        $i++;
                      @endphp                 
                  @endforeach 
              </tbody>
              <tr>
                <td>*</td>
                <td colspan="4"><h5 style="margin:0px;padding:0px;">Total Bill:</h5></td><td><h5 style="margin:0px;padding:0px;"> {{number_format($totalBill,2)}}</h5></td>
              </tr>
            </table>
          </div>
          {{-- <div class="row">
            <button class="btn btn-primary mr-3" type="button" onclick="reprintBill('{{$invoiceNo->invoice_no}}','{{$invoiceNo->date}}','{{$invoiceNo->time}}');">Reprint</button>
          </div> --}}
        </div>
     
        {{-- Reprint Bill Start --}}
          <div class="row border-left" id="reprint-area" style="display:none;">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col"><p style="font-size: 11px;">#</p></th>
                    <th scope="col"style="width:40%;"><p style="font-size:11px;text-align:left;">ITEM</p></th>
                    <th scope="col"><p style="font-size: 11px;">QTY</p></th>
                    <th scope="col"><p style="font-size: 11px;">RATE</p></th>
                    <th scope="col"style="width:10%;"><p style="font-size: 11px;">DIS</p></th>
                    <th scope="col"><p style="font-size: 11px;">AMOUNT</p></th>
                  </tr>
                </thead>
                <tbody>
                  @php 
                  $totalBill_reprint =0.00;
                    $no=1;
                  @endphp
                  @foreach ($salesReportView as $item)
                      <tr>
                          <td><p style="font-size: 11px;">{{ $no }} </p></td>
                          <td><p style="font-size: 11px;">{{$item->name}}</p></td>
                          <td><p style="font-size: 11px;">{{$item->sale_quantity}}</p></td>
                          <td><p style="font-size: 11px;">{{number_format(($item->amount - $item->discount),2)}}</p></td>
                          <td><p style="font-size: 11px;">{{number_format($item->discount,2)}}</p></td>
                          <td><p style="font-size: 11px;">{{number_format((($item->amount - $item->discount)*$item->sale_quantity),2)}}</p></td>
                      </tr> 
                      @php 
                        $totalBill_reprint += (($item->amount - $item->discount)*$item->sale_quantity);
                        $no++;
                      @endphp                 
                  @endforeach
                </tbody>
                <div class="">
                    <tr>
                        <td scope="row">*</td>
                        <td colspan="4"><h5 style="margin:0px;padding:0px;">Total</h5></td>
                        <td><h5 style="margin:0px;padding:0px;">{{number_format($totalBill_reprint,2)}}</h5></td>
                    </tr>
                </div>
              </table>
          </div>
        {{-- Reprint Bill Start --}}
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        $('#salesTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Report ',
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Report  ' ,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  //print bill
  function reprintBill(invoiceNo, date, time){

      var printHtml = '<div style="border-bottom:0.2px solid black;display:flex;flex-direction:column;align-items: center;"><img src="https://www.foodcityy.reecha.lk/assets/img/reecha.png" alt="reecha" width="100"/><div style="font-size:13px"></div><div></div><div style="width:100%;margin-left:30px;margin-bottom:10px;font-size:12px;"><div>Date: '+ date +' </div><div>Time: '+ time +'</div><div>Invoice No: '+ invoiceNo +'</div></div></div>' + $('#reprint-area').html() + '<div style="padding-top:10px;display:flex;flex-direction:column;align-items: center;"><div>Thank You</div></div>';

      var mywindow = window.open('', 'PRINT');
      mywindow.document.write( '<!DOCTYPE html><html lang="en"><head></head><body>'+ printHtml + '</body></html>');
      mywindow.focus();
      mywindow.print();
      mywindow.close();
  }
</script>
