@extends('layouts.navigation')
@section('returnReport', 'active')
@section('content')
    <!-- Main Content -->
    <form action="/sales/return-report" method="get">
        @csrf
        <div class="card-body form">
            <h6>Return Products Report</h6>

            <div class="form-row">
                <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input type="date" id="from" name="from" value="{{$from}}" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date" id="to" name="to" max="{{now()->format('Y-m-d')}}" value="{{$to}}" class="form-control" required>
                </div>

                {{-- <div class="form-group col-md-3">
                    <label>Filter By</label>
                    <select id="filter_by" name="filter_by" class="form-control" required>
                        <option value="" disabled selected>Select</option>
                        <option value="1" >Department Wise</option>
                        <option value="2" >Seller Wise</option>
                    </select>
                </div> --}}

                <div class="form-group col-md-3" style="margin-top:30px">
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
    <div class="card-body">
        <div class="row">
            <div class="col-4"><p class="h6 text-dark">Number of Returns: {{$returnReport->count()}}</p></div>
        </div>
    </div>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">
                            <h2> Products</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="salesReturnTable">
                                    <thead>
                                        <tr>
                                        <th class="text-center">#</th>
                                        <th>Date & Time</th>
                                        <th>Name</th>
                                        <th>Product Code</th>
                                        <th>Transcation ID</th>
                                        <th>Qty</th>
                                        <th>Reason</th>
                                        <th>Description</th>
                                        {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($returnReport as $item)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{$item->created_at}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->product_code}}</td>
                                            <td>{{$item->transection_id}}</td>
                                            <td>{{$item->return_qty}}</td>
                                            <td>{{$item->description}}</td>
                                            <td>{{$item->description}}</td>
                                            {{-- <td>
                                                <a  href="{{route('salesView.report', $item->id)}}" class="btn btn-primary"><i class="far fa-eye"></i></a>
                                            </td> --}}
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>        
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#salesReturnTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Return Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Return Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>