@extends('layouts.navigation')
@section('outdoor_return_report','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>


    <!-- Main Content -->
    <div class="card">
      <div class="card-header">
        <h4 class="header">Outdoor Return</h4>
    </div>

    <form action="/out-door-return-report" method="POST">
        @csrf

        <div class="card-body form">
    
            <div class="form-row">
                <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input type="date" id="from" value="{{ $from }}" name="from" class="form-control" required>
                </div>

                <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date" id="to" value="{{ $to }}" name="to" class="form-control" required>
                </div>

                <div class="form-group col-md-3">
                  <label>Supplier</label>
                  <select id="seller" name="seller" class="form-control" required>
                    <option value="" disabled selected>Select</option>
                    @foreach ($sellers as $seller)
                      <option value="{{$seller->seller_id}}" {{$seller_id==$seller->seller_id?'selected':''}}>{{$seller->seller_name}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group col-md-3">
                  <label>Status</label>
                  <select id="status" class="form-control" name="status" required>
                      <option value="" disabled {{$status==""?'selected':''}}>Select Status</option>
                      <option value="0" {{$status=="0"?'selected':''}}>Pending</option>
                      <option value="1" {{$status=="1"?'selected':''}}>Accepted</option>
                      <option value="2" {{$status=="2"?'selected':''}}>Rejected</option>
                  </select>
              </div>

            </div>
          <div align="right">
            <button type="reset" id="reset" class="btn btn-danger">Reset</button>
            <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
</div>
</form>
                

        <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped " id="inventoryTable">
                <thead>
                        <tr>
                          <th>Product Code</th>
                          <th>Product Name</th>
                          <th>Date</th>
                          <th>Supplier Name</th>
                          <th>GR Number</th>
                          <th>Reason</th>
                          <th>Quantity</th>
                          <th>Quantity Type</th>
                          <th>Unit Price</th>
                          <th>Total</th>
                          <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                      @foreach ($return_item as $return_item)
                      <tr>
                        <td>{{$return_item->product_code}}</td>
                      <td>{{$return_item->product_name}}</td>
                      <td>{{ substr($return_item->created_at, 0, 10) }}</td>
                      <td>{{$return_item->seller_name}}</td>
                      
                      <td>{{$return_item->id}}</td>

                      <td>{{$return_item->reason_name}}</td>
                      <td style="text-align: right">{{ number_format($return_item->qty,2)}}</td>
                      <td>{{ $return_item->pur_item_qty_type }}</td>
                      <td style="text-align: right">{{ number_format($return_item->pur_item_amount/$return_item->pur_item_qty,2) }}</td>
                                <td style="text-align: right">{{ number_format($return_item->pur_item_amount/$return_item->pur_item_qty*$return_item->qty,2)}}</td>

                      <td>
                        <?php 
                          if ($return_item->status==0) {
                            echo "Pending";
                          } else if($return_item->status==1){
                            echo "Accepted";
                          }else if($return_item->status==2){
                            echo "Rejected";
                          }else{

                          } 
                        ?>
                      </td>
                      
                      </tr>            
      
                      @endforeach

                    </tbody>

                </table>
            </div>
        </div>

    {{-- @endif --}}
    </div>

    <script>
        $(document).ready(function() {
            if ($('#filter_by').val() != null) {
              if ($('#filter_by').val() == 1) {
                  var html='';
                  html+='<label>Seller</label>';
                  html+='<select id="seller" name="seller" class="form-control" required>';
                  html+='<option value="" disabled selected>Select</option>';
                  html+='@foreach ($sellers as $seller)';
                  html+='<option value="{{$seller->seller_id}}" {{$seller_id==$seller->seller_id?'selected':''}}>{{$seller->seller_name}}</option>';
                  html+='@endforeach';
                  html+='</select>';
                  $('#append_area').html('');
                  $('#append_area').append(html);  
                }

            }

            $('#filter_by').change(function(e) {
                e.preventDefault();
              
                if ($('#filter_by').val() == 1) {
                  var html='';
                  html+='<label>Seller</label>';
                  html+='<select id="seller" name="seller" class="form-control" required>';
                  html+='<option value="" disabled selected>Select</option>';
                  html+='@foreach ($sellers as $seller)';
                  html+='<option value="{{$seller->seller_id}}" {{$seller_id==$seller->seller_id?'selected':''}}>{{$seller->seller_name}}</option>';
                  html+='@endforeach';
                  html+='</select>';
                  $('#append_area').html('');
                  $('#append_area').append(html);  
                }
            });
            
            $('#submit').click(function (e) { 
              
              $.ajax({ 
                type: 'POST', 
                url: '/getjs-post', 
                dataType: 'json',
                data:{from_date:from,to_date:to,filter:filter_by},
                success: function (data) { 
                  console.log(data);
                
                }
              });
            });
            
            $('#reset').click(function(e) {
                e.preventDefault();
                $('#from').val('');
                $('#to').val('');
                $('#filter_by').val('');
                $('#seller').val('');
                $('#status').val('');

                
            });
        });

        setMinDate();

        $('#from').change(function(e) {
            e.preventDefault();
            setMinDate();
        });

        function setMinDate() {
            var from = $('#from').val();
            if (from) {
                $('#to').attr('min', from);
            }
        }
    </script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#inventoryTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: ' Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
<div class="modal fade" id="otview" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Outdoor Transfer </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          
          <div class="table-responsive">
                <table class="table table-striped " id="inventoryTable">
                  <thead>
                    <tr>
                      <th>Supplier Name</th>
                      <th>Product Name</th>
                      <th>Return Reason</th>
                      <th>Purchase Order Id</th>
                      <th>Quantity</th>
                    </tr>
                  </thead>
                  <tbody id="modal-view">
                      
                  </tbody>
                </table>
          </div>
                          
      </div>
    </div>
  </div>
  </div>
