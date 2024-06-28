@extends('layouts.navigation')
@section('alerts_','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-body form">
            <h6>Product Quantity Alert</h6>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Filter By</label>
                    <select id="filter_by" name="filter_by" class="form-control" onchange="window.location.assign('/product-qty-alert-showall?id=' +this.value)">
                        <option value="" disabled selected>Select</option>
                        <option value="1" {{ $filter_by == 1 ? 'selected' : '' }}>Danger Quantity</option>
                        <option value="2" {{ $filter_by == 2 ? 'selected' : '' }}>Minimum Quantity</option>
                        <option value="3" {{ $filter_by == 3 ? 'selected' : '' }}>Maximum Quantity</option>
                    </select>
                </div>
            </div>
            
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Quantity Type</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batches as $batch)
                        
                            @if ($batch->alert=="1")
                                <tr>
                                    <td>{{$batch->product_code}}</td>
                                    <td>{{$batch->product_name}}</td>
                                    <td>{{$batch->qty_type}}</td>  
                                    <td>{{$batch->qty}}</td> 
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                    messageTop: 'Product Quantity Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Product Quantity Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>