@extends('layouts.navigation')
@section('indoor_return_report','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Indoor Return</h4>
        </div>
    <form action="/indoor-return-report" method="POST" class="needs-validation" novalidate="">
        @csrf

        <div class="card-body form">
           
            <div class="form-row">

                <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input type="date" id="from" name="from" value="{{ $from }}" class="form-control" required >
                </div>

                <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date" id="to" max="{{ now()->format('Y-m-d') }}" name="to" value="{{ $to }}" class="form-control" required>
                </div>

                <div class="form-group col-md-3">
                    <label>Filter By</label>
                    <select id="filter_by" name="filter_by" class="form-control" required>
                        <option value="" disabled selected>Select</option>
                        <option value="1" {{ $filter_by == 1 ? 'selected' : '' }}>Department Wise</option>
                        <option value="2" {{ $filter_by == 2 ? 'selected' : '' }}>Supplier Wise</option>
                        <option value="3" {{ $filter_by == 3 ? 'selected' : '' }}>Product Wise</option>
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
                <button type="reset" class="btn btn-danger" id="reset">Reset</button>
                <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
            </div>

        </div>

    </form>

    {{-- @if (count($indoor_return) > 0) --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th style="text-align: center">Date</th>
                            <th>Department Name</th>
                            <th style="text-align: center">Quantity</th>
                            <th>Quantity Type</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($indoor_return as $indoor_return)
                            <tr>
                                <td>{{ $indoor_return->product_code }}</td>
                                <td>{{ $indoor_return->product_name }}</td>
                                <td>{{ substr($indoor_return->created_at, 0, 10) }}</td>
                                <td>{{ $indoor_return->dept_name }}</td>
                                <td style="text-align: right">{{ number_format($indoor_return->qty,2) }}</td>
                                <td>{{ $indoor_return->pur_item_qty_type }}</td>
                                <td style="text-align: right">{{ number_format($indoor_return->pur_item_amount/$indoor_return->pur_item_qty,2) }}</td>
                                <td style="text-align: right">{{ number_format($indoor_return->pur_item_amount/$indoor_return->pur_item_qty*$indoor_return->qty,2)}}</td>
                                <td>
                                    <?php if ($indoor_return->status==0) {
                                        echo "Pending";
                                    } else if($indoor_return->status==1){
                                        echo "Accepted";
                                    }else if($indoor_return->status==2){
                                        echo "Rejected";
                                    }else{
                                    
                                    } ?>
                                </td>
                                <td style="text-align: center">
                                    <button data-toggle="modal" data-target="#view" type="button"
                                        class="btn btn-info btn-edit btn-view"
                                        data-dept_id="{{ $indoor_return->department_id }}"
                                        data-product_id="{{ $indoor_return->product_id }}"
                                        data-status="{{$indoor_return->status}}">
                                        
                                        <i class="far fa-eye"></i></button>
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
            $('#modal-print').click(function (e) { 
                e.preventDefault();
                var printHtml=$('#print-html').html();
                console.log(printHtml);
                var mywindow = window.open('', 'PRINT');
                mywindow.document.write( '<!DOCTYPE html><html lang="en"><head><style>table, td, th {border: 1px solid #ddd;text-align: left;}table {border-collapse: collapse;width: 100%;}th, td {padding: 15px;}</style></head><body><table border="1">'+ printHtml + '</table></body></html>');
                mywindow.focus();
                mywindow.print();
                mywindow.close();
            });
          
            if ($('#filter_by').val()!=null) {
    
                if ($('#filter_by').val() == 1) {
                  var html='';
                  html+='<label>Department</label>';
                  html+='<select id="department" name="department" class="form-control" required>';
                  html+='<option value="" disabled selected>Select</option>';
                  html+='@foreach ($departments as $department)';
                  html+='<option value="{{$department->department_id}}" {{$department_id==$department->department_id?'selected':''}}>{{$department->dept_name}}</option>';
                  html+='@endforeach';
                  html+='</select>';
                  $('#department_select').html('');
                  $('#department_select').append(html);  
                }

                if ($('#filter_by').val() == 2) {
                  $('#department_select').html('');
                  var html='';
                  html+='<label>Seller</label>';
                  html+='<select id="seller" name="seller" class="form-control" required>';
                  html+='<option value="" disabled selected>Select</option>';
                  html+='@foreach ($sellers as $seller)';
                  html+='<option value="{{$seller->seller_id}}" {{$seller_id==$seller->seller_id?'selected':''}}>{{$seller->seller_name}}</option>';
                  html+='@endforeach';
                  html+='</select>';
                  $('#department_select').html('');
                  $('#department_select').append(html);  
                }

                if ($('#filter_by').val() == 3) {
                  $('#department_select').html('');
                  var html='';
                  html+='<label>Product</label>';
                  html+='<select id="product" name="product" class="form-control" required>';
                  html+='<option value="" disabled selected>Select</option>';
                  html+='@foreach ($products as $product)';
                  html+='<option value="{{$product->product_id}}" {{$product_id==$product->product_id?'selected':''}}>{{$product->product_name}}</option>';
                  html+='@endforeach';
                  html+='</select>';
                  $('#department_select').html('');
                  $('#department_select').append(html);  
                }

            }

            $('#filter_by').change(function(e) {
                e.preventDefault();
                $('#department_select').html('');

                if ($(this).val() == 1) {
                  var html='';
                  html+='<label>Department</label>';
                  html+='<select id="department" name="department" class="form-control" required>';
                  html+='<option value="" disabled selected>Select</option>';
                  html+='@foreach ($departments as $department)';
                  html+='<option value="{{$department->department_id}}" {{$department_id==$department->department_id?'selected':''}}>{{$department->dept_name}}</option>';
                  html+='@endforeach';
                  html+='</select>';
                  $('#department_select').html('');
                  $('#department_select').append(html);  
                }

                if ($(this).val() == 2) {
                  $('#department_select').html('');
                  var html='';
                  html+='<label>Seller</label>';
                  html+='<select id="seller" name="seller" class="form-control" required>';
                  html+='<option value="" disabled selected>Select</option>';
                  html+='@foreach ($sellers as $seller)';
                  html+='<option value="{{$seller->seller_id}}" {{$seller_id==$seller->seller_id?'selected':''}}>{{$seller->seller_name}}</option>';
                  html+='@endforeach';
                  html+='</select>';
                  $('#department_select').html('');
                  $('#department_select').append(html);  
                }

                if ($(this).val() == 3) {
                  $('#department_select').html('');
                  var html='';
                  html+='<label>Product</label>';
                  html+='<select id="product" name="product" class="form-control" required>';
                  html+='<option value="" disabled selected>Select</option>';
                  html+='@foreach ($products as $product)';
                  html+='<option value="{{$product->product_id}}" {{$product_id==$product->product_id?'selected':''}}>{{$product->product_name}}</option>';
                  html+='@endforeach';
                  html+='</select>';
                  $('#department_select').html('');
                  $('#department_select').append(html);  
                }

            });

            $('#reset').click(function (e) { 
              e.preventDefault();
              $('#from').val('');
              $('#to').val('');
              $('#filter_by').val('');
              $('#department').val('');
              $('#department_select').html('');
              $('#status').val('');
            });

            $('.btn-view').on('click', function() {
                $('#modal-view').html('');
                let from = $('#from').val();
                let to = $('#to').val();
                let filter_by = $('#filter_by').val();
                var dept_id = $(this).attr('data-dept_id');
                var pro_id = $(this).attr('data-product_id');
                var status = $(this).attr('data-status');
                var html = '';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/get-specific-indoor-return-report",
                    data: {
                        from: from,
                        to: to,
                        filter_by: filter_by,
                        dept_id: dept_id,
                        pro_id: pro_id,
                        status:status
                    },
                    dataType: "json",
                    success: function(response) {
                        for (const key in response) {
                            var date = response[key]['created_at'].split("T")[0];
                            var dept_id = response[key]['dept_id'];
                            var created_at = date;
                            var purchase_unit_price = response[key]['purchase_unit_price'];
                            var dept_name = response[key]['dept_name'];
                            var pro_id = response[key]['product_id'];
                            var pro_name = response[key]['product_name'];
                            var pro_code = response[key]['product_code'];
                            var pur_amount=response[key]['pur_item_amount'];
                            var pur_qty=response[key]['pur_item_qty'];
                            var purchase_unit_price =  pur_amount/pur_qty
                            var purchase_order_id = response[key]['purchase_order_id'];
                            var qty = response[key]['qty'];
                            var Qty_type = response[key]['pur_item_qty_type'];
                            var total = qty*purchase_unit_price;

                            var status="";
                            if (response[key]['status']==0) {
                                status="Pending";
                            } else if(response[key]['status']==1){
                                status="Accepted";
                            }else if(response[key]['status']==2){
                                status="Rejected";
                            }else{

                            }
                            html += '<tr>';
                            html += '<td>' + created_at + '</td>';
                            html += '<td>' + dept_name + '</td>';
                            html += '<td>' + purchase_order_id + '</td>';
                            html += '<td>' + pro_name + '</td>';
                            html += '<td>' + qty + '</td>';
                            html += '<td>' + Qty_type + '</td>';
                            html += '<td>' + purchase_unit_price + '</td>';
                            html += '<td>' + total + '</td>';
                            html += '<td>' + status + '</td>';
                            html += '<tr>';

                        }
                        $('#modal-view').append(html);
                    }
                });
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



{{-- view modal start --}}
<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Transfer History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="padding: 10px 0">
                    <button id="modal-print" class="btn btn-primary">Print</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="print-html">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Department Name</th>
                                <th>Purchase Order Id</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Quantity Type</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Status</th>
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
{{-- view modal end --}}
