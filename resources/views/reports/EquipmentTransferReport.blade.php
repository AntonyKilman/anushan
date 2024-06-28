@extends('layouts.navigation')
@section('equipment_transfer_report', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>
    <!-- Main Content -->
    <div class="card">
        <form action="/equipment-transfer-report" method="POST">
            @csrf

            <div class="card-body form">
                <h6>Equipment Transfer Report</h6>

                <div class="form-row">
                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" id="from" value="{{ $from }}" name="from" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" id="to" max="{{ now()->format('Y-m-d') }}" value="{{ $to }}"
                            name="to" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Filter By</label>
                        <select id="filter_by" name="filter_by" class="form-control" required>
                            <option value="" disabled selected> Select </option>
                            <option value="1" {{ $filter_by == 1 ? 'selected' : '' }}>Product Wise</option>
                            <option value="2" {{ $filter_by == 2 ? 'selected' : '' }}>Department Wise</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Status</label>
                        <select id="status" class="form-control" name="status" required>
                            <option value="" disabled {{ $status == '' ? 'selected' : '' }}>Select Status</option>
                            <option value="1" {{ $status == '1' ? 'selected' : '' }}>Pending</option>
                            <option value="0" {{ $status == '0' ? 'selected' : '' }}>Got It</option>
                            {{-- <option value="2" {{$status=="2"?'selected':''}}>Rejected</option> --}}
                        </select>
                    </div>
                </div>

                <div align="right">
                    <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>
        </form>

        {{-- @if (count($equipment_item_transfer) > 0) --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="purchaseitemTable">
                    <thead>
                        <tr>
                            @if ($name != '')
                                <th>{{ $name }}</th>
                                <th>Product Code </th>
                                
                            @endif
                            
                            <th>Transfer Date </th>
                            <th>No Of Days </th>
                            <th>Reason</th>
                            <th>Employee </th>
                            <th>Status </th>
                            <th>Quantity</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($equipment_item_transfer as $equipment_item_transfer)
                            <tr>
                                @if ($name != '')
                                    @if ($equipment_item_transfer->product_name)
                                        <td>{{ $equipment_item_transfer->product_name }}</td>
                                        <td>{{ $equipment_item_transfer->product_code }}</td>


                                    @endif
                                    @if ($equipment_item_transfer->dept_name)
                                        <td>{{ $equipment_item_transfer->dept_name }}</td>

                                    @endif
                                @endif

                                <td>{{ $equipment_item_transfer->purchaseDate }}</td>
                                <td>{{ $equipment_item_transfer->noOfDays }}</td>
                                <td>{{ $equipment_item_transfer->reason }}</td>
                                <td>{{ $equipment_item_transfer->f_name }}</td>
                                <td>
                                    @if ($equipment_item_transfer->status == 0)
                                        Got It
                                    @elseif($equipment_item_transfer->status == 1)
                                        Pending
                                    @else
                                    @endif
                                </td>
                                <td>{{ $equipment_item_transfer->qty }}</td>
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
            $('#submit').on('click', function() {
                let from = $('#from').val();
                let to = $('#to').val();
                let filter_by = $('#filter_by').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: '/getjs-post',
                    dataType: 'json',
                    data: {
                        from_date: from,
                        to_date: to,
                        filter: filter_by
                    },
                    success: function(data) {
                        console.log(data);
                    }
                });
            });
            $('#reset').click(function(e) {
                e.preventDefault();
                $('#from').val('');
                $('#to').val('');
                $('#filter_by').val('');
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

        $('#purchaseitemTable').DataTable({
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
