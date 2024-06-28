@extends('layouts.navigation')
@section('Advance Payment', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Advance Payment</h4>
            <div class="card-header-action">
                    <a class="btn btn-success" href="/sales/add-advance-payment">
                        <span class="btn-icon-wrapper pr-2"> </span>
                        Add
                    </a>
            </div>
        </div>


        <div class="card-body">
            <form action="/sales/advance-payment" method="get">
                @csrf
                <div class="card-body form">
                    <div class="form-row">
                        <div class="form-group col-md-7">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Month</label>
                            <input type="month" id="month" name="month" max="{{now()->format('Y-m')}}" value="{{$month}}" class="form-control" required>
                        </div>
        
                        <div class="form-group col-md-2" style="margin-top:30px">
                            <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>   
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th >Customer Name</th>
                            <th >Mobile Number</th>
                            <th >Amount</th>
                            <th >Date</th>
                            <th >Status</th>
                            <th class="col-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($advancepayment as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->phone_number }}</td>
                                <td>{{ number_format($row->amount,2) }}</td>
                                <td>{{ $row->date }}</td>
                                <td>{{ $row->buying_status }}</td>
                                <td class="col-action">
                                    <a class="btn btn-primary view" title="View" data-id="{{ $row->id }}" >
                                        <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-eye"></i>
                                    </a>
                                    @if ($row->buying_status != 'Buy')
                                        <a class="btn btn-danger delete" title="Delete" data-id="{{ $row->id }}" >
                                            <i style="color:rgb(226, 210, 210);cursor: pointer" class="fa fa-trash"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('modal')

{{-- view order details --}}
<div class="modal fade" id="viewDetails" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">view order details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">Customer Name:-</div>
                    <div class="col-6"><span id="customerName"></span></div>
                    <div class="col-6">Customer Mobile:-</div>
                    <div class="col-6"><span id="customerMobile"></span></div>
                    <div class="col-6">Amount:-</div>
                    <div class="col-6"><span id="customerAmount"></span></div>
                    <div class="col-6">Date:-</div>
                    <div class="col-6"><span id="customerDate"></span></div>
                </div>
                <br>
                <hr>
                <div class="row">
                    <h6>Oder Product List</h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>


<script>
    $(document).ready(function() {

        // view
        $('#save-stage').on('click', '.view', function() {
            var id = $(this).attr('data-id');

            $.ajax({
                url: "/sales/view-advance_payment-order-product/" + id,
                method: 'GET',
                data: {},
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {

                    $('#customerName').html(res.name);
                    $('#customerMobile').html(res.phone_number);
                    $('#customerAmount').html(res.amount);
                    $('#customerDate').html(res.date);

                    var array = res.array;
                    $('#tbody').html('');
                    $.each(array, function(index, row) {
                        var body = `<tr>
                                    <td>${index + 1}</td>
                                    <td>${row.product_code}</td>
                                    <td>${row.product_name}</td>
                                    <td>${row.count}</td>
                                </tr>`;

                        $('#tbody').append(body);
                    });

                    $('#viewDetails').modal('show');
                }
            });
            
        });

        // delete
        $('#save-stage').on('click', '.delete', function() {
            var id = $(this).attr('data-id');

            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this data!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {

                    $.ajax({
                        url: "/sales/advance_payment-order-delete/" + id,
                        method: 'GET',
                        data: {},
                        dataType: 'json',
                        contentType: "application/json",
                        success: function(res) {
                            swal('Poof! Your data has been deleted!', {
                                icon: 'success',
                                timer: 2000,
                            });
                            location.reload();
                        }
                    });
                    
                }
            })

            
            
        });

    });
</script>
